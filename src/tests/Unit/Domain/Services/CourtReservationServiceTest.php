<?php
use PHPUnit\Framework\TestCase;
use Juangcarmona\Courtly\Domain\Services\CourtReservationService;
use Juangcarmona\Courtly\Infrastructure\Repositories\CourtReservationRepository;
use Juangcarmona\Courtly\Infrastructure\Repositories\CourtBlockRepository;
use Juangcarmona\Courtly\Infrastructure\Repositories\OpeningHoursRepository;

class CourtReservationServiceTest extends TestCase
{
    public function testSlotIsAvailableWhenNoConflicts(): void
    {
        // Arrange
        $reservationRepo = new class extends CourtReservationRepository {
            public function getReservationsForDate($courtId, $date) {
                return [];
            }
        };

        $blockRepo = new class extends CourtBlockRepository {
            public function getBlockedSlots($courtId) {
                return [];
            }
        };

        $openingRepo = new class extends OpeningHoursRepository {
            public function getByDayOfWeek($dow) {
                return (object)[
                    'open_time' => '08:00',
                    'close_time' => '22:00',
                ];
            }
        };

        $service = new CourtReservationService($reservationRepo, $blockRepo, $openingRepo);

        // Act
        $available = $service->isSlotAvailable(1, '2025-04-25', '10:00-11:00');

        // Assert
        $this->assertTrue($available);
    }
}
