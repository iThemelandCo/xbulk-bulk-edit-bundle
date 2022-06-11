<div class="wpbel-wrap">
    <div class="wpbel-tab-middle-content">
        <div class="wpbel-alert wpbel-alert-default">
            <span><?php esc_html_e('Import/Export posts as CSV files', WBEBL_NAME); ?>.</span>
        </div>
        <?php if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'import-export') : ?>
            <?php include WPBEL_VIEWS_DIR . "alerts/flush_message.php"; ?>
        <?php endif; ?>
        <div class="wpbel-export">
            <form action="<?php echo esc_url(admin_url("admin-post.php")); ?>" method="post">
                <input type="hidden" name="action" value="wpbel_export_posts">
                <div id="wpbel-export-items-selected"></div>
                <div class="wpbel-export-fields">
                    <div class="wpbel-export-field-item">
                        <strong class="label"><?php esc_html_e('Posts', WBEBL_NAME); ?></strong>
                        <label class="wpbel-export-radio">
                            <input type="radio" name="posts" value="all" checked="checked" id="wpbel-export-all-items-in-table">
                            <?php esc_html_e('All Posts In Table', WBEBL_NAME); ?>
                        </label>
                        <label class="wpbel-export-radio">
                            <input type="radio" name="posts" id="wpbel-export-only-selected-items" value="selected" disabled="disabled">
                            <?php esc_html_e('Only Selected posts', WBEBL_NAME); ?>
                        </label>
                    </div>
                    <div class="wpbel-export-field-item">
                        <strong class="label"><?php esc_html_e('Fields', WBEBL_NAME); ?></strong>
                        <label class="wpbel-export-radio">
                            <input type="radio" name="fields" value="all" checked="checked">
                            <?php esc_html_e('All Fields', WBEBL_NAME); ?>
                        </label>
                        <label class="wpbel-export-radio">
                            <input type="radio" name="fields" value="visible">
                            <?php esc_html_e('Only Visible Fields', WBEBL_NAME); ?>
                        </label>
                    </div>
                    <div class="wpbel-export-field-item">
                        <label class="label" for="wpbel-export-delimiter"><?php esc_html_e('Delimiter', WBEBL_NAME); ?></label>
                        <select name="wpbel-export-delimiter" id="wpbel-export-delimiter">
                            <option value=",">,</option>
                        </select>
                    </div>
                </div>
                <div class="wpbel-export-buttons">
                    <div class="wpbel-export-buttons-left">
                        <button type="submit" class="wpbel-button wpbel-button-lg wpbel-button-blue" id="wpbel-export-posts">
                            <i class="lni lni-funnel"></i>
                            <span><?php esc_html_e('Export Now', WBEBL_NAME); ?></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="wpbel-import">
            <div class="wpbel-import-content">
                <p><?php esc_html_e("If you have posts in another system, you can import those into this site. ", WBEBL_NAME); ?></p>
            </div>
            <div class="wpbel-import-buttons">
                <div class="wpbel-import-buttons-left">
                    <a href="<?php echo esc_url(admin_url("import.php")); ?>" target="_blank" class="wpbel-button wpbel-button-lg wpbel-button-blue">
                        <i class="lni lni-funnel"></i>
                        <span><?php esc_html_e('Import Now', WBEBL_NAME); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>