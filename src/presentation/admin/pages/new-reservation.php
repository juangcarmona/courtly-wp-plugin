<?php
require_once plugin_dir_path(__FILE__) . '../../../application/controllers/AdminReservationController.php';

$controller = new AdminReservationController();
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/new-reservation.view.php';

