<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\CommentRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    protected $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function findByTicketId($ticketId) : Builder
    {
        return $this->model->where('ticket_id', $ticketId);
    }
}
