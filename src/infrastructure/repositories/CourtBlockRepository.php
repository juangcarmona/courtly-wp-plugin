<?php

namespace Juangcarmona\Courtly\Infrastructure\Repositories;

use Juangcarmona\Courtly\Domain\Repositories\CourtBlockRepositoryInterface;

class CourtBlockRepository implements CourtBlockRepositoryInterface
{
    private $wpdb;
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'courtly_court_blocks';
    }

    public function getBlockedSlots(int $courtId): array
    {
        $results = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT id, day_of_week, start_time, end_time, reason 
                 FROM {$this->table} 
                 WHERE court_id = %d AND is_blocked = 1",
                $courtId
            )
        );

        // TODO: Map stdClass results to CourtBlock entities
        return $results;
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
