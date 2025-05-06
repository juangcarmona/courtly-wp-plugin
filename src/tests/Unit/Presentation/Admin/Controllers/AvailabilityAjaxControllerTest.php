<?php

namespace Juangcarmona\Courtly\Presentation\Admin\Controllers;

// ✅ Mock wp_send_json solo si no está definida
if (!function_exists(__NAMESPACE__.'\\wp_send_json')) {
    function wp_send_json($data)
    {
        echo json_encode($data);
    }
}

if (!function_exists(__NAMESPACE__.'\\sanitize_text_field')) {
    function sanitize_text_field($str)
    {
        return $str;
    }
}

namespace Juangcarmona\Courtly\Tests\Unit\Presentation\Admin\Controllers;

use Juangcarmona\Courtly\Application\Utils\EventTransformer;
use Juangcarmona\Courtly\Domain\Repositories\CourtBlockRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;
use Juangcarmona\Courtly\Domain\Services\CourtBlockService;
use Juangcarmona\Courtly\Domain\Services\CourtReservationService;
use Juangcarmona\Courtly\Presentation\Admin\Controllers\AvailabilityAjaxController;
use PHPUnit\Framework\TestCase;

class FakeEventTransformer extends EventTransformer
{
    public static function reservationToEvent($reservation): array
    {
        return ['id' => 99];
    }
}

class AvailabilityAjaxControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
    }

    public function testGetCourtsReturnsJson()
    {
        $courtRepo = \Mockery::mock(CourtRepositoryInterface::class);
        $courtRepo->shouldReceive('findAll')->once()->andReturn([
            $c1 = \Mockery::mock(['getId' => 1, 'getName' => 'Court A']),
            $c2 = \Mockery::mock(['getId' => 2, 'getName' => 'Court B']),
        ]);

        $controller = $this->getController(['courtRepo' => $courtRepo]);

        $this->expectOutputString(json_encode([
            ['id' => 1, 'title' => 'Court A'],
            ['id' => 2, 'title' => 'Court B'],
        ]));

        $controller->getCourts();
    }

    public function testGetReservationsReturnsJson()
    {
        $reservation = \Mockery::mock('CourtReservation');
        $reservationRepo = \Mockery::mock(CourtReservationRepositoryInterface::class);
        $reservationRepo->shouldReceive('findBetweenDates')->andReturn([$reservation]);

        $controller = $this->getController([
            'reservationRepo' => $reservationRepo,
            'eventTransformer' => new FakeEventTransformer(),
        ]);

        $this->expectOutputString(json_encode([['id' => 99]]));

        $controller->getReservations();
    }

    public function testGetOpeningHoursReturnsFormattedJson()
    {
        $row = \Mockery::mock([
            'getDayOfWeek' => 1,
            'getOpenTime' => '08:00',
            'getCloseTime' => '20:00',
        ]);

        $openingRepo = \Mockery::mock(OpeningHoursRepositoryInterface::class);
        $openingRepo->shouldReceive('getAll')->once()->andReturn([$row]);

        $controller = $this->getController(['openingHoursRepo' => $openingRepo]);

        $expected = array_fill(0, 7, null);
        $expected[1] = ['start' => '08:00', 'end' => '20:00'];

        $this->expectOutputString(json_encode($expected));

        $controller->getOpeningHours();
    }

    public function testGetBlockedSlotsCallsService()
    {
        $_GET['court_id'] = 1;
        $_GET['start'] = '2025-05-01T00:00:00';
        $_GET['end'] = '2025-05-10T00:00:00';

        $blockService = \Mockery::mock(CourtBlockService::class);
        $blockService->shouldReceive('getBlockedSlotsForRange')
            ->with(1, \Mockery::type(\DateTime::class), \Mockery::type(\DateTime::class))
            ->once()
            ->andReturn([['id' => 1, 'type' => 'block']]);

        $controller = $this->getController(['blockService' => $blockService]);

        $this->expectOutputString(json_encode([['id' => 1, 'type' => 'block']]));

        $controller->getBlockedSlots();
    }

    private function getController(array $deps = []): AvailabilityAjaxController
    {
        return new AvailabilityAjaxController(
            $deps['courtRepo'] ?? \Mockery::mock(CourtRepositoryInterface::class),
            $deps['reservationRepo'] ?? \Mockery::mock(CourtReservationRepositoryInterface::class),
            $deps['blockRepo'] ?? \Mockery::mock(CourtBlockRepositoryInterface::class),
            $deps['openingHoursRepo'] ?? \Mockery::mock(OpeningHoursRepositoryInterface::class),
            $deps['blockService'] ?? \Mockery::mock(CourtBlockService::class),
            $deps['reservationService'] ?? \Mockery::mock(CourtReservationService::class),
            $deps['eventTransformer'] ?? \Mockery::mock(EventTransformer::class)
        );
    }
}
