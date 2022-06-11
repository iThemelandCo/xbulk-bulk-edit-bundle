<div id="wccbef-main">
    <div id="wccbef-loading" class="wccbef-loading">
        <?php esc_html_e('Loading ...', WBEBL_NAME) ?>
    </div>
    <div id="wccbef-header">
        <div class="wccbef-plugin-title">
            <div class="wccbef-plugin-name">
                <img src="<?php echo WCCBEF_IMAGES_URL . 'wccbef_icon_original.svg'; ?>" alt="">
                <span><?php esc_html_e($title); ?></span>
                <strong>Lite</strong>
            </div>
            <span class="wccbef-plugin-description"><?php esc_html_e("Be professionals with managing data in the reliable and flexible way!", WBEBL_NAME); ?></span>
        </div>
        <div class="wccbef-header-left">
            <div class="wccbef-plugin-help">
                <span>
                    <a href="<?php echo (!empty($doc_link)) ? esc_attr($doc_link) : '#'; ?>"><strong class="wccbef-plugin-help-text"><?php esc_html_e('Need Help', WBEBL_NAME); ?></strong> <i class="lni-help"></i></a>
                </span>
            </div>
            <div class="wccbef-full-screen" id="wccbef-full-screen">
                <span><i class="lni lni-frame-expand"></i></span>
            </div>
            <div class="wccbef-upgrade" id="wccbef-upgrade">
                <a href="<?php echo esc_url(WCCBEF_UPGRADE_URL); ?>"><?php echo esc_html(WCCBEF_UPGRADE_TEXT); ?></a>
            </div>
        </div>
    </div>