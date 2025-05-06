# Admin Pages

This folder contains PHP files connected to WordPress admin menus via `add_menu_page` and `add_submenu_page`.

Each file acts as a **page entry point** that:
1. Loads the appropriate `application/controller`
2. Handles input (if needed)
3. Includes the corresponding view

✅ Responsibilities:
- Bootstrap each admin page
- Call the appropriate controller method
- Include the correct `view` file

❌ Avoid complex logic here. Delegate everything to controllers.
