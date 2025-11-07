<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\CommentRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Comment;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    protected $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }
}