<?php
require_once plugin_dir_path(__FILE__) . '../../../Application/Controllers/AdminReservationHistoryController.php';

$controller = new AdminReservationHistoryController();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/ReservationHistoryView.php';
