<div class="wcbef-wrap">
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="wcbef_settings">
        <div class="wcbef-tab-middle-content">
            <div class="wcbef-alert wcbef-alert-default">
                <span><?php esc_html_e('You can set bulk editor settings', WBEBL_NAME); ?></span>
            </div>
            <?php if (\wcbef\classes\helpers\Session::has('flush-message') && !empty($current_tab) && $current_tab == 'settings') : ?>
                <?php include WCBEF_VIEWS_DIR . "alerts/flush_message.php"; ?>
            <?php endif; ?>
            <div class="wcbef-filters-form-group">
                <label for="wcbef-settings-count-per-page"><?php esc_html_e('Count Per Page', WBEBL_NAME); ?></label>
                <select name="count_per_page" id="wcbef-quick-per-page" title="The number of products per page">
                    <?php foreach (\wcbef\classes\helpers\Setting::get_count_per_page_items() as $count_per_page_item) : ?>
                        <option value="<?php echo intval(esc_attr($count_per_page_item)); ?>" <?php if (isset($settings['count_per_page']) && $settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                            <?php echo esc_html($count_per_page_item); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="wcbef-filters-form-group">
                <label for="wcbef-settings-default-sort-by"><?php esc_html_e('Default Sort By', WBEBL_NAME); ?></label>
                <select id="wcbef-settings-default-sort-by" class="wcbef-input-md" name="default_sort_by">
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
            <div class="wcbef-filters-form-group">
                <label for="wcbef-settings-default-sort"><?php esc_html_e('Default Sort', WBEBL_NAME); ?></label>
                <select name="default_sort" id="wcbef-settings-default-sort" class="wcbef-input-md">
                    <option value="asc" <?php echo ($settings['default_sort'] == 'asc') ? 'selected' : ''; ?>>
                        <?php esc_html_e('ASC', WBEBL_NAME); ?>
                    </option>
                    <option value="desc" <?php echo ($settings['default_sort'] == 'desc') ? 'selected' : ''; ?>>
                        <?php esc_html_e('DESC', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wcbef-filters-form-group">
                <label for="wcbef-settings-show-quick-search"><?php esc_html_e('Show Quick Search', WBEBL_NAME); ?></label>
                <select name="show_quick_search" id="wcbef-settings-show-quick-search" class="wcbef-input-md">
                    <option value="yes" <?php echo ($settings['show_quick_search'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Yes', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo ($settings['show_quick_search'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('No', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wcbef-filters-form-group">
                <label for="wcbef-settings-sticky-search-form"><?php esc_html_e('Search Form Mode', WBEBL_NAME); ?></label>
                <select name="sticky_search_form" id="wcbef-settings-sticky-search-form" class="wcbef-input-md">
                    <option value="yes" <?php echo ($settings['sticky_search_form'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Don\'t Push Down the Content', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo ($settings['sticky_search_form'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Push Down the Content', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
            <div class="wcbef-filters-form-group">
                <label for="wcbef-settings-sticky-first-columns"><?php esc_html_e('Sticky ID & Title Columns', WBEBL_NAME); ?></label>
                <select name="sticky_first_columns" id="wcbef-settings-sticky-first-columns" class="wcbef-input-md">
                    <option value="yes" <?php echo ($settings['sticky_first_columns'] == 'yes') ? 'selected' : ''; ?>>
                        <?php esc_html_e('Yes', WBEBL_NAME); ?>
                    </option>
                    <option value="no" <?php echo ($settings['sticky_first_columns'] == 'no') ? 'selected' : ''; ?>>
                        <?php esc_html_e('No', WBEBL_NAME); ?>
                    </option>
                </select>
            </div>
        </div>
        <div class="wcbef-tab-footer">
            <div class="wcbef-tab-footer-left">
                <button type="submit" class="wcbef-button wcbef-button-lg wcbef-button-blue">
                    <?php $img = WCBEF_IMAGES_URL . 'save.svg'; ?>
                    <img src="<?php echo esc_url($img); ?>" alt="">
                    <span><?php esc_html_e('Save Changes', WBEBL_NAME); ?></span>
                </button>
            </div>
            <div class="clear"></div>
        </div>
    </form>
</div>