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

    public function insertReservation($data)
    {
        return $this->wpdb->insert($this->table, $data) !== false;
    }

    public function deleteReservation($id)
    {
        return $this->wpdb->delete($this->table, ['id' => $id]) !== false;
    }
}
