<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index($ticketId)
    {
        $comments = Comment::with('user')
            ->where('ticket_id', $ticketId)
            ->orderBy('created_at', 'asc')
            ->get();
        return CommentResource::collection($comments);
    }

    public function store(Request $request, $ticketId)
    {
        $user = $request->user();
        $data = $request->validate([
            'content' => 'required|string',
        ]);
        $data['ticket_id'] = $ticketId;
        $data['user_id'] = $user->id;
        $comment = $this->commentService->createComment($data);

        event(new CommentCreated($comment));

        return new CommentResource($comment);
    }
}
