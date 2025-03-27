<?php
require_once __DIR__ . '/BaseEntity.php';

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

    public function overlapsWith(CourtReservation $other): bool
    {
        if ($this->courtId !== $other->courtId || $this->reservationDate != $other->reservationDate) {
            return false;
        }

        [$startA, $endA] = explode('-', $this->timeSlot);
        [$startB, $endB] = explode('-', $other->timeSlot);

        return max($startA, $startB) < min($endA, $endB);
    }

    public function getTimeSlot(): string
    {
        return $this->timeSlot;
    }

    public function getReservationDate(): DateTime
    {
        return $this->reservationDate;
    }

    public function getCourtId(): int
    {
        return $this->courtId;
    }
    
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
