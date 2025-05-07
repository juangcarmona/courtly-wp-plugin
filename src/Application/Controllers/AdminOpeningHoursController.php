<?php
namespace Juangcarmona\Courtly\Application\Controllers;

use Juangcarmona\Courtly\Application\Services\OpeningHoursService;

class AdminOpeningHoursController {
    private $openingHoursService;

    public function __construct(OpeningHoursService $openingHoursService)
    {
        $this->openingHoursService = $openingHoursService;
    }

    public function renderPage() {
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'courtly'));
        }

        // Render the opening hours management page
        echo '<div class="wrap">';
        echo '<h1>' . __('Opening Hours', 'courtly') . '</h1>';
        echo '<form method="post" action="options.php">';

        // Output security fields for the registered setting
        settings_fields('courtly_opening_hours');

        // Output setting sections and their fields
        do_settings_sections('courtly_opening_hours');

        // Submit button
        submit_button(__('Save Changes', 'courtly'));

        echo '</form>';
        echo '</div>';
    }

    public function registerSettings() {
        // Register a new setting for opening hours
        register_setting('courtly_opening_hours', 'courtly_opening_hours_data');

        // Add a new section to the settings page
        add_settings_section(
            'courtly_opening_hours_section',
            __('Manage Opening Hours', 'courtly'),
            [$this, 'renderSectionDescription'],
            'courtly_opening_hours'
        );

        // Add fields for each day of the week
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($days as $day) {
            add_settings_field(
                'courtly_opening_hours_' . strtolower($day),
                __($day, 'courtly'),
                [$this, 'renderDayField'],
                'courtly_opening_hours',
                'courtly_opening_hours_section',
                ['day' => $day]
            );
        }
    }

    public function renderSectionDescription() {
        echo '<p>' . __('Set the opening hours for your venue.', 'courtly') . '</p>';
    }

    public function renderDayField($args) {
        $day = $args['day'];
        $option = get_option('courtly_opening_hours_data');
        $value = isset($option[$day]) ? $option[$day] : '';
        echo '<input type="text" name="courtly_opening_hours_data[' . $day . ']" value="' . esc_attr($value) . '" placeholder="e.g., 09:00-17:00, 18:00-21:00">';
    }
}