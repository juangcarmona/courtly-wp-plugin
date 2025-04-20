<?php
require_once plugin_dir_path(__FILE__) . '../../../Application/Controllers/AdminCourtController.php';

$controller = new AdminCourtController();
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/courts.view.php';
