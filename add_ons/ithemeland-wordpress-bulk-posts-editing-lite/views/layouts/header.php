<div id="wpbel-main">
    <div id="wpbel-loading" class="wpbel-loading">
        <?php esc_html_e('Loading ...', WBEBL_NAME) ?>
    </div>
    <div id="wpbel-header">
        <div class="wpbel-plugin-title">
            <div class="wpbel-plugin-name">
                <img src="<?php echo WPBEL_IMAGES_URL . 'wpbel_icon_original.svg'; ?>" alt="">
                <span><?php esc_html_e($title); ?></span>
                <strong>Lite</strong>
            </div>
            <span><?php esc_html_e("Be professionals with managing data in the reliable and flexible way!", WBEBL_NAME); ?></span>
        </div>
        <div class="wpbel-header-left">
            <div class="wpbel-plugin-help">
                <span>
                    <a href="<?php echo (!empty($doc_link)) ? esc_url($doc_link) : '#'; ?>"><strong class="wpbel-plugin-help-text"><?php esc_html_e('Need Help', WBEBL_NAME); ?></strong> <i class="lni-help"></i></a>
                </span>
            </div>
            <div class="wpbel-full-screen" id="wpbel-full-screen">
                <span><i class="lni lni-frame-expand"></i></span>
            </div>
            <div class="wpbel-upgrade" id="wpbel-upgrade">
                <a href="<?php echo esc_url(WPBEL_UPGRADE_URL); ?>"><?php echo esc_html(WPBEL_UPGRADE_TEXT); ?></a>
            </div>
        </div>
    </div>