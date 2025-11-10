<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TicketService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        $userTicketCount = $this->ticketService->getUserTicketCount(Auth::id());
        return view('dashboard', compact('userTicketCount'));
    }
}
