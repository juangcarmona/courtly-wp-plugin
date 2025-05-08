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
  <h1 class="mb-4"><?php echo esc_html__('Courtly Dashboard', 'courtly'); ?></h1>

  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title text-primary"><?php echo esc_html__('Courts', 'courtly'); ?></h5>
          <h3 class="card-text"><?php echo esc_html($total_courts); ?></h3>
          <a href="?page=courtly_courts" class="btn btn-sm btn-outline-primary mt-2"><?php echo esc_html__('Manage Courts', 'courtly'); ?></a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title text-primary"><?php echo esc_html__('User Types', 'courtly'); ?></h5>
          <h3 class="card-text"><?php echo esc_html($total_user_types); ?></h3>
          <a href="?page=courtly_user_types" class="btn btn-sm btn-outline-primary mt-2"><?php echo esc_html__('Manage User Types', 'courtly'); ?></a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="card-title text-primary"><?php echo esc_html__('Users', 'courtly'); ?></h5>
          <h3 class="card-text"><?php echo esc_html($total_users); ?></h3>
          <a href="?page=courtly_users" class="btn btn-sm btn-outline-primary mt-2"><?php echo esc_html__('Manage Users', 'courtly'); ?></a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-primary"><?php echo esc_html__('Users by Type', 'courtly'); ?></h5>
          <ul class="list-group list-group-flush mt-2">
            <?php foreach ($user_types as $type) { ?>
              <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                <span class="me-2"><?php echo esc_html($type->display_name); ?></span>
                <span class="badge bg-primary rounded-pill px-3 py-1"><?php echo esc_html($type->user_count); ?></span>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="mb-4">
    <h2><?php echo esc_html__('Quick Actions', 'courtly'); ?></h2>
    <div class="row mt-3 g-3">
      <div class="col-md-3">
        <a href="?page=courtly_reservation_new" class="btn btn-success w-100">➕ <?php echo esc_html__('New Reservation', 'courtly'); ?></a>
      </div>
      <div class="col-md-3">
        <a href="?page=courtly_activity_calendar" class="btn btn-success w-100">📅 <?php echo esc_html__('View Calendar', 'courtly'); ?></a>
      </div>
      <div class="col-md-3">
        <a href="?page=courtly_availability" class="btn btn-success w-100">🕒 <?php echo esc_html__('Set Availability', 'courtly'); ?></a>
      </div>
      <div class="col-md-3">
        <a href="?page=courtly_opening_hours" class="btn btn-success w-100">⏰ <?php echo esc_html__('Opening Hours', 'courtly'); ?></a>
      </div>
    </div>
  </div>
</div>
