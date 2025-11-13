<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Support\Facades\Auth;

class TicketUpdater
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    /**
     * Fill the ticket model with incoming data.
     * 
     * @param Ticket $ticket
     * @param array $data
     * @return bool Returns true if any attributes were changed, false otherwise.
     */
    public function fillTicket(Ticket $ticket, array $data): bool
    {
        $ticket->fill([
            'title' => $data['title'] ?? $ticket->title,
            'description' => $data['description'] ?? $ticket->description,
            'user_id' => $data['user_id'] ?? $ticket->user_id,
            'priority_id' => $data['priority_id'] ?? $ticket->priority_id,
            'status_id' => $data['status_id'] ?? $ticket->status_id,
            'assigned_user_id' => $data['assigned_user_id'] ?? $ticket->assigned_user_id,
        ]);

        return $ticket->isDirty();
    }

    /**
     * Compare the current and incoming categories for a ticket.
     * 
     * @param Ticket $ticket
     * @param array $data
     * @return array Returns an array with current categories, incoming categories, and a boolean indicating if they differ.
     */
    public function compareCategories(Ticket $ticket, array $data): array
    {
        $current = $ticket->relationLoaded('categories')
            ? $ticket->categories->pluck('id')->map('strval')->sort()->values()->all()
            : $ticket->categories()->pluck('categories.id')->map('strval')->sort()->values()->all();

        $incoming = isset($data['categories'])
            ? collect($data['categories'])->map('strval')->sort()->values()->all()
            : $current;

        return [$current, $incoming, $current !== $incoming];
    }

    /**
     * Compare the current and incoming labels for a ticket.
     * 
     * @param Ticket $ticket
     * @param array $data
     * @return array Returns an array with current labels, incoming labels, and a boolean indicating if they differ.
     */
    public function compareLabels(Ticket $ticket, array $data): array
    {
        $current = $ticket->relationLoaded('labels')
            ? $ticket->labels->pluck('id')->map('strval')->sort()->values()->all()
            : $ticket->labels()->pluck('labels.id')->map('strval')->sort()->values()->all();

        $incoming = isset($data['labels'])
            ? collect($data['labels'])->map('strval')->sort()->values()->all()
            : $current;

        return [$current, $incoming, $current !== $incoming];
    }

    /**
     * Sync categories for a ticket.
     * 
     * @param Ticket $ticket
     * @param array $incomingCategories
     * @return void
     */
    public function syncCategories(Ticket $ticket, array $incomingCategories): void
    {
        $ticket->categories()->sync($incomingCategories ?? []);
    }

    /**
     * Sync labels for a ticket.
     * 
     * @param Ticket $ticket
     * @param array $incomingLabels
     * @return void
     */
    public function syncLabels(Ticket $ticket, array $incomingLabels): void
    {
        $ticket->labels()->sync($incomingLabels ?? []);
    }

    /**
     * Log the ticket update action.
     * 
     * @param Ticket $ticket
     * @param array $modelChanges
     * @param array $relationChanges
     * @return void
     */
    public function logUpdate(Ticket $ticket, array $modelChanges = [], array $relationChanges = []): void
    {
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'changes' => array_filter([
                'attributes' => $modelChanges ?: null,
                'relations' => $relationChanges ?: null,
            ]),
        ]);
    }
}