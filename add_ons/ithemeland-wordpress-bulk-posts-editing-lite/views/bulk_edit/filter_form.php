<div id="wpbel-filter-form" <?php echo (isset($settings['sticky_search_form']) && $settings['sticky_search_form'] == 'no') ? 'style="position:static"' : '' ?>>
    <div id="wpbel-filter-form-content" class="wpbel-hide" data-visibility="hidden">
        <input type="hidden" id="filter-form-changed" value="">
        <div class="wpbel-wrap">
            <ul class="wpbel-tabs-list" data-content-id="wpbel-bulk-edit-filter-tabs-contents">
                <li><a class="selected" data-content="bulk-edit-filter-general" href="#"><?php esc_html_e('General', WBEBL_NAME); ?></a></li>
                <?php if ($GLOBALS['wpbel_common']['active_post_type'] != 'page') : ?>
                    <li>
                        <a data-content="bulk-edit-filter-categories-tags-taxonomies" href="#">
                            <?php esc_html_e('Categories/Tags/Taxonomies', WBEBL_NAME); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li><a data-content="bulk-edit-filter-date-type" href="#"><?php esc_html_e('Date & Type', WBEBL_NAME); ?></a></li>
                <li><a data-content="bulk-edit-filter-custom-fields" href="#"><?php esc_html_e('Custom Fields', WBEBL_NAME); ?></a></li>
            </ul>
            <div class="wpbel-tabs-contents" id="wpbel-bulk-edit-filter-tabs-contents">
                <div class="selected wpbel-tab-content-item" data-content="bulk-edit-filter-general">
                    <div class="wpbel-form-group" data-name="post_ids">
                        <label for="wpbel-filter-form-post-ids"><?php esc_html_e('Post ID(s)', WBEBL_NAME); ?></label>
                        <select id="wpbel-filter-form-post-ids-operator" title="Select Operator" data-field="operator">
                            <option value="exact"><?php esc_html_e('Exact', WBEBL_NAME); ?></option>
                        </select>
                        <input type="text" id="wpbel-filter-form-post-ids" data-field="value" placeholder="<?php esc_html_e('for example: 1,2,3 or 1-10 or 1,2,3|10-20', WBEBL_NAME); ?>">
                        <label class="wpbel-ml10">
                            <input type="checkbox" id="wpbel-filter-form-post-ids-parent-only" value="yes">
                            <?php esc_html_e('Only Parent Posts', WBEBL_NAME); ?>
                        </label>
                    </div>
                    <div class="wpbel-form-group" data-name="post_title">
                        <label for="wpbel-filter-form-post-title"><?php esc_html_e('Post Title', WBEBL_NAME); ?></label>
                        <select id="wpbel-filter-form-post-title-operator" data-field="operator" title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>">
                            <option value="like"><?php esc_html_e('Like', WBEBL_NAME); ?></option>
                            <option value="exact"><?php esc_html_e('Exact', WBEBL_NAME); ?></option>
                            <option value="not"><?php esc_html_e('Not', WBEBL_NAME); ?></option>
                            <option value="begin"><?php esc_html_e('Begin', WBEBL_NAME); ?></option>
                            <option value="end"><?php esc_html_e('End', WBEBL_NAME); ?></option>
                        </select>
                        <input type="text" id="wpbel-filter-form-post-title" data-field="value" placeholder="<?php esc_html_e('Post Title ...', WBEBL_NAME); ?>">
                    </div>
                    <div class="wpbel-form-group" data-name="post_content">
                        <label for="wpbel-filter-form-post-content"><?php esc_html_e('Post Content', WBEBL_NAME); ?></label>
                        <select id="wpbel-filter-form-post-content-operator" data-field="operator" title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>">
                            <option value="like"><?php esc_html_e('Like', WBEBL_NAME); ?></option>
                            <option value="exact"><?php esc_html_e('Exact', WBEBL_NAME); ?></option>
                            <option value="not"><?php esc_html_e('Not', WBEBL_NAME); ?></option>
                            <option value="begin"><?php esc_html_e('Begin', WBEBL_NAME); ?></option>
                            <option value="end"><?php esc_html_e('End', WBEBL_NAME); ?></option>
                        </select>
                        <input type="text" id="wpbel-filter-form-post-content" data-field="value" placeholder="<?php esc_html_e('Post Content ...', WBEBL_NAME); ?>">
                    </div>
                    <div class="wpbel-form-group" data-name="post_excerpt">
                        <label><?php esc_html_e('Post Excerpt', WBEBL_NAME); ?></label>
                        <select title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" disabled="disabled">
                            <option value="like"><?php esc_html_e('Like', WBEBL_NAME); ?></option>
                            <option value="exact"><?php esc_html_e('Exact', WBEBL_NAME); ?></option>
                            <option value="not"><?php esc_html_e('Not', WBEBL_NAME); ?></option>
                            <option value="begin"><?php esc_html_e('Begin', WBEBL_NAME); ?></option>
                            <option value="end"><?php esc_html_e('End', WBEBL_NAME); ?></option>
                        </select>
                        <input type="text" placeholder="<?php esc_html_e('Post Excerpt ...', WBEBL_NAME); ?>" disabled="disabled">
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="post_slug">
                        <label><?php esc_html_e('Post Slug', WBEBL_NAME); ?></label>
                        <select title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" disabled="disabled">
                            <option value="like"><?php esc_html_e('Like', WBEBL_NAME); ?></option>
                        </select>
                        <input type="text" placeholder="<?php esc_html_e('Post Slug ...', WBEBL_NAME); ?>" disabled="disabled">
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="post_url">
                        <label><?php esc_html_e('Post URL', WBEBL_NAME); ?></label>
                        <select title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" disabled="disabled">
                            <option value="like"><?php esc_html_e('Like', WBEBL_NAME); ?></option>
                        </select>
                        <input type="text" placeholder="<?php esc_html_e('Post URL ...', WBEBL_NAME); ?>" disabled="disabled">
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="author">
                        <label><?php esc_html_e('By Author', WBEBL_NAME); ?></label>
                        <select class="wpbel-input-md" disabled="disabled">
                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        </select>
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                </div>
                <div class="wpbel-tab-content-item" data-content="bulk-edit-filter-categories-tags-taxonomies">
                    <?php if (!empty($taxonomies)) : ?>
                        <?php foreach ($taxonomies as $name => $taxonomy) : ?>
                            <div class="wpbel-form-group" data-type="taxonomy" data-taxonomy="<?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? esc_attr($name) : ''; ?>">
                                <label for="wpbel-filter_form-post-attr-<?php echo esc_attr($name); ?>"><?php echo esc_attr($taxonomy['label']); ?></label>
                                <select <?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'id="wpbel-filter_form-post-attr-operator-' . esc_attr($name) . '"' : ''; ?> title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" data-field="operator" <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'disabled="disabled"' : ''; ?>>
                                    <option value="or"><?php esc_html_e('OR', WBEBL_NAME); ?></option>
                                    <option value="and"><?php esc_html_e('AND', WBEBL_NAME); ?></option>
                                    <option value="not_in"><?php esc_html_e('NOT IN', WBEBL_NAME); ?></option>
                                </select>
                                <select class="wpbel-select2" data-field="value" <?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'id="wpbel-filter_form-post-attr-' . esc_attr($name) . '"' : ''; ?> multiple <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'disabled="disabled"' : ''; ?>>
                                    <?php if (!empty($taxonomy['terms'])) : ?>
                                        <?php foreach ($taxonomy['terms'] as $value_item) : ?>
                                            <option value="<?php echo esc_attr($value_item->slug); ?>"><?php echo esc_html($value_item->name); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? '<span class="wpbel-short-description">Upgrade to pro version</span>' : ''; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="wpbel-alert wpbel-alert-warning">
                            <span><?php esc_html_e('There is not any added Custom Taxonomies', WBEBL_NAME); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="wpbel-tab-content-item" data-content="bulk-edit-filter-date-type">
                    <div class="wpbel-form-group" data-name="post_status">
                        <label><?php esc_html_e('Post Status', WBEBL_NAME); ?></label>
                        <select class="wpbel-input-md" disabled="disabled">
                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        </select>
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="comment_status">
                        <label for="wpbel-filter-form-comment-status"><?php esc_html_e('Comment Status', WBEBL_NAME); ?></label>
                        <select class="wpbel-input-md" disabled="disabled">
                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        </select>
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="ping_status">
                        <label for="wpbel-filter-form-ping-status"><?php esc_html_e('Allow Pingback', WBEBL_NAME); ?></label>
                        <select class="wpbel-input-md" disabled="disabled">
                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        </select>
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="sticky">
                        <label for="wpbel-filter-form-post-sticky"><?php esc_html_e('Sticky', WBEBL_NAME); ?></label>
                        <select class="wpbel-input-md" disabled="disabled">
                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        </select>
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="date_published">
                        <label><?php esc_html_e('Date Published', WBEBL_NAME); ?></label>
                        <input type="text" class="wpbel-input-ft wpbel-datepicker wpbel-date-from" placeholder="<?php esc_html_e('Date Published From ...', WBEBL_NAME); ?>" disabled="disabled">
                        <input type="text" class="wpbel-input-ft wpbel-datepicker" placeholder="<?php esc_html_e('Date Published To ...', WBEBL_NAME); ?>" disabled="disabled">
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="date_published_gmt">
                        <label><?php esc_html_e('Date Published GMT', WBEBL_NAME); ?></label>
                        <input type="text" class="wpbel-input-ft wpbel-datepicker wpbel-date-from" placeholder="<?php esc_html_e('Date Published GMT From ...', WBEBL_NAME); ?>" disabled="disabled">
                        <input type="text" class="wpbel-input-ft wpbel-datepicker" placeholder="<?php esc_html_e('Date Published GMT To ...', WBEBL_NAME); ?>" disabled="disabled">
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="date_modified">
                        <label><?php esc_html_e('Date Modified', WBEBL_NAME); ?></label>
                        <input type="text" class="wpbel-input-ft wpbel-datepicker wpbel-date-from" placeholder="<?php esc_html_e('Date Modified From ...', WBEBL_NAME); ?>" disabled="disabled">
                        <input type="text" class="wpbel-input-ft wpbel-datepicker" placeholder="<?php esc_html_e('Date Modified To ...', WBEBL_NAME); ?>" disabled="disabled">
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="date_modified_gmt">
                        <label><?php esc_html_e('Date Modified GMT', WBEBL_NAME); ?></label>
                        <input type="text" class="wpbel-input-ft wpbel-datepicker wpbel-date-from" placeholder="<?php esc_html_e('Date Modified From ...', WBEBL_NAME); ?>" disabled="disabled">
                        <input type="text" class="wpbel-input-ft wpbel-datepicker" placeholder="<?php esc_html_e('Date Modified To ...', WBEBL_NAME); ?>" disabled="disabled">
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                    <div class="wpbel-form-group" data-name="menu_order">
                        <label><?php esc_html_e('Menu Order', WBEBL_NAME); ?></label>
                        <input type="number" class="wpbel-input-ft" placeholder="<?php esc_html_e('Menu Order From ...', WBEBL_NAME); ?>" disabled="disabled">
                        <input type="number" class="wpbel-input-ft" placeholder="<?php esc_html_e('Menu Order To ...', WBEBL_NAME); ?>" disabled="disabled">
                        <span class="wpbel-short-description">Upgrade to pro version</span>
                    </div>
                </div>
                <div class="wpbel-tab-content-item" data-content="bulk-edit-filter-custom-fields">
                    <?php if (!empty($meta_fields)) : ?>
                        <?php foreach ($meta_fields as $custom_field) : ?>
                            <div class="wpbel-form-group">
                                <label><?php echo esc_html($custom_field['title']); ?></label>
                                <?php if (in_array($custom_field['main_type'], wpbel\classes\repositories\Meta_Field::get_fields_name_have_operator()) || ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::STRING_TYPE)) : ?>
                                    <select title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" disabled="disabled">
                                        <option value="like"><?php esc_html_e('Like', WBEBL_NAME); ?></option>
                                    </select>
                                    <input type="text" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." disabled="disabled">
                                <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::NUMBER) : ?>
                                    <input type="number" class="wpbel-input-md" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_html_e('From ...', WBEBL_NAME); ?>" disabled="disabled">
                                    <input type="number" class="wpbel-input-md" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_html_e('To ...', WBEBL_NAME); ?>" disabled="disabled">
                                <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CHECKBOX) : ?>
                                    <select disabled="disabled">
                                        <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                    </select>
                                <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CALENDAR) : ?>
                                    <input type="text" class="wpbel-input-md wpbel-datepicker" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_html_e('From ...', WBEBL_NAME); ?>" disabled="disabled">
                                    <input type="text" class="wpbel-input-md wpbel-datepicker" placeholder="<?php echo esc_attr($custom_field['title']); ?> <?php esc_html_e('To ...', WBEBL_NAME); ?>" disabled="disabled">
                                <?php endif; ?>
                                <span class="wpbel-short-description">Upgrade to pro version</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="wpbel-alert wpbel-alert-warning">
                            <span><?php esc_html_e('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', WBEBL_NAME); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="wpbel-tab-footer">
                <div class="wpbel-tab-footer-left">
                    <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-blue wpbel-filter-form-action" data-search-action="pro_search">
                        <?php esc_html_e('Get posts', WBEBL_NAME); ?>
                    </button>
                    <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-white" id="wpbel-filter-form-reset">
                        <?php esc_html_e('Reset Filters', WBEBL_NAME); ?>
                    </button>
                </div>
                <div class="wpbel-tab-footer-right">
                    <input type="text" placeholder="Filter Name ..." class="wpbel-h50" disabled="disabled">
                    <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-blue" disabled="disabled">
                        <?php esc_html_e('Save Profile', WBEBL_NAME); ?>
                    </button>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="wpbel-filter-form-button">
        <a class="wpbel-filter-form-toggle">
            <span class="lni lni-funnel wpbel-mr5"></span>
            <?php esc_html_e('Filter Form', WBEBL_NAME); ?>
            <span class="lni lni-chevron-down wpbel-ml5 wpbel-filter-form-icon"></span>
        </a>
    </div>
</div>