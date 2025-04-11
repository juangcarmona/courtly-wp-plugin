<?php
// admin/pages/calendar.php

?>

<div class="wrap">
    <h1 class="mb-4">General Calendar</h1>

    <?php if (!empty($data['errors'])): ?>
      <div class="courtly-alert courtly-error" style="margin-bottom: 15px; color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; padding: 10px; border-radius: 4px;">
        <?php foreach ($data['errors'] as $error): ?>
          <p style="margin: 0;"><?= esc_html($error) ?></p>
        <?php endforeach; ?>
      </div>
    <?php elseif (!empty($data['success'])): ?>
      <div class="courtly-alert courtly-success" style="margin-bottom: 15px; color: #0f5132; background-color: #d1e7dd; border: 1px solid #badbcc; padding: 10px; border-radius: 4px;">
        🎉 Your reservation was successfully created.
      </div>
    <?php endif; ?>

    <div id="courtly-calendar"></div>
</div>

<?php
if (!function_exists('courtly_render_footer')) {
    require_once plugin_dir_path(__FILE__) . '/../../shared/footer.php';
}
courtly_render_footer();
?>
