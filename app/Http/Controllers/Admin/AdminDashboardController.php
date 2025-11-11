<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use App\Models\Label;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tickets' => 3,
            'total_users' => 10,
            'total_categories' => 5,
            'total_labels' => 8,
            'open_tickets' => 2,
            'resolved_tickets' => 1,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function tickets()
    {
        $tickets = Ticket::with(['user', 'status', 'priority'])->latest()->paginate(20);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function users()
    {
        $users = User::where('role', 'user')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function categories()
    {
        $categories = Category::withCount('tickets')->latest()->paginate(20);
        return view('admin.categories', compact('categories'));
    }

    public function labels()
    {
        $labels = Label::withCount('tickets')->latest()->paginate(20);
        return view('admin.labels', compact('labels'));
    }

    public function ticketLogs()
    {
        return view('admin.ticket-logs');
    }
}