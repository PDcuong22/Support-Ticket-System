<?php

namespace App\Services;

use App\Interfaces\StatusRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class StatusService
{
    protected $statusRepository;

    public function __construct(StatusRepositoryInterface $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function getAllStatuses()
    {
        return $this->statusRepository->all();
    }

    public function createStatus(array $data)
    {
        return $this->statusRepository->create($data);
    }

    public function updateStatus($id, array $data)
    {
        return $this->statusRepository->update($id, $data);
    }

    public function deleteStatus($id)
    {
        return $this->statusRepository->delete($id);
    }

    public function getStatusByName(string $name): ?Model
    {
        return $this->statusRepository->findByName($name);
    }
}