<div class="wrap">
    <h1><?php esc_html_e('Court Availability', 'courtly'); ?></h1>

    <form method="GET">
        <input type="hidden" name="page" value="courtly_availability">
        <label for="court_id"><?php esc_html_e('Select Court:', 'courtly'); ?></label>
        <select name="court_id" onchange="this.form.submit()">
            <?php foreach ($data['courts'] as $court) { ?>
                <option value="<?php echo esc_attr($court->getId()); ?>" <?php echo selected($data['selectedCourtId'], $court->getId()); ?>>
                    <?php echo esc_html($court->getName()); ?>
                </option>
            <?php } ?>
        </select>
    </form>

    <hr>
    <div id="courtly-calendar"></div>
</div>
