<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Domain\Services\CourtReservationService;

class PublicReservationController
{
    private array $errors = [];

    public function __construct(
        private CourtReservationService $reservationService
    ) {}

    public function handlePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (
            !is_user_logged_in() ||
            !isset($_POST['court_id'], $_POST['start_time'], $_POST['end_time']) ||
            !wp_verify_nonce($_POST['_wpnonce'], 'courtly_user_create_reservation')
        ) {
            return;
        }

        $user_id = get_current_user_id();
        $court_id = intval($_POST['court_id']);
        $start_time = sanitize_text_field($_POST['start_time']);
        $end_time = sanitize_text_field($_POST['end_time']);

        $result = $this->reservationService->createReservation($user_id, $court_id, $start_time, $end_time);

        if ($result === true) {
            wp_redirect(add_query_arg('reservation', 'success', wp_get_referer()));
            exit;
        }

        $this->errors[] = $result;
    }

    public function getViewData(): array
    {
        $user = wp_get_current_user();
        $type = get_user_meta($user->ID, 'courtly_user_type', true);

        return [
            'user' => $user,
            'user_type' => $type,
            'errors' => $this->errors,
            'success' => isset($_GET['reservation']) && $_GET['reservation'] === 'success'
        ];
    }
}
