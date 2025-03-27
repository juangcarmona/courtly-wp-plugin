<div class="wrap">
    <h1>Manage Court Availability</h1>

    <form method="GET">
        <input type="hidden" name="page" value="courtly_blocks">
        <label for="court_id">Select Court:</label>
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
        <label for="opening_time">From:</label>
        <input type="time" name="opening_time" value="<?= esc_attr($data['opening']->open_time ?? '09:00') ?>" required>
        <label for="closing_time">To:</label>
        <input type="time" name="closing_time" value="<?= esc_attr($data['opening']->close_time ?? '21:00') ?>" required>
        <input type="submit" class="button button-primary" value="Save">
    </form>

    <hr>
    <div id="courtly-calendar"></div>
</div>
