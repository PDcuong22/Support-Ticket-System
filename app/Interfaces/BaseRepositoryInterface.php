<?php

namespace App\Interfaces;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all() : Collection;
    public function find($id) : ?Model;
    public function create(array $data) : Model;
    public function update($id, array $data) : ?Model;
    public function delete($id) : bool;
}