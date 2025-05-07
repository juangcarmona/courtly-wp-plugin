<div class="wrap">
    <h1 class="mb-4"><?php esc_html_e('Manage Opening Hours', 'courtly'); ?></h1>

    <form method="POST" class="card p-4 shadow-sm">
        <?php wp_nonce_field('courtly_update_opening_hours'); ?>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
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
                            <td><strong><?php echo esc_html($day); ?></strong></td>
                            <td>
                                <input type="time" class="form-control" name="opening_hours[<?php echo $index; ?>][open]" value="<?php echo esc_attr($open); ?>" required>
                            </td>
                            <td>
                                <input type="time" class="form-control" name="opening_hours[<?php echo $index; ?>][close]" value="<?php echo esc_attr($close); ?>" required>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success">
                💾 <?php echo esc_html__('Save Opening Hours', 'courtly'); ?>
            </button>
        </div>
    </form>
</div>
