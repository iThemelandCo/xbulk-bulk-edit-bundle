<?php if (!empty($product)) : ?>
    <div class="wcbef-modal" id="wcbef-modal-gallery-<?php echo esc_attr($product['id']); ?>">
        <div class="wcbef-modal-container">
            <div class="wcbef-modal-box wcbef-modal-box-sm">
                <div class="wcbef-modal-content">
                    <div class="wcbef-modal-title">
                        <h2><?php esc_html_e('Gallery Edit', WBEBL_NAME); ?> - <span class="wcbef-modal-product-title"><?php echo esc_html($product['post_title']); ?></span></h2>
                        <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                            <i class="lni lni-close"></i>
                        </button>
                    </div>
                    <div class="wcbef-modal-body">
                        <div class="wcbef-wrap">
                            <div class="wcbef-inline-gallery-edit">
                                <div class="wcbef-inline-image-preview">
                                    <div class="wcbef-inline-edit-gallery-item">
                                        <button type="button" class="wcbef-open-uploader wcbef-inline-edit-gallery-add-image" data-product-id="<?php echo esc_attr($product['id']); ?>" data-target="inline-edit-gallery" data-type="multiple">
                                            <i class="lni lni-plus"></i>
                                        </button>
                                    </div>
                                    <div data-gallery-id="wcbef-gallery-items-<?php echo esc_attr($product['id']); ?>">
                                        <?php if (is_array($product['gallery']) && !empty($product['gallery'])) : ?>
                                            <?php foreach ($product['gallery'] as $gallery_item) : ?>
                                                <div class="wcbef-inline-edit-gallery-item">
                                                    <?php echo wp_get_attachment_image(intval($gallery_item)); ?>
                                                    <input type="hidden" class="wcbef-inline-edit-gallery-image-ids" value="<?php echo esc_attr($gallery_item); ?>">
                                                    <button type="button" class="wcbef-inline-edit-gallery-image-item-delete">x</button>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wcbef-modal-footer">
                        <button type="button" data-product-id="<?php echo esc_attr($product['id']); ?>" data-field="gallery" data-content-type="gallery" class="wcbef-button wcbef-button-blue wcbef-edit-action-with-button" data-toggle="modal-close">
                            <?php esc_html_e('Apply Changes', WBEBL_NAME); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>