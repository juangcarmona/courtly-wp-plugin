<?php
require_once plugin_dir_path(__FILE__) . '../../../Application/Controllers/AdminAvailabilityController.php';

$controller = new AdminAvailabilityController();
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/availability.view.php';
