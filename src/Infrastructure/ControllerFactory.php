<?php

namespace Juangcarmona\Courtly\Infrastructure;

use Juangcarmona\Courtly\Application\Controllers\AdminAvailabilityController;
use Juangcarmona\Courtly\Application\Controllers\AdminCourtController;
use Juangcarmona\Courtly\Application\Controllers\AdminReservationDetailController;
use Juangcarmona\Courtly\Application\Controllers\AdminReservationHistoryController;
use Juangcarmona\Courtly\Application\Controllers\AdminReservationListController;
use Juangcarmona\Courtly\Application\Controllers\AdminReservationNewController;
use Juangcarmona\Courtly\Application\Controllers\AdminUserController;
use Juangcarmona\Courtly\Application\Controllers\AdminUserTypeController;
use Juangcarmona\Courtly\Application\Controllers\PublicReservationController;
use Juangcarmona\Courtly\Application\Controllers\PublicReservationDetailController;

class ControllerFactory
{
    public static function make(string $controllerName, array $args = []): object
    {
        return match ($controllerName) {
            AdminAvailabilityController::class => new AdminAvailabilityController(
                CourtlyContainer::courtRepository(),
                CourtlyContainer::openingHoursRepository()
            ),
            AdminCourtController::class => new AdminCourtController(
                CourtlyContainer::courtRepository()
            ),
            AdminReservationDetailController::class => new AdminReservationDetailController(
                CourtlyContainer::courtReservationRepository(),
                CourtlyContainer::courtRepository()
            ),            
            AdminReservationHistoryController::class => new AdminReservationHistoryController(
                CourtlyContainer::courtReservationRepository(),
                CourtlyContainer::courtRepository()
            ),
            AdminReservationListController::class => new AdminReservationListController(
                CourtlyContainer::courtReservationRepository(),
                CourtlyContainer::courtRepository()
            ),
            AdminReservationNewController::class => new AdminReservationNewController(
                CourtlyContainer::courtReservationService(),
                CourtlyContainer::userTypeRepository()
            ),
            AdminUserController::class => new AdminUserController(
                CourtlyContainer::userTypeRepository()
            ),
            AdminUserTypeController::class => new AdminUserTypeController(
                CourtlyContainer::userTypeRepository()
            ),
            PublicReservationController::class => new PublicReservationController(
                CourtlyContainer::courtReservationService()
            ),
            PublicReservationDetailController::class => new PublicReservationDetailController(
                $args['reservationId'] ?? 0,
                CourtlyContainer::courtReservationRepository(),
                CourtlyContainer::courtRepository()
            ),
            default => throw new \InvalidArgumentException("Unknown controller: $controllerName")
        };
    }
}