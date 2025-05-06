<?php
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;
use Juangcarmona\Courtly\Application\Controllers\AdminCourtController;

$controller = ControllerFactory::make(AdminCourtController::class);
$controller->handlePost();
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../Views/CourtsView.php';
