<div class="wrap">
    <h1><?php esc_html_e('Courts', 'courtly'); ?></h1>
    <form method="POST">
        <?php wp_nonce_field('courtly_add_court'); ?>
        <h2><?php esc_html_e('Add New Court', 'courtly'); ?></h2>
        <table class="form-table">
            <tr>
                <th><label for="number"><?php esc_html_e('Court Number', 'courtly'); ?></label></th>
                <td><input type="number" name="number" required /></td>
            </tr>
            <tr>
                <th><label for="name"><?php esc_html_e('Name', 'courtly'); ?></label></th>
                <td><input type="text" name="name" required /></td>
            </tr>
            <tr>
                <th><label for="description"><?php esc_html_e('Description', 'courtly'); ?></label></th>
                <td><textarea name="description" rows="3"></textarea></td>
            </tr>
        </table>
        <p>
            <input type="submit" class="btn btn-sm btn-primary" value="<?php esc_attr_e('Add Court', 'courtly'); ?>">
        </p>
    </form>

    <hr>

    <h2><?php esc_html_e('Existing Courts', 'courtly'); ?></h2>
    <table class="widefat fixed">
        <thead>
            <tr>
                <th><?php esc_html_e('ID', 'courtly'); ?></th>
                <th><?php esc_html_e('Number', 'courtly'); ?></th>
                <th><?php esc_html_e('Name', 'courtly'); ?></th>
                <th><?php esc_html_e('Description', 'courtly'); ?></th>
            </tr>
        </thead>
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
