<div class="wccbef-modal" id="wccbef-modal-products">
    <div class="wccbef-modal-container">
        <div class="wccbef-modal-box wccbef-modal-box-sm">
            <div class="wccbef-modal-content">
                <div class="wccbef-modal-title">
                    <h2><?php esc_html_e('Products', WBEBL_NAME); ?> <span id="wccbef-modal-products-item-title" class="wccbef-modal-item-title"></span></h2>
                    <button type="button" class="wccbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wccbef-modal-body">
                    <div class="wccbef-wrap">
                        <div class="wccbef-col-full">
                            <label for="wccbef-modal-products-items"><strong><?php esc_html_e('Select Products', WBEBL_NAME); ?></strong></label>
                            <select id="wccbef-modal-products-items" class="wccbef-select2-products" data-placeholder="<?php esc_html_e('Select Products', WBEBL_NAME); ?> ..." multiple></select>
                        </div>
                    </div>
                </div>
                <div class="wccbef-modal-footer">
                    <button type="button" class="wccbef-button wccbef-button-blue wccbef-float-left wccbef-modal-products-save-changes" data-item-id="" data-field="" data-toggle="modal-close">
                        <?php esc_html_e('Save Changes', WBEBL_NAME); ?>
                    </button>
                    <button type="button" class="wccbef-button wccbef-button-gray wccbef-float-right" data-toggle="modal-close">
                        <?php esc_html_e('Close', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>