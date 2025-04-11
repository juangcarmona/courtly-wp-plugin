<?php
class AdminAssets {
    public static function enqueue($hook) {
        if (!isset($_GET['page']) || strpos($_GET['page'], 'courtly') === false) return;

        wp_enqueue_style('courtly-bootstrap-css', 'https://bootswatch.com/5/minty/bootstrap.min.css');
        wp_enqueue_style('courtly-calendar-css', plugin_dir_url(__DIR__) . '/../shared/calendar/calendar.css');
        wp_enqueue_script('fullcalendar-js', 'https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js', [], false, true);

        add_filter('script_loader_tag', [self::class, 'markAsModule'], 10, 3);

        $localize = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'is_admin' => true,
            'locale' => get_locale(),
            'current_user_id' => get_current_user_id(),
            'is_logged_in' => is_user_logged_in(),
            'user_type' => get_user_meta(get_current_user_id(), 'courtly_user_type', true),
            'display_name' => wp_get_current_user()->display_name,
        ];

        switch ($_GET['page']) {
            case 'courtly_calendar':
                wp_enqueue_script('courtly-dashboard-calendar',
                    plugin_dir_url(__FILE__) . 'js/dashboard-calendar.js',
                    ['jquery', 'fullcalendar-js'], false, true
                );
                wp_localize_script('courtly-dashboard-calendar', 'courtlyAjax', $localize);
                break;

            case 'courtly_availability':
                wp_enqueue_script('courtly-availability-calendar',
                    plugin_dir_url(__FILE__) . 'js/availability-calendar.js',
                    ['jquery', 'fullcalendar-js'], false, true
                );
                $localize['court_id'] = isset($_GET['court_id']) ? intval($_GET['court_id']) : 1;
                wp_localize_script('courtly-availability-calendar', 'courtlyAjax', $localize);
                break;

            case 'courtly_reservation_new':
                wp_enqueue_script('courtly-reservation-new-calendar',
                    plugin_dir_url(__FILE__) . 'js/reservation-new-calendar.js',
                    ['jquery', 'fullcalendar-js'], false, true
                );
                wp_localize_script('courtly-reservation-new-calendar', 'courtlyAjax', $localize);
                break;
        }
    }

    public static function markAsModule($tag, $handle, $src) {
        $handles = [
            'courtly-dashboard-calendar',
            'courtly-availability-calendar',
            'courtly-reservation-new-calendar'
        ];
        if (in_array($handle, $handles)) {
            return '<script type="module" src="' . esc_url($src) . '"></script>';
        }
        return $tag;
    }
}
