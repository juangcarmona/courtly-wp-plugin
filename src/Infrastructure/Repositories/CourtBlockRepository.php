<?php

namespace Juangcarmona\Courtly\Infrastructure\Repositories;
use Juangcarmona\Courtly\Domain\Entities\CourtBlock;
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
                "SELECT id, court_id, day_of_week, start_time, end_time, is_blocked, reason 
                 FROM {$this->table} 
                 WHERE court_id = %d AND is_blocked = 1",
                $courtId
            )
        );
    
        // Map each result to a CourtBlock entity
        return array_map(function ($row) {
            return new CourtBlock(
                id: (int)$row->id,
                courtId: (int)$row->court_id,
                dayOfWeek: (int)$row->day_of_week,
                startTime: $row->start_time,
                endTime: $row->end_time,
                isBlocked: (bool)$row->is_blocked,
                reason: $row->reason
            );
        }, $results);
    }

    public function insertBlockedSlot(array $data): bool
    {
        return $this->wpdb->insert($this->table, $data) !== false;
    }

    public function deleteBlockedSlot(int $id): bool
    {
        return $this->wpdb->delete($this->table, ['id' => $id]) !== false;
    }
}
