<?php

namespace Juangcarmona\Courtly\Infrastructure\Repositories;

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
        return $this->wpdb->get_results("SELECT * FROM {$this->table} ORDER BY day_of_week ASC");
    }

    public function getByDayOfWeek(int $dayOfWeek): ?object
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table} WHERE day_of_week = %d", $dayOfWeek)
        ) ?: null;
    }

    public function upsert(int $dayOfWeek, string $openTime, string $closeTime): bool
    {
        $existing = $this->getByDayOfWeek($dayOfWeek);

        if ($existing) {
            return $this->wpdb->update(
                $this->table,
                ['open_time' => $openTime, 'close_time' => $closeTime],
                ['day_of_week' => $dayOfWeek]
            ) !== false;
        } else {
            return $this->wpdb->insert(
                $this->table,
                ['day_of_week' => $dayOfWeek, 'open_time' => $openTime, 'close_time' => $closeTime]
            ) !== false;
        }
    }
}
