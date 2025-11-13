<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\LabelRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Label;

class LabelRepository extends BaseRepository implements LabelRepositoryInterface
{
    protected $model;

    public function __construct(Label $model)
    {
        $this->model = $model;
    }

    public function countAll() : int
    {
        return $this->model->count();
    }
}