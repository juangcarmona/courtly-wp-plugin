<?php

namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Domain\Repositories\UserTypeRepositoryInterface;

class AdminUserController
{
    public function __construct(
        private UserTypeRepositoryInterface $typeRepo
    ) {}

    public function handlePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_update_user_types')) {
            foreach ($_POST['user_type'] as $userId => $type) {
                update_user_meta((int) $userId, 'courtly_user_type', sanitize_text_field($type));
            }

            add_action('admin_notices', function () {
                echo '<div class="notice notice-success is-dismissible"><p>' .
                    esc_html__('User types updated.', 'courtly') .
                    '</p></div>';
            });
        }
    }

    public function getViewData(): array
    {
        return [
            'user_types' => $this->typeRepo->findAll(),
            'users' => get_users(),
        ];
    }
}
