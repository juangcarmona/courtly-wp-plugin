<?php

use Juangcarmona\Courtly\Application\Controllers\AdminReservationDetailController;
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;

$reservationId = isset($_GET['reservationId']) ? (int) $_GET['reservationId'] : 0;

$controller = ControllerFactory::make(AdminReservationDetailController::class, [
    'reservationId' => $reservationId,
]);
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__).'/../Views/ReservationDetailView.php';
