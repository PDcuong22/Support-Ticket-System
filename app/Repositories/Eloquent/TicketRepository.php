<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\TicketRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
    protected $model;

    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }

    public function allWith(array $relations) : Builder
    {
        return $this->model->with($relations);
    }

    public function countByUserId($userId): int
    {
        return $this->model->where('user_id', $userId)->count();
    }

    public function countByAssignedUserId($userId): int
    {
        return $this->model->where('assigned_user_id', $userId)->count();
    }

    public function findWithRelations(int $id, array $relations = []): ?Model
    {
        return $this->model->with($relations)->find($id);
    }

    public function countAll() : int
    {
        return $this->model->count();
    }

    public function countByStatus($status) : int
    {
        return $this->model->where('status_id', $status)->count();
    }
}