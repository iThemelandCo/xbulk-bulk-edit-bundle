<div class="wcbef-modal" id="wcbef-modal-variation-bulk-edit">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-lg">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('Variation Bulk Edit', WBEBL_NAME); ?></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wcbef-modal-body wcbef-pb0">
                    <div class="wcbef-wrap">
                        <div class="wcbef-tabs">
                            <div class="wcbef-tabs-navigation">
                                <nav class="wcbef-tabs-navbar">
                                    <ul class="wcbef-tabs-list" data-content-id="wcbef-variation-bulk-edit-tabs">
                                        <li>
                                            <a class="selected" data-content="set-variation" href="#"><?php esc_html_e('Set Variation', WBEBL_NAME); ?></a>
                                        </li>
                                        <li><a data-content="delete-variation" href="#"><?php esc_html_e('Delete Variation', WBEBL_NAME); ?></a></li>
                                        <li><a data-content="attach-variation" href="#"><?php esc_html_e('Attach Variation', WBEBL_NAME); ?></a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wcbef-tabs-contents" id="wcbef-variation-bulk-edit-tabs">
                                <div class="selected wcbef-tab-content-item" data-content="set-variation">
                                    <div class="wcbef-variation-bulk-edit-left">
                                        <div class="wcbef-variation-bulk-edit-product-variations">
                                            <label for="wcbef-variation-bulk-edit-attributes"><?php esc_html_e('Product Attributes', WBEBL_NAME); ?></label>
                                            <select id="wcbef-variation-bulk-edit-attributes" class="wcbef-select2" multiple>
                                                <?php if (!empty($attributes)) : ?>
                                                    <?php foreach ($attributes as $attribute) : ?>
                                                        <option value="<?php echo esc_attr($attribute->attribute_name); ?>"><?php echo esc_html($attribute->attribute_name); ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="wcbef-variation-bulk-edit-attributes">
                                            <span class="wcbef-variation-bulk-edit-attributes-title"><?php esc_html_e('Select Attributes', WBEBL_NAME); ?></span>
                                            <div id="wcbef-variation-bulk-edit-attributes-added">

                                            </div>
                                        </div>
                                        <div class="wcbef-variation-bulk-edit-create">
                                            <div class="wcbef-variation-bulk-edit-create-mode">
                                                <div class="wcbef-pb20"><span><?php esc_html_e('How To Create Variations ?', WBEBL_NAME); ?></span></div>
                                                <label class="wcbef-variation-bulk-edit-create-mode">
                                                    <input type="radio" name="create_variation_mode" checked="checked" data-mode="all_combination">
                                                    <?php esc_html_e('All Combinations', WBEBL_NAME); ?>
                                                </label>
                                                <label>
                                                    <input type="radio" name="create_variation_mode" data-mode="individual_combination">
                                                    <?php esc_html_e('Individual Combination', WBEBL_NAME); ?>
                                                </label>
                                            </div>
                                            <div id="wcbef-variation-bulk-edit-individual" style="display: none">
                                                <div class="wcbef-variation-bulk-edit-individual-items">

                                                </div>
                                                <button type="button" id="wcbef-variation-bulk-edit-manual-add" disabled="disabled" class="wcbef-button wcbef-button-blue wcbef-button-md wcbef-mt20">
                                                    <i class="lni lni-shuffle"></i>
                                                    <?php esc_html_e('Add', WBEBL_NAME); ?>
                                                </button>
                                            </div>
                                            <button type="button" id="wcbef-variation-bulk-edit-generate" disabled="disabled" class="wcbef-button wcbef-button-blue wcbef-button-md wcbef-mt20">
                                                <i class="lni lni-shuffle"></i>
                                                <?php esc_html_e('Generate', WBEBL_NAME); ?>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="wcbef-variation-bulk-edit-right">
                                        <div class="wcbef-variation-bulk-edit-right-title">
                                            <span><?php esc_html_e('Current Variations', WBEBL_NAME); ?></span>
                                        </div>
                                        <div class="wcbef-variation-bulk-edit-current-variations">
                                            <div class="wcbef-variation-bulk-edit-current-items">

                                            </div>
                                            <div class="wcbef-variation-bulk-edit-right-footer">
                                                <button type="button" disabled="disabled" class="wcbef-button wcbef-button-md wcbef-button-blue wcbef-variation-bulk-edit-do-bulk" id="wcbef-variation-bulk-edit-do-bulk-variations">
                                                    <i class="lni lni-shuffle"></i>
                                                    <?php esc_html_e('Do Bulk Variations', WBEBL_NAME); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wcbef-tab-content-item" data-content="delete-variation">
                                    <div class="wcbef-alert wcbef-alert-danger">
                                        <span class="wcbef-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                                        <a href="<?php echo esc_url(WCBEF_UPGRADE_URL); ?>"><?php echo esc_html(WCBEF_UPGRADE_TEXT); ?></a>
                                    </div>
                                </div>
                                <div class="wcbef-tab-content-item" data-content="attach-variation">
                                    <div class="wcbef-alert wcbef-alert-danger">
                                        <span class="wcbef-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                                        <a href="<?php echo esc_url(WCBEF_UPGRADE_URL); ?>"><?php echo esc_html(WCBEF_UPGRADE_TEXT); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>