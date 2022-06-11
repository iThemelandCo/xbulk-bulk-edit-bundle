<div class="wpbel-modal" id="wpbel-modal-item-duplicate">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-sm">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Duplicate', WBEBL_NAME); ?></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-form-group">
                            <label class="wpbel-label-big" for="wpbel-bulk-edit-duplicate-number">
                                <?php esc_html_e('Enter how many item(s) to Duplicate!', WBEBL_NAME); ?>
                            </label>
                            <input type="number" class="wpbel-input-numeric-sm" id="wpbel-bulk-edit-duplicate-number" value="1" placeholder="<?php esc_html_e('Number ...', WBEBL_NAME); ?>">
                        </div>
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" class="wpbel-button wpbel-button-blue" id="wpbel-bulk-edit-duplicate-start">
                        <?php esc_html_e('Start Duplicate', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>