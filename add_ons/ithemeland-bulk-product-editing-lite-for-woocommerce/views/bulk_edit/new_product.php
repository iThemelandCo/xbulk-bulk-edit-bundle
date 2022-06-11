<div class="wcbef-modal" id="wcbef-modal-new-product">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-sm">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('New Product', WBEBL_NAME); ?></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wcbef-modal-body">
                    <div class="wcbef-wrap">
                        <div class="wcbef-filters-form-group">
                            <label class="wcbef-label-big" for="wcbef-new-product-count">
                                <?php esc_html_e('Enter how many new product(s) to create!', WBEBL_NAME); ?>
                            </label>
                            <input type="number" class="wcbef-input-numeric-sm" id="wcbef-new-product-count" value="1" placeholder="<?php esc_html_e('Number ...', WBEBL_NAME); ?>">
                        </div>
                    </div>
                </div>
                <div class="wcbef-modal-footer">
                    <button type="button" class="wcbef-button wcbef-button-blue" id="wcbef-create-new-product"><?php esc_html_e('Create', WBEBL_NAME); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>