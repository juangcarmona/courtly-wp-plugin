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
        $openingHours = $this->openingRepo->getAll();

        $formattedHours = [];
        foreach ($openingHours as $hour) {
            $timeRanges = $hour->getTimeRanges();
            $openTime = $timeRanges[0]['start'] ?? null; // Assuming the first range represents the opening time
            $closeTime = $timeRanges[count($timeRanges) - 1]['end'] ?? null; // Assuming the last range represents the closing time

            $formattedHours[$hour->getDayOfWeek()] = [
                'open' => $openTime,
                'close' => $closeTime,
                'closed' => $hour->isClosed(),
            ];
        }

        return compact('courts', 'selectedCourtId', 'formattedHours');
    }

    public function handlePost(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_update_availability')) {
            $openingHours = $_POST['opening_hours'] ?? [];

            foreach ($openingHours as $dayOfWeek => $hours) {
                $this->openingRepo->upsert(
                    (int) $dayOfWeek,
                    sanitize_text_field($hours['open']),
                    sanitize_text_field($hours['close'])
                );
            }

            add_action('admin_notices', function () {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Availability updated.', 'courtly') . '</p></div>';
            });
        }
    }
}
