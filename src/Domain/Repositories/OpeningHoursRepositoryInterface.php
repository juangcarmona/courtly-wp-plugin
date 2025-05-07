<?php

namespace Juangcarmona\Courtly\Domain\Repositories;

interface OpeningHoursRepositoryInterface
{
    public function getAll(): array;

    public function getByDayOfWeek(int $dayOfWeek): ?object; // TODO: Replace `object` with OpeningHours entity

    public function upsert(int $dayOfWeek, array $timeRanges, bool $closed): bool;
}
