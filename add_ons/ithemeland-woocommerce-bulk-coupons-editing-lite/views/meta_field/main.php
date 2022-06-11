<div class="wccbef-wrap">
    <div class="wccbef-tab-middle-content">
        <div class="wccbef-alert wccbef-alert-default">
            <span><?php esc_html_e('You can add new coupons meta fields in two ways: 1- Individually 2- Get from other coupon.', WBEBL_NAME); ?></span>
        </div>
        <?php if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'meta-fields') : ?>
            <?php include WCCBEF_VIEWS_DIR . "alerts/flush_message.php"; ?>
        <?php endif; ?>
        <div class="wccbef-meta-fields-left">
            <div class="wccbef-meta-fields-manual">
                <label for="wccbef-meta-fields-manual_key_name"><?php esc_html_e('Manually', WBEBL_NAME); ?></label>
                <div class="wccbef-meta-fields-manual-field">
                    <input type="text" id="wccbef-meta-fields-manual_key_name" placeholder="<?php esc_html_e('Enter Meta Key ...', WBEBL_NAME); ?>">
                    <button type="button" class="wccbef-button wccbef-button-square wccbef-button-blue" id="wccbef-add-meta-field-manual">
                        <i class="lni lni-plus wccbef-m0"></i>
                    </button>
                </div>
            </div>
            <div class="wccbef-meta-fields-automatic">
                <label for="wccbef-add-meta-fields-coupon-id"><?php esc_html_e('Automatically From Coupon', WBEBL_NAME); ?></label>
                <div class="wccbef-meta-fields-automatic-field">
                    <input type="text" id="wccbef-add-meta-fields-coupon-id" placeholder="<?php esc_html_e('Enter Coupon ID ...', WBEBL_NAME); ?>">
                    <button type="button" class="wccbef-button wccbef-button-square wccbef-button-blue" id="wccbef-get-meta-fields-by-coupon-id">
                        <i class="lni lni-plus wccbef-m0"></i>
                    </button>
                </div>
            </div>
        </div>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <input type="hidden" name="action" value="wccbef_meta_fields">
            <div class="wccbef-meta-fields-right" id="wccbef-meta-fields-items">
                <p class="wccbef-meta-fields-empty-text" <?php echo (!empty($meta_fields)) ? 'style="display:none";' : ''; ?>><?php esc_html_e("Please add your meta key manually", WBEBL_NAME); ?><br> <?php esc_html_e("OR", WBEBL_NAME); ?><br><?php esc_html_e("From another coupon", WBEBL_NAME); ?></p>
                <?php if (!empty($meta_fields)) : ?>
                    <?php foreach ($meta_fields as $meta_field) : ?>
                        <?php include WCCBEF_VIEWS_DIR . 'meta_field/meta_field_item.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="droppable-helper"></div>
            </div>
            <div class="wccbef-meta-fields-buttons">
                <div class="wccbef-meta-fields-buttons-left">
                    <button type="submit" value="1" name="save_meta_fields" class="wccbef-button wccbef-button-lg wccbef-button-blue">
                        <?php $img = WCCBEF_IMAGES_URL . 'save.svg'; ?>
                        <img src="<?php echo esc_url($img); ?>" alt="">
                        <span><?php esc_html_e('Save Fields', WBEBL_NAME); ?></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>