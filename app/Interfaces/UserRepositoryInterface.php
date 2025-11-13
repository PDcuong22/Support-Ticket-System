<?php

namespace App\Interfaces;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function allWith(array $relations) : Collection;
    public function findByRole(string $roleName) : Collection;
    public function countAll() : int;
}