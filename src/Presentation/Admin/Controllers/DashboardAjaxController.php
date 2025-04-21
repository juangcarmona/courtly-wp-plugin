<?php

namespace Juangcarmona\Courtly\Presentation\Admin\Controllers;

use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\UserTypeRepositoryInterface;

class DashboardAjaxController
{
    private CourtRepositoryInterface $courtRepo;
    private UserTypeRepositoryInterface $userTypeRepo;

    public function __construct(
        CourtRepositoryInterface $courtRepo,
        UserTypeRepositoryInterface $userTypeRepo
    ) {
        $this->courtRepo = $courtRepo;
        $this->userTypeRepo = $userTypeRepo;
    }

    public function registerHooks(): void
    {
        add_action('wp_ajax_courtly_get_dashboard_stats', [$this, 'getStats']);
        add_action('wp_ajax_courtly_get_dashboard_user_types', [$this, 'getUserTypesBreakdown']);
    }

    public function getStats(): void
    {
        global $wpdb;
        $totalUsers = (int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}users");

        $data = [
            'total_courts' => count($this->courtRepo->findAll()),
            'total_users' => $totalUsers,
            'total_user_types' => $this->userTypeRepo->countAll(),
        ];

        wp_send_json($data);
    }

    public function getUserTypesBreakdown(): void
    {
        $result = $this->userTypeRepo->getUserTypeBreakdown();
        wp_send_json($result);
    }
}
