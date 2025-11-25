<?php

namespace App\Services;

use App\Interfaces\TicketRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Services\AttachmentService;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class TicketService
{
    protected $ticketRepository;
    protected $attachmentService;
    protected $ticketUpdater;
    protected $statusService;

    public function __construct(TicketRepositoryInterface $ticketRepository, AttachmentService $attachmentService, \App\Services\TicketUpdater $ticketUpdater, StatusService $statusService)
    {
        $this->ticketRepository = $ticketRepository;
        $this->attachmentService = $attachmentService;
        $this->ticketUpdater = $ticketUpdater;
        $this->statusService = $statusService;
    }

    /**
     * Get all tickets.
     *
     * @return Collection
     */
    public function getAllTickets(): Collection
    {
        return $this->ticketRepository->all();
    }

    /**
     * Get all tickets with specified relations loaded.
     *
     * @param array $relations
     * @return Builder
     */
    public function getAllTicketsWithRelations(array $relations): Builder
    {
        return $this->ticketRepository->allWith($relations);
    }

    /**
     * Get a ticket by its ID.
     *
     * @param int $id
     * @return Ticket|null
     */
    public function getTicketById(int $id): ?Ticket
    {
        return $this->ticketRepository->find($id);
    }

    /**
     * Create a new ticket with associated data.
     *
     * @param array $data
     * @return Ticket
     * @throws \Exception
     */
    public function createTicket(array $data, ?User $uploadedBy = null): Ticket
    {
        DB::beginTransaction();
        try {
            $ticket = $this->ticketRepository->create([
                'title' => $data['title'],
                'description' => $data['description'],
                'user_id' => $data['user_id'],
                'status_id' => $data['status_id'],
                'priority_id' => $data['priority_id'],
            ]);

            if (isset($data['labels'])) {
                $ticket->labels()->attach($data['labels']);
            }

            if (isset($data['categories'])) {
                $ticket->categories()->attach($data['categories']);
            }

            if (isset($data['attachments'])) {
                foreach ($data['attachments'] as $file) {
                    $this->attachmentService->uploadAttachment($ticket, $file, $uploadedBy);
                }
            }

            TicketLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => $data['user_id'],
                'action' => 'created',
                'changes' => $ticket->toArray(),
            ]);

            DB::commit();
            return $ticket->load([
                'labels',
                'categories',
                'attachments',
                'status',
                'priority',
                'user',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a ticket with given data.
     *
     * @param Ticket $ticket
     * @param array $data
     * @return Ticket|null
     * @throws \Exception
     */
    public function updateTicket(Ticket $ticket, array $data): ?Ticket
    {
        DB::beginTransaction();
        try {
            $hasModelChanges = $this->ticketUpdater->fillTicket($ticket, $data);

            [$currentCategories, $incomingCategories, $shouldSyncCategories] = $this->ticketUpdater->compareCategories($ticket, $data);
            [$currentLabels, $incomingLabels, $shouldSyncLabels] = $this->ticketUpdater->compareLabels($ticket, $data);

            if (isset($data['attachments_to_remove'])) {
                foreach ($data['attachments_to_remove'] as $attachmentId) {
                    $this->attachmentService->deleteAttachment($attachmentId);
                }
            }

            if (isset($data['attachments'])) {
                foreach ($data['attachments'] as $file) {
                    $uploadedBy = $actor ?? Auth::id();
                    $this->attachmentService->uploadAttachment($ticket, $file, $uploadedBy);
                }
            }

            if (! $hasModelChanges && ! $shouldSyncCategories && ! $shouldSyncLabels) {
                DB::commit();
                return $ticket->fresh(['labels', 'categories', 'attachments', 'status', 'priority', 'user']);
            }

            if ($hasModelChanges) {
                $ticket->save();
            }

            if ($shouldSyncCategories) {
                $this->ticketUpdater->syncCategories($ticket, $incomingCategories);
            }

            if ($shouldSyncLabels) {
                $this->ticketUpdater->syncLabels($ticket, $incomingLabels);
            }

            $modelChanges = $ticket->getChanges();
            $relationChanges = [];
            if ($shouldSyncCategories) {
                $relationChanges['categories'] = [
                    'before' => $currentCategories,
                    'after' => $incomingCategories,
                ];
            }
            if ($shouldSyncLabels) {
                $relationChanges['labels'] = [
                    'before' => $currentLabels,
                    'after' => $incomingLabels,
                ];
            }

            $this->ticketUpdater->logUpdate($ticket, $modelChanges, $relationChanges);

            DB::commit();
            return $ticket->fresh(['labels', 'categories', 'attachments', 'status', 'priority', 'user']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a ticket along with its attachments.
     *
     * @param Ticket $ticket
     * @return bool
     * @throws Throwable
     */
    public function deleteTicket(Ticket $ticket): bool
    {
        try {
            $ticket = $this->ticketRepository->find($ticket->id) ?? $ticket;

            $ok = $this->attachmentService->deleteAttachmentsForTicket($ticket);
            if (! $ok) {
                throw new \RuntimeException('Failed to delete attachments for ticket: ' . ($ticket->id ?? 'unknown'));
            }

            $result = $this->ticketRepository->delete($ticket->id);
            return $result;
        } catch (Throwable $e) {
            Log::error('Ticket deletion failed', [
                'ticket_id' => $ticket->id ?? null,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get the count of tickets for a specific user.
     *
     * @param mixed $userId
     * @return int
     */
    public function getUserTicketCount($userId): int
    {
        return $this->ticketRepository->countByUserId($userId);
    }

    /**
     * Get the count of tickets assigned to a specific user.
     *
     * @param mixed $userId
     * @return int
     */
    public function getAssignedTicketCount($userId): int
    {
        return $this->ticketRepository->countByAssignedUserId($userId);
    }

    /**
     * Get a ticket with specified relations loaded.
     *
     * @param int $id
     * @param array $relations
     * @return Ticket|null
     */
    public function getTicketWithRelations(int $id, array $relations = []): ?Ticket
    {
        return $this->ticketRepository->findWithRelations($id, $relations);
    }

    /**
     * Get the total count of tickets.
     *
     * @return int
     */
    public function getTotalTicketCount() : int
    {
        return $this->ticketRepository->countAll();
    }

    /**
     * Get the count of tickets by status.
     *
     * @param string $status
     * @return int
     */
    public function getTicketCountByStatus(string $status) : int
    {
        $statusModel = $this->statusService->getStatusByName($status);
        if (!$statusModel) {
            return 0;
        }

        return $this->ticketRepository->countByStatus($statusModel->id);
    }
}
