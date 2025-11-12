<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketLog;
use Illuminate\Http\Request;

class TicketLogController extends Controller
{
    public function index(Request $request)
    {
        $query = TicketLog::with(['ticket', 'user'])->latest();

        if ($request->filled('ticket_id')) {
            $query->where('ticket_id', $request->ticket_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('admin.ticket-logs.index', compact('logs'));
    }
}