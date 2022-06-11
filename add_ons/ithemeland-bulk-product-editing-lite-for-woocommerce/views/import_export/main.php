<div class="wcbef-wrap">
    <div class="wcbef-tab-middle-content">
        <div class="wcbef-alert wcbef-alert-default">
            <span><?php esc_html_e('Import/Export products as CSV fiels', WBEBL_NAME); ?>.</span>
        </div>
        <?php if (\wcbef\classes\helpers\Session::has('flush-message') && !empty($current_tab) && $current_tab == 'import-export') : ?>
            <?php include WCBEF_VIEWS_DIR . "alerts/flush_message.php"; ?>
        <?php endif; ?>
        <div class="wcbef-export">
            <form action="<?php echo esc_url(admin_url("admin-post.php")); ?>" method="post">
                <input type="hidden" name="action" value="wcbef_export_products">
                <div id="wcbef-export-product-selected"></div>
                <div class="wcbef-export-fields">
                    <div class="wcbef-export-field-item">
                        <strong class="label"><?php esc_html_e('Products', WBEBL_NAME); ?></strong>
                        <label class="wcbef-export-radio">
                            <input type="radio" name="products" value="all" checked="checked" id="wcbef-export-all-products-in-table">
                            <?php esc_html_e('All Products In Table', WBEBL_NAME); ?>
                        </label>
                        <label class="wcbef-export-radio">
                            <input type="radio" name="products" id="wcbef-export-only-selected-products" value="selected" disabled="disabled">
                            <?php esc_html_e('Only Selected products', WBEBL_NAME); ?>
                        </label>
                    </div>
                    <div class="wcbef-export-field-item">
                        <strong class="label"><?php esc_html_e('Fields', WBEBL_NAME); ?></strong>
                        <label class="wcbef-export-radio">
                            <input type="radio" name="fields" value="all" checked="checked">
                            <?php esc_html_e('All Fields', WBEBL_NAME); ?>
                        </label>
                        <label class="wcbef-export-radio">
                            <input type="radio" name="fields" value="visible">
                            <?php esc_html_e('Only Visible Fields', WBEBL_NAME); ?>
                        </label>
                    </div>
                    <div class="wcbef-export-field-item">
                        <label class="label" for="wcbef-export-delimiter"><?php esc_html_e('Delimiter', WBEBL_NAME); ?></label>
                        <select name="wcbef-export-delimiter" id="wcbef-export-delimiter">
                            <option value=",">,</option>
                        </select>
                    </div>
                </div>
                <div class="wcbef-export-buttons">
                    <div class="wcbef-export-buttons-left">
                        <button type="submit" class="wcbef-button wcbef-button-lg wcbef-button-blue" id="wcbef-export-products">
                            <i class="lni lni-funnel"></i>
                            <span><?php esc_html_e('Export Now', WBEBL_NAME); ?></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="wcbef-import">
            <div class="wcbef-import-content">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mihi, inquam, qui te id ipsum rogavi?</p>
            </div>
            <div class="wcbef-import-buttons">
                <div class="wcbef-import-buttons-left">
                    <a href="<?php echo esc_url(admin_url("edit.php?post_type=product&page=product_importer")); ?>" target="_blank" class="wcbef-button wcbef-button-lg wcbef-button-blue">
                        <i class="lni lni-funnel"></i>
                        <span><?php esc_html_e('Import Now', WBEBL_NAME); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>