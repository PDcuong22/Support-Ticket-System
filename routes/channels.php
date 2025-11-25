<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Ticket;

Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    $ticket = Ticket::find($ticketId);
    if (! $user || ! $ticket) return false;

    return $user->id === $ticket->user_id
        || $user->id === $ticket->assigned_user_id
        || in_array(optional($user->role)->name, ['Admin', 'Support Agent']);
});