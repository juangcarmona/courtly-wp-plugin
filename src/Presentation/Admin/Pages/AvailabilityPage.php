<?php
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Application\Controllers\AdminAvailabilityController;

$controller = ControllerFactory::make(AdminAvailabilityController::class);
$controller->handlePost();
$data = $controller->getViewData();

include __DIR__ . '/../Views/AvailabilityView.php';
