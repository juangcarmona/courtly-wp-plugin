<?php

namespace Juangcarmona\Courtly\Tests\Unit\Domain\Services;

use Juangcarmona\Courtly\Domain\Entities\CourtBlock;
use Juangcarmona\Courtly\Domain\Repositories\CourtBlockRepositoryInterface;
use Juangcarmona\Courtly\Domain\Services\CourtBlockService;
use PHPUnit\Framework\TestCase;

class CourtBlockServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
    }

    public function testGetBlockedSlotsForRangeReturnsMatchingEvents(): void
    {
        $mockBlock = \Mockery::mock(CourtBlock::class);
        $mockBlock->shouldReceive('getDayOfWeek')->andReturn(1); // Monday
        $mockBlock->shouldReceive('getStartTime')->andReturn('09:00');
        $mockBlock->shouldReceive('getEndTime')->andReturn('10:00');
        $mockBlock->shouldReceive('getId')->andReturn(42);
        $mockBlock->shouldReceive('getReason')->andReturn('Maintenance');

        $repo = \Mockery::mock(CourtBlockRepositoryInterface::class);
        $repo->shouldReceive('getBlockedSlots')->with(1)->andReturn([$mockBlock]);

        $service = new CourtBlockService($repo);

        $start = new \DateTimeImmutable('2025-05-05'); // Monday
        $end = new \DateTimeImmutable('2025-05-07'); // Wednesday

        $events = $service->getBlockedSlotsForRange(1, $start, $end);

        $this->assertCount(1, $events);
        $this->assertEquals('Maintenance', $events[0]['title']);
        $this->assertEquals('block', $events[0]['type']);
    }

    public function testGetBlockedSlotsForRangeSkipsNonMatchingDays(): void
    {
        $mockBlock = \Mockery::mock(CourtBlock::class);
        $mockBlock->shouldReceive('getDayOfWeek')->andReturn(3); // Wednesday (no match)
        $mockBlock->shouldReceive('getStartTime')->andReturn('10:00');
        $mockBlock->shouldReceive('getEndTime')->andReturn('11:00');
        $mockBlock->shouldReceive('getId')->andReturn(21);
        $mockBlock->shouldReceive('getReason')->andReturn('Class');

        $repo = \Mockery::mock(CourtBlockRepositoryInterface::class);
        $repo->shouldReceive('getBlockedSlots')->with(1)->andReturn([$mockBlock]);

        $service = new CourtBlockService($repo);

        $start = new \DateTimeImmutable('2025-05-05'); // Monday
        $end = new \DateTimeImmutable('2025-05-06'); // Tuesday

        $events = $service->getBlockedSlotsForRange(1, $start, $end);

        $this->assertEmpty($events);
    }

    public function testSaveBlockedSlotPersistsCorrectData(): void
    {
        $repo = \Mockery::mock(CourtBlockRepositoryInterface::class);
        $repo->shouldReceive('insertBlockedSlot')
            ->once()
            ->with(\Mockery::on(function ($data) {
                return $data['court_id'] === 2
                       && $data['day_of_week'] === 2
                       && $data['start_time'] === '10:00:00'
                       && $data['end_time'] === '11:00:00'
                       && $data['reason'] === 'Reserved By John Doe';
            }))
            ->andReturn(true);

        $service = new CourtBlockService($repo);

        $start = new \DateTimeImmutable('2025-05-06 10:00'); // Tuesday
        $end = new \DateTimeImmutable('2025-05-06 11:00');

        $result = $service->saveBlockedSlot(2, $start, $end, 'Reserved By John Doe');
        $this->assertTrue($result);
    }

    public function testDeleteBlockedSlotReturnsTrueOnSuccess(): void
    {
        $repo = \Mockery::mock(CourtBlockRepositoryInterface::class);
        $repo->shouldReceive('deleteBlockedSlot')->with(55)->andReturn(true);

        $service = new CourtBlockService($repo);

        $this->assertTrue($service->deleteBlockedSlot(55));
    }

    public function testDeleteBlockedSlotReturnsFalseOnFailure(): void
    {
        $repo = \Mockery::mock(CourtBlockRepositoryInterface::class);
        $repo->shouldReceive('deleteBlockedSlot')->with(99)->andReturn(false);

        $service = new CourtBlockService($repo);

        $this->assertFalse($service->deleteBlockedSlot(99));
    }
}
