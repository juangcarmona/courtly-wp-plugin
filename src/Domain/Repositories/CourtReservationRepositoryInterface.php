<?php

namespace Juangcarmona\Courtly\Domain\Repositories;

use DateTimeInterface;
use Juangcarmona\Courtly\Domain\Entities\CourtReservation;

interface CourtReservationRepositoryInterface
{
    /**
     * Get reservations for a specific court and date.
     */
    public function getReservationsForDate(int $courtId, string $date): array;

    /**
     * Get all reservations for a user on a specific date.
     */
    public function getReservationsForUserOnDate(int $userId, string $date): array;

    /**
     * Find a reservation by ID.
     */
    public function findById(int $id): ?CourtReservation;

    /**
     * Find all reservations between two dates.
     */
    public function findBetweenDates(DateTimeInterface $start, DateTimeInterface $end): array;

    /**
     * Find reservations starting from a given day (forward range).
     */
    public function findFrom(DateTimeInterface $fromDay, int $rangeInDays = 30): array;

    /**
     * Find reservations before a given day (backward range).
     */
    public function findBefore(DateTimeInterface $fromDay, int $rangeInDays = 30): array;

    /**
     * Insert a new reservation.
     */
    public function insertReservation(array $data): bool;

    /**
     * Delete a reservation by ID.
     */
    public function deleteReservation(int $id): bool;
}
