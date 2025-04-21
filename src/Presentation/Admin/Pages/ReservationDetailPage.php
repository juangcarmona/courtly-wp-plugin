<?php
require_once plugin_dir_path(__FILE__) . '../../../Application/Controllers/AdminReservationDetailController.php';

$controller = new AdminReservationDetailController();
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/ReservationDetailView.php';
