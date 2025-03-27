<?php
require_once plugin_dir_path(__FILE__) . '/../../infrastructure/repositories/CourtRepository.php';

class AdminCourtController {
    private CourtRepository $repo;

    public function __construct() {
        $this->repo = new CourtRepository();
    }

    public function handlePost(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_add_court')) {
            $name = sanitize_text_field($_POST['name']);
            $number = intval($_POST['number']);
            $description = sanitize_textarea_field($_POST['description']);

            if ($name && $number) {
                $this->repo->insert([
                    'name' => $name,
                    'number' => $number,
                    'description' => $description,
                ]);

                add_action('admin_notices', function () {
                    echo '<div class="notice notice-success is-dismissible"><p>Court added successfully.</p></div>';
                });
            }
        }
    }

    public function getViewData(): array {
        return ['courts' => $this->repo->findAll()];
    }
}
