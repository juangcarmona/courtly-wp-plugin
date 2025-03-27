<?php
require_once plugin_dir_path(__FILE__) . '../../../application/controllers/AdminUserTypeController.php';

$controller = new AdminUserTypeController();
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/user-types.view.php';
