<div class="wpbel-wrap">
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="wpbel_settings">
        <div class="wpbel-tab-middle-content">
            <div class="wpbel-alert wpbel-alert-default">
                <span><?php esc_html_e('You can set bulk editor settings', WBEBL_NAME); ?></span>
            </div>
            <?php if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'settings') : ?>
                <?php include WPBEL_VIEWS_DIR . "alerts/flush_message.php"; ?>
            <?php endif; ?>
            <div class="wpbel-form-group">
                <label for="wpbel-settings-count-per-page"><?php esc_html_e('Count Per Page', WBEBL_NAME); ?></label>
                <select name="count_per_page" id="wpbel-settings-count-per-page" title="The number of posts per page">
                    <?php foreach (\wpbel\classes\helpers\Setting_Helper::get_count_per_page_items() as $count_per_page_item) : ?>
                        <option value="<?php echo intval($count_per_page_item); ?>" <?php if (isset($settings['count_per_page']) && $settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                            <?php echo esc_html($count_per_page_item); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="wpbel-form-group">
                <label for="wpbel-settings-default-sort-by"><?php esc_html_e('Default Sort By', WBEBL_NAME); ?></label>
                <select id="wpbel-settings-default-sort-by" class="wpbel-input-md" name="default_sort_by">
                    <option value="id" <?php echo ($settings['default_sort_by'] == 'id') ? 'selected' : ''; ?>>
                        <?php esc_html_e('ID', WBEBL_NAME); ?>
                    </option>
                    <option value="title" <?php echo ($settings['default_sort_by'] == 'title') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Title', WBEBL_NAME); ?>
                    </option>
                    <option value="regular_price" <?php echo ($settings['default_sort_by'] == 'regular_price') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Regular price', WBEBL_NAME); ?>
                    </option>
                    <option value="sale_price" <?php echo ($settings['default_sort_by'] == 'sale_price') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Sale price', WBEBL_NAME); ?>
                    </option>
                    <option value="sku" <?php echo ($settings['default_sort_by'] == 'sku') ? 'selected' : ''; ?>>
                        <?php esc_html_e('SKU', WBEBL_NAME); ?>
                    </option>
                    <option value="manage_stock" <?php echo ($settings['default_sort_by'] == 'manage_stock') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Manage Stock', WBEBL_NAME); ?>
                    </option>
                    <option value="stock_quantity" <?php echo ($settings['default_sort_by'] == 'stock_quantity') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Stock Quantity', WBEBL_NAME); ?>
                    </option>
                    <option value="stock_status" <?php echo ($settings['default_sort_by'] == 'stock_status') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Stock Status', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wpbel-form-group">
                <label for="wpbel-settings-default-sort"><?php esc_html_e('Default Sort', WBEBL_NAME); ?></label>
                <select name="default_sort" id="wpbel-settings-default-sort" class="wpbel-input-md">
                    <option value="asc" <?php echo ($settings['default_sort'] == 'asc') ? 'selected' : ''; ?>>
                        <?php esc_html_e('ASC', WBEBL_NAME); ?>
                    </option>
                    <option value="desc" <?php echo ($settings['default_sort'] == 'desc') ? 'selected' : ''; ?>>
                        <?php esc_html_e('DESC', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wpbel-form-group">
                <label for="wpbel-settings-show-quick-search"><?php esc_html_e('Show Quick Search', WBEBL_NAME); ?></label>
                <select name="show_quick_search" id="wpbel-settings-show-quick-search" class="wpbel-input-md">
                    <option value="yes" <?php echo ($settings['show_quick_search'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Yes', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo ($settings['show_quick_search'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('No', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wpbel-form-group">
                <label for="wpbel-settings-sticky-search-form"><?php esc_html_e('Search Form Mode', WBEBL_NAME); ?></label>
                <select name="sticky_search_form" id="wpbel-settings-sticky-search-form" class="wpbel-input-md">
                    <option value="yes" <?php echo ($settings['sticky_search_form'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Don\'t Push Down the Content', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo ($settings['sticky_search_form'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Push Down the Content', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wpbel-form-group">
                <label for="wpbel-settings-sticky-first-columns"><?php esc_html_e('Sticky ID & Title Columns', WBEBL_NAME); ?></label>
                <select name="sticky_first_columns" id="wpbel-settings-sticky-first-columns" class="wpbel-input-md">
                    <option value="yes" <?php echo ($settings['sticky_first_columns'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Yes', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo ($settings['sticky_first_columns'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('No', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
        </div>
        <div class="wpbel-tab-footer">
            <div class="wpbel-tab-footer-left">
                <button type="submit" class="wpbel-button wpbel-button-lg wpbel-button-blue">
                    <?php $img = WPBEL_IMAGES_URL . 'save.svg'; ?>
                    <img src="<?php echo esc_url($img); ?>" alt="">
                    <span><?php esc_html_e('Save Changes', WBEBL_NAME); ?></span>
                </button>
            </div>
            <div class="clear"></div>
        </div>
    </form>
</div>