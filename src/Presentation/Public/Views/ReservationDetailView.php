<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<div class="courtly-user-booking wide-layout p-4">
  <div class="d-flex flex-wrap justify-content-center gap-3 text-center mb-4">
    <div class="border rounded p-3">
      <div class="fw-bold small text-uppercase text-muted"><?php esc_html_e('ID', 'courtly'); ?></div>
      <div><?php echo esc_html($data['id']); ?></div>
    </div>
    <div class="border rounded p-3">
      <div class="fw-bold small text-uppercase text-muted"><?php esc_html_e('Court', 'courtly'); ?></div>
      <div><?php echo esc_html($data['court']); ?></div>
    </div>
    <div class="border rounded p-3">
      <div class="fw-bold small text-uppercase text-muted"><?php esc_html_e('Date', 'courtly'); ?></div>
      <div><?php echo esc_html($data['date']); ?></div>
    </div>
    <div class="border rounded p-3">
      <div class="fw-bold small text-uppercase text-muted"><?php esc_html_e('Time Slot', 'courtly'); ?></div>
      <div><?php echo esc_html($data['slot']); ?></div>
    </div>
  </div>

  <?php if ($data['cancel_allowed']) { ?>
    <form method="POST" onsubmit="return confirm('<?php esc_attr_e('Are you sure you want to cancel this reservation?', 'courtly'); ?>');" class="text-center mt-4">
      <?php wp_nonce_field('courtly_cancel_reservation_'.$data['id']); ?>
      <input type="hidden" name="courtly_cancel_reservation_id" value="<?php echo esc_attr($data['id']); ?>">
      <button type="submit" class="btn btn-sm btn-danger me-2"><?php esc_html_e('Cancel Reservation', 'courtly'); ?></button>
      <button type="button" class="btn btn-sm btn-secondary" onclick="window.history.back();">← <?php esc_html_e('Back', 'courtly'); ?></button>
    </form>
  <?php } elseif ($data['cancel_blocked_message']) { ?>
    <div class="text-center mt-4">
      <p class="text-muted mb-3"><em><?php echo esc_html__($data['cancel_blocked_message'], 'courtly'); ?></em></p>
      <button type="button" class="btn btn-sm btn-secondary" onclick="window.history.back();">← <?php esc_html_e('Back', 'courtly'); ?></button>
    </div>
  <?php } ?>

  <div class="mt-5 text-center">
    <?php
      if (!function_exists('courtly_render_footer')) {
          require_once plugin_dir_path(__FILE__).'../../Shared/FooterRenderer.php';
      }
courtly_render_footer();
?>
  </div>
</div>
