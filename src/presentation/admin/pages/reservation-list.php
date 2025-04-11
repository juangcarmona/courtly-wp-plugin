<?php
require_once plugin_dir_path(__FILE__) . '../../../application/controllers/AdminReservationListController.php';

$controller = new AdminReservationListController();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/reservation-list.view.php';
