<?php
require_once plugin_dir_path(__FILE__) . '/../../Infrastructure/Repositories/UserTypeRepository.php';

class AdminUserTypeController {
    private UserTypeRepository $repo;

    public function __construct() {
        $this->repo = new UserTypeRepository();
    }

    public function handlePost(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_add_user_type')) {
            $name = sanitize_title($_POST['name']);
            $display_name = sanitize_text_field($_POST['display_name']);
            $days = intval($_POST['booking_days_in_advance']);

            if ($name && $display_name && $days > 0) {
                $this->repo->insert([
                    'name' => $name,
                    'display_name' => $display_name,
                    'booking_days_in_advance' => $days,
                ]);

                add_action('admin_notices', function () {
                    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('User type added successfully.', 'courtly') . '</p></div>';
                });
            }
        }
    }

    public function getViewData(): array {
        return ['user_types' => $this->repo->findAll()];
    }
}
