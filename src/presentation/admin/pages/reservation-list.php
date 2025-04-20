<?php
require_once plugin_dir_path(__FILE__) . '../../../Application/Controllers/AdminReservationListController.php';

$controller = new AdminReservationListController();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/reservation-list.view.php';
