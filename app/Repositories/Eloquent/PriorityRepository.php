<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\PriorityRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Priority;

class PriorityRepository extends BaseRepository implements PriorityRepositoryInterface
{
    protected $model;

    public function __construct(Priority $model)
    {
        $this->model = $model;
    }
}