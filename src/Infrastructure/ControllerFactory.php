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
use Juangcarmona\Courtly\Presentation\Admin\Controllers\AvailabilityAjaxController;
use Juangcarmona\Courtly\Presentation\Admin\Controllers\AvailabilityController;
use Juangcarmona\Courtly\Presentation\Admin\Controllers\CourtController;

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
            AvailabilityAjaxController::class => new AvailabilityAjaxController(
                CourtlyContainer::courtRepository(),
                CourtlyContainer::courtReservationRepository(),
                CourtlyContainer::courtBlockRepository(),
                CourtlyContainer::openingHoursRepository(),
                CourtlyContainer::courtBlockService(),
                CourtlyContainer::courtReservationService(),
                CourtlyContainer::eventTransformer()
            ),
            default => throw new \InvalidArgumentException("Unknown controller: $controllerName")
        };
    }
}