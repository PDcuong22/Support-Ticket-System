<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\CategoryRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}