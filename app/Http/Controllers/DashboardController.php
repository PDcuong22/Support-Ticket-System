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
        $user = Auth::user();
        $userTicketCount = 0;
        if (! $user) {
            return redirect()->route('login');
        }
        if (optional($user->role)->name === 'User') {
            $userTicketCount = $this->ticketService->getUserTicketCount($user->id);
        } elseif(optional($user->role)->name === 'Support Agent') {
            $userTicketCount = $this->ticketService->getAssignedTicketCount($user->id);
        } 
        return view('dashboard', compact('userTicketCount'));
    }
}
