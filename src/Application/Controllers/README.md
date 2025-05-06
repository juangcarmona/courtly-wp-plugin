# Application Controllers

This folder contains application-level controllers.

These controllers orchestrate business logic in response to user actions. They can be called from:

- WordPress admin pages (e.g. `Pages/CourtsPage.php`)
- Shortcodes or public-facing pages
- REST endpoints or AJAX controllers (as delegates)

✅ Responsibilities:
- Coordinate domain services and repositories
- Validate and transform input
- Return structured data for views or API responses

❌ Do NOT render HTML or output raw JSON.
❌ Do NOT directly interact with WordPress hooks like `add_action('wp_ajax_...')`
