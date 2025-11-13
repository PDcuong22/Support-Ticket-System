<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Services\TicketService;
use App\Services\UserService;
use App\Services\StatusService;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;

class AdminTicketController extends Controller
{
    protected $ticketService;
    protected $userService;
    protected $statusService;

    public function __construct(TicketService $ticketService, UserService $userService, StatusService $statusService)
    {
        $this->ticketService = $ticketService;
        $this->userService = $userService;
        $this->statusService = $statusService;
    }

    public function index(Request $request)
    {
        $status = $request->query('status'); 
        $query = $this->ticketService->getAllTicketsWithRelations(['status', 'priority', 'categories', 'labels']);

        if (! empty($status)) {
            $statusModel = $this->statusService->getStatusByName($status);
            if ($statusModel) {
                $query->where('status_id', $statusModel->id);
            }
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.tickets.index', compact('tickets', 'status'));
    }

    public function edit(Ticket $ticket)
    {
        $agents = $this->userService->getUsersByRole('Support Agent');
        $ticket = $this->ticketService->getTicketWithRelations($ticket->id, ['user','status', 'priority', 'categories', 'labels', 'attachments', 'assignedUser']);
        return view('admin.tickets.edit', compact('ticket', 'agents'));
    }

    public function update(StoreTicketRequest $request, Ticket $ticket)
    {        
        $validatedData = $request->validated();
        $this->ticketService->updateTicket($ticket, $validatedData);

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $this->ticketService->deleteTicket($ticket);
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
