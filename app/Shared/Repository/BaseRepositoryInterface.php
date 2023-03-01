<?php

namespace App\Shared\Repository;

use App\Shared\Entity\BaseEntity;

interface BaseRepositoryInterface
{
    public function store(BaseEntity $entity): int;
    public function show(int $id): ?BaseEntity;
    public function update(int $id, BaseEntity $entity): bool;    
    public function destroy(int $id): bool;    
    public function setTransaction(bool $value): void;
    public function startTransaction(): void;
    public function commitTransaction(): void;
    public function rollBackTransaction(): void;
}
