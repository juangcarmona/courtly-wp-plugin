<?php

use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Application\Controllers\AdminAvailabilityController;

$controller =  ControllerFactory::make(AdminOpeningHoursController::class);
$viewData = $controller->getViewData();

include __DIR__ . '/../Views/OpeningHoursView.php';
