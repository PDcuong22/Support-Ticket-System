<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Services\TicketService;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;

class AdminTicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }
    public function index()
    {
        $tickets = $this->ticketService->getAllTickets();
        return view('admin.tickets.index', compact('tickets'));
    }

    public function edit($id)
    {
        $agents = User::whereHas('role', function ($query) {
            $query->where('name', 'Support Agent');
        })->get();
        $categories = Ticket::findOrFail($id)->categories;
        $labels = Ticket::findOrFail($id)->labels;
        $ticket = Ticket::with(['user', 'status', 'priority', 'attachments'])->findOrFail($id);
        return view('admin.tickets.edit', compact('ticket', 'agents', 'categories', 'labels'));
    }

    public function update(StoreTicketRequest $request, Ticket $ticket)
    {        
        $validatedData = $request->validated();
        $this->ticketService->updateTicket($ticket, $validatedData);

        return redirect()->route('admin.tickets')->with('success', 'Ticket updated successfully.');
    }
}
