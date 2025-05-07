<?php

namespace Juangcarmona\Courtly\Tests\Unit\Application\Controllers;

use Juangcarmona\Courtly\Application\Controllers\AdminCourtAvailabilityController;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AdminCourtAvailabilityControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
    }

    public function testGetViewDataReturnsExpectedStructure(): void
    {
        $courtMock = \Mockery::mock();
        $courtMock->shouldReceive('getId')->andReturn(1);

        $hourMock1 = \Mockery::mock();
        $hourMock1->shouldReceive('getDayOfWeek')->andReturn(1);
        $hourMock1->shouldReceive('getOpenTime')->andReturn('08:00');
        $hourMock1->shouldReceive('getCloseTime')->andReturn('18:00');

        $hourMock2 = \Mockery::mock();
        $hourMock2->shouldReceive('getDayOfWeek')->andReturn(2);
        $hourMock2->shouldReceive('getOpenTime')->andReturn('09:00');
        $hourMock2->shouldReceive('getCloseTime')->andReturn('17:00');

        $courtRepo = \Mockery::mock(CourtRepositoryInterface::class);
        $courtRepo->shouldReceive('findAll')->andReturn([$courtMock]);

        $openingRepo = \Mockery::mock(OpeningHoursRepositoryInterface::class);
        $openingRepo->shouldReceive('getAll')->andReturn([$hourMock1, $hourMock2]);

        $_GET['court_id'] = null;

        $controller = new AdminCourtAvailabilityController($courtRepo, $openingRepo);
        $data = $controller->getViewData();

        $this->assertArrayHasKey('courts', $data);
        $this->assertArrayHasKey('selectedCourtId', $data);
        $this->assertArrayHasKey('formattedHours', $data);

        $this->assertEquals(1, $data['selectedCourtId']);
        $this->assertEquals([
            1 => ['open' => '08:00', 'close' => '18:00'],
            2 => ['open' => '09:00', 'close' => '17:00'],
        ], $data['formattedHours']);
    }

    public function testHandlePostCallsUpsertForEachDay(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['opening_hours'] = [
            1 => ['open' => '08:00', 'close' => '18:00'],
            2 => ['open' => '09:00', 'close' => '17:00'],
        ];

        if (!function_exists('check_admin_referer')) {
            eval('function check_admin_referer($arg) { return true; }');
        }
        if (!function_exists('sanitize_text_field')) {
            eval('function sanitize_text_field($arg) { return $arg; }');
        }
        if (!function_exists('add_action')) {
            eval('function add_action($hook, $cb) { }');
        }
        if (!function_exists('esc_html__')) {
            eval('function esc_html__($str, $domain = null) { return $str; }');
        }

        $courtRepo = \Mockery::mock(CourtRepositoryInterface::class);
        $openingRepo = \Mockery::mock(OpeningHoursRepositoryInterface::class);
        $openingRepo->shouldReceive('upsert')->once()->with(1, '08:00', '18:00');
        $openingRepo->shouldReceive('upsert')->once()->with(2, '09:00', '17:00');

        $controller = new AdminCourtAvailabilityController($courtRepo, $openingRepo);
        $controller->handlePost();

        $this->assertTrue(true); // Para que no marque como risky test
    }
}
