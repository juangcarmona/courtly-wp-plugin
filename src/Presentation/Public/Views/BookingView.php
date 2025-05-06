<?php
$current_user = wp_get_current_user();
$user_type = get_user_meta($current_user->ID, 'courtly_user_type', true);
?>

<div class="courtly-user-booking wide-layout">
<form method="POST">
  <?php wp_nonce_field('courtly_user_create_reservation'); ?>
  <input type="hidden" name="court_id" id="courtly-court-id">
  <input type="hidden" name="start_time" id="courtly-start-time">
  <input type="hidden" name="end_time" id="courtly-end-time">

    <?php if (!empty($data['errors'])) { ?>
      <div class="courtly-alert courtly-error" style="margin-bottom: 15px; color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; padding: 10px; border-radius: 4px;">
        <?php foreach ($data['errors'] as $error) { ?>
          <p style="margin: 0;"><?php echo esc_html__($error, 'courtly'); ?></p>
        <?php } ?>
      </div>
    <?php } elseif (!empty($data['success'])) { ?>
      <div class="courtly-alert courtly-success" style="margin-bottom: 15px; color: #0f5132; background-color: #d1e7dd; border: 1px solid #badbcc; padding: 10px; border-radius: 4px;">
        🎉 <?php echo esc_html__('Your reservation was successfully created.', 'courtly'); ?>
      </div>
    <?php } ?>

    <div class="courtly-selection-info">
      <div id="courtly-slot-details">
        📍 <strong id="courtly-court-name">—</strong> 
        📅 <span id="courtly-date">—</span>
        🕒 <span id="courtly-time">—</span>
      </div>

      <button type="submit" class="courtly-book-btn btn btn-primary" style="display: none;">
        <?php echo esc_html__('Confirm Reservation', 'courtly'); ?>
      </button>
    </div>

    <div id="courtly-calendar"></div>
</form>

<?php
  if (!function_exists('courtly_render_footer')) {
      require_once plugin_dir_path(__FILE__).'../../Shared/FooterRenderer.php';
  }
courtly_render_footer();
?>
</div>
