<div class="wpbel-modal" id="wpbel-modal-select-post">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-sm">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Select Post', WBEBL_NAME); ?> - <span id="wpbel-modal-select-post-item-title" class="wpbel-modal-item-title"></span></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-inline-select-post">
                            <?php if (!empty($all_posts->posts)) : ?>
                                <select id="wpbel-select-post-value" class="wpbel-select2">
                                    <option value="0"><?php esc_html_e('No Parent', WBEBL_NAME); ?></option>
                                    <?php foreach ($all_posts->posts as $post_item) : ?>
                                        <option value="<?php echo esc_attr($post_item->ID); ?>"><?php echo esc_html($post_item->post_title); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-content-type="select_post" class="wpbel-button wpbel-button-blue wpbel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>