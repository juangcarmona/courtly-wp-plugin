<div class="wrap">
    <h1><?php esc_html_e('Manage Opening Hours', 'courtly'); ?></h1>

    <form method="POST">
        <?php wp_nonce_field('courtly_update_opening_hours'); ?>

        <table class="form-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Day', 'courtly'); ?></th>
                    <th><?php esc_html_e('Open', 'courtly'); ?></th>
                    <th><?php esc_html_e('Close', 'courtly'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $days = [
                    __('Sunday', 'courtly'),
                    __('Monday', 'courtly'),
                    __('Tuesday', 'courtly'),
                    __('Wednesday', 'courtly'),
                    __('Thursday', 'courtly'),
                    __('Friday', 'courtly'),
                    __('Saturday', 'courtly'),
                ];

    foreach ($days as $index => $day) {
        $open = $data['formattedHours'][$index]['open'] ?? '09:00';
        $close = $data['formattedHours'][$index]['close'] ?? '21:00';
        ?>
                    <tr>
                        <td><?php echo esc_html($day); ?></td>
                        <td>
                            <input
                                type="time"
                                name="opening_hours[<?php echo $index; ?>][open]"
                                value="<?php echo esc_attr($open); ?>"
                                required
                            >
                        </td>
                        <td>
                            <input
                                type="time"
                                name="opening_hours[<?php echo $index; ?>][close]"
                                value="<?php echo esc_attr($close); ?>"
                                required
                            >
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p>
            <input type="submit" class="button button-primary" value="<?php esc_attr_e('Save', 'courtly'); ?>">
        </p>
    </form>
</div>
