<?php

namespace App\Repositories\Eloquent;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByRole($roleId)
    {
        return $this->model->where('role_id', $roleId)->get();
    }
}