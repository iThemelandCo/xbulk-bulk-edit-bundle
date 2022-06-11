<div class="wccbef-modal" id="wccbef-modal-item-duplicate">
    <div class="wccbef-modal-container">
        <div class="wccbef-modal-box wccbef-modal-box-sm">
            <div class="wccbef-modal-content">
                <div class="wccbef-modal-title">
                    <h2><?php esc_html_e('Duplicate', WBEBL_NAME); ?></h2>
                    <button type="button" class="wccbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wccbef-modal-body">
                    <div class="wccbef-wrap">
                        <div class="wccbef-form-group">
                            <label class="wccbef-label-big" for="wccbef-bulk-edit-duplicate-number">
                                <?php esc_html_e('Enter how many item(s) to Duplicate!', WBEBL_NAME); ?>
                            </label>
                            <input type="number" class="wccbef-input-numeric-sm" id="wccbef-bulk-edit-duplicate-number" value="1" placeholder="<?php esc_html_e('Number ...', WBEBL_NAME); ?>">
                        </div>
                    </div>
                </div>
                <div class="wccbef-modal-footer">
                    <button type="button" class="wccbef-button wccbef-button-blue" id="wccbef-bulk-edit-duplicate-start">
                        <?php esc_html_e('Start Duplicate', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>