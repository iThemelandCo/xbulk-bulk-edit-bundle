<div class="wobef-modal" id="wobef-modal-bulk-edit">
    <div class="wobef-modal-container">
        <div class="wobef-modal-box wobef-modal-box-lg">
            <div class="wobef-modal-content">
                <div class="wobef-modal-title">
                    <h2><?php esc_html_e('Bulk Edit Form', WBEBL_NAME); ?></h2>
                    <button type="button" class="wobef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wobef-modal-body">
                    <div class="wobef-wrap">
                        <div class="wobef-tabs">
                            <div class="wobef-tabs-navigation">
                                <nav class="wobef-tabs-navbar">
                                    <ul class="wobef-tabs-list" data-content-id="wobef-bulk-edit-tabs">
                                        <?php if (!empty($bulk_edit_form_tabs_title) && is_array($bulk_edit_form_tabs_title)) : ?>
                                            <?php $bulk_edit_tab_title_counter = 1; ?>
                                            <?php foreach ($bulk_edit_form_tabs_title as $tab_key => $tab_label) : ?>
                                                <li><a class="<?php echo ($bulk_edit_tab_title_counter == 1) ? 'selected' : ''; ?>" data-content="<?php echo esc_attr($tab_key); ?>" href="#"><?php echo esc_html($tab_label); ?></a></li>
                                                <?php $bulk_edit_tab_title_counter++; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wobef-tabs-contents wobef-mt30" id="wobef-bulk-edit-tabs">
                                <?php if (!empty($bulk_edit_form_tabs_content)) : ?>
                                    <?php foreach ($bulk_edit_form_tabs_content as $tab_key => $bulk_edit_tab) : ?>
                                        <?php echo (!empty($bulk_edit_tab['wrapper_start'])) ? sprintf('%s', $bulk_edit_tab['wrapper_start']) : ''; ?>
                                        <?php
                                        if (!empty($bulk_edit_tab['fields_top']) && is_array($bulk_edit_tab['fields_top'])) {
                                            foreach ($bulk_edit_tab['fields_top'] as $top_item) {
                                                echo sprintf('%s', $top_item);
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($bulk_edit_tab['fields']) && is_array($bulk_edit_tab['fields'])) : ?>
                                            <?php foreach ($bulk_edit_tab['fields'] as $field_key => $field_items) : ?>
                                                <?php if (!empty($field_items) && is_array($field_items)) : ?>
                                                    <div class="wobef-form-group" data-name="<?php echo esc_attr($field_key); ?>">
                                                        <?php foreach ($field_items as $field_key => $field_html) : ?>
                                                            <?php echo sprintf('%s', $field_html); ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php echo (!empty($bulk_edit_tab['wrapper_end'])) ? $bulk_edit_tab['wrapper_end'] : ''; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wobef-modal-footer">
                    <button type="button" class="wobef-button wobef-button-lg wobef-button-blue" id="wobef-bulk-edit-form-do-bulk-edit">
                        <?php esc_html_e('Do Bulk Edit', WBEBL_NAME); ?>
                    </button>
                    <button type="button" class="wobef-button wobef-button-lg wobef-button-white" id="wobef-bulk-edit-form-reset">
                        <?php esc_html_e('Reset Form', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>