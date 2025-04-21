<?php
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Application\Controllers\AdminReservationNewController;

$controller = ControllerFactory::make(AdminReservationNewController::class);
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/ReservationNewView.php';

