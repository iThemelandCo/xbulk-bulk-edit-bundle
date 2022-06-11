<div class="wobef-modal" id="wobef-modal-column-profiles">
    <div class="wobef-modal-container">
        <div class="wobef-modal-box wobef-modal-box-lg">
            <div class="wobef-modal-content">
                <div class="wobef-modal-title">
                    <h2><?php esc_html_e('Column Profiles', WBEBL_NAME); ?></h2>
                    <button type="button" class="wobef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="<?php echo (!empty($column_profile_action_form)) ? $column_profile_action_form : ''; ?>">
                    <div class="wobef-modal-body">
                        <div class="wobef-wrap">
                            <div class="wobef-alert wobef-alert-default">
                                <span><?php esc_html_e('You can load saved column profile presets through Column Manager. You can change the columns and save your changes too.', WBEBL_NAME); ?></span>
                            </div>
                            <div class="wobef-column-profiles-choose">
                                <label for="wobef-column-profiles-choose"><?php esc_html_e('Choose Preset', WBEBL_NAME); ?></label>
                                <select id="wobef-column-profiles-choose" name="preset_key">
                                    <?php if (!empty($column_manager_presets)) : ?>
                                        <?php foreach ($column_manager_presets as $column_manager_preset) : ?>
                                            <?php if ($i == 0) {
                                                $first_key = $column_manager_preset['key'];
                                            } ?>
                                            <option value="<?php echo esc_attr($column_manager_preset['key']); ?>" <?php echo (!empty($active_columns_key) && $active_columns_key == $column_manager_preset['key']) ? 'selected' : ''; ?>><?php echo esc_html($column_manager_preset['name']); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <label class="wobef-column-profile-select-all">
                                    <input type="checkbox" id="wobef-column-profile-select-all" data-profile-name="<?php echo (!empty($active_columns_key)) ? esc_attr($active_columns_key) : ''; ?>">
                                    <span><?php esc_html_e('Select All', WBEBL_NAME); ?></span>
                                </label>
                            </div>
                            <div class="wobef-column-profiles-fields">
                                <?php if (!empty($column_manager_presets)) : ?>
                                    <?php foreach ($column_manager_presets as $column_manager_preset) : ?>
                                        <?php if (!empty($grouped_fields)) : ?>
                                            <div class="wobef-column-profile-fields" <?php echo (!empty($active_columns_key) && $active_columns_key != $column_manager_preset['key']) ? 'style="display:none"' : ''; ?> data-content="<?php echo esc_attr($column_manager_preset['key']); ?>">
                                                <?php foreach ($grouped_fields as $group_name => $column_fields) : ?>
                                                    <?php if (!empty($column_fields)) : ?>
                                                        <div class="wobef-column-profile-fields-group">
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
                    <div class="wobef-modal-footer">
                        <button type="submit" class="wobef-button wobef-button-blue wobef-float-left" id="wobef-column-profiles-apply" data-preset-key="<?php echo (!empty($first_key)) ? $first_key : ''; ?>">
                            <?php esc_html_e('Apply To Table', WBEBL_NAME); ?>
                        </button>
                        <div class="wobef-column-profile-search wobef-float-right">
                            <label for="wobef-column-profile-search"><?php esc_html_e('Search', WBEBL_NAME); ?> </label>
                            <input type="text" id="wobef-column-profile-search" placeholder="<?php esc_html_e('Search Column ...', WBEBL_NAME); ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>