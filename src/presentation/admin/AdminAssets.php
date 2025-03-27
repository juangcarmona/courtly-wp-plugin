<?php
class AdminAssets {
    public static function enqueue($hook) {
        if (strpos($hook, 'courtly') === false) return;

        wp_enqueue_style('courtly-bootstrap-css', 'https://bootswatch.com/5/minty/bootstrap.min.css');
        wp_enqueue_script('fullcalendar-js', 'https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js', [], false, true);
        wp_enqueue_style('courtly-calendar-css', plugin_dir_url(__FILE__) . 'css/admin-calendar.css');

        add_filter('script_loader_tag', [self::class, 'markAsModule'], 10, 3);

        if ($hook === 'toplevel_page_courtly_settings') {
            wp_enqueue_script('courtly-dashboard-calendar',
                plugin_dir_url(__FILE__) . 'js/dashboard-calendar.js',
                ['jquery', 'fullcalendar-js'], false, true);

            wp_localize_script('courtly-dashboard-calendar', 'courtlyAjax', [
                'ajax_url' => admin_url('admin-ajax.php'),
            ]);
        }

        if ($hook === 'courtly_page_courtly_blocks') {
            wp_enqueue_script('courtly-availability-calendar',
                plugin_dir_url(__FILE__) . 'js/availability-calendar.js',
                ['jquery', 'fullcalendar-js'], false, true);

            $court_id = isset($_GET['court_id']) ? intval($_GET['court_id']) : 1;

            wp_localize_script('courtly-availability-calendar', 'courtlyAjax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'court_id' => $court_id
            ]);
        }
    }

    public static function markAsModule($tag, $handle, $src) {
        $handles = ['courtly-dashboard-calendar', 'courtly-availability-calendar'];
        if (in_array($handle, $handles)) {
            return '<script type="module" src="' . esc_url($src) . '"></script>';
        }
        return $tag;
    }
}
