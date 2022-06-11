<?php if (!empty($post) && !empty($key_decoded)) : ?>
    <div class="wpbel-modal" id="wpbel-modal-taxonomy-<?php echo esc_attr($key_decoded); ?>-<?php echo esc_attr($post['id']); ?>">
        <div class="wpbel-modal-container">
            <div class="wpbel-modal-box wpbel-modal-box-sm">
                <div class="wpbel-modal-content">
                    <div class="wpbel-modal-title">
                        <h2><?php esc_html_e('Taxonomy Edit', WBEBL_NAME); ?> - <span class="wpbel-modal-item-title"><?php echo esc_html($post['post_title']); ?></span></h2>
                        <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                            <i class="lni lni-close"></i>
                        </button>
                    </div>
                    <div class="wpbel-wrap">
                        <div class="wpbel-modal-top-search">
                            <input class="wpbel-search-in-list" title="Type for search" data-id="#wpbel-modal-taxonomy-<?php echo esc_attr($key_decoded); ?>-<?php echo esc_attr($post['id']); ?>" type="text" placeholder="<?php esc_html_e('Type for search', WBEBL_NAME); ?> ...">
                        </div>
                    </div>
                    <div class="wpbel-modal-body">
                        <div class="wpbel-wrap">
                            <div class="wpbel-post-items-list">
                                <?php
                                $checked = ($key_decoded != 'category') ? wp_get_post_terms(intval($post['id']), esc_sql($key_decoded), ['fields' => 'names']) : wp_get_post_terms(intval($post['id']), esc_sql($key_decoded), ['fields' => 'ids']);
                                $taxonomy_items = wpbel\classes\helpers\Taxonomy_Helper::get_post_taxonomy_list($key_decoded, $checked);
                                ?>
                                <?php if (!empty($taxonomy_items)) : ?>
                                    <?php echo sprintf('%s', $taxonomy_items); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="wpbel-modal-footer">
                        <button type="button" data-item-id="<?php echo esc_attr($post['id']); ?>" data-field="<?php echo esc_attr($key_decoded); ?>" data-toggle="modal-close" class="wpbel-button wpbel-button-blue wpbel-inline-edit-taxonomy-save">
                            <?php esc_html_e('Apply Changes', WBEBL_NAME); ?>
                        </button>
                        <button type="button" class="wpbel-button wpbel-button-white wpbel-inline-edit-add-new-taxonomy" data-item-id="<?php echo esc_attr($post['id']); ?>" data-item-name="<?php echo esc_attr($post['post_title']); ?>" data-field="<?php echo esc_attr($key_decoded); ?>" data-toggle="modal" data-target="#wpbel-modal-new-post-taxonomy">
                            <?php esc_html_e('Add New', WBEBL_NAME); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>