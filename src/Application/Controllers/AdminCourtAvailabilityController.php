<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;

class AdminCourtAvailabilityController
{
    public function __construct(
        private CourtRepositoryInterface $courtRepo
    ) {
    }

    public function getViewData(): array
    {
        $courts = $this->courtRepo->findAll();
        $selectedCourtId = isset($_GET['court_id']) ? intval($_GET['court_id']) : ($courts[0]->getId() ?? null);

        return compact('courts', 'selectedCourtId');
    }
}
