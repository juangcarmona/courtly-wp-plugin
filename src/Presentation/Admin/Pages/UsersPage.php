<?php
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Application\Controllers\AdminUserController;

$controller = ControllerFactory::make(AdminUserController::class);
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/UsersView.php';
