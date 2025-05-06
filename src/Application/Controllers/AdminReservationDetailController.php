<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Domain\Entities\CourtReservation;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;

class AdminReservationDetailController
{
    private CourtReservation $reservation;

    public function __construct(
        private CourtReservationRepositoryInterface $reservationRepo,
        private CourtRepositoryInterface $courtRepo,
        int $reservationId = 0
    ) {
        if (!current_user_can('manage_options')) {
            wp_die(__('Access denied.', 'courtly'));
        }

        $id = $reservationId > 0 ? $reservationId : intval($_GET['id'] ?? 0);
        if (!$id) {
            wp_die(__('Reservation ID missing.', 'courtly'));
        }

        $this->reservation = $this->reservationRepo->findById($id);
        if (!$this->reservation) {
            wp_die(__('Reservation not found.', 'courtly'));
        }
    }

    public function handlePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (
            !isset($_POST['courtly_cancel_reservation_id'])
            || !is_numeric($_POST['courtly_cancel_reservation_id'])
            || !current_user_can('manage_options')
            || !wp_verify_nonce($_POST['_wpnonce'], 'courtly_cancel_reservation_'.$_POST['courtly_cancel_reservation_id'])
        ) {
            return;
        }

        $id = intval($_POST['courtly_cancel_reservation_id']);
        $this->reservationRepo->deleteReservation($id);

        wp_redirect(admin_url('admin.php?page=courtly_activity_calendar'));
        exit;
    }

    public function getViewData(): array
    {
        $user = get_user_by('ID', $this->reservation->getUserId());
        $court = $this->courtRepo->findById($this->reservation->getCourtId());

        $range = explode('-', $this->reservation->getTimeSlot());
        $start = $range[0] ?? '00:00';

        $reservationTime = new \DateTimeImmutable(
            $this->reservation->getReservationDate()->format('Y-m-d').' '.$start
        );

        $now = new \DateTimeImmutable();
        $hoursUntil = $reservationTime->getTimestamp() - $now->getTimestamp();

        $cancel_allowed = $hoursUntil > (24 * 3600);
        $cancel_blocked_message = null;

        if ($reservationTime < $now) {
            $cancel_blocked_message = __('This reservation has already passed.', 'courtly');
        } elseif (!$cancel_allowed) {
            $cancel_blocked_message = __('Reservation cannot be cancelled (less than 24h remaining).', 'courtly');
        }

        return [
            'id' => $this->reservation->getId(),
            'user' => $user ? $user->display_name : __('Unknown', 'courtly'),
            'court' => $court ? $court->getName() : 'Court #'.$this->reservation->getCourtId(),
            'date' => $this->reservation->getReservationDate()->format('Y-m-d'),
            'slot' => $this->reservation->getTimeSlot(),
            'cancel_allowed' => $cancel_allowed,
            'cancel_blocked_message' => $cancel_blocked_message,
        ];
    }
}
