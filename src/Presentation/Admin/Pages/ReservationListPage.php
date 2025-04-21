<?php
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Application\Controllers\AdminReservationListController;


$controller = ControllerFactory::make(AdminReservationListController::class);
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/ReservationListView.php';
