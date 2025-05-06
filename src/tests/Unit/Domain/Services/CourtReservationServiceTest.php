<?php

namespace Juangcarmona\Courtly\Tests\Unit\Domain\Services;

use Juangcarmona\Courtly\Domain\Repositories\CourtBlockRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;
use Juangcarmona\Courtly\Domain\Services\CourtReservationService;
use PHPUnit\Framework\TestCase;

class CourtReservationServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
    }

    private function makeServiceWith(
        array $reservations = [],
        array $blocks = [],
        $opening = null,
        array $userReservations = [],
        bool $insertSuccess = true
    ): CourtReservationService {
        $reservationRepo = \Mockery::mock(CourtReservationRepositoryInterface::class);
        $reservationRepo->shouldReceive('getReservationsForDate')->andReturn($reservations);
        $reservationRepo->shouldReceive('getReservationsForUserOnDate')->andReturn($userReservations);
        $reservationRepo->shouldReceive('insertReservation')->andReturn($insertSuccess);

        $blockRepo = \Mockery::mock(CourtBlockRepositoryInterface::class);
        $blockRepo->shouldReceive('getBlockedSlots')->andReturn($blocks);

        $openingRepo = \Mockery::mock(OpeningHoursRepositoryInterface::class);
        $openingRepo->shouldReceive('getByDayOfWeek')->andReturn($opening);

        return new CourtReservationService($reservationRepo, $blockRepo, $openingRepo);
    }

    public function testSlotAvailableNoConflictsReturnsTrue(): void
    {
        $opening = new class {
            public function getOpenTime()
            {
                return '08:00';
            }

            public function getCloseTime()
            {
                return '22:00';
            }
        };

        $service = $this->makeServiceWith([], [], $opening);
        $this->assertTrue($service->isSlotAvailable(1, '2025-04-25', '10:00-11:00'));
    }

    public function testSlotWithExistingReservationReturnsFalse(): void
    {
        $reservation = (object) ['time_slot' => '10:00-11:00'];
        $opening = new class {
            public function getOpenTime()
            {
                return '08:00';
            }

            public function getCloseTime()
            {
                return '22:00';
            }
        };

        $service = $this->makeServiceWith([$reservation], [], $opening);
        $this->assertFalse($service->isSlotAvailable(1, '2025-04-25', '10:00-11:00'));
    }

    public function testSlotBlockedReturnsFalse(): void
    {
        $block = (object) [
            'day_of_week' => 5, 'start_time' => '09:00', 'end_time' => '11:00',
        ];
        $opening = new class {
            public function getOpenTime()
            {
                return '08:00';
            }

            public function getCloseTime()
            {
                return '22:00';
            }
        };

        $service = $this->makeServiceWith([], [$block], $opening);
        $this->assertFalse($service->isSlotAvailable(1, '2025-04-25', '10:00-11:00'));
    }

    public function testSlotOutsideOpeningHoursReturnsFalse(): void
    {
        $opening = new class {
            public function getOpenTime()
            {
                return '12:00';
            }

            public function getCloseTime()
            {
                return '22:00';
            }
        };

        $service = $this->makeServiceWith([], [], $opening);
        $this->assertFalse($service->isSlotAvailable(1, '2025-04-25', '10:00-11:00'));
    }

    public function testNoOpeningHoursReturnsFalse(): void
    {
        $service = $this->makeServiceWith([], [], null);
        $this->assertFalse($service->isSlotAvailable(1, '2025-04-25', '10:00-11:00'));
    }

    public function testCreateReservationFailsWhenUserHasTooMany(): void
    {
        $service = $this->makeServiceWith([], [], new class {
            public function getOpenTime()
            {
                return '08:00';
            }

            public function getCloseTime()
            {
                return '22:00';
            }
        }, [1, 2]); // simulate 2 existing reservations

        $result = $service->createReservation(1, 1, '2025-04-25T10:00', '2025-04-25T11:00');
        $this->assertStringContainsString('already has a reservation', $result);
    }

    public function testCreateReservationFailsWhenTooLong(): void
    {
        $service = $this->makeServiceWith([], [], new class {
            public function getOpenTime()
            {
                return '08:00';
            }

            public function getCloseTime()
            {
                return '22:00';
            }
        });

        $result = $service->createReservation(1, 1, '2025-04-25T10:00', '2025-04-25T12:30');
        $this->assertStringContainsString('Reservations must be', $result);
    }

    public function testCreateReservationFailsWhenUnavailable(): void
    {
        $service = $this->makeServiceWith(
            [(object) ['time_slot' => '10:00-11:00']],
            [],
            new class {
                public function getOpenTime()
                {
                    return '08:00';
                }

                public function getCloseTime()
                {
                    return '22:00';
                }
            }
        );

        $result = $service->createReservation(1, 1, '2025-04-25T10:00', '2025-04-25T11:00');
        $this->assertEquals('Time slot unavailable.', $result);
    }

    public function testCreateReservationFailsWhenInsertFails(): void
    {
        $service = $this->makeServiceWith([], [], new class {
            public function getOpenTime()
            {
                return '08:00';
            }

            public function getCloseTime()
            {
                return '22:00';
            }
        }, [], false);

        $result = $service->createReservation(1, 1, '2025-04-25T10:00', '2025-04-25T11:00');
        $this->assertEquals('Failed to create reservation.', $result);
    }

    public function testCreateReservationSucceeds(): void
    {
        $service = $this->makeServiceWith([], [], new class {
            public function getOpenTime()
            {
                return '08:00';
            }

            public function getCloseTime()
            {
                return '22:00';
            }
        });

        $result = $service->createReservation(1, 1, '2025-04-25T10:00', '2025-04-25T11:00');
        $this->assertTrue($result);
    }
}
