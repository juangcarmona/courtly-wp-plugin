<?php

namespace Juangcarmona\Courtly\Tests\Unit\Application\Controllers;

use Juangcarmona\Courtly\Application\Controllers\AdminOpeningHoursController;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AdminOpeningHoursControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
    }

    public function testGetViewDataReturnsFormattedHours(): void
    {
        $hour1 = \Mockery::mock();
        $hour1->shouldReceive('getDayOfWeek')->andReturn(1);
        $hour1->shouldReceive('getOpenTime')->andReturn('08:00');
        $hour1->shouldReceive('getCloseTime')->andReturn('18:00');

        $hour2 = \Mockery::mock();
        $hour2->shouldReceive('getDayOfWeek')->andReturn(2);
        $hour2->shouldReceive('getOpenTime')->andReturn('09:00');
        $hour2->shouldReceive('getCloseTime')->andReturn('17:00');

        $openingRepo = \Mockery::mock(OpeningHoursRepositoryInterface::class);
        $openingRepo->shouldReceive('getAll')->andReturn([$hour1, $hour2]);

        $controller = new AdminOpeningHoursController($openingRepo);
        $data = $controller->getViewData();

        $this->assertArrayHasKey('formattedHours', $data);
        $this->assertEquals([
            1 => ['open' => '08:00', 'close' => '18:00'],
            2 => ['open' => '09:00', 'close' => '17:00'],
        ], $data['formattedHours']);
    }

    public function testHandlePostCallsUpsertCorrectly(): void
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

        $openingRepo = \Mockery::mock(OpeningHoursRepositoryInterface::class);
        $openingRepo->shouldReceive('upsert')->once()->with(1, '08:00', '18:00');
        $openingRepo->shouldReceive('upsert')->once()->with(2, '09:00', '17:00');

        $controller = new AdminOpeningHoursController($openingRepo);
        $controller->handlePost();

        $this->assertTrue(true); // to avoid risky test
    }
}
