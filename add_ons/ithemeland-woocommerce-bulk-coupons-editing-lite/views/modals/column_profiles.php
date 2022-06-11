<div class="wccbef-modal" id="wccbef-modal-column-profiles">
    <div class="wccbef-modal-container">
        <div class="wccbef-modal-box wccbef-modal-box-lg">
            <div class="wccbef-modal-content">
                <div class="wccbef-modal-title">
                    <h2><?php esc_html_e('Column Profiles', WBEBL_NAME); ?></h2>
                    <button type="button" class="wccbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="wccbef_load_column_profile">
                    <div class="wccbef-modal-body">
                        <div class="wccbef-wrap">
                            <div class="wccbef-alert wccbef-alert-default">
                                <span><?php esc_html_e('You can load saved column profile presets through Column Manager. You can change the columns and save your changes too.', WBEBL_NAME); ?></span>
                            </div>
                            <div class="wccbef-column-profiles-choose">
                                <label for="wccbef-column-profiles-choose"><?php esc_html_e('Choose Preset', WBEBL_NAME); ?></label>
                                <select id="wccbef-column-profiles-choose" name="preset_key">
                                    <?php if (!empty($column_manager_presets)) : ?>
                                        <?php foreach ($column_manager_presets as $column_manager_preset) : ?>
                                            <?php if ($i == 0) {
                                                $first_key = $column_manager_preset['key'];
                                            } ?>
                                            <option value="<?php echo esc_attr($column_manager_preset['key']); ?>" <?php echo (!empty($active_columns_key) && $active_columns_key == $column_manager_preset['key']) ? 'selected' : ''; ?>><?php echo esc_html($column_manager_preset['name']); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <label class="wccbef-column-profile-select-all">
                                    <input type="checkbox" id="wccbef-column-profile-select-all" data-profile-name="<?php echo (!empty($active_columns_key)) ? esc_attr($active_columns_key) : ''; ?>">
                                    <span><?php esc_html_e('Select All', WBEBL_NAME); ?></span>
                                </label>
                            </div>
                            <div class="wccbef-column-profiles-fields">
                                <?php if (!empty($column_manager_presets)) : ?>
                                    <?php foreach ($column_manager_presets as $column_manager_preset) : ?>
                                        <?php if (!empty($grouped_fields)) : ?>
                                            <div class="wccbef-column-profile-fields" <?php echo (!empty($active_columns_key) && $active_columns_key != $column_manager_preset['key']) ? 'style="display:none"' : ''; ?> data-content="<?php echo esc_attr($column_manager_preset['key']); ?>">
                                                <?php foreach ($grouped_fields as $group_name => $column_fields) : ?>
                                                    <?php if (!empty($column_fields)) : ?>
                                                        <div class="wccbef-column-profile-fields-group">
                                                            <h3><?php echo esc_html($group_name); ?></h3>
                                                            <ul>
                                                                <?php foreach ($column_fields as $name => $column_field) : ?>
                                                                    <?php
                                                                    if ($column_manager_preset['key'] == $active_columns_key) {
                                                                        $selected = (!empty($active_columns) && in_array($name, array_keys($active_columns))) ? 'checked="checked"' : '';
                                                                    } else {
                                                                        $selected = (!empty($column_manager_preset['checked']) && in_array($name, $column_manager_preset['checked'])) ? 'checked="checked"' : '';
                                                                    }
                                                                    ?>
                                                                    <li>
                                                                        <label>
                                                                            <input type="checkbox" name="columns_<?php echo esc_attr($column_manager_preset['key']); ?>[]" value="<?php echo esc_attr($name); ?>" <?php echo esc_attr($selected); ?>>
                                                                            <?php echo esc_html($column_field['label']); ?>
                                                                        </label>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="wccbef-modal-footer">
                        <button type="submit" class="wccbef-button wccbef-button-blue wccbef-float-left" id="wccbef-column-profiles-apply" data-preset-key="<?php echo (!empty($first_key)) ? $first_key : ''; ?>">
                            <?php esc_html_e('Apply To Table', WBEBL_NAME); ?>
                        </button>
                        <div class="wccbef-column-profile-search wccbef-float-right">
                            <label for="wccbef-column-profile-search"><?php esc_html_e('Search', WBEBL_NAME); ?> </label>
                            <input type="text" id="wccbef-column-profile-search" placeholder="<?php esc_html_e('Search Column ...', WBEBL_NAME); ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>