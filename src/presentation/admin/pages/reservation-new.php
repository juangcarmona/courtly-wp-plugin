<?php
require_once plugin_dir_path(__FILE__) . '../../../Application/Controllers/AdminReservationNewController.php';

$controller = new AdminReservationNewController ();
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/reservation-new.view.php';

