<?php if (!defined('ABSPATH')) exit; ?>

<div class="courtly-user-booking wide-layout p-4">
  <div class="d-flex flex-wrap justify-content-center gap-3 text-center mb-4">
    <div class="border rounded p-3">
      <div class="fw-bold small text-uppercase text-muted">ID</div>
      <div><?= esc_html($data['id']) ?></div>
    </div>
    <div class="border rounded p-3">
      <div class="fw-bold small text-uppercase text-muted">Court</div>
      <div><?= esc_html($data['court']) ?></div>
    </div>
    <div class="border rounded p-3">
      <div class="fw-bold small text-uppercase text-muted">Date</div>
      <div><?= esc_html($data['date']) ?></div>
    </div>
    <div class="border rounded p-3">
      <div class="fw-bold small text-uppercase text-muted">Time Slot</div>
      <div><?= esc_html($data['slot']) ?></div>
    </div>
  </div>

  <?php if ($data['cancel_allowed']): ?>
    <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?');" class="text-center mt-4">
      <?php wp_nonce_field('courtly_cancel_reservation_' . $data['id']); ?>
      <input type="hidden" name="courtly_cancel_reservation_id" value="<?= esc_attr($data['id']) ?>">
      <button type="submit" class="btn btn-sm btn-danger me-2">Cancel Reservation</button>
      <button type="button" class="btn btn-sm btn-secondary" onclick="window.history.back();">← Back</button>
    </form>
  <?php elseif ($data['cancel_blocked_message']): ?>
    <div class="text-center mt-4">
      <p class="text-muted mb-3"><em><?= esc_html($data['cancel_blocked_message']) ?></em></p>
      <button type="button" class="btn btn-sm btn-secondary" onclick="window.history.back();">← Back</button>
    </div>
  <?php endif; ?>

  <div class="mt-5 text-center">
    <?php
      if (!function_exists('courtly_render_footer')) {
        require_once plugin_dir_path(__FILE__) . '/../../shared/footer.php';
      }
      courtly_render_footer();
    ?>
  </div>
</div>
