# Admin AJAX Controllers

This folder contains AJAX controllers for the admin interface.

These are registered via `add_action('wp_ajax_...')` and handle requests triggered from JavaScript (typically FullCalendar or custom AJAX logic). They receive parameters, call the necessary services, and return **JSON responses**.

✅ Responsibilities:
- Handle `wp_ajax_*` hooks
- Receive and sanitize input
- Return JSON via `wp_send_json(...)`

❌ Do NOT render HTML or handle direct views.
❌ Do NOT contain business logic — delegate to services or application controllers.
