<?php
require_once plugin_dir_path(__FILE__) . '../../../Application/Controllers/AdminUserController.php';

$controller = new AdminUserController();
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/users.view.php';
