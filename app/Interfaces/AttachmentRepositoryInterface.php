<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface AttachmentRepositoryInterface extends BaseRepositoryInterface {
    public function getByTicket($ticketId) : Collection;
}
