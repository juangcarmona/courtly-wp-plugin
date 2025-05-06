<?php

namespace Juangcarmona\Courtly\Domain\Entities;

class UserType implements BaseEntity
{
    private int $id;
    private string $name;
    private string $displayName;
    private int $bookingDaysInAdvance;

    public function __construct(int $id, string $name, string $displayName, int $bookingDaysInAdvance)
    {
        $this->id = $id;
        $this->name = $name;
        $this->displayName = $displayName;
        $this->bookingDaysInAdvance = $bookingDaysInAdvance;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getBookingDaysInAdvance(): int
    {
        return $this->bookingDaysInAdvance;
    }

    public static function schema(string $table): string
    {
        return "CREATE TABLE IF NOT EXISTS $table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            display_name VARCHAR(100) NOT NULL,
            booking_days_in_advance INT NOT NULL
        )";
    }
}
