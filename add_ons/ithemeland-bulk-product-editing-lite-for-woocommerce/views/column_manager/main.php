<div class="wcbef-wrap">
    <div class="wcbef-tab-middle-content">
        <div class="wcbef-alert wcbef-alert-default">
            <span><?php esc_html_e('Mange columns of table. You can Create your customize presets and use them in column profile section.', WBEBL_NAME); ?></span>
        </div>
        <div class="wcbef-alert wcbef-alert-danger">
            <span class="wcbef-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
            <a href="<?php echo esc_url(WCBEF_UPGRADE_URL); ?>"><?php echo esc_html(WCBEF_UPGRADE_TEXT); ?></a>
        </div>
        <?php if (\wcbef\classes\helpers\Session::has('flush-message') && !empty($current_tab) && $current_tab == 'column-manager') : ?>
            <?php include WCBEF_VIEWS_DIR . "alerts/flush_message.php"; ?>
        <?php endif; ?>
        <div class="wcbef-column-manager-items">
            <h3><?php esc_html_e('Column Profiles', WBEBL_NAME); ?></h3>
            <div class="wcbef-table-border-radius">
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
                                    <td><?php echo intval($i); ?></td>
                                    <td>
                                        <span class="wcbef-history-name"><?php echo (isset($column_manager_preset['name'])) ? esc_html($column_manager_preset['name']) : ''; ?></span>
                                    </td>
                                    <td><?php echo (isset($column_manager_preset['date_modified'])) ? esc_html(date('d M Y', strtotime($column_manager_preset['date_modified']))) : ''; ?></td>
                                    <td>
                                        <?php if (!in_array($key, \wcbef\classes\helpers\Columns::get_default_columns_name())) : ?>
                                            <button type="button" class="wcbef-button wcbef-button-blue wcbef-column-manager-edit-field-btn" data-toggle="modal" data-target="#wcbef-modal-column-manager-edit-preset" value="<?php echo esc_attr($key); ?>" data-preset-name="<?php echo (isset($column_manager_preset['name'])) ? esc_attr($column_manager_preset['name']) : ''; ?>">
                                                <i class="lni lni-pencil"></i>
                                                <?php esc_html_e('Edit', WBEBL_NAME); ?>
                                            </button>
                                            <button type="button" name="delete_preset" class="wcbef-button wcbef-button-red wcbef-column-manager-delete-preset" value="<?php echo esc_attr($key); ?>">
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
        <div class="wcbef-column-manager-new-profile">
            <h3 class="wcbef-column-manager-section-title"><?php esc_html_e('Create New Profile', WBEBL_NAME); ?></h3>
            <div class="wcbef-column-manager-new-profile-left">
                <input type="text" title="<?php esc_html_e('Search Field', WBEBL_NAME); ?>" data-action="new" placeholder="<?php esc_html_e('Search Field ...', WBEBL_NAME); ?>" class="wcbef-column-manager-search-field">
                <div class="wcbef-column-manager-available-fields" data-action="new">
                    <label class="wcbef-column-manager-check-all-fields-btn" data-action="new">
                        <input type="checkbox" class="wcbef-column-manager-check-all-fields">
                        <span><?php esc_html_e('Select All', WBEBL_NAME); ?></span>
                    </label>
                    <ul>
                        <?php if (!empty($column_fields)) : ?>
                            <?php foreach ($column_fields as $column_key => $column_field) : ?>
                                <li data-name="<?php echo esc_attr($column_key); ?>" data-added="false">
                                    <label>
                                        <input type="checkbox" data-name="<?php echo esc_attr($column_key); ?>" value="<?php echo esc_attr($column_field['label']); ?>">
                                        <?php echo esc_html($column_field['label']); ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="wcbef-column-manager-new-profile-middle">
                <div class="wcbef-column-manager-middle-buttons">
                    <div>
                        <button type="button" class="wcbef-button wcbef-button-lg wcbef-button-square-lg" disabled="disabled">
                            <i class="lni lni-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="action" value="wcbef_column_manager_new_preset">
            <div class="wcbef-column-manager-new-profile-right">
                <div class="wcbef-column-manager-right-top">
                    <input type="text" placeholder="Profile name ..." disabled>
                    <button type="button" class="wcbef-button wcbef-button-lg wcbef-button-blue" disabled="disabled">
                        <img src="<?php echo WCBEF_IMAGES_URL . 'save.svg'; ?>" alt="">
                        <?php esc_html_e('Save Preset', WBEBL_NAME); ?>
                    </button>
                </div>
                <div class="wcbef-column-manager-added-fields-wrapper">
                    <p class="wcbef-column-manager-empty-text"><?php esc_html_e('Please add your columns here', WBEBL_NAME); ?></p>
                    <div class="wcbef-column-manager-added-fields" data-action="new">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>