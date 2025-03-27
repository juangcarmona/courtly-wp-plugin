<?php

class OpeningHours implements BaseEntity
{
    private int $id;
    private int $dayOfWeek;    // 0 = Sunday, 6 = Saturday
    private string $openTime;  // HH:MM:SS
    private string $closeTime;

    public function __construct(int $dayOfWeek, string $openTime, string $closeTime, int $id = 0)
    {
        $this->id = $id;
        $this->dayOfWeek = $dayOfWeek;
        $this->openTime = $openTime;
        $this->closeTime = $closeTime;
    }

    public static function schema(string $table): string
    {
        return "CREATE TABLE IF NOT EXISTS $table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            day_of_week TINYINT NOT NULL,
            open_time TIME NOT NULL,
            close_time TIME NOT NULL,
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

    public function getOpenTime(): string
    {
        return $this->openTime;
    }

    public function getCloseTime(): string
    {
        return $this->closeTime;
    }

    public function setOpenTime(string $time): void
    {
        $this->openTime = $time;
    }

    public function setCloseTime(string $time): void
    {
        $this->closeTime = $time;
    }
}
