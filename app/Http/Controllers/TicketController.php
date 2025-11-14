<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Services\TicketService;
use App\Services\LabelService;
use App\Services\StatusService;
use App\Services\PriorityService;
use App\Services\CategoryService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Ticket;

class TicketController extends Controller
{
    protected $ticketService;
    protected $labelService;
    protected $statusService;
    protected $priorityService;
    protected $categoryService;

    public function __construct(TicketService $ticketService, LabelService $labelService, StatusService $statusService, PriorityService $priorityService, CategoryService $categoryService)
    {
        $this->ticketService = $ticketService;
        $this->labelService = $labelService;
        $this->statusService = $statusService;
        $this->priorityService = $priorityService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $statusId = $request->query('status');
        $priorityId = $request->query('priority');
        $categoryId = $request->query('category');

        $query = $this->ticketService->getAllTicketsWithRelations(['status', 'priority', 'categories', 'labels']);

        $user = Auth::user();
        if ($user && optional($user->role)->name === 'Support Agent') {
            $query->where('assigned_user_id', $user->id);
        } elseif ($user && optional($user->role)->name === 'User') {
            $query->where('user_id', $user->id);
        }

        if (! empty($statusId)) {
            $query->where('status_id', $statusId);
        }

        if (! empty($priorityId)) {
            $query->where('priority_id', $priorityId);
        }

        if (! empty($categoryId)) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = $this->ticketService->getTicketWithRelations($id, ['status', 'priority', 'categories', 'labels', 'attachments']);
        return view('tickets.show', compact('ticket'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = Auth::id();
            $validatedData['status_id'] = 1;

            $ticket = $this->ticketService->createTicket($validatedData);

            return redirect()->route('tickets.show', compact('ticket'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create ticket: ' . $e->getMessage()]);
        }
    }
}
