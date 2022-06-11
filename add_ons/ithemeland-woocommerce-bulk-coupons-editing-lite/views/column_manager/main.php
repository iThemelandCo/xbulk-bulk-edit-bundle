<div class="wccbef-wrap">
    <div class="wccbef-tab-middle-content">
        <div class="wccbef-alert wccbef-alert-default">
            <span><?php esc_html_e('Mange columns of table. You can Create your customize presets and use them in column profile section.', WBEBL_NAME); ?></span>
        </div>
        <div class="wccbef-alert wccbef-alert-danger">
            <span class="wccbef-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
            <a href="<?php echo esc_url(WCCBEF_UPGRADE_URL); ?>"><?php echo esc_html(WCCBEF_UPGRADE_TEXT); ?></a>
        </div>
        <?php if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'column-manager') : ?>
            <?php include WCCBEF_VIEWS_DIR . "alerts/flush_message.php"; ?>
        <?php endif; ?>
        <div class="wccbef-column-manager-items">
            <h3><?php esc_html_e('Column Profiles', WBEBL_NAME); ?></h3>
            <div class="wccbef-table-border-radius">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php esc_html_e('Profile Name', WBEBL_NAME); ?></th>
                            <th><?php esc_html_e('Date Modified', WBEBL_NAME); ?></th>
                            <th><?php esc_html_e('Actions', WBEBL_NAME); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($column_manager_presets)) : ?>
                            <?php $i = 1 ?>
                            <?php foreach ($column_manager_presets as $key => $column_manager_preset) : ?>
                                <tr>
                                    <td><?php echo esc_html($i); ?></td>
                                    <td>
                                        <span class="wccbef-history-name"><?php echo (isset($column_manager_preset['name'])) ? esc_html($column_manager_preset['name']) : ''; ?></span>
                                    </td>
                                    <td><?php echo (isset($column_manager_preset['date_modified'])) ? esc_html(date('d M Y', strtotime($column_manager_preset['date_modified']))) : ''; ?></td>
                                    <td>
                                        <?php if (!in_array($key, \wccbef\classes\repositories\Column::get_default_columns_name())) : ?>
                                            <button type="button" class="wccbef-button wccbef-button-blue wccbef-column-manager-edit-field-btn" value="" disabled="disabled">
                                                <i class="lni lni-pencil"></i>
                                                <?php esc_html_e('Edit', WBEBL_NAME); ?>
                                            </button>
                                            <button type="button" name="" class="wccbef-button wccbef-button-red wccbef-column-manager-delete-preset" value="" disabled="disabled">
                                                <i class="lni lni-trash"></i>
                                                <?php esc_html_e('Delete', WBEBL_NAME); ?>
                                            </button>
                                        <?php else : ?>
                                            <i class="lni lni-lock"></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="wccbef-column-manager-new-profile">
            <h3 class="wccbef-column-manager-section-title"><?php esc_html_e('Create New Profile', WBEBL_NAME); ?></h3>
            <div class="wccbef-column-manager-new-profile-left">
                <input type="text" title="<?php esc_html_e('Search Field', WBEBL_NAME); ?>" data-action="new" placeholder="<?php esc_html_e('Search Field ...', WBEBL_NAME); ?>" class="wccbef-column-manager-search-field">
                <div class="wccbef-column-manager-available-fields" data-action="new">
                    <label class="wccbef-column-manager-check-all-fields-btn" data-action="new">
                        <input type="checkbox" class="wccbef-column-manager-check-all-fields">
                        <span><?php esc_html_e('Select All', WBEBL_NAME); ?></span>
                    </label>
                    <ul>
                        <?php if (!empty($column_items)) : ?>
                            <?php foreach ($column_items as $column_key => $column_field) : ?>
                                <li data-name="<?php echo esc_attr($column_key); ?>" data-added="false">
                                    <label>
                                        <input type="checkbox" data-type="field" data-name="<?php echo esc_attr($column_key); ?>" value="<?php echo esc_attr($column_field['label']); ?>">
                                        <?php echo esc_html($column_field['label']); ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="wccbef-column-manager-new-profile-middle">
                <div class="wccbef-column-manager-middle-buttons">
                    <div>
                        <button type="button" class="wccbef-button wccbef-button-lg wccbef-button-square-lg wccbef-button-blue wccbef-column-manager-add-field" disabled="disabled">
                            <i class="lni lni-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="wccbef-column-manager-new-profile-right">
                <div class="wccbef-column-manager-right-top">
                    <input type="text" title="Profile Name" name="preset_name" placeholder="Profile name ..." disabled="disabled">
                    <button type="submit" name="save_preset" class="wccbef-button wccbef-button-lg wccbef-button-blue" disabled="disabled">
                        <img src="<?php echo WCCBEF_IMAGES_URL . 'save.svg'; ?>" alt="">
                        <?php esc_html_e('Save Preset', WBEBL_NAME); ?>
                    </button>
                </div>
                <div class="wccbef-column-manager-added-fields-wrapper">
                    <p class="wccbef-column-manager-empty-text"><?php esc_html_e('Please add your columns here', WBEBL_NAME); ?></p>
                    <div class="wccbef-column-manager-added-fields" data-action="new">
                        <div class="items"></div>
                        <img src="<?php echo WCCBEF_IMAGES_URL . 'loading.gif'; ?>" alt="" class="wccbef-box-loading wccbef-hide">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>