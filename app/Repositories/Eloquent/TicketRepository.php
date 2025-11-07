<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\TicketRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Ticket;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
    protected $model;

    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }
}