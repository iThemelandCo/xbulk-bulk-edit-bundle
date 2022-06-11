<div id="wccbef-filter-form" <?php echo (isset($settings['sticky_search_form']) && $settings['sticky_search_form'] == 'no') ? 'style="position:static"' : '' ?>>
    <div id="wccbef-filter-form-content" class="wccbef-hide" data-visibility="hidden">
        <input type="hidden" id="filter-form-changed" value="">
        <div class="wccbef-wrap">
            <i class="lni lni-close wccbef-filter-form-toggle" id="wccbef-bulk-edit-filter-form-close-button"></i>
            <ul class="wccbef-tabs-list" data-content-id="wccbef-bulk-edit-filter-tabs-contents">
                <?php if (!empty($filter_form_tabs_title) && is_array($filter_form_tabs_title)) : ?>
                    <?php $filter_tab_title_counter = 1; ?>
                    <?php foreach ($filter_form_tabs_title as $tab_key => $tab_label) : ?>
                        <li><a class="<?php echo ($filter_tab_title_counter == 1) ? 'selected' : ''; ?>" data-content="<?php echo esc_attr($tab_key); ?>" href="#"><?php echo esc_html($tab_label); ?></a></li>
                        <?php $filter_tab_title_counter++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="wccbef-tabs-contents" id="wccbef-bulk-edit-filter-tabs-contents">
                <?php if (!empty($filter_form_tabs_content)) : ?>
                    <?php foreach ($filter_form_tabs_content as $tab_key => $filter_tab) : ?>
                        <?php echo (!empty($filter_tab['wrapper_start'])) ? $filter_tab['wrapper_start'] : ''; ?>
                        <?php
                        if (!empty($filter_tab['fields_top']) && is_array($filter_tab['fields_top'])) {
                            foreach ($filter_tab['fields_top'] as $top_item) {
                                echo sprintf('%s', $top_item);
                            }
                        }
                        ?>
                        <?php if (!empty($filter_tab['fields']) && is_array($filter_tab['fields'])) : ?>
                            <?php foreach ($filter_tab['fields'] as $field_key => $field_items) : ?>
                                <?php if (!empty($field_items) && is_array($field_items)) : ?>
                                    <div class="wccbef-form-group" data-name="<?php echo esc_attr($field_key); ?>">
                                        <?php foreach ($field_items as $field_html) : ?>
                                            <?php echo sprintf('%s', $field_html); ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php echo (!empty($filter_tab['wrapper_end'])) ? $filter_tab['wrapper_end'] : ''; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="wccbef-tab-footer">
                <div class="wccbef-tab-footer-left">
                    <button type="button" id="wccbef-filter-form-get-coupons" class="wccbef-button wccbef-button-lg wccbef-button-blue wccbef-filter-form-action" data-search-action="pro_search">
                        <?php esc_html_e('Get coupons', WBEBL_NAME); ?>
                    </button>
                    <button type="button" class="wccbef-button wccbef-button-lg wccbef-button-white" id="wccbef-filter-form-reset">
                        <?php esc_html_e('Reset Filters', WBEBL_NAME); ?>
                    </button>
                </div>
                <div class="wccbef-tab-footer-right">
                    <input type="text" name="save_filter" id="wccbef-filter-form-save-preset-name" placeholder="Filter Name ..." class="wccbef-h50" title="Filter Name">
                    <button type="button" id="wccbef-filter-form-save-preset" class="wccbef-button wccbef-button-lg wccbef-button-blue">
                        <?php esc_html_e('Save Profile', WBEBL_NAME); ?>
                    </button>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="wccbef-filter-form-button">
        <a class="wccbef-filter-form-toggle">
            <span class="lni lni-funnel wccbef-mr5"></span>
            <?php esc_html_e('Filter Form', WBEBL_NAME); ?>
            <span class="lni lni-chevron-down wccbef-ml5 wccbef-filter-form-icon"></span>
        </a>
    </div>
</div>