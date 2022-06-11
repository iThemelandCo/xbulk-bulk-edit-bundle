<div class="wcbef-modal" id="wcbef-modal-select-products">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-sm">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('Select Products', WBEBL_NAME); ?> - <span id="wcbef-modal-select-products-product-title" class="wcbef-modal-product-title"></span></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wcbef-modal-body">
                    <div class="wcbef-wrap">
                        <div class="wcbef-inline-select-products">
                            <?php if (!empty($all_products->posts)) : ?>
                                <select id="wcbef-select-products-value" class="wcbef-select2 wcbef-w100p" multiple>
                                    <?php foreach ($all_products->posts as $product_item) : ?>
                                        <option value="<?php echo esc_attr($product_item->ID); ?>"><?php echo esc_html($product_item->post_title); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="wcbef-modal-footer">
                    <button type="button" data-product-id="" data-field="" data-content-type="select_products" class="wcbef-button wcbef-button-blue wcbef-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>