<?php

namespace Juangcarmona\Courtly\Presentation\Admin\Controllers;

use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeImmutable;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtBlockRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;
use Juangcarmona\Courtly\Domain\Services\CourtBlockService;
use Juangcarmona\Courtly\Domain\Services\CourtReservationService;
use Juangcarmona\Courtly\Application\Utils\EventTransformer;

class AvailabilityAjaxController
{
    public function __construct(
        private CourtRepositoryInterface $courtRepo,
        private CourtReservationRepositoryInterface $reservationRepo,
        private CourtBlockRepositoryInterface $blockRepo,
        private OpeningHoursRepositoryInterface $openingHoursRepo,
        private CourtBlockService $blockService,
        private CourtReservationService $reservationService,
        private EventTransformer $eventTransformer
    ) {}

    public function registerHooks(): void
    {
        $hooks = [
            'courtly_get_courts'          => 'getCourts',
            'courtly_get_reservations'    => 'getReservations',
            'courtly_get_opening_hours'   => 'getOpeningHours',
            'courtly_get_blocks'          => 'getBlocks',
            'courtly_get_blocked_slots'   => 'getBlockedSlots',
            'courtly_save_blocked_slot'   => 'saveBlockedSlot',
            'courtly_remove_blocked_slot' => 'removeBlockedSlot',
        ];

        foreach ($hooks as $action => $method) {
            add_action("wp_ajax_{$action}", [$this, $method]);
            add_action("wp_ajax_nopriv_{$action}", [$this, $method]);
        }
    }

    public function getCourts(): void
    {
        $courts = $this->courtRepo->findAll();
        $resources = array_map(fn($c) => [
            'id'    => $c->getId(),
            'title' => $c->getName(),
        ], $courts);

        wp_send_json($resources);
    }

    public function getReservations(): void
    {
        $reservations = $this->reservationRepo->findBetweenDates(
            new DateTime('today -30 days'),
            new DateTime('today +30 days')
        );

        $events = array_map(fn($r) => $this->eventTransformer::reservationToEvent($r), $reservations);
        wp_send_json($events);
    }

    public function getOpeningHours(): void
    {
        $rows = $this->openingHoursRepo->getAll();

        $result = [];
        foreach ($rows as $row) {
            $result[(int)$row->day_of_week] = [
                'start' => $row->getOpenTime(),
                'end'   => $row->getCloseTime(),
            ];
        }

        wp_send_json($result);
    }

    public function getBlocks(): void
    {
        $today  = new DateTimeImmutable('today');
        $end    = $today->add(new DateInterval('P30D'));
        $period = new DatePeriod($today, new DateInterval('P1D'), $end);

        $courts = $this->courtRepo->findAll();
        $events = [];

        foreach ($courts as $court) {
            $blocks = $this->blockRepo->getBlockedSlots($court->getId());

            foreach ($period as $date) {
                foreach ($blocks as $block) {
                    if ($block->getDayOfWeek() === (int)$date->format('w')) {
                        $events[] = $this->eventTransformer::blockToEvent($date, $block, $court->getId(), $court->getName());
                    }
                }
            }
        }

        wp_send_json($events);
    }

    public function getBlockedSlots(): void
    {
        $court_id = intval($_GET['court_id']);
        $start    = new DateTime($_GET['start']);
        $end      = new DateTime($_GET['end']);

        wp_send_json($this->blockService->getBlockedSlotsForRange($court_id, $start, $end));
    }

    public function saveBlockedSlot(): void
    {
        $court_id = intval($_POST['court_id']);
        $start    = new DateTime($_POST['start_time']);
        $end      = new DateTime($_POST['end_time']);
        $reason   = sanitize_text_field($_POST['reason']);

        $success = $this->blockService->saveBlockedSlot($court_id, $start, $end, $reason);
        wp_send_json(['status' => $success ? 'success' : 'error']);
    }

    public function removeBlockedSlot(): void
    {
        $event_id = intval($_POST['event_id']);
        $success  = $this->blockService->deleteBlockedSlot($event_id);
        wp_send_json(['status' => $success ? 'success' : 'error']);
    }
}
