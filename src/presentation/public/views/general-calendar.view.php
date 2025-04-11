<div class="courtly-user-booking wide-layout">
    <div id="courtly-calendar" style="margin-bottom: 50px;"></div>

    <?php
    if (!function_exists('courtly_render_footer')) {
        require_once plugin_dir_path(__FILE__) . '/../../shared/footer.php';
    }
    courtly_render_footer();
    ?>
</div>
