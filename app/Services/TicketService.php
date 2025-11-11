<?php

namespace App\Services;

use App\Interfaces\TicketRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Services\AttachmentService;

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

    public function updateTicket($id, array $data)
    {
        return $this->ticketRepository->update($id, $data);
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