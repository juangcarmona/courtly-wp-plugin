<?php

require_once plugin_dir_path(__FILE__) . '/../../Infrastructure/Repositories/CourtRepository.php';
require_once plugin_dir_path(__FILE__) . '/../../Infrastructure/Repositories/OpeningHoursRepository.php';

class AdminAvailabilityController {
    private CourtRepository $courtRepo;
    private OpeningHoursRepository $openingRepo;

    public function __construct() {
        $this->courtRepo = new CourtRepository();
        $this->openingRepo = new OpeningHoursRepository();
    }

    public function getViewData(): array {
        $courts = $this->courtRepo->findAll();
        $selectedCourtId = isset($_GET['court_id']) ? intval($_GET['court_id']) : ($courts[0]->id ?? null);
        $opening = $selectedCourtId !== null
            ? $this->openingRepo->getByDayOfWeek(date('w')) // assumes day-of-week = today
            : null;

        return compact('courts', 'selectedCourtId', 'opening');
    }

    public function handlePost(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_update_availability')) {
            $courtId = intval($_POST['court_id']);
            $openTime = sanitize_text_field($_POST['opening_time']);
            $closeTime = sanitize_text_field($_POST['closing_time']);

            $weekday = (int) date('w'); // TEMP: should be configurable
            $this->openingRepo->upsert($weekday, $openTime, $closeTime);

            add_action('admin_notices', function () {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Availability updated.', 'courtly') . '</p></div>';
            });
        }
    }
}
