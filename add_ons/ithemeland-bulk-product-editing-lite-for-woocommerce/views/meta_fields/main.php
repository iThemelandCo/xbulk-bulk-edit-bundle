<div class="wcbef-wrap">
    <div class="wcbef-tab-middle-content">
        <div class="wcbef-alert wcbef-alert-default">
            <span><?php esc_html_e('You can add new products meta fields in two ways: 1- Individually 2- Get from other product.', WBEBL_NAME); ?></span>
        </div>
        <div class="wcbef-alert wcbef-alert-danger">
            <span class="wcbef-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
            <a href="<?php echo esc_url(WCBEF_UPGRADE_URL); ?>"><?php echo esc_html(WCBEF_UPGRADE_TEXT); ?></a>
        </div>
        <div class="wcbef-meta-fields-left">
            <div class="wcbef-meta-fields-manual">
                <label><?php esc_html_e('Manually', WBEBL_NAME); ?></label>
                <div class="wcbef-meta-fields-manual-field">
                    <input type="text" placeholder="<?php esc_html_e('Enter Meta Key ...', WBEBL_NAME); ?>" disabled>
                    <button type="button" class="wcbef-button wcbef-button-square wcbef-button-blue" disabled="disabled">
                        <i class="lni lni-plus wcbef-m0"></i>
                    </button>
                </div>
            </div>
            <div class="wcbef-meta-fields-automatic">
                <label><?php esc_html_e('Automatically From product', WBEBL_NAME); ?></label>
                <div class="wcbef-meta-fields-automatic-field">
                    <input type="text" placeholder="<?php esc_html_e('Enter Product ID ...', WBEBL_NAME); ?>" disabled>
                    <button type="button" class="wcbef-button wcbef-button-square wcbef-button-blue" disabled="disabled">
                        <i class="lni lni-plus wcbef-m0"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="wcbef-meta-fields-right" id="wcbef-meta-fields-items">
            <p class="wcbef-meta-fields-empty-text"><?php esc_html_e('Please add your meta key manually <br> OR <br> From another product', WBEBL_NAME); ?></p>
            <div class="droppable-helper"></div>
        </div>
        <div class="wcbef-meta-fields-buttons">
            <div class="wcbef-meta-fields-buttons-left">
                <button type="button" disabled="disabled" class="wcbef-button wcbef-button-lg wcbef-button-blue">
                    <?php $img = WCBEF_IMAGES_URL . 'save.svg'; ?>
                    <img src="<?php echo esc_url($img); ?>" alt="">
                    <span><?php esc_html_e('Save Fields', WBEBL_NAME); ?></span>
                </button>
            </div>
        </div>
    </div>
</div>