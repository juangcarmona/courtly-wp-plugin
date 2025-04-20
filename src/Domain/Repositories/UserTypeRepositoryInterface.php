<?php

namespace Juangcarmona\Courtly\Domain\Repositories;

interface UserTypeRepositoryInterface
{
    public function findAll(): array;

    public function countAll(): int;

    public function getUserTypeBreakdown(): array;

    public function findByName(string $name): ?object; // TODO: Replace `object` with UserType entity

    public function insert(array $data): bool;
}
