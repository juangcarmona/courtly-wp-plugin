<div class="wrap">
    <h1>User Types</h1>
    <form method="POST">
        <?php wp_nonce_field('courtly_add_user_type'); ?>
        <h2>Add New User Type</h2>
        <table class="form-table">
            <tr><th><label for="name">Internal Name</label></th><td><input type="text" name="name" required /></td></tr>
            <tr><th><label for="display_name">Display Name</label></th><td><input type="text" name="display_name" required /></td></tr>
            <tr><th><label for="booking_days_in_advance">Booking Days in Advance</label></th><td><input type="number" name="booking_days_in_advance" min="1" required /></td></tr>
        </table>
        <p><input type="submit" class="btn btn-sm btn-primary" value="Add User Type"></p>
    </form>

    <hr>

    <h2>Existing User Types</h2>
    <table class="widefat fixed">
        <thead><tr><th>ID</th><th>Internal</th><th>Display</th><th>Days in Advance</th></tr></thead>
        <tbody>
        <?php foreach ($data['user_types'] as $type): ?>
            <tr>
                <td><?= esc_html($type->id) ?></td>
                <td><?= esc_html($type->name) ?></td>
                <td><?= esc_html($type->display_name) ?></td>
                <td><?= esc_html($type->booking_days_in_advance) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
