<?php

use Juangcarmona\Courtly\Application\Controllers\PublicReservationController;
use Juangcarmona\Courtly\Application\Controllers\PublicReservationDetailController;
use Juangcarmona\Courtly\Infrastructure\ControllerFactory;

if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('courtly_general_calendar', function () {
    wp_enqueue_style('courtly-bootstrap-css', 'https://bootswatch.com/5/minty/bootstrap.min.css');
    wp_enqueue_style('courtly-calendar-css', plugin_dir_url(__DIR__).'/../Shared/Calendar/Calendar.css');
    wp_enqueue_script('fullcalendar-js', 'https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js', [], false, true);
    wp_enqueue_script('courtly-general-calendar',
        plugin_dir_url(__FILE__).'Js/PublicAvailabilityCalendar.js',
        ['fullcalendar-js'], false, true
    );
    $reservation_page_id = get_option('courtly_reservation_detail_page_id');
    $base_url = get_permalink($reservation_page_id);
    wp_localize_script('courtly-general-calendar', 'courtlyAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'reservation_detail_base_url' => $base_url,
        'is_admin' => false,
        'locale' => get_locale(),
        'current_user_id' => get_current_user_id(),
        'is_logged_in' => is_user_logged_in(),
        'user_type' => get_user_meta(get_current_user_id(), 'courtly_user_type', true),
        'display_name' => wp_get_current_user()->display_name,
        'translations' => [
            'no_reason_provided' => __('No reason provided', 'courtly'),
            'every' => __('Every', 'courtly'),
        ],
    ]);

    add_filter('script_loader_tag', function ($tag, $handle, $src) {
        if ($handle === 'courtly-general-calendar') {
            return '<script type="module" src="'.esc_url($src).'"></script>';
        }

        return $tag;
    }, 10, 3);

    ob_start();
    include __DIR__.'/Views/GeneralCalendarView.php';

    return ob_get_clean();
});

add_shortcode('courtly_user_booking', function () {
    if (!is_user_logged_in()) {
        return '<div class="courtly-booking-login"><p>'.sprintf(
            esc_html__('Please %1$slog in%2$s to book a court.', 'courtly'),
            '<a href="'.esc_url(wp_login_url()).'">',
            '</a>'
        ).'</p></div>';
    }

    $controller = ControllerFactory::make(PublicReservationController::class);
    $controller->handlePost();
    $data = $controller->getViewData();

    $reservation_page_id = get_option('courtly_reservation_detail_page_id');
    $base_url = get_permalink($reservation_page_id);

    wp_enqueue_style('courtly-bootstrap-css', 'https://bootswatch.com/5/minty/bootstrap.min.css');
    wp_enqueue_style('courtly-calendar-css', plugin_dir_url(__DIR__).'/../Shared/Calendar/Calendar.css');
    wp_enqueue_script('fullcalendar-js', 'https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js', [], false, true);
    wp_enqueue_script('courtly-user-booking-calendar',
        plugin_dir_url(__FILE__).'Js/PublicUserBookingCalendar.js',
        ['fullcalendar-js'], false, true
    );

    wp_localize_script('courtly-user-booking-calendar', 'courtlyAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'reservation_detail_base_url' => $base_url,
        'is_admin' => false,
        'locale' => get_locale(),
        'current_user_id' => get_current_user_id(),
        'is_logged_in' => is_user_logged_in(),
        'user_type' => get_user_meta(get_current_user_id(), 'courtly_user_type', true),
        'display_name' => wp_get_current_user()->display_name,
        'translations' => [
            'no_reason_provided' => __('No reason provided', 'courtly'),
            'every' => __('Every', 'courtly'),
        ],
    ]);

    add_filter('script_loader_tag', function ($tag, $handle, $src) {
        if ($handle === 'courtly-user-booking-calendar') {
            return '<script type="module" src="'.esc_url($src).'"></script>';
        }

        return $tag;
    }, 10, 3);

    ob_start();
    include __DIR__.'/Views/BookingView.php';

    return ob_get_clean();
});

add_shortcode('courtly_reservation_detail', function () {
    $id = get_query_var('courtly_reservation_id');
    if (!$id) {
        return '<p class="text-danger">'.esc_html__('Reservation ID not provided.', 'courtly').'</p>';
    }

    $controller = ControllerFactory::make(PublicReservationDetailController::class, ['reservationId' => (int) $id]);

    $controller->handlePost();
    $data = $controller->getViewData();

    wp_enqueue_style('courtly-bootstrap-css', 'https://bootswatch.com/5/minty/bootstrap.min.css');

    ob_start();
    include __DIR__.'/Views/ReservationDetailView.php';

    return ob_get_clean();
});
