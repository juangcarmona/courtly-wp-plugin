<?php
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Application\Controllers\AdminUserTypeController;

$controller = ControllerFactory::make(AdminUserTypeController::class);
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/UserTypesView.php';
