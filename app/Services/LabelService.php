<?php

namespace App\Services;

use App\Interfaces\LabelRepositoryInterface;

class LabelService
{
    protected $labelRepository;

    public function __construct(LabelRepositoryInterface $labelRepository)
    {
        $this->labelRepository = $labelRepository;
    }

    public function getAllLabels()
    {
        return $this->labelRepository->all();
    }

    public function createLabel(array $data)
    {
        return $this->labelRepository->create($data);
    }

    public function updateLabel($id, array $data)
    {
        return $this->labelRepository->update($id, $data);
    }

    public function deleteLabel($id)
    {
        return $this->labelRepository->delete($id);
    }

    public function countAll() : int
    {
        return $this->labelRepository->countAll();
    }
}