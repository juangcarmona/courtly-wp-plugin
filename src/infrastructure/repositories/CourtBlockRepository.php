<?php

class CourtBlockRepository
{
    private $wpdb;
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'courtly_court_blocks';
    }

    public function getBlockedSlots($courtId)
    {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT id, day_of_week, start_time, end_time, reason 
                 FROM {$this->table} 
                 WHERE court_id = %d AND is_blocked = 1",
                $courtId
            )
        );
    }

    public function insertBlockedSlot($data)
    {
        return $this->wpdb->insert($this->table, $data) !== false;
    }

    public function deleteBlockedSlot($id)
    {
        return $this->wpdb->delete($this->table, ['id' => $id]) !== false;
    }
}
