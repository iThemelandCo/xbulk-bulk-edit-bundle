<?php if (!empty($product) && !empty($key) && !empty($key_decoded)) : ?>
    <div class="wcbef-modal" id="wcbef-modal-attribute-<?php echo esc_attr($key_decoded); ?>-<?php echo esc_attr($product['id']); ?>">
        <div class="wcbef-modal-container">
            <div class="wcbef-modal-box wcbef-modal-box-sm">
                <div class="wcbef-modal-content">
                    <div class="wcbef-modal-title">
                        <h2><?php esc_html_e('Attribute Edit', WBEBL_NAME); ?> - <span class="wcbef-modal-product-title"><?php echo esc_html($product['post_title']); ?></span></h2>
                        <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                            <i class="lni lni-close"></i>
                        </button>
                    </div>
                    <div class="wcbef-wrap">
                        <div class="wcbef-modal-top-search">
                            <input class="wcbef-search-in-list" title="<?php esc_html_e('Type for search', WBEBL_NAME); ?>" data-id="#wcbef-modal-attribute-<?php echo esc_attr($key_decoded); ?>-<?php echo esc_attr($product['id']); ?>" type="text" placeholder="<?php esc_html_e('Type for search ...', WBEBL_NAME); ?>">
                        </div>
                    </div>
                    <div class="wcbef-modal-body">
                        <div class="wcbef-wrap">
                            <div class="wcbef-product-items-list">
                                <ul>
                                    <?php $attribute_items = get_terms(['taxonomy' => $key_decoded, 'hide_empty' => false]); ?>
                                    <?php if (!empty($attribute_items)) : ?>
                                        <?php foreach ($attribute_items as $attribute_item) : ?>
                                            <?php
                                            $current_terms = wp_get_post_terms($product['id'], $key_decoded, ['fields' => 'ids']);
                                            if (is_array($current_terms) && in_array($attribute_item->term_id, $current_terms)) {
                                                $checked = 'checked="checked"';
                                            } else {
                                                $checked = '';
                                            }
                                            ?>
                                            <li>
                                                <label>
                                                    <input type="checkbox" class="wcbef-inline-edit-attribute-<?php echo esc_attr($key_decoded); ?>-<?php echo esc_attr($product['id']); ?>" value="<?php echo esc_attr($attribute_item->term_id) ?>" <?php echo esc_attr($checked); ?>>
                                                    <?php echo esc_html($attribute_item->name); ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="wcbef-modal-footer">
                        <button type="button" data-product-id="<?php echo esc_attr($product['id']); ?>" data-field="<?php echo esc_attr($key_decoded); ?>" data-toggle="modal-close" class="wcbef-button wcbef-button-blue wcbef-inline-edit-attribute-save">
                            <?php esc_html_e('Apply Changes', WBEBL_NAME); ?>
                        </button>
                        <button type="button" class="wcbef-button wcbef-button-white wcbef-inline-edit-add-new-attribute" data-product-id="<?php echo esc_attr($product['id']); ?>" data-field="<?php echo esc_attr($key_decoded); ?>" data-product-name="<?php echo esc_attr($product['post_title']); ?>" data-toggle="modal" data-target="#wcbef-modal-new-product-attribute">
                            <?php esc_html_e('Add New', WBEBL_NAME); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>