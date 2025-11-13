<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\StatusRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;

class StatusRepository extends BaseRepository implements StatusRepositoryInterface
{
    protected $model;

    public function __construct(Status $model)
    {
        $this->model = $model;
    }

    public function findByName(string $name) : ?Model
    {
        return $this->model->where('name', $name)->first();
    }
}