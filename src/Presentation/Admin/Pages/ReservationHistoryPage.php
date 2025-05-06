<?php
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Application\Controllers\AdminReservationHistoryController;

$controller = ControllerFactory::make(AdminReservationHistoryController::class);
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/ReservationHistoryView.php';
