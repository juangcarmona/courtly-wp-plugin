<?php
require_once plugin_dir_path(__FILE__) . '../../../Application/Controllers/AdminUserTypeController.php';

$controller = new AdminUserTypeController();
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/UserTypesView.php';
