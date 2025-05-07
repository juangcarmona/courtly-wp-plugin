<?php
namespace Juangcarmona\Courtly\Domain\Entities;

class OpeningHours implements BaseEntity
{
    private int $id;
    private int $dayOfWeek;    // 0 = Sunday, 6 = Saturday
    private bool $closed;
    private array $timeRanges; // Array of ['start' => 'HH:MM:SS', 'end' => 'HH:MM:SS']

    public function __construct(int $dayOfWeek, array $timeRanges = [], bool $closed = false, int $id = 0)
    {
        $this->id = $id;
        $this->dayOfWeek = $dayOfWeek;
        $this->timeRanges = $timeRanges;
        $this->closed = $closed;
    }

    public static function schema(string $table): string
    {
        return "CREATE TABLE IF NOT EXISTS $table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            day_of_week TINYINT NOT NULL,
            time_ranges JSON NOT NULL,
            closed BOOLEAN NOT NULL DEFAULT 0,
            UNIQUE KEY unique_day (day_of_week)
        )";
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDayOfWeek(): int
    {
        return $this->dayOfWeek;
    }

    public function isClosed(): bool
    {
        return $this->closed;
    }

    public function getTimeRanges(): array
    {
        return $this->timeRanges;
    }

    public function setClosed(bool $closed): void
    {
        $this->closed = $closed;
    }

    public function setTimeRanges(array $timeRanges): void
    {
        $this->timeRanges = $timeRanges;
    }
}
