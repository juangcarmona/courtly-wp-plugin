<?php
namespace Juangcarmona\Courtly\Domain\Entities;
use Juangcarmona\Courtly\Domain\BaseEntity;

class CourtReservation implements BaseEntity
{
    private int $id;
    private int $userId;
    private int $courtId;
    private DateTime $reservationDate;
    private string $timeSlot; // e.g., "17:00-18:00"
    private DateTime $createdAt;

    public function __construct(int $id, int $userId, int $courtId, DateTime $reservationDate, string $timeSlot, DateTime $createdAt)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->courtId = $courtId;
        $this->reservationDate = $reservationDate;
        $this->timeSlot = $timeSlot;
        $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getCourtId(): int { return $this->courtId; }
    public function getTimeSlot(): string { return $this->timeSlot; }
    public function getReservationDate(): DateTime { return $this->reservationDate; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
    
    public static function schema(string $table): string
    {
        return "CREATE TABLE IF NOT EXISTS $table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            court_id BIGINT UNSIGNED NOT NULL,
            reservation_date DATE NOT NULL,
            time_slot VARCHAR(20) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_reservation (court_id, reservation_date, time_slot)
        )";
    }
}
