<?php

class PublicReservationDetailController {
    private CourtReservation $reservation;
    private WP_User $user;

    public function __construct(int $reservationId) {
        if (!is_user_logged_in()) {
            wp_die(__('You must be logged in to view reservation details.', 'courtly'));
        }

        $this->user = wp_get_current_user();

        $reservation = CourtlyContainer::courtReservationRepository()->findById($reservationId);
        if (!$reservation) {wp_die(__('Reservation not found.', 'courtly'));
            wp_die(__('Reservation not found.', 'courtly'));
        }

        if ($reservation->getUserId() !== $this->user->ID) {
            wp_die(__('You are not allowed to view this reservation.', 'courtly'));
        }

        $this->reservation = $reservation;
    }

    public function handlePost(): void {
        error_log('[Courtly] handlePost() called');
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log('[Courtly] Not a POST request');
            return;
        }
    
        error_log('[Courtly] POST request detected');
        error_log('[Courtly] $_POST: ' . print_r($_POST, true));
    
        if (
            !isset($_POST['courtly_cancel_reservation_id']) ||
            !is_numeric($_POST['courtly_cancel_reservation_id'])
        ) {
            error_log('[Courtly] Missing or invalid reservation ID');
            return;
        }
    
        $id = intval($_POST['courtly_cancel_reservation_id']);
        error_log("[Courtly] Cancel request for reservation ID: $id");
    
        if (!is_user_logged_in()) {
            error_log('[Courtly] User not logged in');
            return;
        }
    
        $userId = get_current_user_id();
        error_log("[Courtly] Current user ID: $userId");
    
        $reservation = CourtlyContainer::courtReservationRepository()->findById($id);
        if (!$reservation) {
            error_log("[Courtly] Reservation not found in DB");
            return;
        }
    
        error_log("[Courtly] Reservation belongs to user ID: " . $reservation->getUserId());
    
        if ($reservation->getUserId() !== $userId) {
            error_log('[Courtly] User not authorized to cancel');
            return;
        }
    
        if (!wp_verify_nonce($_POST['_wpnonce'], 'courtly_cancel_reservation_' . $id)) {
            error_log('[Courtly] Invalid nonce');
            return;
        }
    
        // Check 24h limit
        $range = explode('-', $reservation->getTimeSlot());
        $start = $range[0] ?? '00:00';
        $reservationTime = new DateTimeImmutable(
            $reservation->getReservationDate()->format('Y-m-d') . ' ' . $start
        );
        $now = new DateTimeImmutable();
        $hoursDiff = ($reservationTime->getTimestamp() - $now->getTimestamp()) / 3600;
    
        error_log("[Courtly] Hours until reservation: $hoursDiff");
    
        if ($hoursDiff < COURTLY_MIN_HOURS_TO_CANCEL) {
            error_log('[Courtly] Cannot cancel: less than 24h');
            return;
        }
    
        CourtlyContainer::courtReservationRepository()->deleteReservation($id);
        error_log("[Courtly] Reservation $id cancelled");
    
        $bookingPageId = get_option('courtly_user_booking_page_id');
        $bookingUrl = $bookingPageId ? get_permalink($bookingPageId) : home_url();
        error_log("[Courtly] Redirecting to $bookingUrl");
    
        wp_redirect($bookingUrl);
        exit;
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
            $cancel_blocked_message = __('This reservation has already passed.', 'courtly');
        } elseif (!$cancel_allowed) {
            $cancel_blocked_message = __('Reservation cannot be cancelled (less than 24h remaining).', 'courtly');
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
