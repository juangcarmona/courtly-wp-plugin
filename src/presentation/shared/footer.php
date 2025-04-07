<?php
// courtly/presentation/shared/footer.php

require_once plugin_dir_path(__FILE__) . '/../Constants.php';

function courtly_render_footer() {
    echo '<footer class="courtly-footer text-muted text-center mt-4" style="font-size: 0.8em;" >';
    echo COURTLY_FOOTER_HTML;
    echo '</footer>';
}
