<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
interface CommentRepositoryInterface extends BaseRepositoryInterface {
    public function findByTicketId($ticketId): Builder;
}
