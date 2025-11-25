<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\TicketService;

class TicketController extends Controller
{
    use AuthorizesRequests;
    protected $ticketService;
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $with = ['user', 'assignedUser', 'status', 'priority', 'categories', 'labels', 'attachments'];

        $query = Ticket::with($with)->orderBy('created_at', 'desc');

        if (! $user->can('viewAny', Ticket::class)) {
            $query->where(fn($q) => $q->where('user_id', $user->id)->orWhere('assigned_user_id', $user->id));
        }

        $tickets = $query->paginate(10);

        return TicketResource::collection($tickets);
    }

    public function show(Ticket $ticket)
    {
        $user = request()->user();
        $ticket->load(['status', 'priority', 'categories', 'labels', 'attachments']);
        if ($user->can('viewAny', Ticket::class)) {
            $ticket->load(['user', 'assignedUser']);
        }
        return new TicketResource($ticket);
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = $request->user()->id;
            $validatedData['status_id'] = 1;

            $ticket = $this->ticketService->createTicket($validatedData);

            $ticket->load(['user', 'assignedUser', 'status', 'priority', 'categories', 'labels']);

            return new TicketResource($ticket);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create ticket', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(StoreTicketRequest $request, Ticket $ticket)
    {
        $validatedData = $request->validated();
        $this->ticketService->updateTicket($ticket, $validatedData);

        $ticket->load(['user', 'assignedUser', 'status', 'priority', 'categories', 'labels']);

        return new TicketResource($ticket);
    }
}
