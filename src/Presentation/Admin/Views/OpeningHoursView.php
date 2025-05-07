<?php
$options = $data['options'] ?? [];
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
?>

<div class="wrap">
    <h1><?php esc_html_e('Opening Hours', 'courtly'); ?></h1>

    <form method="POST" action="options.php">
        <?php
        settings_fields('courtly_opening_hours');
        do_settings_sections('courtly_opening_hours');
        ?>

        <table class="form-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Day', 'courtly'); ?></th>
                    <th><?php esc_html_e('Closed', 'courtly'); ?></th>
                    <th><?php esc_html_e('Time Ranges', 'courtly'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($days as $day):
                    $day_key = strtolower($day);
                    $is_closed = $options[$day_key]['closed'] ?? false;
                    $time_ranges = $options[$day_key]['time_ranges'] ?? [];
                ?>
                <tr>
                    <td><?php echo esc_html($day); ?></td>
                    <td>
                        <input type="checkbox" name="courtly_opening_hours_data[<?php echo $day_key; ?>][closed]" value="1" <?php checked($is_closed, true); ?>>
                    </td>
                    <td>
                        <div class="time-ranges" data-name="courtly_opening_hours_data[<?php echo $day_key; ?>][time_ranges][]">
                            <?php foreach ($time_ranges as $range): ?>
                                <div class="time-range">
                                    <input type="time" name="courtly_opening_hours_data[<?php echo $day_key; ?>][time_ranges][][start]" value="<?php echo esc_attr($range['start']); ?>">
                                    <input type="time" name="courtly_opening_hours_data[<?php echo $day_key; ?>][time_ranges][][end]" value="<?php echo esc_attr($range['end']); ?>">
                                    <button type="button" class="remove-time-range">&times;</button>
                                </div>
                            <?php endforeach; ?>
                            <button type="button" class="add-time-range">+ <?php esc_html_e('Add Time Range', 'courtly'); ?></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php submit_button(__('Save Changes', 'courtly')); ?>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.add-time-range').forEach(function (button) {
            button.addEventListener('click', function () {
                const container = this.parentElement;
                const name = container.getAttribute('data-name');
                const newRange = document.createElement('div');
                newRange.classList.add('time-range');
                newRange.innerHTML = `
                    <input type="time" name="${name}[start]">
                    <input type="time" name="${name}[end]">
                    <button type="button" class="remove-time-range">&times;</button>
                `;
                container.insertBefore(newRange, this);
            });
        });

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-time-range')) {
                event.target.closest('.time-range').remove();
            }
        });
    });
</script>
