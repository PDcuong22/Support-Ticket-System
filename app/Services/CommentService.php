<?php

namespace App\Services;

use App\Interfaces\CommentRepositoryInterface;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getAllComments()
    {
        return $this->commentRepository->all();
    }

    public function createComment(array $data)
    {
        return $this->commentRepository->create($data);
    }

    public function updateComment($id, array $data)
    {
        return $this->commentRepository->update($id, $data);
    }

    public function deleteComment($id)
    {
        return $this->commentRepository->delete($id);
    }
}