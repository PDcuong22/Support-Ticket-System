<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListTicketRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\Status;
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

    public function index(ListTicketRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $with = ['user', 'assignedUser', 'status', 'priority', 'categories', 'labels', 'attachments'];

        $query = Ticket::with($with);

        if (!empty($data['q'])) {
            $q = $data['q'];
            $query->where(function ($qbuilder) use ($q) {
                $qbuilder->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if (!empty($data['status_id'])) {
            $query->where('status_id', $data['status_id']);
        }

        if (!empty($data['priority_id'])) {
            $query->where('priority_id', $data['priority_id']);
        }

        if (!empty($data['category_id']) && is_array($data['category_id'])) {
            $categoryIds = $data['category_id'];
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        $perPage = $data['size'] ?? 10;

        if (! $user->can('viewAny', Ticket::class)) {
            $query->where(fn($q) => $q->where('user_id', $user->id)->orWhere('assigned_user_id', $user->id));
        }
        $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return TicketResource::collection($paginator);
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

            $ticket = $this->ticketService->createTicket($validatedData, $request->user());

            $ticket->load(['user', 'assignedUser', 'status', 'priority', 'categories', 'labels']);

            return new TicketResource($ticket);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create ticket', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(StoreTicketRequest $request, Ticket $ticket)
    {
        try {
            $validatedData = $request->validated();
            $res = $this->ticketService->updateTicket($ticket, $validatedData, $request->user());

            return new TicketResource($res);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update ticket', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Ticket $ticket)
    {
        try {
            $this->authorize('delete', $ticket);
            $this->ticketService->deleteTicket($ticket);

            return response()->json(['message' => 'Ticket deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete ticket', 'error' => $e->getMessage()], 500);
        }
    }

    public function stats()
    {
        try {
            $user = request()->user();
            if ($user->can('viewAny', Ticket::class)) {
                $total = Ticket::count();
                $open = $this->ticketService->getTicketCountByStatus('Open');
                $closed = $this->ticketService->getTicketCountByStatus('Closed');
                return response()->json(['data' => compact('total', 'open', 'closed')]);
            } else {
                $total = Ticket::where('user_id', $user->id)
                    ->orWhere('assigned_user_id', $user->id)
                    ->count();
                return response()->json(['data' => compact('total')], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
