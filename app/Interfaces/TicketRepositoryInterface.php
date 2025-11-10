<?php

namespace App\Interfaces;

interface TicketRepositoryInterface extends BaseRepositoryInterface {
    public function countByUserId($userId);
}