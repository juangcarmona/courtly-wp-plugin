<div class="wrap">
  <h1 class="mb-4"><?php esc_html_e('Reservation Detail', 'courtly'); ?></h1>

  <table class="table table-bordered w-auto">
    <tr><th><?php esc_html_e('ID', 'courtly'); ?></th><td><?php echo esc_html($data['id']); ?></td></tr>
    <tr><th><?php esc_html_e('User', 'courtly'); ?></th><td><?php echo esc_html($data['user']); ?></td></tr>
    <tr><th><?php esc_html_e('Court', 'courtly'); ?></th><td><?php echo esc_html($data['court']); ?></td></tr>
    <tr><th><?php esc_html_e('Date', 'courtly'); ?></th><td><?php echo esc_html($data['date']); ?></td></tr>
    <tr><th><?php esc_html_e('Time Slot', 'courtly'); ?></th><td><?php echo esc_html($data['slot']); ?></td></tr>
  </table>

  <div class="d-flex gap-3 mt-4">
    <?php if ($data['cancel_allowed']) { ?>
      <form method="post" onsubmit="return confirm('<?php esc_attr_e('Are you sure you want to cancel this reservation?', 'courtly'); ?>');">
        <?php wp_nonce_field('courtly_cancel_reservation_'.$data['id']); ?>
        <input type="hidden" name="courtly_cancel_reservation_id" value="<?php echo esc_attr($data['id']); ?>">
        <button type="submit" class="btn btn-danger"><?php esc_html_e('Cancel Reservation', 'courtly'); ?></button>
      </form>
    <?php } elseif ($data['cancel_blocked_message']) { ?>
      <p class="text-muted align-self-center mb-0">
        <em><?php echo esc_html__($data['cancel_blocked_message'], 'courtly'); ?></em>
      </p>
    <?php } ?>

    <a href="javascript:history.back()" class="btn btn-secondary">← <?php esc_html_e('Back', 'courtly'); ?></a>
  </div>
</div>
