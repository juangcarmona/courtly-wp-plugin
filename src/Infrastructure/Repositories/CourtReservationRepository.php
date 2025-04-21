<?php

namespace Juangcarmona\Courtly\Infrastructure\Repositories;

use Juangcarmona\Courtly\Domain\Entities\CourtReservation;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;
use DateTime;
use DateTimeInterface;

class CourtReservationRepository implements CourtReservationRepositoryInterface
{
    private $wpdb;
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'courtly_reservations';
    }

    public function getReservationsForDate(int $courtId, string $date): array
    {
        $rows = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table} 
                 WHERE court_id = %d AND reservation_date = %s",
                $courtId,
                $date
            )
        );
    
        return array_map([$this, 'mapRowToEntity'], $rows);
    }
    
    public function getReservationsForUserOnDate(int $userId, string $date): array
    {
        $rows = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table} 
                 WHERE user_id = %d AND reservation_date = %s",
                $userId,
                $date
            )
        );
    
        return array_map([$this, 'mapRowToEntity'], $rows);
    }
    
    public function findById(int $id): ?CourtReservation
    {
        $row = $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id)
        );

        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function findBetweenDates(DateTimeInterface $start, DateTimeInterface $end): array
    {
        $rows = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table}
                 WHERE reservation_date BETWEEN %s AND %s",
                $start->format('Y-m-d'),
                $end->format('Y-m-d')
            )
        );

        return array_map([$this, 'mapRowToEntity'], $rows);
    }

    public function findFrom(DateTimeInterface $fromDay, int $rangeInDays = 30): array
    {
        $end = (clone $fromDay)->modify("+$rangeInDays days");
        return $this->findBetweenDates($fromDay, $end);
    }

    public function findBefore(DateTimeInterface $fromDay, int $rangeInDays = 30): array
    {
        $start = (clone $fromDay)->modify("-$rangeInDays days");
        return $this->findBetweenDates($start, $fromDay);
    }

    public function insertReservation(array $data): bool
    {
        return $this->wpdb->insert($this->table, $data) !== false;
    }

    public function deleteReservation(int $id): bool
    {
        return $this->wpdb->delete($this->table, ['id' => $id]) !== false;
    }

    private function mapRowToEntity(object $row): CourtReservation
    {
        return new CourtReservation(
            $row->id,
            $row->user_id,
            $row->court_id,
            new DateTime($row->reservation_date),
            $row->time_slot,
            new DateTime($row->created_at)
        );
    }
}
