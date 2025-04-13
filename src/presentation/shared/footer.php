<?php
// courtly/presentation/shared/footer.php
require_once plugin_dir_path(__FILE__) . '/../Constants.php';

function courtly_render_footer() {
    $html = sprintf(
        // translators: Footer text with brand and author.
        __('🥎 <strong>%1$s</strong> — Your padel court booking assistant. 🥎</br> Made with ❤️ by <a href="%2$s" target="_blank" rel="noopener">Juan G Carmona</a> 🌍 Madrid, Spain', 'courtly'),
        esc_html(COURTLY_BRAND_NAME),
        esc_url('https://juangcarmona.com')
    );

    echo '<footer class="courtly-footer text-muted text-center mt-4" style="font-size: 0.8em;">';
    echo wp_kses_post($html);
    echo '</footer>';
}

