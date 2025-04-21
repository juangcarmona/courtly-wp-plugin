<div class="wrap">
  <h1><?php esc_html_e('Reservation History (Last 12 Months)', 'courtly'); ?></h1>

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th><?php esc_html_e('ID', 'courtly'); ?></th>
        <th><?php esc_html_e('User', 'courtly'); ?></th>
        <th><?php esc_html_e('Court', 'courtly'); ?></th>
        <th><?php esc_html_e('Date', 'courtly'); ?></th>
        <th><?php esc_html_e('Start', 'courtly'); ?></th>
        <th><?php esc_html_e('End', 'courtly'); ?></th>
        <th><?php esc_html_e('Action', 'courtly'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data['past'] as $r): ?>
        <tr>
          <td><?= esc_html($r->id) ?></td>
          <td><?= esc_html($r->user_name ?? '-') ?></td>
          <td><?= esc_html($r->court_name ?? '-') ?></td>
          <td><?= esc_html($r->date) ?></td>
          <td><?= esc_html($r->start_time) ?></td>
          <td><?= esc_html($r->end_time) ?></td>
          <td>
            <a href="admin.php?page=courtly_reservation_detail&id=<?= esc_attr($r->id) ?>" class="btn btn-sm btn-primary">
              <?php esc_html_e('View', 'courtly'); ?>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
