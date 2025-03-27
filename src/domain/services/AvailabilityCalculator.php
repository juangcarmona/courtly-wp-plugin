<?php

require_once plugin_dir_path(__FILE__) . '/../repositories/OpeningHoursRepository.php';
require_once plugin_dir_path(__FILE__) . '/../repositories/CourtBlockRepository.php';
require_once plugin_dir_path(__FILE__) . '/../repositories/CourtReservationRepository.php';

class AvailabilityCalculatorService
{
    private $openingHoursRepository;
    private $courtBlockRepository;
    private $courtReservationRepository;

    public function __construct(
        OpeningHoursRepository $openingHoursRepo,
        CourtBlockRepository $courtBlockRepo,
        CourtReservationRepository $courtReservationRepo
    ) {
        $this->openingHoursRepository = $openingHoursRepo;
        $this->courtBlockRepository = $courtBlockRepo;
        $this->courtReservationRepository = $courtReservationRepo;
    }

    public function getAvailableSlots($courtId, $date)
    {
        $dayOfWeek = strtolower(date('l', strtotime($date))); // e.g., 'monday'
        $opening = $this->openingHoursRepository->getByDay($dayOfWeek);
        if (!$opening) return [];

        $start = new DateTime($date . ' ' . $opening->getStartTime());
        $end = new DateTime($date . ' ' . $opening->getEndTime());

        $interval = new DateInterval('PT1H');
        $period = new DatePeriod($start, $interval, $end);

        $slots = [];
        foreach ($period as $slotStart) {
            $slotEnd = clone $slotStart;
            $slotEnd->add($interval);

            $slots[] = [
                'start' => $slotStart->format(DateTime::ATOM),
                'end' => $slotEnd->format(DateTime::ATOM),
                'available' => true
            ];
        }

        // Apply recurrent blocks
        $blocks = $this->courtBlockRepository->getBlockedSlots($courtId);
        foreach ($blocks as $block) {
            if (strtolower($block->day_of_week) !== $dayOfWeek) continue;

            foreach ($slots as &$slot) {
                $slotStart = substr($slot['start'], 11, 5); // "HH:MM"
                if ($slotStart >= $block->start_time && $slotStart < $block->end_time) {
                    $slot['available'] = false;
                }
            }
        }

        // Apply reservations
        $reservations = $this->courtReservationRepository->getReservationsForDate($courtId, $date);
        foreach ($reservations as $res) {
            foreach ($slots as &$slot) {
                $slotStart = substr($slot['start'], 11, 5);
                if ($slotStart >= $res->start_time && $slotStart < $res->end_time) {
                    $slot['available'] = false;
                }
            }
        }

        return $slots;
    }
}
