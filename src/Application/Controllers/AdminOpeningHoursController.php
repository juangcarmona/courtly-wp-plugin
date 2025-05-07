<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Domain\Services\OpeningHoursService;

class AdminOpeningHoursController
{
    private array $errors = [];

    public function __construct(private OpeningHoursService $openingHoursService)
    {
    }

    public function handlePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (!isset($_POST['opening_hours_nonce']) || !wp_verify_nonce($_POST['opening_hours_nonce'], 'save_opening_hours')) {
            $this->errors[] = __('Invalid request.', 'courtly');

            return;
        }

        $raw = $_POST['opening_hours'] ?? [];
        $this->openingHoursService->saveOpeningHours($raw);

        wp_redirect(admin_url('admin.php?page=courtly_opening_hours'));
        exit;
    }

    public function getViewData(): array
    {
        $openingHours = $this->openingHoursService->getAllOpeningHours();

        $formatted = [];
        foreach ($openingHours as $hour) {
            $formatted[] = [
                'day_of_week' => $hour->getDayOfWeek(),
                'time_ranges' => $hour->getTimeRanges(),
                'closed' => $hour->isClosed(),
            ];
        }

        return [
            'opening_hours' => $formatted,
            'errors' => $this->errors,
        ];
    }
}
