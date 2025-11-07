<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\RoleRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Role;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }
}