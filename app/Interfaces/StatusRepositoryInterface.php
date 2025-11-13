<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface StatusRepositoryInterface extends BaseRepositoryInterface {
    public function findByName(string $name) : ?Model;
}