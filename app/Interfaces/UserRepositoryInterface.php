<?php

namespace App\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByRole($roleId);
}