<?php

use Juangcarmona\Courtly\Application\Controllers\AdminOpeningHoursController;
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;

$controller = ControllerFactory::make(AdminOpeningHoursController::class);
$controller->handlePost();
$data = $controller->getViewData();

include __DIR__.'/../Views/OpeningHoursView.php';
