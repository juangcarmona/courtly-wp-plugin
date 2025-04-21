<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Domain\Constants;
use Juangcarmona\Courtly\Domain\Entities\CourtReservation;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;
use WP_User;
use DateTimeImmutable;

class PublicReservationDetailController
{
    private CourtReservation $reservation;
    private WP_User $user;

    public function __construct(
        int $reservationId,
        private CourtReservationRepositoryInterface $reservationRepo,
        private CourtRepositoryInterface $courtRepo
    ) {
        if (!is_user_logged_in()) {
            wp_die(__('You must be logged in to view reservation details.', 'courtly'));
        }

        $this->user = wp_get_current_user();
        $reservation = $this->reservationRepo->findById($reservationId);

        if (!$reservation) {
            wp_die(__('Reservation not found.', 'courtly'));
        }

        if ($reservation->getUserId() !== $this->user->ID) {
            wp_die(__('You are not allowed to view this reservation.', 'courtly'));
        }

        $this->reservation = $reservation;
    }

    public function handlePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (
            !isset($_POST['courtly_cancel_reservation_id']) ||
            !is_numeric($_POST['courtly_cancel_reservation_id']) ||
            !is_user_logged_in()
        ) return;

        $id = intval($_POST['courtly_cancel_reservation_id']);
        $userId = get_current_user_id();

        $reservation = $this->reservationRepo->findById($id);
        if (!$reservation || $reservation->getUserId() !== $userId) return;

        if (!wp_verify_nonce($_POST['_wpnonce'], 'courtly_cancel_reservation_' . $id)) return;

        $start = explode('-', $reservation->getTimeSlot())[0] ?? '00:00';
        $reservationTime = new DateTimeImmutable(
            $reservation->getReservationDate()->format('Y-m-d') . ' ' . $start
        );
        $now = new DateTimeImmutable();
        $hoursDiff = ($reservationTime->getTimestamp() - $now->getTimestamp()) / 3600;

        if ($hoursDiff < Constants::COURTLY_MIN_HOURS_TO_CANCEL) return;

        $this->reservationRepo->deleteReservation($id);

        $bookingPageId = get_option('courtly_user_booking_page_id');
        $redirectUrl = $bookingPageId ? get_permalink($bookingPageId) : home_url();
        wp_redirect($redirectUrl);
        exit;
    }

    public function getViewData(): array
    {
        $court = $this->courtRepo->findById($this->reservation->getCourtId());

        $start = explode('-', $this->reservation->getTimeSlot())[0] ?? '00:00';
        $reservationTime = new DateTimeImmutable(
            $this->reservation->getReservationDate()->format('Y-m-d') . ' ' . $start
        );

        $now = new DateTimeImmutable();
        $hoursUntil = ($reservationTime->getTimestamp() - $now->getTimestamp()) / 3600;

        $cancelAllowed = $hoursUntil > Constants::COURTLY_MIN_HOURS_TO_CANCEL;
        $cancelBlockedMessage = null;

        if ($reservationTime < $now) {
            $cancelBlockedMessage = __('This reservation has already passed.', 'courtly');
        } elseif (!$cancelAllowed) {
            $cancelBlockedMessage = __('Reservation cannot be cancelled (less than 24h remaining).', 'courtly');
        }

        return [
            'id' => $this->reservation->getId(),
            'court' => $court ? $court->getName() : 'Court #' . $this->reservation->getCourtId(),
            'date' => $this->reservation->getReservationDate()->format('Y-m-d'),
            'slot' => $this->reservation->getTimeSlot(),
            'cancel_allowed' => $cancelAllowed,
            'cancel_blocked_message' => $cancelBlockedMessage,
        ];
    }
}
