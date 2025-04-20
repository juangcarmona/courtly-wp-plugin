<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use DateTimeImmutable;
use DateTimeZone;
use Juangcarmona\Courtly\Domain\Entities\CourtReservation;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;

class AdminReservationListController
{
    private array $upcoming;
    private array $past;
    private array $userMap = [];
    private array $courtMap = [];

    public function __construct(
        private CourtReservationRepositoryInterface $reservationRepo,
        private CourtRepositoryInterface $courtRepo
    ) {
        $today = new DateTimeImmutable('today', new DateTimeZone('UTC'));

        $this->loadUserAndCourtMaps();

        $this->upcoming = $this->enrichReservations(
            $this->reservationRepo->findFrom($today, 30),
            $this->userMap,
            $this->courtMap
        );

        $this->past = $this->enrichReservations(
            $this->reservationRepo->findBefore($today, 90),
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
            $this->courtMap[$c->id] = $c->name ?? 'Court ' . $c->id;
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
        return [
            'upcoming' => $this->upcoming,
            'past' => $this->past,
        ];
    }
}
