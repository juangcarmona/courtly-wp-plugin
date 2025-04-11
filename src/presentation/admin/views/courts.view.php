<div class="wrap">
    <h1>Courts</h1>
    <form method="POST">
        <?php wp_nonce_field('courtly_add_court'); ?>
        <h2>Add New Court</h2>
        <table class="form-table">
            <tr><th><label for="number">Court Number</label></th><td><input type="number" name="number" required /></td></tr>
            <tr><th><label for="name">Name</label></th><td><input type="text" name="name" required /></td></tr>
            <tr><th><label for="description">Description</label></th><td><textarea name="description" rows="3"></textarea></td></tr>
        </table>
        <p><input type="submit" class="btn btn-sm btn-primary" value="Add Court"></p>
    </form>

    <hr>

    <h2>Existing Courts</h2>
    <table class="widefat fixed">
        <thead><tr><th>ID</th><th>Number</th><th>Name</th><th>Description</th></tr></thead>
        <tbody>
        <?php foreach ($data['courts'] as $court): ?>
            <tr>
                <td><?= esc_html($court->id) ?></td>
                <td><?= esc_html($court->number) ?></td>
                <td><?= esc_html($court->name) ?></td>
                <td><?= esc_html($court->description) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
