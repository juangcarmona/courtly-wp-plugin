<?php

namespace Juangcarmona\Courtly\Domain\Repositories;
use Juangcarmona\Courtly\Domain\Entities\Court;
interface CourtRepositoryInterface
{
    /** @return Court[] */
    public function findAll(): array;

    public function findById(int $id): ?Court;

    public function insert(array $data): bool;

    public function delete(int $id): bool;
}
