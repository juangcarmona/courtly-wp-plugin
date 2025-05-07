<div class="wrap">
    <h1><?php esc_html_e('Court Availability', 'courtly'); ?></h1>

    <form method="GET">
        <input type="hidden" name="page" value="courtly_availability">
        <label for="court_id"><?php esc_html_e('Select Court:', 'courtly'); ?></label>
        <select name="court_id" onchange="this.form.submit()">
            <?php foreach ($data['courts'] as $court): ?>
                <option value="<?= esc_attr($court->getId()) ?>" <?= selected($data['selectedCourtId'], $court->getId()) ?>>
                    <?= esc_html($court->getName()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <hr>

    <form method="POST">
        <?php wp_nonce_field('courtly_update_availability'); ?>
        <input type="hidden" name="court_id" value="<?= esc_attr($data['selectedCourtId']) ?>">

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
                    __('Sunday', 'courtly'), __('Monday', 'courtly'), __('Tuesday', 'courtly'), __('Wednesday', 'courtly'), __('Thursday', 'courtly'), __('Friday', 'courtly'), __('Saturday', 'courtly')
                ];
                foreach ($days as $index => $day):
                    $open = $data['formattedHours'][$index]['open'] ?? '09:00';
                    $close = $data['formattedHours'][$index]['close'] ?? '21:00';
                ?>
                    <tr>
                        <td><?= esc_html($day) ?></td>
                        <td><input type="time" name="opening_hours[<?= $index ?>][open]" value="<?= esc_attr($open) ?>" required></td>
                        <td><input type="time" name="opening_hours[<?= $index ?>][close]" value="<?= esc_attr($close) ?>" required></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <input type="submit" class="btn btn-sm btn-primary" value="<?php esc_attr_e('Save', 'courtly'); ?>">
    </form>

    <hr>
    <div id="courtly-calendar"></div>
</div>
