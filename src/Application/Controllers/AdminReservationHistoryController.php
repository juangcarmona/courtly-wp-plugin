<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use DateTimeImmutable;
use Juangcarmona\Courtly\Domain\Entities\CourtReservation;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;

class AdminReservationHistoryController
{
    private array $pastReservations;
    private array $userMap = [];
    private array $courtMap = [];

    public function __construct(
        private CourtReservationRepositoryInterface $reservationRepo,
        private CourtRepositoryInterface $courtRepo
    ) {
        $this->loadUserAndCourtMaps();

        $today = new DateTimeImmutable('today');

        $this->pastReservations = $this->enrichReservations(
            $this->reservationRepo->findBefore($today, 365),
            $this->userMap,
            $this->courtMap
        );
    }

    private function loadUserAndCourtMaps(): void
    {
        $users = get_users(['fields' => ['ID', 'display_name']]);
        foreach ($users as $u) {
            $this->userMap[$u->ID] = $u->display_name;
        }

        $courts = $this->courtRepo->findAll();
        foreach ($courts as $c) {
            $this->courtMap[$c->getId()] = $c->getName() ?? 'Court ' . $c->getId();
        }
    }

    private function enrichReservations(array $reservations, array $userMap, array $courtMap): array
    {
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

    public function getViewData(): array
    {
        return ['past' => $this->pastReservations];
    }
}
