<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;
use Juangcarmona\Courtly\Infrastructure\CourtlyContainer;

class AdminAvailabilityController
{
    public function __construct(
        private CourtRepositoryInterface $courtRepo,
        private OpeningHoursRepositoryInterface $openingRepo
    ) {}

    public function getViewData(): array {
        $courts = $this->courtRepo->findAll();
        $selectedCourtId = isset($_GET['court_id']) ? intval($_GET['court_id']) : ($courts[0]->getId() ?? null);
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
