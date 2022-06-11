<div class="wcbef-modal" id="wcbef-modal-new-product-attribute">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-sm">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('New Product Attribute', WBEBL_NAME); ?> - <span id="wcbef-modal-new-product-attribute-product-title" class="wcbef-modal-product-title"></span></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wcbef-modal-body">
                    <div class="wcbef-wrap">
                        <div class="wcbef-filters-form-group">
                            <div class="wcbef-new-product-attribute-form-group">
                                <label for="wcbef-new-product-attribute-name"><?php esc_html_e('Name', WBEBL_NAME); ?></label>
                                <input type="text" id="wcbef-new-product-attribute-name" placeholder="<?php esc_html_e('Attribute Name ...', WBEBL_NAME); ?>">
                            </div>
                            <div class="wcbef-new-product-attribute-form-group">
                                <label for="wcbef-new-product-attribute-slug"><?php esc_html_e('Slug', WBEBL_NAME); ?></label>
                                <input type="text" id="wcbef-new-product-attribute-slug" placeholder="<?php esc_html_e('Attribute Slug ...', WBEBL_NAME); ?>">
                            </div>
                            <div class="wcbef-new-product-attribute-form-group">
                                <label for="wcbef-new-product-attribute-description"><?php esc_html_e('Description', WBEBL_NAME); ?></label>
                                <textarea id="wcbef-new-product-attribute-description" rows="8" placeholder="<?php esc_html_e('Description ...', WBEBL_NAME); ?>"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wcbef-modal-footer">
                    <button type="button" class="wcbef-button wcbef-button-blue" id="wcbef-create-new-product-attribute" data-field="">
                        <?php esc_html_e('Create', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>