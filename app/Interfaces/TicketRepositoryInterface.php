<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
    public function allWith(array $relations) : Builder;
    public function countByUserId($userId): int;
    public function countByAssignedUserId($userId): int;
    public function findWithRelations(int $id, array $relations = []): ?Model;
    public function countAll() : int;
    public function countByStatus(string $status) : int;
}
