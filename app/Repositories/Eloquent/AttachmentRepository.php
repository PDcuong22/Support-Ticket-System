<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\AttachmentRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Collection;

class AttachmentRepository extends BaseRepository implements AttachmentRepositoryInterface
{
    protected $model;

    public function __construct(Attachment $model)
    {
        $this->model = $model;
    }

    public function getByTicket($ticketId): Collection
    {
        return $this->model->where('ticket_id', $ticketId)->get();
    }
}