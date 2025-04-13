<div class="wrap">
    <h1><?php esc_html_e('Create User Reservation', 'courtly'); ?></h1>
    <p><?php esc_html_e('Select a user and then a time slot below to create a reservation.', 'courtly'); ?></p>

    <form id="courtly-reservation-form" method="POST">
        <?php wp_nonce_field('courtly_create_reservation'); ?>

        <div style="display: flex; align-items: flex-end; gap: 20px; flex-wrap: wrap;">
            <div>
                <label for="user_id"><strong><?php esc_html_e('Select User:', 'courtly'); ?></strong></label><br>
                <select id="courtly-user-select" name="user_id">
                    <option value=""><?php esc_html_e('-- Select User --', 'courtly'); ?></option>
                    <?php foreach ($data['users'] as $user): ?>
                        <option value="<?= esc_attr($user->ID) ?>"
                            <?= selected($_POST['user_id'] ?? '', $user->ID, false) ?>
                            data-type="<?= esc_attr($user->courtly_type) ?>"
                            data-days="<?= esc_attr($user->booking_days_in_advance) ?>">
                            <?= esc_html($user->display_name) ?> (<?= esc_html($user->courtly_type ?: esc_html__('n/a', 'courtly')) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <strong><?php esc_html_e('Selected:', 'courtly'); ?></strong>
                <span id="courtly-summary"><?php esc_html_e('None', 'courtly'); ?></span><br>
                <button type="submit" class="btn btn-sm btn-primary" disabled id="courtly-submit-btn">
                    <?php esc_html_e('Confirm Reservation', 'courtly'); ?>
                </button>
            </div>
        </div>

        <input type="hidden" name="court_id" id="courtly-court-id">
        <input type="hidden" name="start_time" id="courtly-start-time">
        <input type="hidden" name="end_time" id="courtly-end-time">

        <?php if (!empty($data['errors'])): ?>
            <div class="notice notice-error">
                <?php foreach ($data['errors'] as $error): ?>
                    <p><?= esc_html($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="courtly-calendar-wrapper" style="display: none;">
            <div id="courtly-calendar" style="margin-top: 20px;"></div>
        </div>
    </form>
</div>
