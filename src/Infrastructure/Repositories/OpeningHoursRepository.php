<?php

namespace Juangcarmona\Courtly\Infrastructure\Repositories;
use Juangcarmona\Courtly\Domain\Entities\OpeningHours;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;

class OpeningHoursRepository implements OpeningHoursRepositoryInterface
{
    private $wpdb;
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'courtly_opening_hours';
    }

    public function getAll(): array
    {
        $rows = $this->wpdb->get_results("SELECT * FROM {$this->table} ORDER BY day_of_week ASC");
        return array_map([$this, 'mapRowToEntity'], $rows);
    }
    
    public function getByDayOfWeek(int $dayOfWeek): ?OpeningHours
    {
        $row = $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table} WHERE day_of_week = %d", $dayOfWeek)
        );
    
        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function upsert(int $dayOfWeek, array $timeRanges, bool $closed): bool
    {
        $existing = $this->getByDayOfWeek($dayOfWeek);

        $data = [
            'time_ranges' => json_encode($timeRanges),
            'closed' => $closed ? 1 : 0
        ];

        if ($existing) {
            return $this->wpdb->update(
                $this->table,
                $data,
                ['day_of_week' => $dayOfWeek]
            ) !== false;
        } else {
            $data['day_of_week'] = $dayOfWeek;
            return $this->wpdb->insert(
                $this->table,
                $data
            ) !== false;
        }
    }

    private function mapRowToEntity(object $row): OpeningHours
    {
        return new OpeningHours(
            (int)$row->day_of_week,
            json_decode($row->time_ranges, true) ?? [],
            (bool)$row->closed,
            (int)$row->id
        );
    }

}
