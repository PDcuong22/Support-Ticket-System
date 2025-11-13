<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<int, Model>
     */
    public function all() : Collection
    {
        return $this->model->all();
    }

    /**
     * @param mixed $id
     * @return Model|null
     */
    public function find($id) : ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data) : Model
    {
        return $this->model->create($data);
    }

    /**
     * @param mixed $id
     * @param array $data
     * @return Model|null
     */
    public function update($id, array $data) : ?Model
    {
        $record = $this->find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function delete($id) : bool
    {
        $record = $this->find($id);
        if ($record) {
            return (bool) $record->delete();
        }
        return false;
    }
}