<?php
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Application\Controllers\AdminOpeningHoursController;

$controller = ControllerFactory::make(AdminOpeningHoursController::class);
$controller->registerSettings(); // if needed before rendering
$data = $controller->getViewData();

include __DIR__ . '/../Views/OpeningHoursView.php';
