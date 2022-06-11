<div class="wccbef-wrap">
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="wccbef_settings">
        <div class="wccbef-tab-middle-content">
            <div class="wccbef-alert wccbef-alert-default">
                <span><?php esc_html_e('You can set bulk editor settings', WBEBL_NAME); ?></span>
            </div>
            <?php if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'settings') : ?>
                <?php include WCCBEF_VIEWS_DIR . "alerts/flush_message.php"; ?>
            <?php endif; ?>
            <div class="wccbef-form-group">
                <label for="wccbef-settings-count-per-page"><?php esc_html_e('Count Per Page', WBEBL_NAME); ?></label>
                <select name="settings[count_per_page]" id="wccbef-quick-per-page" title="The number of coupons per page">
                    <?php foreach (\wccbef\classes\helpers\Setting::get_count_per_page_items() as $count_per_page_item) : ?>
                        <option value="<?php echo intval($count_per_page_item); ?>" <?php if (isset($settings['count_per_page']) && $settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                            <?php echo esc_html($count_per_page_item); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="wccbef-form-group">
                <label for="wccbef-settings-default-sort-by"><?php esc_html_e('Default Sort By', WBEBL_NAME); ?></label>
                <select id="wccbef-settings-default-sort-by" class="wccbef-input-md" name="settings[default_sort_by]">
                    <option value="id" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'id') ? 'selected' : ''; ?>>
                        <?php esc_html_e('ID', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wccbef-form-group">
                <label for="wccbef-settings-default-sort"><?php esc_html_e('Default Sort', WBEBL_NAME); ?></label>
                <select name="settings[default_sort]" id="wccbef-settings-default-sort" class="wccbef-input-md">
                    <option value="ASC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'ASC') ? 'selected' : ''; ?>>
                        <?php esc_html_e('ASC', WBEBL_NAME); ?>
                    </option>
                    <option value="DESC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'DESC') ? 'selected' : ''; ?>>
                        <?php esc_html_e('DESC', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wccbef-form-group">
                <label for="wccbef-settings-show-quick-search"><?php esc_html_e('Show Quick Search', WBEBL_NAME); ?></label>
                <select name="settings[show_quick_search]" id="wccbef-settings-show-quick-search" class="wccbef-input-md">
                    <option value="yes" <?php echo (isset($settings['show_quick_search']) && $settings['show_quick_search'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Yes', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo (isset($settings['show_quick_search']) && $settings['show_quick_search'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('No', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wccbef-form-group">
                <label for="wccbef-settings-sticky-search-form"><?php esc_html_e('Search Form Mode', WBEBL_NAME); ?></label>
                <select name="settings[sticky_search_form]" id="wccbef-settings-sticky-search-form" class="wccbef-input-md">
                    <option value="yes" <?php echo (isset($settings['sticky_search_form']) && $settings['sticky_search_form'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Don\'t Push Down the Content', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo (isset($settings['sticky_search_form']) && $settings['sticky_search_form'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Push Down the Content', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wccbef-form-group">
                <label for="wccbef-settings-sticky-first-columns"><?php esc_html_e("Sticky 'ID & Title' Columns", WBEBL_NAME); ?></label>
                <select name="settings[sticky_first_columns]" id="wccbef-settings-sticky-first-columns" class="wccbef-input-md">
                    <option value="yes" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Yes', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('No', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wccbef-form-group">
                <label for="wccbef-settings-display-full-columns-title"><?php esc_html_e('Display Columns Label', WBEBL_NAME); ?></label>
                <select name="settings[display_full_columns_title]" id="wccbef-settings-display-full-columns-title" class="wccbef-input-md">
                    <option value="yes" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Completely', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('In short', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
        </div>
        <div class="wccbef-tab-footer">
            <div class="wccbef-tab-footer-left">
                <button type="submit" class="wccbef-button wccbef-button-lg wccbef-button-blue">
                    <?php $img = WCCBEF_IMAGES_URL . 'save.svg'; ?>
                    <img src="<?php echo esc_url($img); ?>" alt="">
                    <span><?php esc_html_e('Save Changes', WBEBL_NAME); ?></span>
                </button>
            </div>
            <div class="clear"></div>
        </div>
    </form>
</div>