<div class="wccbef-modal" id="wccbef-modal-bulk-edit">
    <div class="wccbef-modal-container">
        <div class="wccbef-modal-box wccbef-modal-box-lg">
            <div class="wccbef-modal-content">
                <div class="wccbef-modal-title">
                    <h2><?php esc_html_e('Bulk Edit Form', WBEBL_NAME); ?></h2>
                    <button type="button" class="wccbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wccbef-modal-body">
                    <div class="wccbef-wrap">
                        <div class="wccbef-tabs">
                            <div class="wccbef-tabs-navigation">
                                <nav class="wccbef-tabs-navbar">
                                    <ul class="wccbef-tabs-list" data-content-id="wccbef-bulk-edit-tabs">
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
                            <div class="wccbef-tabs-contents wccbef-mt30" id="wccbef-bulk-edit-tabs">
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
                                                    <div class="wccbef-form-group" data-name="<?php echo esc_attr($field_key); ?>">
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
                <div class="wccbef-modal-footer">
                    <button type="button" class="wccbef-button wccbef-button-lg wccbef-button-blue" id="wccbef-bulk-edit-form-do-bulk-edit">
                        <?php esc_html_e('Do Bulk Edit', WBEBL_NAME); ?>
                    </button>
                    <button type="button" class="wccbef-button wccbef-button-lg wccbef-button-white" id="wccbef-bulk-edit-form-reset">
                        <?php esc_html_e('Reset Form', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>