<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;

class CommentCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment->load('user');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('ticket.' . $this->comment->ticket_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->comment->id,
            'content' => $this->comment->content,
            'user_name' => optional($this->comment->user)->name,
            'created_at' => $this->comment->created_at->toDateTimeString(),
        ];
    }

    public function broadcastAs()
    {
        return 'CommentCreated';
    }
}
