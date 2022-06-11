<div class="wpbel-wrap">
    <div class="wpbel-tab-middle-content">
        <div class="wpbel-alert wpbel-alert-default">
            <span><?php esc_html_e('You can add new posts meta fields in two ways: 1- Individually 2- Get from other post.', WBEBL_NAME); ?></span>
        </div>
        <div class="wpbel-alert wpbel-alert-danger">
            <span class="wpbel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
            <a href="<?php echo esc_url(WPBEL_UPGRADE_URL); ?>"><?php echo esc_html(WPBEL_UPGRADE_TEXT); ?></a>
        </div>
        <div class="wpbel-meta-fields-left">
            <div class="wpbel-meta-fields-manual">
                <label><?php esc_html_e('Manually', WBEBL_NAME); ?></label>
                <div class="wpbel-meta-fields-manual-field">
                    <input type="text" placeholder="<?php esc_html_e('Enter Meta Key ...', WBEBL_NAME); ?>" disabled="disabled">
                    <button type="button" class="wpbel-button wpbel-button-square wpbel-button-blue" disabled="disabled">
                        <i class="lni lni-plus wpbel-m0"></i>
                    </button>
                </div>
            </div>
            <div class="wpbel-meta-fields-automatic">
                <label><?php esc_html_e('Automatically From post', WBEBL_NAME); ?></label>
                <div class="wpbel-meta-fields-automatic-field">
                    <input type="text" placeholder="<?php esc_html_e('Enter Post ID ...', WBEBL_NAME); ?>" disabled="disabled">
                    <button type="button" class="wpbel-button wpbel-button-square wpbel-button-blue" disabled="disabled">
                        <i class="lni lni-plus wpbel-m0"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="wpbel-meta-fields-right" id="wpbel-meta-fields-items">
            <p class="wpbel-meta-fields-empty-text" <?php echo (!empty($meta_fields)) ? 'style="display:none";' : ''; ?>><?php echo sprintf(__('Please add your meta key manually %s OR %s From another post', WBEBL_NAME), '<br>', '<br>'); ?></p>
        </div>
        <div class="wpbel-meta-fields-buttons">
            <div class="wpbel-meta-fields-buttons-left">
                <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-blue" disabled="disabled">
                    <?php $img = WPBEL_IMAGES_URL . 'save.svg'; ?>
                    <img src="<?php echo esc_url($img); ?>" alt="">
                    <span><?php esc_html_e('Save Fields', WBEBL_NAME); ?></span>
                </button>
            </div>
        </div>
    </div>
</div>