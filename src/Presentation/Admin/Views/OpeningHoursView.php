<?php
$openingHours = $data['opening_hours'] ?? [];

$days = [
    'sunday' => __('Sunday', 'courtly'),
    'monday' => __('Monday', 'courtly'),
    'tuesday' => __('Tuesday', 'courtly'),
    'wednesday' => __('Wednesday', 'courtly'),
    'thursday' => __('Thursday', 'courtly'),
    'friday' => __('Friday', 'courtly'),
    'saturday' => __('Saturday', 'courtly'),
];
?>

<div class="wrap">
    <h1 class="mb-4"><?php esc_html_e('Opening Hours', 'courtly'); ?></h1>

    <form method="post">
        <?php wp_nonce_field('save_opening_hours', 'opening_hours_nonce'); ?>

        <div class="list-group">
            <?php foreach ($days as $key => $label) {
                $dayData = array_filter($openingHours, fn ($d) => strtolower($d['day_of_week']) === $key);
                $dayData = array_values($dayData)[0] ?? ['time_ranges' => [], 'closed' => false];
                $ranges = $dayData['time_ranges'] ?? [];
                $closed = $dayData['closed'] ?? false;
                ?>
            <div class="list-group-item mb-3 rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="fw-bold fs-5 mb-0"><?php echo esc_html($label); ?></label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                            id="closed_<?php echo $key; ?>"
                            name="opening_hours[<?php echo $key; ?>][closed]"
                            value="1"
                            <?php checked($closed, true); ?>>
                        <label class="form-check-label" for="closed_<?php echo $key; ?>"><?php esc_html_e('Closed', 'courtly'); ?></label>
                    </div>
                </div>

                <div class="time-ranges" data-name="opening_hours[<?php echo $key; ?>][time_ranges][]">
                    <?php foreach ($ranges as $range) { ?>
                        <div class="input-group mb-2 time-range">
                            <input type="time" class="form-control" name="opening_hours[<?php echo $key; ?>][time_ranges][][start]" value="<?php echo esc_attr($range['start']); ?>">
                            <span class="input-group-text">→</span>
                            <input type="time" class="form-control" name="opening_hours[<?php echo $key; ?>][time_ranges][][end]" value="<?php echo esc_attr($range['end']); ?>">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-time-range">&times;</button>
                        </div>
                    <?php } ?>

                    <button type="button" class="btn btn-outline-primary btn-sm add-time-range mt-2">
                        + <?php esc_html_e('Add Time Range', 'courtly'); ?>
                    </button>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <?php esc_html_e('Save Changes', 'courtly'); ?>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-time-range').forEach(button => {
        button.addEventListener('click', function () {
            const container = this.parentElement;
            const name = container.getAttribute('data-name');

            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2', 'time-range');
            div.innerHTML = `
                <input type="time" class="form-control" name="${name}[start]">
                <span class="input-group-text">→</span>
                <input type="time" class="form-control" name="${name}[end]">
                <button type="button" class="btn btn-outline-danger btn-sm remove-time-range">&times;</button>
            `;
            container.insertBefore(div, this);
        });
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-time-range')) {
            e.target.closest('.time-range').remove();
        }
    });
});
</script>
