<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;

class AdminOpeningHoursController
{
    public function __construct(
        private OpeningHoursRepositoryInterface $openingRepo
    ) {
    }

    public function getViewData(): array
    {
        $openingHours = $this->openingRepo->getAll();

        $formattedHours = [];
        foreach ($openingHours as $hour) {
            $formattedHours[$hour->getDayOfWeek()] = [
                'open' => $hour->getOpenTime(),
                'close' => $hour->getCloseTime(),
            ];
        }

        return compact('formattedHours');
    }

    public function handlePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_update_opening_hours')) {
            $openingHours = $_POST['opening_hours'] ?? [];

            foreach ($openingHours as $dayOfWeek => $hours) {
                $this->openingRepo->upsert(
                    (int) $dayOfWeek,
                    sanitize_text_field($hours['open']),
                    sanitize_text_field($hours['close'])
                );
            }

            add_action('admin_notices', function () {
                echo '<div class="notice notice-success is-dismissible"><p>'.esc_html__('Opening hours updated.', 'courtly').'</p></div>';
            });
        }
    }
}
