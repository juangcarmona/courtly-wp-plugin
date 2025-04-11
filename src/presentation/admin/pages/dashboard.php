<?php
global $wpdb;
$prefix = $wpdb->prefix;

$total_courts = $wpdb->get_var("SELECT COUNT(*) FROM {$prefix}courtly_courts");
$total_users = $wpdb->get_var("SELECT COUNT(*) FROM {$prefix}users");
$total_user_types = $wpdb->get_var("SELECT COUNT(*) FROM {$prefix}courtly_user_types");

$user_types = $wpdb->get_results("
    SELECT ut.display_name, COUNT(u.ID) as user_count
    FROM {$prefix}courtly_user_types ut
    LEFT JOIN {$prefix}usermeta um ON um.meta_key = 'courtly_user_type' AND um.meta_value = ut.name
    LEFT JOIN {$prefix}users u ON u.ID = um.user_id
    GROUP BY ut.name
");
?>

<div class="wrap">
  <h1 class="mb-4">Courtly Dashboard</h1>

  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title text-primary">Courts</h5>
          <h3 class="card-text"><?= esc_html($total_courts) ?></h3>
          <a href="?page=courtly_courts" class="btn btn-sm btn-outline-primary mt-2">Manage Courts</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title text-primary">User Types</h5>
          <h3 class="card-text"><?= esc_html($total_user_types) ?></h3>
          <a href="?page=courtly_user_types" class="btn btn-sm btn-outline-primary mt-2">Manage User Types</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title text-primary">Users</h5>
          <h3 class="card-text"><?= esc_html($total_users) ?></h3>
          <a href="?page=courtly_users" class="btn btn-sm btn-outline-primary mt-2">Manage Users</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-primary">Users by Type</h5>
          <ul class="list-group list-group-flush mt-2">
            <?php foreach ($user_types as $type): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= esc_html($type->display_name) ?>
                <span class="badge bg-primary rounded-pill"><?= esc_html($type->user_count) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="mb-4">
  <h2>Quick Actions</h2>
  <div class="row mt-3 g-3">
    <div class="col-md-4">
      <a href="?page=courtly_reservation_new" class="btn btn-primary w-100">➕ New Reservation</a>
    </div>
    <div class="col-md-4">
      <a href="?page=courtly_calendar" class="btn btn-primary w-100">📅 View Calendar</a>
    </div>
    <div class="col-md-4">
      <a href="?page=courtly_availability" class="btn btn-primary w-100">🕒 Set Availability</a>
    </div>
  </div>
</div>

</div>
