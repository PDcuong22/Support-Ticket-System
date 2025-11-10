<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Ticket $ticket)
    {
        try {
            $ticket->comments()->create([
                'content' => $request->input('content'),
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('tickets.show', $ticket)->with('success', 'Comment added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tickets.show', $ticket)->with('error', 'Failed to add comment.' . $e->getMessage());
        }
    }

    public function destroy(Ticket $ticket, $commentId)
    {
        try {
            $comment = $ticket->comments()->findOrFail($commentId);
            
            if($comment->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
            $comment->delete();

            return redirect()->route('tickets.show', $ticket)->with('success', 'Comment deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tickets.show', $ticket)->with('error', 'Failed to delete comment.' . $e->getMessage());
        }
    }
}
