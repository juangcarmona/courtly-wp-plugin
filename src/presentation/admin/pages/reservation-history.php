<?php
require_once plugin_dir_path(__FILE__) . '../../../application/controllers/AdminReservationHistoryController.php';

$controller = new AdminReservationHistoryController();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/reservation-history.view.php';
