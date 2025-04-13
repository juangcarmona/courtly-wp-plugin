<?php

require_once plugin_dir_path(__FILE__) . '/../../infrastructure/repositories/UserTypeRepository.php';

class AdminUserController {
    private UserTypeRepository $typeRepo;

    public function __construct() {
        $this->typeRepo = new UserTypeRepository();
    }

    public function handlePost(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_update_user_types')) {
            foreach ($_POST['user_type'] as $user_id => $type) {
                update_user_meta($user_id, 'courtly_user_type', sanitize_text_field($type));
            }

            add_action('admin_notices', function () {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('User types updated.', 'courtly') . '</p></div>';
            });
        }
    }

    public function getViewData(): array {
        return [
            'user_types' => $this->typeRepo->findAll(),
            'users' => get_users(),
        ];
    }
}
