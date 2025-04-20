<?php

namespace Juangcarmona\Courtly\Domain\Services;
use Juangcarmona\Courtly\Domain\Constants;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtBlockRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;

class CourtReservationService {
    private CourtReservationRepositoryInterface $reservationRepo;
    private CourtBlockRepositoryInterface $blockRepo;
    private OpeningHoursRepositoryInterface $openingRepo;

    public function __construct(
        CourtReservationRepositoryInterface $reservationRepo,
        CourtBlockRepositoryInterface $blockRepo,
        OpeningHoursRepositoryInterface $openingRepo
    ) {
        $this->reservationRepo = $reservationRepo;
        $this->blockRepo = $blockRepo;
        $this->openingRepo = $openingRepo;
    }

    public function isSlotAvailable(int $courtId, string $date, string $slot): bool {
        $dow = (int)(new DateTime($date))->format('w');
        error_log("[Courtly] Checking availability for Court $courtId on $date at slot $slot (DOW: $dow)");

        // Check reservation collision
        $existing = $this->reservationRepo->getReservationsForDate($courtId, $date);
        foreach ($existing as $r) {
            if ($r->time_slot === $slot) {
                error_log("[Courtly] ❌ Slot $slot already reserved.");
                return false;
            }
        }

        // Check recurring blocks
        foreach ($this->blockRepo->getBlockedSlots($courtId) as $block) {
            if ((int)$block->day_of_week === $dow &&
                $slot >= $block->start_time && $slot < $block->end_time) {
                error_log("[Courtly] ❌ Slot $slot is blocked ({$block->start_time} → {$block->end_time}).");
                return false;
            }
        }

        // Check against opening hours
        $opening = $this->openingRepo->getByDayOfWeek($dow);
        if (!$opening) {
            error_log("[Courtly] ❌ No opening hours defined for day $dow.");
            return false;
        }
        if ($slot < $opening->open_time || $slot >= $opening->close_time) {
            error_log("[Courtly] ❌ Slot $slot is outside opening hours ({$opening->open_time} → {$opening->close_time}).");
            return false;
        }

        error_log("[Courtly] ✅ Slot $slot is available.");
        return true;
    }

    public function createReservation(int $userId, int $courtId, string $startIso, string $endIso) {
        $start = new DateTime($startIso);
        $end = new DateTime($endIso);

        $date = $start->format('Y-m-d');
        $slot = $start->format('H:i') . '-' . $end->format('H:i');

        $user = get_userdata($userId);
        $username = $user ? $user->display_name : "User #$userId";

        error_log("[Courtly] Creating reservation for $username on $date ($slot) at Court $courtId");
        // ⛔ Max X reservation(s) per user per day
        $userReservations = $this->reservationRepo->getReservationsForUserOnDate($userId, $date) ?? [];
        if (count($userReservations) >= Constants::MAX_RESERVATIONS_PER_DAY) {
            error_log("[Courtly] ❌ Reservation denied. $username already has a reservation on $date.");
            return sprintf(__('%s already has a reservation that day.', 'courtly'), $username);
        }

        // ⛔ Max duration        
        $interval = $start->diff($end);
        $minutes = ($interval->h * 60) + $interval->i;
        if ($minutes > Constants::COURTLY_MAX_RESERVATION_MINUTES) {
            error_log("[Courtly] ❌ Reservation denied. Duration exceeds limit (" . Constants::COURTLY_MAX_RESERVATION_MINUTES . " mins).");
            return sprintf(__('Reservations must be %d minutes or less.', 'courtly'), Constants::COURTLY_MAX_RESERVATION_MINUTES);
        }

        // ⛔ Availability check
        if (!$this->isSlotAvailable($courtId, $date, $slot)) {
            error_log("[Courtly] ❌ Reservation denied for $username. Slot is not available.");
            return __('Time slot unavailable.', 'courtly');
        }

        // ✅ All good → Save reservation
        $success = $this->reservationRepo->insertReservation([
            'user_id' => $userId,
            'court_id' => $courtId,
            'reservation_date' => $date,
            'time_slot' => $slot,
            'created_at' => current_time('mysql', 1)
        ]);

        if ($success) {
            error_log("[Courtly] ✅ Reservation saved for $username on Court $courtId, $date $slot.");
            return true;
        } else {
            error_log("[Courtly] ❌ DB insertion failed for reservation of $username.");
            return __('Failed to create reservation.', 'courtly');
        }
    }
}
