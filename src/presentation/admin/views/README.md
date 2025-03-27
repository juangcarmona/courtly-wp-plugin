# Admin Views

This folder contains PHP view templates for rendering HTML inside admin pages.

Views should receive data from an application controller, and **only render HTML** using variables passed to them.

✅ Responsibilities:
- Render HTML markup
- Use data passed from controller (e.g., via `$data`)

❌ Do NOT access `$_POST`, `$_GET`, or global state.
❌ Do NOT call services or repositories.
