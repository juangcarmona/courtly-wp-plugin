<?php

namespace Juangcarmona\Courtly\Tests\Unit\Application\Controllers;

use Juangcarmona\Courtly\Application\Controllers\AdminAvailabilityController;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AdminAvailabilityControllerTest extends TestCase
{
    private $courtRepoMock;
    private $openingRepoMock;
    private $controller;

    protected function setUp(): void
    {
        $this->courtRepoMock = $this->createMock(CourtRepositoryInterface::class);
        $this->openingRepoMock = $this->createMock(OpeningHoursRepositoryInterface::class);

        $this->controller = new AdminAvailabilityController(
            $this->courtRepoMock,
            $this->openingRepoMock
        );
    }

    public function testGetViewData(): void
    {
        $mockCourts = [
            (object) ['getId' => fn() => 1],
            (object) ['getId' => fn() => 2]
        ];

        $mockOpeningHours = [
            (object) ['getDayOfWeek' => fn() => 'Monday', 'getOpenTime' => fn() => '08:00', 'getCloseTime' => fn() => '18:00'],
            (object) ['getDayOfWeek' => fn() => 'Tuesday', 'getOpenTime' => fn() => '09:00', 'getCloseTime' => fn() => '17:00']
        ];

        $this->courtRepoMock->method('findAll')->willReturn($mockCourts);
        $this->openingRepoMock->method('getAll')->willReturn($mockOpeningHours);

        $result = $this->controller->getViewData();

        $this->assertArrayHasKey('courts', $result);
        $this->assertArrayHasKey('selectedCourtId', $result);
        $this->assertArrayHasKey('formattedHours', $result);

        $this->assertEquals(1, $result['selectedCourtId']);
        $this->assertEquals([
            'Monday' => ['open' => '08:00', 'close' => '18:00'],
            'Tuesday' => ['open' => '09:00', 'close' => '17:00']
        ], $result['formattedHours']);
    }

    public function testHandlePost(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['opening_hours'] = [
            'Monday' => ['open' => '08:00', 'close' => '18:00'],
            'Tuesday' => ['open' => '09:00', 'close' => '17:00']
        ];

        $this->openingRepoMock->expects($this->exactly(2))
            ->method('upsert')
            ->withConsecutive(
                ['Monday', '08:00', '18:00'],
                ['Tuesday', '09:00', '17:00']
            );

        $this->controller->handlePost();

        $this->expectOutputRegex('/Availability updated\./');
    }
}