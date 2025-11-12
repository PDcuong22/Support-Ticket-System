<?php

namespace App\Services;

use App\Interfaces\TicketRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Services\AttachmentService;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    protected $ticketRepository;
    protected $attachmentService;

    public function __construct(TicketRepositoryInterface $ticketRepository, AttachmentService $attachmentService)
    {
        $this->ticketRepository = $ticketRepository;
        $this->attachmentService = $attachmentService;
    }

    public function getAllTickets()
    {
        return $this->ticketRepository->all();
    }

    public function getTicketById($id)
    {
        return $this->ticketRepository->find($id);
    }

    public function createTicket(array $data)
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
                    $this->attachmentService->uploadAttachment($ticket, $file);
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

    public function updateTicket(Ticket $ticket, array $data)
    {
        DB::beginTransaction();
        try {
            $ticket->fill([
                'title' => $data['title'] ?? $ticket->title,
                'description' => $data['description'] ?? $ticket->description,
                'user_id' => $data['user_id'] ?? $ticket->user_id,
                'priority_id' => $data['priority_id'] ?? $ticket->priority_id,
                'status_id' => $data['status_id'] ?? $ticket->status_id,
                'assigned_to' => $data['assigned_to'] ?? $ticket->assigned_to,
            ]);

            $hasModelChanges = $ticket->isDirty();

            // compare categories/labels (produce arrays for possible log)
            $currentCategories = $ticket->relationLoaded('categories')
                ? $ticket->categories->pluck('id')->map('strval')->sort()->values()->all()
                : $ticket->categories()->pluck('categories.id')->map('strval')->sort()->values()->all();

            $incomingCategories = isset($data['categories'])
                ? collect($data['categories'])->map('strval')->sort()->values()->all()
                : $currentCategories;

            $shouldSyncCategories = $currentCategories !== $incomingCategories;

            $currentLabels = $ticket->relationLoaded('labels')
                ? $ticket->labels->pluck('id')->map('strval')->sort()->values()->all()
                : $ticket->labels()->pluck('labels.id')->map('strval')->sort()->values()->all();

            $incomingLabels = isset($data['labels'])
                ? collect($data['labels'])->map('strval')->sort()->values()->all()
                : $currentLabels;

            $shouldSyncLabels = $currentLabels !== $incomingLabels;

            if (! $hasModelChanges && ! $shouldSyncCategories && ! $shouldSyncLabels) {
                DB::commit();
                return $ticket->fresh(['labels','categories','attachments','status','priority','user']);
            }

            if ($hasModelChanges) {
                $ticket->save();
            }

            if ($shouldSyncCategories && method_exists($ticket, 'categories')) {
                $ticket->categories()->sync($incomingCategories);
            }

            if ($shouldSyncLabels && method_exists($ticket, 'labels')) {
                $ticket->labels()->sync($incomingLabels);
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

            TicketLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action' => 'updated',
                'changes' => array_filter([
                    'attributes' => $modelChanges ?: null,
                    'relations' => $relationChanges ?: null,
                ]),
            ]);

            DB::commit();
            return $ticket->fresh(['labels','categories','attachments','status','priority','user']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteTicket($id)
    {
        return $this->ticketRepository->delete($id);
    }

    public function getUserTicketCount($userId)
    {
        return $this->ticketRepository->countByUserId($userId);
    }
}