<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use App\Models\Label;

use App\Services\TicketService;
use App\Services\UserService;
use App\Services\CategoryService;
use App\Services\LabelService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    protected $ticketService;
    protected $userService;
    protected $categoryService;
    protected $labelService;

    public function __construct(TicketService $ticketService, UserService $userService, CategoryService $categoryService, LabelService $labelService)
    {
        $this->ticketService = $ticketService;
        $this->userService = $userService;
        $this->categoryService = $categoryService;
        $this->labelService = $labelService;
    }
    public function index()
    {
        $stats = [
            'total_tickets' => $this->ticketService->getTotalTicketCount(),
            'total_users' => $this->userService->getUsersCount(),
            'total_categories' => $this->categoryService->countAll(),
            'total_labels' => $this->labelService->countAll(),
            'open_tickets' => $this->ticketService->getTicketCountByStatus('Open'),
            'resolved_tickets' => $this->ticketService->getTicketCountByStatus('Resolved'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

}