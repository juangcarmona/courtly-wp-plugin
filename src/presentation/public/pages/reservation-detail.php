<?php
// Path: presentation/public/pages/reservation-detail.php

if (!defined('ABSPATH')) exit;

get_header(); 

require_once plugin_dir_path(__FILE__) . '../../../application/controllers/PublicReservationDetailController.php';

$reservationId = get_query_var('courtly_reservation_id');
$controller = new PublicReservationDetailController((int)$reservationId);
$data = $controller->getViewData();

include plugin_dir_path(__FILE__) . '/../views/reservation-detail.view.php';

get_footer(); 
