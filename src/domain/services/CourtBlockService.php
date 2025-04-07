<?php

class CourtBlockService
{
    private CourtBlockRepository $blockRepo;

    public function __construct(CourtBlockRepository $blockRepo)
    {
        $this->blockRepo = $blockRepo;
    }

    public function getBlockedSlotsForRange($courtId, $start, $end)
    {
        $weeklyBlocks = $this->blockRepo->getBlockedSlots($courtId);
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        $events = [];
        $tzUtc = new DateTimeZone('UTC');

        foreach ($period as $date) {
            $weekday = (int)$date->format('w');
            foreach ($weeklyBlocks as $block) {
                if ((int)$block->day_of_week === $weekday) {
                    $startDateTime = new DateTime($date->format('Y-m-d') . ' ' . $block->start_time, $tzUtc);
                    $endDateTime = new DateTime($date->format('Y-m-d') . ' ' . $block->end_time, $tzUtc);

                    $events[] = [
                        'id' => $block->id,
                        'title' => $block->reason ?: 'Blocked',
                        'start' => $startDateTime->format(DateTime::ATOM),
                        'end' => $endDateTime->format(DateTime::ATOM),
                        'backgroundColor' => '#dc3545',
                        'borderColor' => '#dc3545',
                        'editable' => false,
                        'extendedProps' => [
                            'reason' => $block->reason,
                            'type' => 'block'
                        ],
                        'type' => 'block'
                    ];
                }
            }
        }

        return $events;
    }

    public function saveBlockedSlot($courtId, $start, $end, $reason = null)
    {
        $dayOfWeek = (int)$start->format('w');

        return $this->blockRepo->insertBlockedSlot([
            'court_id' => $courtId,
            'day_of_week' => $dayOfWeek,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'is_blocked' => 1,
            'reason' => $reason
        ]);
    }

    public function deleteBlockedSlot($eventId)
    {
        return $this->blockRepo->deleteBlockedSlot($eventId);
    }
}
