<?php

namespace App\Repositories\Eloquent;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $relations
     * @return Collection<int, Model>
     */
    public function allWith(array $relations): Collection
    {
        return $this->model->with($relations)->get();
    }

    /**
     * @param string $roleName
     * @return Collection<int, Model>
     */
    public function findByRole(string $roleName): Collection
    {
        if (method_exists($this->model, 'roles')) {
            return $this->model->whereHas('roles', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            })->get();
        }

        if (method_exists($this->model, 'role')) {
            return $this->model->whereHas('role', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            })->get();
        }
        
        return $this->model->select('users.*')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('roles.name', $roleName)
            ->get();
    }

    public function countAll() : int
    {
        return $this->model->count();
    }
}