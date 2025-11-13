<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return Collection<int, Model>
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->all();
    }

    /**
     * @param array $relations
     * @return Collection<int, Model>
     */
    public function getAllUsersWithRelations(array $relations): Collection
    {
        return $this->userRepository->allWith($relations);
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function createUser(array $data): Model
    {
        return $this->userRepository->create($data);
    }

    /**
     * @param mixed $id
     * @param array $data
     * @return Model|null
     */
    public function updateUser($id, array $data): ?Model
    {
        return $this->userRepository->update($id, $data);
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function deleteUser($id): bool
    {
        return $this->userRepository->delete($id);
    }

    /**
     * @param string $roleName
     * @return Collection<int, Model>
     */
    public function getUsersByRole(string $roleName): Collection
    {
        return $this->userRepository->findByRole($roleName);
    }

    /**
     * @return int
     */
    public function getUsersCount() : int
    {
        return $this->userRepository->countAll();
    }
}