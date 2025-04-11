<div class="wrap">
  <h1>Reservation History (Last 12 Months)</h1>

  <table class="table table-striped table-hover">
    <thead><tr><th>ID</th><th>User</th><th>Court</th><th>Date</th><th>Start</th><th>End</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($data['past'] as $r): ?>
        <tr>
          <td><?= esc_html($r->id) ?></td>
          <td><?= esc_html($r->user_name ?? '-') ?></td>
          <td><?= esc_html($r->court_name ?? '-') ?></td>
          <td><?= esc_html($r->date) ?></td>
          <td><?= esc_html($r->start_time) ?></td>
          <td><?= esc_html($r->end_time) ?></td>
          <td><a href="admin.php?page=courtly_reservation_detail&id=<?= esc_attr($r->id) ?>" class="btn btn-sm btn-primary">View</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
