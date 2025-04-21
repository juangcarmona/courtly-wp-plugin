<?php

namespace Juangcarmona\Courtly\Domain\Services;

use Juangcarmona\Courtly\Domain\Repositories\CourtBlockRepositoryInterface;
use DatePeriod;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

class CourtBlockService
{
    private CourtBlockRepositoryInterface $blockRepo;

    public function __construct(CourtBlockRepositoryInterface $blockRepo)
    {
        $this->blockRepo = $blockRepo;
    }

    /**
     * @param int $courtId
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     * @return array
     */
    public function getBlockedSlotsForRange(int $courtId, DateTimeInterface $start, DateTimeInterface $end): array
    {
        $weeklyBlocks = $this->blockRepo->getBlockedSlots($courtId); // TODO: stdClass → entity
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        $events = [];
        $tzUtc = new DateTimeZone('UTC');

        foreach ($period as $date) {
            $weekday = (int)$date->format('w');
        
            /** @var CourtBlock $block */
            foreach ($weeklyBlocks as $block) {
                if ($block->getDayOfWeek() === $weekday) {
                    $startDateTime = new DateTime($date->format('Y-m-d') . ' ' . $block->getStartTime(), $tzUtc);
                    $endDateTime = new DateTime($date->format('Y-m-d') . ' ' . $block->getEndTime(), $tzUtc);
        
                    $events[] = [
                        'id' => $block->getId(),
                        'title' => $block->getReason() ?: __('Blocked', 'courtly'),
                        'start' => $startDateTime->format(DateTime::ATOM),
                        'end' => $endDateTime->format(DateTime::ATOM),
                        'backgroundColor' => '#dc3545',
                        'borderColor' => '#dc3545',
                        'editable' => false,
                        'extendedProps' => [
                            'reason' => $block->getReason(),
                            'type' => 'block'
                        ],
                        'type' => 'block'
                    ];
                }
            }
        }
        

        return $events;
    }

    /**
     * @param int $courtId
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     * @param string|null $reason
     * @return bool
     */
    public function saveBlockedSlot(int $courtId, DateTimeInterface $start, DateTimeInterface $end, ?string $reason = null): bool
    {
        $dayOfWeek = (int)$start->format('w');

        return $this->blockRepo->insertBlockedSlot([
            'court_id' => $courtId,
            'day_of_week' => $dayOfWeek,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'is_blocked' => 1,
            'reason' => $reason,
        ]);
    }

    /**
     * @param int $eventId
     * @return bool
     */
    public function deleteBlockedSlot(int $eventId): bool
    {
        return $this->blockRepo->deleteBlockedSlot($eventId);
    }
}
