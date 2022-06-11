<?php if (!empty($product) && !empty($key_decoded)): ?>
    <div class="wcbef-modal" id="wcbef-modal-taxonomy-<?php echo esc_attr($key_decoded); ?>-<?php echo esc_attr($product['id']); ?>">
        <div class="wcbef-modal-container">
            <div class="wcbef-modal-box wcbef-modal-box-sm">
                <div class="wcbef-modal-content">
                    <div class="wcbef-modal-title">
                        <h2><?php esc_html_e('Taxonomy Edit', WBEBL_NAME);?> - <span class="wcbef-modal-product-title"><?php echo esc_html($product['post_title']); ?></span></h2>
                        <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                            <i class="lni lni-close"></i>
                        </button>
                    </div>
                    <div class="wcbef-wrap">
                        <div class="wcbef-modal-top-search">
                            <input class="wcbef-search-in-list" title="Type for search" data-id="#wcbef-modal-taxonomy-<?php echo esc_attr($key_decoded); ?>-<?php echo esc_attr($product['id']); ?>" type="text" placeholder="<?php esc_html_e('Type for search', WBEBL_NAME);?> ...">
                        </div>
                    </div>
                    <div class="wcbef-modal-body">
                        <div class="wcbef-wrap">
                            <div class="wcbef-product-items-list">
                                <?php
                                $checked = $checked = wp_get_post_terms(intval($product['id']), esc_sql($key_decoded), ['fields' => 'ids']);
                                $taxonomy_items = \wcbef\classes\helpers\Taxonomy::wcbef_product_taxonomy_list($key_decoded, $checked);
                                ?>
                                <?php if (!empty($taxonomy_items)): ?>
                                    <?php echo sprintf('%s', $taxonomy_items); ?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="wcbef-modal-footer">
                        <button type="button" data-product-id="<?php echo esc_attr($product['id']); ?>" data-field="<?php echo esc_attr($key_decoded); ?>" data-toggle="modal-close" class="wcbef-button wcbef-button-blue wcbef-inline-edit-taxonomy-save">
                            <?php esc_html_e('Apply Changes', WBEBL_NAME);?>
                        </button>
                        <button type="button" class="wcbef-button wcbef-button-white wcbef-inline-edit-add-new-taxonomy" data-product-id="<?php echo esc_attr($product['id']); ?>" data-product-name="<?php echo esc_attr($product['post_title']); ?>" data-field="<?php echo esc_attr($key_decoded); ?>" data-toggle="modal" data-target="#wcbef-modal-new-product-taxonomy">
                            <?php esc_html_e('Add New', WBEBL_NAME);?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>