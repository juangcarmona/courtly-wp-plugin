<?php

namespace Juangcarmona\Courtly\Domain\Repositories;

interface CourtRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?object;

    public function insert(array $data): bool;

    public function delete(int $id): bool;
}
