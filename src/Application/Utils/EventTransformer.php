<?php

namespace Juangcarmona\Courtly\Application\Utils;

use Juangcarmona\Courtly\Domain\Entities\CourtBlock;
use Juangcarmona\Courtly\Domain\Entities\CourtReservation;

class EventTransformer
{
    public static function reservationToEvent(CourtReservation $r): array
    {
        [$start, $end] = explode('-', $r->getTimeSlot());
        $date = $r->getReservationDate()->format('Y-m-d');

        return [
            'title' => 'Reserved',
            'start' => "{$date}T{$start}",
            'end' => "{$date}T{$end}",
            'resourceId' => $r->getCourtId(),
            'backgroundColor' => '#0073aa',
            'borderColor' => '#0073aa',
            'type' => 'reservation',
            'extendedProps' => [
                'id' => $r->getId(),
                'type' => 'reservation',
            ],
        ];
    }

    public static function blockToEvent(\DateTimeInterface $date, CourtBlock $block, int $courtId, string $courtName): array
    {
        $start = new \DateTimeImmutable("{$date->format('Y-m-d')} {$block->getStartTime()}", new \DateTimeZone('UTC'));
        $end = new \DateTimeImmutable("{$date->format('Y-m-d')} {$block->getEndTime()}", new \DateTimeZone('UTC'));

        return [
            'title' => $block->getReason() ?: __('Blocked', 'courtly'),
            'start' => $start->format(\DateTime::ATOM),
            'end' => $end->format(\DateTime::ATOM),
            'resourceId' => $courtId,
            'backgroundColor' => '#dc3545',
            'borderColor' => '#dc3545',
            'type' => 'block',
            'extendedProps' => [
                'id' => $block->getId(),
                'type' => 'block',
                'court' => $courtName,
                'reason' => $block->getReason(),
            ],
        ];
    }
}
