<?php

use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Presentation\Admin\Controllers\AdminAvailabilityController;

$controller = ControllerFactory::make(AdminReservationDetailController::class);

$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/ReservationDetailView.php';
