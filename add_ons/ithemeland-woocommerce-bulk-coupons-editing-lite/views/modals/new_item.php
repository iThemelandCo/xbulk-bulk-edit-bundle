<div class="wccbef-modal" id="wccbef-modal-new-item">
    <div class="wccbef-modal-container">
        <div class="wccbef-modal-box wccbef-modal-box-sm">
            <div class="wccbef-modal-content">
                <div class="wccbef-modal-title">
                    <h2 id="wccbef-new-item-title"></h2>
                    <button type="button" class="wccbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wccbef-modal-body">
                    <div class="wccbef-wrap">
                        <div class="wccbef-form-group">
                            <label class="wccbef-label-big" for="wccbef-new-item-count" id="wccbef-new-item-description"></label>
                            <input type="number" class="wccbef-input-numeric-sm wccbef-m0" id="wccbef-new-item-count" value="1" placeholder="<?php esc_html_e('Number ...', WBEBL_NAME); ?>">
                        </div>
                        <div id="wccbef-new-item-extra-fields">
                            <?php if (!empty($new_item_extra_fields)) : ?>
                                <?php foreach ($new_item_extra_fields as $extra_field) : ?>
                                    <div class="wccbef-form-group">
                                        <?php echo sprintf("%s", $extra_field['label']); ?>
                                        <?php echo sprintf("%s", $extra_field['field']); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="wccbef-modal-footer">
                    <button type="button" class="wccbef-button wccbef-button-blue" id="wccbef-create-new-item"><?php esc_html_e('Create', WBEBL_NAME); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>