<div class="wccbef-wrap">
    <div class="wccbef-tab-middle-content">
        <div class="wccbef-alert wccbef-alert-default">
            <span><?php esc_html_e('Import/Export coupons as CSV files', WBEBL_NAME); ?>.</span>
        </div>
        <?php if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'import-export') : ?>
            <?php include WCCBEF_VIEWS_DIR . "alerts/flush_message.php"; ?>
        <?php endif; ?>
        <div class="wccbef-export">
            <form action="<?php echo esc_url(admin_url("admin-post.php")); ?>" method="post">
                <input type="hidden" name="action" value="wccbef_export_coupons">
                <div id="wccbef-export-items-selected"></div>
                <div class="wccbef-export-fields">
                    <div class="wccbef-export-field-item">
                        <strong class="label"><?php esc_html_e('Coupons', WBEBL_NAME); ?></strong>
                        <label class="wccbef-export-radio">
                            <input type="radio" name="coupons" value="all" checked="checked" id="wccbef-export-all-items-in-table">
                            <?php esc_html_e('All Coupons In Table', WBEBL_NAME); ?>
                        </label>
                        <label class="wccbef-export-radio">
                            <input type="radio" name="coupons" id="wccbef-export-only-selected-items" value="selected" disabled="disabled">
                            <?php esc_html_e('Only Selected coupons', WBEBL_NAME); ?>
                        </label>
                    </div>
                    <div class="wccbef-export-field-item">
                        <strong class="label"><?php esc_html_e('Fields', WBEBL_NAME); ?></strong>
                        <label class="wccbef-export-radio">
                            <input type="radio" name="fields" value="all" checked="checked">
                            <?php esc_html_e('All Fields', WBEBL_NAME); ?>
                        </label>
                        <label class="wccbef-export-radio">
                            <input type="radio" name="fields" value="visible">
                            <?php esc_html_e('Only Visible Fields', WBEBL_NAME); ?>
                        </label>
                    </div>
                    <div class="wccbef-export-field-item">
                        <label class="label" for="wccbef-export-delimiter"><?php esc_html_e('Delimiter', WBEBL_NAME); ?></label>
                        <select name="wccbef-export-delimiter" id="wccbef-export-delimiter">
                            <option value=",">,</option>
                        </select>
                    </div>
                </div>
                <div class="wccbef-export-buttons">
                    <div class="wccbef-export-buttons-left">
                        <button type="submit" class="wccbef-button wccbef-button-lg wccbef-button-blue" id="wccbef-export-coupons">
                            <i class="lni lni-funnel"></i>
                            <span><?php esc_html_e('Export Now', WBEBL_NAME); ?></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="wccbef-import">
            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="wccbef_import_coupons">
                <div class="wccbef-import-content">
                    <p><?php esc_html_e("If you have coupons in another system, you can import those into this site. ", WBEBL_NAME); ?></p>
                    <input type="file" name="import_file" required>
                </div>
                <div class="wccbef-import-buttons">
                    <div class="wccbef-import-buttons-left">
                        <button type="submit" name="import" class="wccbef-button wccbef-button-lg wccbef-button-blue">
                            <i class="lni lni-funnel"></i>
                            <span><?php esc_html_e('Import Now', WBEBL_NAME); ?></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>