<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if(!$user){
            return false;
        }

        return $this->isAdmin($user)
            || $user->id === $ticket->user_id
            || $user->id === $ticket->assigned_user_id;
    }

    public function comment(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return (bool) $user;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $this->isAdminOrAgent($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return false;
    }

    protected function getRoleName(User $user): ?string
    {
        if (method_exists($user, 'role') && $user->role) {
            return optional($user->role)->name;
        }
        return $user->role ?? null;
    }

    protected function isAdmin(User $user): bool
    {
        return strcasecmp($this->getRoleName($user) ?? '', 'admin') === 0;
    }

    protected function isAdminOrAgent(User $user): bool
    {
        $r = strtolower($this->getRoleName($user) ?? '');
        return in_array($r, ['admin', 'support agent', 'agent', 'support'], true);
    }
}
