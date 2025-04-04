<?php
global $wpdb;
$prefix = $wpdb->prefix;



// Fetch statistics
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
    <h1>Courtly Dashboard</h1>
    
    <div class="courtly-summary">
        <div class="courtly-stat">
            <h2>Courts</h2>
            <h3><?= esc_html($total_courts) ?></h3>
            <a href="?page=courtly_courts" class="button">Manage Courts</a>
        </div>
        <div class="courtly-stat">
            <h2>User Types</h2>
            <h3><?= esc_html($total_user_types) ?></h3>
            <a href="?page=courtly_user_types" class="button">Manage User Types</a>
        </div>
        <div class="courtly-stat">
            <h2>Users</h2>            
            <h3><?= esc_html($total_users) ?></h3>
            <a href="?page=courtly_users" class="button">Manage Users</a>
        </div>
        <div class="courtly-stat">
            <h2>Users by type</h2>            
            <ul>
                <?php foreach ($user_types as $type): ?>
                    <li><?= esc_html($type->display_name) ?>: <?= esc_html($type->user_count) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <hr>

    <h2>Reservation Availability (per court)</h2>
    <div id="courtly-calendar"></div>
</div>

<style>
    .courtly-summary {
        display: flex;
        gap: 20px;
    }
    .courtly-stat {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    .courtly-stat h2 {
        margin: 0;
        font-size: 2rem;
        color: #0073aa;
    }
</style>
