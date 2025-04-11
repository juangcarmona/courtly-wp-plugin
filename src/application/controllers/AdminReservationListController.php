<?php

class AdminReservationListController {
    private $upcoming;
    private $past;
    private array $userMap;
    private array $courtMap;

    public function __construct() {
        $repo = CourtlyContainer::courtReservationRepository();
        $today = new DateTimeImmutable('today', new DateTimeZone('UTC'));
    
        $this->loadUserAndCourtMaps();
    
        $this->upcoming = $this->enrichReservations(
            $repo->findFrom($today, 30),
            $this->userMap,
            $this->courtMap
        );
        
        $this->past = $this->enrichReservations(
            $repo->findBefore($today, 90),
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
                'user_name' => $userMap[$r->getUserId()] ?? 'Unknown',
                'court_name' => $courtMap[$r->getCourtId()] ?? 'Court #' . $r->getCourtId(),
            ];
        }, $reservations);
    }
    

    public function getViewData(): array {
        return [
            'upcoming' => $this->upcoming,
            'past' => $this->past,
        ];
    }
}
