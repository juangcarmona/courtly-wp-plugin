<?php

namespace Juangcarmona\Courtly\Presentation\Admin\Controllers;

use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;

class ReservationAjaxController
{
    private CourtReservationRepositoryInterface $reservationRepository;

    public function __construct(CourtReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function registerHooks(): void
    {
        add_action('wp_ajax_courtly_cancel_reservation', [$this, 'cancel']);
    }

    public function cancel(): void
    {
        check_ajax_referer('courtly_cancel_reservation');

        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        if (!$id) {
            wp_send_json_error(['message' => __('Missing reservation ID', 'courtly')]);
        }

        $this->reservationRepository->delete($id);

        wp_send_json_success(['message' => __('Reservation cancelled', 'courtly')]);
    }
}
