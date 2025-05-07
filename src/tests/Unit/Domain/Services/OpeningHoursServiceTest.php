<?php

namespace Tests\Unit\Domain\Services;

use Juangcarmona\Courtly\Domain\Services\OpeningHoursService;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;
use PHPUnit\Framework\TestCase;

class OpeningHoursServiceTest extends TestCase
{
    private $repositoryMock;
    private $service;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(OpeningHoursRepositoryInterface::class);
        $this->service = new OpeningHoursService($this->repositoryMock);
    }

    public function testGetAllOpeningHours(): void
    {
        $expected = [
            ['day_of_week' => 1, 'time_ranges' => [['start' => '09:00', 'end' => '17:00']], 'closed' => false],
        ];

        $this->repositoryMock->method('getAll')->willReturn($expected);

        $result = $this->service->getAllOpeningHours();

        $this->assertSame($expected, $result);
    }

    public function testSaveOpeningHours(): void
    {
        $dayOfWeek = 1;
        $timeRanges = [['start' => '09:00', 'end' => '17:00']];
        $closed = false;

        $this->repositoryMock->expects($this->once())
            ->method('upsert')
            ->with($dayOfWeek, $timeRanges, $closed)
            ->willReturn(true);

        $result = $this->service->saveOpeningHours($dayOfWeek, $timeRanges, $closed);

        $this->assertTrue($result);
    }
}