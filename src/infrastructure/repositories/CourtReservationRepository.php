<?php

class CourtReservationRepository
{
    private $wpdb;
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'courtly_reservations';
    }

    public function getReservationsForDate($courtId, $date)
    {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table} 
                 WHERE court_id = %d AND reservation_date = %s",
                $courtId,
                $date
            )
        );
    }

    public function getReservationsForUserOnDate(int $userId, string $date): array {
        global $wpdb;
        $table = $wpdb->prefix . 'courtly_reservations';
    
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE user_id = %d AND reservation_date = %s",
                $userId,
                $date
            )
        );
    }

    public function findById(int $id): ?CourtReservation {
        global $wpdb;
        $row = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id)
        );
        
        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function findBetweenDates(DateTimeInterface $start, DateTimeInterface $end): array {
        global $wpdb;
        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}courtly_reservations
                 WHERE reservation_date BETWEEN %s AND %s",
                $start->format('Y-m-d'), $end->format('Y-m-d')
            )
        );
        return $rows;
    }

    public function findFrom(DateTimeInterface $fromDay, int $rangeInDays = 30): array {
        $end = (clone $fromDay)->modify("+$rangeInDays days");
        $rows = $this->findBetweenDates($fromDay, $end);
        return array_map([$this, 'mapRowToEntity'], $rows);
    }
    
    public function findBefore(DateTimeInterface $fromDay, int $rangeInDays = 30): array {
        $start = (clone $fromDay)->modify("-$rangeInDays days");
        $rows = $this->findBetweenDates($start, $fromDay);
        return array_map([$this, 'mapRowToEntity'], $rows);
    }  

    public function insertReservation($data)
    {
        return $this->wpdb->insert($this->table, $data) !== false;
    }

    public function deleteReservation($id)
    {
        return $this->wpdb->delete($this->table, ['id' => $id]) !== false;
    }
    
    private function mapRowToEntity($row): CourtReservation {
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
