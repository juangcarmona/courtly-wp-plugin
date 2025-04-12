<?php

class PublicReservationDetailController {
    private CourtReservation $reservation;
    private WP_User $user;

    public function __construct(int $reservationId) {
        if (!is_user_logged_in()) {
            wp_die('You must be logged in to view reservation details.');
        }

        $this->user = wp_get_current_user();

        $reservation = CourtlyContainer::courtReservationRepository()->findById($reservationId);
        if (!$reservation) {
            wp_die('Reservation not found.');
        }

        // Solo puede ver su propia reserva
        if ($reservation->getUserId() !== $this->user->ID) {
            wp_die('You are not allowed to view this reservation.');
        }

        $this->reservation = $reservation;
    }

    public function getViewData(): array {
        $court = CourtlyContainer::courtRepository()->findById($this->reservation->getCourtId());

        $range = explode('-', $this->reservation->getTimeSlot());
        $start = $range[0] ?? '00:00';

        $reservationTime = new DateTimeImmutable(
            $this->reservation->getReservationDate()->format('Y-m-d') . ' ' . $start
        );

        $now = new DateTimeImmutable();
        $hoursUntil = ($reservationTime->getTimestamp() - $now->getTimestamp()) / 3600;

        $cancel_allowed = $hoursUntil > COURTLY_MIN_HOURS_TO_CANCEL;
        $cancel_blocked_message = null;

        if ($reservationTime < $now) {
            $cancel_blocked_message = 'This reservation has already passed.';
        } elseif (!$cancel_allowed) {
            $cancel_blocked_message = 'Cannot cancel reservation: less than 24h remaining.';
        }

        return [
            'id' => $this->reservation->getId(),
            'court' => $court ? $court->name : 'Court #' . $this->reservation->getCourtId(),
            'date' => $this->reservation->getReservationDate()->format('Y-m-d'),
            'slot' => $this->reservation->getTimeSlot(),
            'cancel_allowed' => $cancel_allowed,
            'cancel_blocked_message' => $cancel_blocked_message,
        ];
    }
}
