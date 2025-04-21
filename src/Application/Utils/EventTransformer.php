<?php
namespace Juangcarmona\Courtly\Application\Utils;

class EventTransformer {
    public static function reservationToEvent($r): array {
        [$start, $end] = explode('-', $r->time_slot);

        return [
            'title' => 'Reserved',
            'start' => "{$r->reservation_date}T{$start}",
            'end' => "{$r->reservation_date}T{$end}",
            'resourceId' => $r->court_id,
            'backgroundColor' => '#0073aa',
            'borderColor' => '#0073aa',
            'type' => 'reservation',
            'extendedProps' => [
                'id' => $r->id,
                'type' => 'reservation',
            ]
        ];
    }

    public static function blockToEvent(DateTimeInterface $date, $block, $courtId, $courtName): array {
        $start = new DateTimeImmutable("{$date->format('Y-m-d')} {$block->start_time}", new DateTimeZone('UTC'));
        $end = new DateTimeImmutable("{$date->format('Y-m-d')} {$block->end_time}", new DateTimeZone('UTC'));

        return [
            'title' => $block->reason ?: __('Blocked', 'courtly'),
            'start' => $start->format(DateTime::ATOM),
            'end' => $end->format(DateTime::ATOM),
            'resourceId' => $courtId,
            'backgroundColor' => '#dc3545',
            'borderColor' => '#dc3545',
            'type' => 'block',
            'extendedProps' => [
                'id' => $block->id,
                'type' => 'block',
                'court' => $courtName,
                'reason' => $block->reason,
            ]
        ];
    }
}
