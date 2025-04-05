<?php

class AdminReservationController {
    private $users;
    private $userTypes;

    public function __construct() {
        $this->users = get_users();
        $this->userTypes = CourtlyContainer::userTypeRepository()->findAll();
    }

    public function handlePost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (
            !isset($_POST['user_id'], $_POST['court_id'], $_POST['start_time'], $_POST['end_time']) ||
            !wp_verify_nonce($_POST['_wpnonce'], 'courtly_create_reservation')
        ) return;

        $user_id = intval($_POST['user_id']);
        $court_id = intval($_POST['court_id']);
        $start_time = sanitize_text_field($_POST['start_time']);
        $end_time = sanitize_text_field($_POST['end_time']);

        CourtlyContainer::courtReservationService()->createReservation($user_id, $court_id, $start_time, $end_time);
    }

    public function getViewData() {
        foreach ($this->users as &$user) {
            $type_key = get_user_meta($user->ID, 'courtly_user_type', true);
            $type_info = array_filter($this->userTypes, fn($t) => $t->name === $type_key);
            $user->courtly_type = $type_key;
            $user->booking_days_in_advance = reset($type_info)->booking_days_in_advance ?? 0;
        }

        return [
            'users' => $this->users,
            'user_types' => $this->userTypes
        ];
    }
}
