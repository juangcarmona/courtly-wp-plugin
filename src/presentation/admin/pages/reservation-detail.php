<?php
require_once plugin_dir_path(__FILE__) . '../../../application/controllers/AdminReservationDetailController.php';

$controller = new AdminReservationDetailController();
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/reservation-detail.view.php';
