<?php

use Juangcarmona\Courtly\Application\Controllers\AdminCourtAvailabilityController;
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;

$controller = ControllerFactory::make(AdminCourtAvailabilityController::class);
// $controller->handlePost();
$data = $controller->getViewData();

include __DIR__.'/../Views/CourtAvailabilityView.php';
