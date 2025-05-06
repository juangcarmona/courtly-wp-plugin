<?php

namespace Juangcarmona\Courtly\Domain\Entities;

class CourtBlock implements BaseEntity
{
    private int $id;
    private int $courtId;
    private int $dayOfWeek; // 0 = Sunday ... 6 = Saturday
    private string $startTime;
    private string $endTime;
    private bool $isBlocked;
    private ?string $reason;

    public function __construct(int $id, int $courtId, int $dayOfWeek, string $startTime, string $endTime, bool $isBlocked = true, ?string $reason = null)
    {
        $this->id = $id;
        $this->courtId = $courtId;
        $this->dayOfWeek = $dayOfWeek;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->isBlocked = $isBlocked;
        $this->reason = $reason;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCourtId(): int
    {
        return $this->courtId;
    }

    public function getDayOfWeek(): int
    {
        return $this->dayOfWeek;
    }

    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    public function appliesToDate(\DateTime $date): bool
    {
        return (int) $date->format('w') === $this->dayOfWeek;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public static function schema(string $table): string
    {
        return "CREATE TABLE IF NOT EXISTS $table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            court_id BIGINT UNSIGNED NOT NULL,
            day_of_week TINYINT NOT NULL,
            start_time TIME NOT NULL,
            end_time TIME NOT NULL,
            is_blocked BOOLEAN DEFAULT TRUE,
            reason VARCHAR(255) DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
    }
}
