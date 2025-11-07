<?php

namespace App\Services;

use App\Interfaces\TicketRepositoryInterface;

class TicketService
{
    protected $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function getAllTickets()
    {
        return $this->ticketRepository->all();
    }

    public function createTicket(array $data)
    {
        return $this->ticketRepository->create($data);
    }

    public function updateTicket($id, array $data)
    {
        return $this->ticketRepository->update($id, $data);
    }

    public function deleteTicket($id)
    {
        return $this->ticketRepository->delete($id);
    }
}