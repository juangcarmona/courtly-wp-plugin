<?php

class AdminReservationHistoryController {
    private array $pastReservations;
    private array $userMap;
    private array $courtMap;

    public function __construct() {
        $this->loadUserAndCourtMaps();

        $repo = CourtlyContainer::courtReservationRepository();
        $oneYearAgo = (new DateTimeImmutable('today'))->modify('-1 year');
        $today = new DateTimeImmutable('today');

        $this->pastReservations = $this->enrichReservations(
            $repo->findBefore($today, 365),
            $this->userMap,
            $this->courtMap
        );
    }

    private function loadUserAndCourtMaps(): void {
        $users = get_users(['fields' => ['ID', 'display_name']]);
        $this->userMap = [];
        foreach ($users as $u) {
            $this->userMap[$u->ID] = $u->display_name;
        }

        $courts = CourtlyContainer::courtRepository()->findAll();
        $this->courtMap = [];
        foreach ($courts as $c) {
            $this->courtMap[$c->id] = $c->name ?? 'Court ' . $c->id;
        }
    }

    private function enrichReservations(array $reservations, array $userMap, array $courtMap): array {
        return array_map(function (CourtReservation $r) use ($userMap, $courtMap) {
            return (object)[
                'id' => $r->getId(),
                'date' => $r->getReservationDate()->format('Y-m-d'),
                'start_time' => explode('-', $r->getTimeSlot())[0],
                'end_time' => explode('-', $r->getTimeSlot())[1],
                'user_name' => $userMap[$r->getUserId()] ?? __('Unknown', 'courtly'),
                'court_name' => $courtMap[$r->getCourtId()] ?? sprintf(__('Court #%d', 'courtly'), $r->getCourtId()),
            ];
        }, $reservations);
    }

    public function getViewData(): array {
        return ['past' => $this->pastReservations];
    }
}
