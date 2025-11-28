<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    Log::info("User {$user->id} is attempting to access ticket channel {$ticketId}");
    $ticket = Ticket::find($ticketId);
    if (! $user || ! $ticket) return false;

    return $user->id === $ticket->user_id
        || $user->id === $ticket->assigned_user_id
        || in_array(optional($user->role)->name, ['Admin']);
});
