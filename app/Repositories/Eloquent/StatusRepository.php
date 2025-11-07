<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\StatusRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Status;

class StatusRepository extends BaseRepository implements StatusRepositoryInterface
{
    protected $model;

    public function __construct(Status $model)
    {
        $this->model = $model;
    }
}