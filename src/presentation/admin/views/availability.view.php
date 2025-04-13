<div class="wrap">
    <h1><?php esc_html_e('Manage Court Availability', 'courtly'); ?></h1>

    <form method="GET">
        <input type="hidden" name="page" value="courtly_availability">
        <label for="court_id"><?php esc_html_e('Select Court:', 'courtly'); ?></label>
        <select name="court_id" onchange="this.form.submit()">
            <?php foreach ($data['courts'] as $court): ?>
                <option value="<?= esc_attr($court->id) ?>" <?= selected($data['selectedCourtId'], $court->id) ?>>
                    <?= esc_html($court->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <hr>

    <form method="POST">
        <?php wp_nonce_field('courtly_update_availability'); ?>
        <input type="hidden" name="court_id" value="<?= esc_attr($data['selectedCourtId']) ?>">
        <label for="opening_time"><?php esc_html_e('From:', 'courtly'); ?></label>
        <input type="time" name="opening_time" value="<?= esc_attr($data['opening']->open_time ?? '09:00') ?>" required>
        <label for="closing_time"><?php esc_html_e('To:', 'courtly'); ?></label>
        <input type="time" name="closing_time" value="<?= esc_attr($data['opening']->close_time ?? '21:00') ?>" required>
        <input type="submit" class="btn btn-sm btn-primary" value="<?php esc_attr_e('Save', 'courtly'); ?>">
    </form>

    <hr>
    <div id="courtly-calendar"></div>
</div>
