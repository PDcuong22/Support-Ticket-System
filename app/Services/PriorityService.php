<?php

namespace App\Services;

use App\Interfaces\PriorityRepositoryInterface;

class PriorityService
{
    protected $priorityRepository;

    public function __construct(PriorityRepositoryInterface $priorityRepository)
    {
        $this->priorityRepository = $priorityRepository;
    }

    public function getAllPriorities()
    {
        return $this->priorityRepository->all();
    }

    public function createPriority(array $data)
    {
        return $this->priorityRepository->create($data);
    }

    public function updatePriority($id, array $data)
    {
        return $this->priorityRepository->update($id, $data);
    }

    public function deletePriority($id)
    {
        return $this->priorityRepository->delete($id);
    }
}