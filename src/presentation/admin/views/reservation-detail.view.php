<div class="wrap">
  <h1 class="mb-4">Reservation Detail</h1>

  <table class="table table-bordered w-auto">
    <tr><th>ID</th><td><?= esc_html($data['id']) ?></td></tr>
    <tr><th>User</th><td><?= esc_html($data['user']) ?></td></tr>
    <tr><th>Court</th><td><?= esc_html($data['court']) ?></td></tr>
    <tr><th>Date</th><td><?= esc_html($data['date']) ?></td></tr>
    <tr><th>Time Slot</th><td><?= esc_html($data['slot']) ?></td></tr>
  </table>

  <div class="d-flex gap-3 mt-4">
    <?php if ($data['cancel_allowed']): ?>
      <form method="post" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
        <?php wp_nonce_field('courtly_cancel_reservation_' . $data['id']); ?>
        <input type="hidden" name="courtly_cancel_reservation_id" value="<?= esc_attr($data['id']) ?>">
        <button type="submit" class="btn btn-danger">Cancel Reservation</button>
      </form>
    <?php elseif ($data['cancel_blocked_message']): ?>
      <p class="text-muted align-self-center mb-0">
        <em><?= esc_html($data['cancel_blocked_message']) ?></em>
      </p>
    <?php endif; ?>

    <a href="javascript:history.back()" class="btn btn-secondary">← Back</a>
  </div>
</div>
