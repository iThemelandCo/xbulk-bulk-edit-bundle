<div class="wpbel-modal" id="wpbel-modal-bulk-edit">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-lg">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Bulk Edit Form', WBEBL_NAME); ?></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-tabs">
                            <div class="wpbel-tabs-navigation">
                                <nav class="wpbel-tabs-navbar">
                                    <ul class="wpbel-tabs-list" data-content-id="wpbel-bulk-edit-tabs">
                                        <li><a class="selected" data-content="general" href="#"><?php esc_html_e('General', WBEBL_NAME); ?></a></li>
                                        <?php if ($GLOBALS['wpbel_common']['active_post_type'] != 'page') : ?>
                                            <li>
                                                <a data-content="categories-tags-taxonomies" href="#">
                                                    <?php esc_html_e('Categories/Tags/Taxonomies', WBEBL_NAME); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li><a data-content="date-type" href="#"><?php esc_html_e('Date & Type', WBEBL_NAME); ?></a></li>
                                        <li><a data-content="custom-fields" href="#"><?php esc_html_e('Custom Fields', WBEBL_NAME); ?></a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wpbel-tabs-contents wpbel-mt30" id="wpbel-bulk-edit-tabs">
                                <div class="selected wpbel-tab-content-item" data-content="general">
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label for="wpbel-bulk-edit-form-post-title"><?php esc_html_e('Post Title', WBEBL_NAME); ?></label>
                                            <select title="Select Operator" id="wpbel-bulk-edit-form-post-title-operator" data-field="operator">
                                                <?php if (!empty($edit_text_operators)) : ?>
                                                    <?php foreach ($edit_text_operators as $operator_name => $operator_label) : ?>
                                                        <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <option value="text_remove_duplicate"><?php esc_html_e('Remove Duplicate', WBEBL_NAME); ?></option>
                                            </select>
                                            <input type="text" id="wpbel-bulk-edit-form-post-title" data-field="value" placeholder="<?php esc_html_e('Post Title ...', WBEBL_NAME); ?>">
                                            <?php include "variable.php"; ?>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label for="wpbel-bulk-edit-form-post-slug"><?php esc_html_e('Post Slug', WBEBL_NAME); ?></label>
                                            <select disabled="disabled">
                                                <option value=""><?php esc_html_e('Select'); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Post Slug ...', WBEBL_NAME); ?>" disabled="disabled">
                                            <span class="wpbel-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label><?php esc_html_e('Post Password', WBEBL_NAME); ?></label>
                                            <select disabled="disabled">
                                                <option value=""><?php esc_html_e('Select'); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Post Password ...', WBEBL_NAME); ?>" disabled="disabled">
                                            <span class="wpbel-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label><?php esc_html_e('Description', WBEBL_NAME); ?></label>
                                            <select disabled="disabled">
                                                <option value=""><?php esc_html_e('Select'); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Description ...', WBEBL_NAME); ?>" disabled="disabled">
                                            <span class="wpbel-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <div>
                                            <label>
                                                <?php esc_html_e('Short Description', WBEBL_NAME); ?>
                                            </label>
                                            <select disabled="disabled">
                                                <option value=""><?php esc_html_e('Select'); ?></option>
                                            </select>
                                            <textarea placeholder="<?php esc_html_e('Short Description ...', WBEBL_NAME); ?>" disabled="disabled"></textarea>
                                            <span class="wpbel-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Menu Order', WBEBL_NAME); ?></label>
                                        <input type="number" placeholder="<?php esc_html_e('Menu Order ...', WBEBL_NAME); ?>" class="wpbel-input-md" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group wpbel-select-child-md">
                                        <label><?php esc_html_e('Post Parent', WBEBL_NAME); ?></label>
                                        <select class="wpbel-select2 wpbel-ml5" disabled="disabled">
                                            <option value=""><?php esc_html_e('No Parent', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Comment Status', WBEBL_NAME); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Allow Pingback', WBEBL_NAME); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Author', WBEBL_NAME); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Image', WBEBL_NAME); ?></label>
                                        <button type="button" data-type="single" class="wpbel-button wpbel-button-blue wpbel-ml10 wpbel-h43 wpbel-float-left" disabled="disabled">
                                            <?php esc_html_e('Choose Image', WBEBL_NAME); ?>
                                        </button>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                </div>
                                <div class="wpbel-tab-content-item" data-content="categories-tags-taxonomies">
                                    <?php if (!empty($taxonomies)) : ?>
                                        <?php foreach ($taxonomies as $name => $taxonomy) : ?>
                                            <div class="wpbel-bulk-edit-form-group" data-type="taxonomy" data-taxonomy="<?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? esc_attr($name) : ''; ?>">
                                                <label for="wpbel-bulk-edit-form-post-attr-<?php echo esc_attr($name); ?>"><?php echo esc_html($taxonomy['label']); ?></label>
                                                <select <?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'id="wpbel-bulk-edit-form-post-attr-operator-' . esc_attr($name) . '"' : ''; ?> title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" data-field="operator" <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'disabled="disabled"' : ''; ?>>
                                                    <?php if (!empty($edit_taxonomy_operators)) : ?>
                                                        <?php foreach ($edit_taxonomy_operators as $operator_name => $operator_label) : ?>
                                                            <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <select class="wpbel-select2" data-field="value" <?php echo (wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'id="wpbel-bulk-edit-form-post-attr-' . esc_attr($name) . '"' : ''; ?> multiple <?php echo (!wpbel\classes\helpers\Taxonomy_Helper::isAllowed($name)) ? 'disabled="disabled"' : ''; ?>>
                                                    <?php if (!empty($taxonomy['terms'])) : ?>
                                                        <?php foreach ($taxonomy['terms'] as $value_item) : ?>
                                                            <option value="<?php echo esc_attr(($name != 'category') ? $value_item->name : $value_item->term_id); ?>"><?php echo esc_html($value_item->name); ?></option>
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
                                <div class="wpbel-tab-content-item" data-content="date-type">
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Post Type', WBEBL_NAME); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select Type ...', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label for="wpbel-bulk-edit-form-post-post-status"><?php esc_html_e('Post Status', WBEBL_NAME); ?></label>
                                        <select class="wpbel-input-md" title="Select" data-field="value" id="wpbel-bulk-edit-form-post-post-status">
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            <?php if (!empty($post_statuses)) : ?>
                                                <?php foreach ($post_statuses as $post_status_name => $post_status_label) : ?>
                                                    <option value="<?php echo esc_attr($post_status_name); ?>"><?php echo esc_html($post_status_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Sticky', WBEBL_NAME); ?></label>
                                        <select class="wpbel-input-md" disabled="disabled">
                                            <option value=""><?php esc_html_e('Select ...', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Date Published', WBEBL_NAME); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Date Published ...', WBEBL_NAME); ?>" class="wpbel-input-md wpbel-datepicker" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Date Published GMT', WBEBL_NAME); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Date Published GMT ...', WBEBL_NAME); ?>" class="wpbel-input-md wpbel-datepicker" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Date Modified', WBEBL_NAME); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Date Modified ...', WBEBL_NAME); ?>" class="wpbel-input-md wpbel-datepicker" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Date Modified GMT', WBEBL_NAME); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Date Modified GMT ...', WBEBL_NAME); ?>" class="wpbel-input-md wpbel-datepicker" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wpbel-form-group">
                                        <label><?php esc_html_e('Post URL', WBEBL_NAME); ?></label>
                                        <input type="text" placeholder="Post URL ..." class="wpbel-input-md" disabled="disabled">
                                        <span class="wpbel-short-description">Upgrade to pro version</span>
                                    </div>
                                </div>
                                <div class="wpbel-tab-content-item" data-content="custom-fields">
                                    <?php if (!empty($meta_fields)) : ?>
                                        <?php foreach ($meta_fields as $custom_field) : ?>
                                            <div class="wpbel-bulk-edit-form-group" data-type="custom_field" data-taxonomy="<?php echo esc_attr($custom_field['key']); ?>">
                                                <div>
                                                    <label><?php echo esc_html($custom_field['title']); ?></label>
                                                    <?php if (in_array($custom_field['main_type'], wpbel\classes\repositories\Meta_Field::get_fields_name_have_operator()) || ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::STRING_TYPE)) : ?>
                                                        <select disabled="disabled">
                                                            <?php if (!empty($edit_text_operators)) : ?>
                                                                <?php foreach ($edit_text_operators as $operator_name => $operator_label) : ?>
                                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input type="text" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." disabled="disabled">
                                                    <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == wpbel\classes\repositories\Meta_Field::NUMBER) : ?>
                                                        <select disabled="disabled">
                                                            <?php if (!empty($edit_number_operators)) : ?>
                                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input type="number" class="wpbel-input-md" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." disabled="disabled">
                                                    <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CHECKBOX) : ?>
                                                        <select disabled="disabled">
                                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                                        </select>
                                                    <?php elseif ($custom_field['main_type'] == wpbel\classes\repositories\Meta_Field::CALENDAR) : ?>
                                                        <input type="text" class="wpbel-input-md wpbel-datepicker" placeholder="<?php echo esc_html($custom_field['title']); ?> ..." disabled="disabled">
                                                    <?php endif; ?>
                                                    <span class="wpbel-short-description">Upgrade to pro version</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="wpbel-alert wpbel-alert-warning">
                                            <span><?php esc_html_e('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', WBEBL_NAME); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-blue" id="wpbel-bulk-edit-form-do-bulk-edit">
                        <?php esc_html_e('Do Bulk Edit', WBEBL_NAME); ?>
                    </button>
                    <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-white" id="wpbel-bulk-edit-form-reset">
                        <?php esc_html_e('Reset Form', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>