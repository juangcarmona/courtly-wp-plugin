<?php

require_once plugin_dir_path(__FILE__) . '../../../infrastructure/CourtlyContainer.php';

class DashboardAjaxController {
    public static function registerHooks() {
        add_action('wp_ajax_courtly_get_dashboard_stats', [self::class, 'getStats']);
        add_action('wp_ajax_courtly_get_dashboard_user_types', [self::class, 'getUserTypesBreakdown']);
    }

    public static function getStats() {
        $courtRepo = CourtlyContainer::courtRepository();
        $userTypeRepo = CourtlyContainer::userTypeRepository();

        global $wpdb;
        $totalUsers = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}users");

        $data = [
            'total_courts' => count($courtRepo->findAll()),
            'total_users' => $totalUsers,
            'total_user_types' => $userTypeRepo->countAll(),
        ];

        wp_send_json($data);
    }

    public static function getUserTypesBreakdown() {
        $repo = CourtlyContainer::userTypeRepository();
        $result = $repo->getUserTypeBreakdown();
        wp_send_json($result);
    }
}
