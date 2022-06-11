<table id="wpbel-items-list">
    <thead>
        <tr>
            <?php if (isset($show_id_column) && $show_id_column === true) : ?>
                <?php
                if ('id' == $sort_by) {
                    if ($sort_type == 'asc') {
                        $sortable_icon = "<i class='dashicons dashicons-arrow-up'></i>";
                    } else {
                        $sortable_icon = "<i class='dashicons dashicons-arrow-down'></i>";
                    }
                } else {
                    $img =  WPBEL_IMAGES_URL . "/sortable.png";
                    $sortable_icon = "<img src='" . esc_url($img) . "' alt=''>";
                }
                ?>
                <th class="wpbel-td70 <?php echo ($sticky_first_columns == 'yes') ? 'wpbel-td-sticky wpbel-td-sticky-id' : ''; ?>">
                    <input type="checkbox" class="wpbel-check-item-main" title="<?php esc_html_e('Select All', WBEBL_NAME); ?>">
                    <label data-column-name="id" class="wpbel-sortable-column"><?php esc_html_e('ID', WBEBL_NAME); ?><span class="wpbel-sortable-column-icon"><?php echo sprintf('%s', $sortable_icon); ?></span></span>
                </th>
            <?php endif; ?>
            <?php if (!empty($next_static_columns)) : ?>
                <?php foreach ($next_static_columns as $static_column) : ?>
                    <?php
                    if ($static_column['field'] == $sort_by) {
                        if ($sort_type == 'asc') {
                            $sortable_icon = "<i class='dashicons dashicons-arrow-up'></i>";
                        } else {
                            $sortable_icon = "<i class='dashicons dashicons-arrow-down'></i>";
                        }
                    } else {
                        $img =  WPBEL_IMAGES_URL . "/sortable.png";
                        $sortable_icon = "<img src='" . esc_url($img) . "' alt=''>";
                    }
                    ?>
                    <th data-column-name="<?php echo esc_attr($static_column['field']) ?>" class="wpbel-sortable-column wpbel-td120 <?php echo ($sticky_first_columns == 'yes') ? 'wpbel-td-sticky wpbel-td-sticky-title' : ''; ?>"><?php echo esc_html($static_column['title']); ?><span class="wpbel-sortable-column-icon"><?php echo sprintf('%s', $sortable_icon); ?></span></th>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($columns)) : ?>
                <?php foreach ($columns as $column_name => $column) : ?>
                    <?php
                    $title = (!empty($columns_title) && isset($columns_title[$column_name])) ? $columns_title[$column_name] : '';
                    $sortable_icon = '';
                    if (isset($column['sortable']) && $column['sortable'] === true) {
                        if ($column_name == $sort_by) {
                            if ($sort_type == 'asc') {
                                $sortable_icon = "<i class='dashicons dashicons-arrow-up'></i>";
                            } else {
                                $sortable_icon = "<i class='dashicons dashicons-arrow-down'></i>";
                            }
                        } else {
                            $img =  WPBEL_IMAGES_URL . "/sortable.png";
                            $sortable_icon = "<img src='" . esc_url($img) . "' alt=''>";
                        }
                    }
                    ?>
                    <th data-column-name="<?php echo esc_attr($column_name); ?>" <?php echo (!empty($column['sortable'])) ? 'class="wpbel-sortable-column"' : ''; ?>><?php echo (strlen($column['title']) > 12) ? esc_html(mb_substr($column['title'], 0, 12)) . '.' : esc_html($column['title']); ?><?php echo (!empty($title)) ? "<span class='wpbel-column-title dashicons dashicons-info' title='" . esc_attr($title) . "'></span>" : "" ?> <span class="wpbel-sortable-column-icon"><?php echo sprintf('%s', $sortable_icon); ?></span></th>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($items_loading)) : ?>
            <tr>
                <td colspan="8" class="wpbel-text-alert"><?php esc_html_e('Loading ...', WBEBL_NAME); ?></td>
            </tr>
        <?php elseif (!empty($items) && count($items) > 0) : ?>
            <?php if (!empty($item_provider && is_object($item_provider))) : ?>
                <?php $item_provider->get_items($items, $variations, $columns); ?>
            <?php endif; ?>
        <?php else : ?>
            <tr>
                <td colspan="8" class="wpbel-text-alert"><?php esc_html_e('No Data Available!', WBEBL_NAME); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <?php if (isset($show_id_column) && $show_id_column === true) : ?>
                <th <?php echo ($sticky_first_columns == 'yes') ? 'class="wpbel-td-sticky wpbel-td-sticky-id"' : ''; ?>>ID</th>
            <?php endif; ?>
            <?php if (!empty($next_static_columns)) : ?>
                <?php foreach ($next_static_columns as $static_column) : ?>
                    <th <?php echo ($sticky_first_columns == 'yes') ? 'class="wpbel-td-sticky wpbel-td-sticky-title"' : ''; ?>><?php echo esc_html($static_column['title']); ?></th>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($columns)) : ?>
                <?php foreach ($columns as $column) : ?>
                    <th><?php echo (strlen($column['title']) > 12) ? esc_html(mb_substr($column['title'], 0, 12)) . '.' : esc_html($column['title']); ?></th>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>
    </tfoot>
</table>