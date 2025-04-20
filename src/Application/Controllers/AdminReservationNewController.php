<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Domain\Repositories\UserTypeRepositoryInterface;
use Juangcarmona\Courtly\Domain\Services\CourtReservationService;

class AdminReservationNewController
{
    private array $users;
    private array $userTypes;
    private array $errors = [];

    public function __construct(
        private CourtReservationService $reservationService,
        private UserTypeRepositoryInterface $userTypeRepo
    ) {
        $this->users = get_users();
        $this->userTypes = $this->userTypeRepo->findAll();
    }

    public function handlePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (
            !isset($_POST['user_id'], $_POST['court_id'], $_POST['start_time'], $_POST['end_time']) ||
            !wp_verify_nonce($_POST['_wpnonce'], 'courtly_create_reservation')
        ) {
            return;
        }

        $userId = intval($_POST['user_id']);
        $courtId = intval($_POST['court_id']);
        $startTime = sanitize_text_field($_POST['start_time']);
        $endTime = sanitize_text_field($_POST['end_time']);

        $result = $this->reservationService->createReservation($userId, $courtId, $startTime, $endTime);

        if ($result === true) {
            wp_redirect(admin_url('admin.php?page=courtly_activity_calendar'));
            exit;
        }

        $this->errors[] = $result;
    }

    public function getViewData(): array
    {
        foreach ($this->users as &$user) {
            $typeKey = get_user_meta($user->ID, 'courtly_user_type', true);
            $typeInfo = array_filter($this->userTypes, fn($t) => $t->name === $typeKey);
            $user->courtly_type = $typeKey;
            $user->booking_days_in_advance = reset($typeInfo)->booking_days_in_advance ?? 0;
        }

        return [
            'users' => $this->users,
            'user_types' => $this->userTypes,
            'errors' => $this->errors,
        ];
    }
}
