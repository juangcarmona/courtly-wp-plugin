<?php

require_once plugin_dir_path(__FILE__) . '../../../Infrastructure/CourtlyContainer.php';

class ReservationAjaxController {
    public static function registerHooks() {
        add_action('wp_ajax_courtly_cancel_reservation', [self::class, 'cancel']);
    }

    public static function cancel() {
        check_ajax_referer('courtly_cancel_reservation');

        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        if (!$id) {
            wp_send_json_error(['message' => 'Missing reservation ID']);
        }

        CourtlyContainer::courtReservationRepository()->delete($id);

        wp_send_json_success(['message' => 'Reservation cancelled']);
    }
}
