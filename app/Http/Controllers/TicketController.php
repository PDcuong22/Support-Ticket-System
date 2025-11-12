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

    public function index()
    {
        $tickets = $this->ticketService->getAllTickets();
        return view('tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = $this->ticketService->getTicketById($id);
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
