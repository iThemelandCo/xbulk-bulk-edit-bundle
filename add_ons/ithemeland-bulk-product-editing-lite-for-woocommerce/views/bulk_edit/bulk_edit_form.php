<div class="wcbef-modal" id="wcbef-modal-bulk-edit">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-lg">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('Bulk Edit Form', WBEBL_NAME); ?></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wcbef-modal-body">
                    <div class="wcbef-wrap">
                        <div class="wcbef-tabs">
                            <div class="wcbef-tabs-navigation">
                                <nav class="wcbef-tabs-navbar">
                                    <ul class="wcbef-tabs-list" data-content-id="wcbef-bulk-edit-tabs">
                                        <li><a class="selected" data-content="general" href="#"><?php esc_html_e('General', WBEBL_NAME); ?></a></li>
                                        <li>
                                            <a data-content="categories-tags-taxonomies" href="#">
                                                <?php esc_html_e('Categories/Tags/Taxonomies', WBEBL_NAME); ?>
                                            </a>
                                        </li>
                                        <li><a data-content="pricing" href="#"><?php esc_html_e('Pricing', WBEBL_NAME); ?></a></li>
                                        <li><a data-content="shipping" href="#"><?php esc_html_e('Shipping', WBEBL_NAME); ?></a></li>
                                        <li><a data-content="stock" href="#"><?php esc_html_e('Stock', WBEBL_NAME); ?></a></li>
                                        <li><a data-content="type" href="#"><?php esc_html_e('Type', WBEBL_NAME); ?></a></li>
                                        <li><a data-content="custom-fields" href="#"><?php esc_html_e('Custom Fields', WBEBL_NAME); ?></a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wcbef-tabs-contents" id="wcbef-bulk-edit-tabs">
                                <div class="selected wcbef-tab-content-item" data-content="general">
                                    <div class="wcbef-filters-form-group">
                                        <div>
                                            <label for="wcbef-bulk-edit-form-product-title"><?php esc_html_e('Product Title', WBEBL_NAME); ?></label>
                                            <select title="Select Operator" id="wcbef-bulk-edit-form-product-title-operator" data-field="operator">
                                                <?php if (!empty($edit_text_operators)) : ?>
                                                    <?php foreach ($edit_text_operators as $operator_name => $operator_label) : ?>
                                                        <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <option value="text_remove_duplicate"><?php esc_html_e('Remove Duplicate', WBEBL_NAME); ?></option>
                                            </select>
                                            <input type="text" id="wcbef-bulk-edit-form-product-title" data-field="value" placeholder="<?php esc_html_e('Product Title ...', WBEBL_NAME); ?>">
                                            <?php include "variable.php"; ?>
                                        </div>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <div>
                                            <label><?php esc_html_e('Product Slug', WBEBL_NAME); ?></label>
                                            <select disabled>
                                                <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Product Slug ...', WBEBL_NAME); ?>" disabled>
                                            <span class="wcbef-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <div>
                                            <label><?php esc_html_e('Product SKU', WBEBL_NAME); ?></label>
                                            <select disabled>
                                                <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Product SKU ...', WBEBL_NAME); ?>" disabled>
                                            <span class="wcbef-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <div>
                                            <label><?php esc_html_e('Description', WBEBL_NAME); ?></label>
                                            <select disabled>
                                                <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Description ...', WBEBL_NAME); ?>" disabled>
                                            <span class="wcbef-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <div>
                                            <label><?php esc_html_e('Short Description', WBEBL_NAME); ?></label>
                                            <select disabled>
                                                <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Short Description ...', WBEBL_NAME); ?>" disabled>
                                            <span class="wcbef-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <div>
                                            <label><?php esc_html_e('Purchase Note', WBEBL_NAME); ?></label>
                                            <select disabled>
                                                <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            </select>
                                            <input type="text" placeholder="<?php esc_html_e('Purchase Note ...', WBEBL_NAME); ?>" disabled>
                                            <span class="wcbef-short-description">Upgrade to pro version</span>
                                        </div>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Menu Order', WBEBL_NAME); ?></label>
                                        <input type="number" placeholder="<?php esc_html_e('Menu Order ...', WBEBL_NAME); ?>" disabled class="wcbef-input-md">
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Sold Individually', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Enable Reviews', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-product-product-status"><?php esc_html_e('Product Status', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" title="Select" data-field="value" id="wcbef-bulk-edit-form-product-product-status">
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            <?php if (!empty($product_statuses)) : ?>
                                                <?php foreach ($product_statuses as $product_status_name => $product_status_label) : ?>
                                                    <option value="<?php echo esc_attr($product_status_name); ?>"><?php echo esc_html($product_status_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Catalog Visibility', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Date', WBEBL_NAME); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Date ...', WBEBL_NAME); ?>" class="wcbef-input-md" disabled>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Author', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Image', WBEBL_NAME); ?></label>
                                        <button type="button" class="wcbef-button wcbef-button-blue wcbef-ml10 wcbef-h43 wcbef-float-left" disabled="disabled">
                                            <?php esc_html_e('Choose Image', WBEBL_NAME); ?>
                                        </button>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Gallery', WBEBL_NAME); ?></label>
                                        <button type="button" class="wcbef-button wcbef-button-blue wcbef-ml10 wcbef-h43 wcbef-float-left" disabled="disabled">
                                            <?php esc_html_e('Choose Image', WBEBL_NAME); ?>
                                        </button>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                </div>
                                <div class="wcbef-tab-content-item" data-content="categories-tags-taxonomies">
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-categories"><?php esc_html_e('Categories', WBEBL_NAME); ?></label>
                                        <select title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" id="wcbef-bulk-edit-form-categories-operator">
                                            <?php if (!empty($edit_taxonomy_operators)) : ?>
                                                <?php foreach ($edit_taxonomy_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <select class="wcbef-select2" id="wcbef-bulk-edit-form-categories" data-field="value" multiple>
                                            <?php if (!empty($categories)) : ?>
                                                <?php foreach ($categories as $category) : ?>
                                                    <option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-tags"><?php esc_html_e('Tags', WBEBL_NAME); ?></label>
                                        <select title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" id="wcbef-bulk-edit-form-tags-operator">
                                            <?php if (!empty($edit_taxonomy_operators)) : ?>
                                                <?php foreach ($edit_taxonomy_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <select class="wcbef-select2" id="wcbef-bulk-edit-form-tags" data-field="value" multiple>
                                            <?php if (!empty($tags)) : ?>
                                                <?php foreach ($tags as $tag) : ?>
                                                    <option value="<?php echo esc_attr($tag->term_id); ?>"><?php echo esc_html($tag->name); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <?php if (!empty($taxonomies)) : ?>
                                        <?php foreach ($taxonomies as $name => $taxonomy) : ?>
                                            <div class="wcbef-bulk-edit-form-group">
                                                <label><?php echo esc_html($taxonomy['label']); ?></label>
                                                <select disabled>
                                                    <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                                </select>
                                                <select class="wcbef-select2" disabled multiple>
                                                    <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                                </select>
                                                <span class="wcbef-short-description">Upgrade to pro version</span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="wcbef-tab-content-item" data-content="pricing">
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-regular-price"><?php esc_html_e('Regular Price', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-regular-price-operator" data-field="operator" title="<?php esc_html_e('Select', WBEBL_NAME); ?>">
                                            <?php if (!empty($edit_number_operators)) : ?>
                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php if (!empty($edit_regular_price_operators)) : ?>
                                                <?php foreach ($edit_regular_price_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <input type="number" id="wcbef-bulk-edit-form-regular-price" data-field="value" placeholder="<?php esc_html_e('Regular Price ...', WBEBL_NAME); ?>">
                                        <select id="wcbef-bulk-edit-form-regular-price-round-item" title="<?php esc_html_e('Select round item', WBEBL_NAME); ?>">
                                            <option value=""><?php esc_html_e('Round Item', WBEBL_NAME); ?></option>
                                            <?php foreach (\wcbef\classes\helpers\Operator::round_items() as $key => $round_item) : ?>
                                                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($round_item); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-sale-price"><?php esc_html_e('Sale Price', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-sale-price-operator" data-field="operator" title="<?php esc_html_e('Select', WBEBL_NAME); ?>">
                                            <?php if (!empty($edit_number_operators)) : ?>
                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php if (!empty($edit_sale_price_operators)) : ?>
                                                <?php foreach ($edit_sale_price_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <input type="number" placeholder="Sale Price ..." data-field="value" id="wcbef-bulk-edit-form-sale-price">
                                        <select id="wcbef-bulk-edit-form-sale-price-round-item" title="Select round item">
                                            <option value=""><?php esc_html_e('Round Item', WBEBL_NAME); ?></option>
                                            <?php foreach (\wcbef\classes\helpers\Operator::round_items() as $key => $round_item) : ?>
                                                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($round_item); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Sale Date From', WBEBL_NAME); ?></label>
                                        <input type="text" class="wcbef-input-md wcbef-datepicker" placeholder="<?php esc_html_e('Sale Date From ...', WBEBL_NAME); ?>" disabled>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Sale Date To', WBEBL_NAME); ?></label>
                                        <input type="text" class="wcbef-input-md wcbef-datepicker" placeholder="<?php esc_html_e('Sale Date To ...', WBEBL_NAME); ?>" disabled>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Tax Status', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Tax Class', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value="">Select</option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                </div>
                                <div class="wcbef-tab-content-item" data-content="shipping">
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-shipping-class"><?php esc_html_e('Shipping Class', WBEBL_NAME); ?></label>
                                        <select name="" id="wcbef-bulk-edit-form-shipping-class" class="wcbef-input-md" data-field="value">
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            <?php if (!empty($shipping_classes)) : ?>
                                                <?php foreach ($shipping_classes as $shipping_class) : ?>
                                                    <option value="<?php echo esc_attr($shipping_class->term_id); ?>"><?php echo esc_html($shipping_class->name); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-width"><?php esc_html_e('Width', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-width-operator" title="Select">
                                            <?php if (!empty($edit_number_operators)) : ?>
                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <input type="number" placeholder="<?php esc_html_e('Width ...', WBEBL_NAME); ?>" id="wcbef-bulk-edit-form-width" data-field="value">
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-height"><?php esc_html_e('Height', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-height-operator">
                                            <?php if (!empty($edit_number_operators)) : ?>
                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <input type="number" placeholder="<?php esc_html_e('Height ...', WBEBL_NAME); ?>" id="wcbef-bulk-edit-form-height" data-field="value">
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-length"><?php esc_html_e('Length', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-length-operator" title="<?php esc_html_e('Select', WBEBL_NAME); ?>">
                                            <?php if (!empty($edit_number_operators)) : ?>
                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <input type="number" placeholder="<?php esc_html_e('Length ...', WBEBL_NAME); ?>" id="wcbef-bulk-edit-form-length" data-field="value">
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-weight"><?php esc_html_e('Weight', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-weight-operator" title="Select">
                                            <?php if (!empty($edit_number_operators)) : ?>
                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <input type="number" placeholder="<?php esc_html_e('Weight ...', WBEBL_NAME); ?>" id="wcbef-bulk-edit-form-weight" data-field="value">
                                    </div>
                                </div>
                                <div class="wcbef-tab-content-item" data-content="stock">
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-manage-stock"><?php esc_html_e('Manage Stock', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-manage-stock" class="wcbef-input-md" data-field="value">
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            <option value="yes"><?php esc_html_e('Yes', WBEBL_NAME); ?>Yes</option>
                                            <option value="no"><?php esc_html_e('No', WBEBL_NAME); ?>No</option>
                                        </select>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Stock Status', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value="">Select</option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-stock-quantity"><?php esc_html_e('Stock Quantity', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-stock-quantity-operator" title="Select Operator">
                                            <?php if (!empty($edit_number_operators)) : ?>
                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <input type="number" placeholder="<?php esc_html_e('Stock Quantity ...', WBEBL_NAME); ?>" data-field="value" id="wcbef-bulk-edit-form-stock-quantity">
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Allow Backorders', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value="">Select</option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                </div>
                                <div class="wcbef-tab-content-item" data-content="type">
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Product Type', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value="">Select</option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-featured"><?php esc_html_e('Featured', WBEBL_NAME); ?></label>
                                        <select name="" id="wcbef-bulk-edit-form-featured" class="wcbef-input-md" data-field="value">
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                            <option value="yes"><?php esc_html_e('Yes', WBEBL_NAME); ?>Yes</option>
                                            <option value="no"><?php esc_html_e('No', WBEBL_NAME); ?>No</option>
                                        </select>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Virtual', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Downloadable', WBEBL_NAME); ?></label>
                                        <select class="wcbef-input-md" disabled>
                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                        </select>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Download Limit', WBEBL_NAME); ?></label>
                                        <input type="number" class="wcbef-input-md" placeholder="<?php esc_html_e('Download Limit ...', WBEBL_NAME); ?>" disabled>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Download Expiry', WBEBL_NAME); ?></label>
                                        <input type="number" class="wcbef-input-md" placeholder="<?php esc_html_e('Download Expiry ...', WBEBL_NAME); ?>" disabled>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Product URL', WBEBL_NAME); ?></label>
                                        <input type="text" placeholder="Product URL ..." class="wcbef-input-md" disabled>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label><?php esc_html_e('Button Text', WBEBL_NAME); ?></label>
                                        <input type="text" placeholder="<?php esc_html_e('Button Text ...', WBEBL_NAME); ?>" class="wcbef-input-md" disabled>
                                        <span class="wcbef-short-description">Upgrade to pro version</span>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-upsells"><?php esc_html_e('Upsells', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-upsells-operator" title="<?php esc_html_e('Select', WBEBL_NAME); ?>">
                                            <?php if (!empty($edit_taxonomy_operators)) : ?>
                                                <?php foreach ($edit_taxonomy_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <select id="wcbef-bulk-edit-form-upsells" multiple data-field="value" class="wcbef-get-products-ajax wcbef-select2"></select>
                                    </div>
                                    <div class="wcbef-filters-form-group">
                                        <label for="wcbef-bulk-edit-form-cross-sells"><?php esc_html_e('Cross-Sells', WBEBL_NAME); ?></label>
                                        <select id="wcbef-bulk-edit-form-cross-sells-operator" title="<?php esc_html_e('Select', WBEBL_NAME); ?>">
                                            <?php if (!empty($edit_taxonomy_operators)) : ?>
                                                <?php foreach ($edit_taxonomy_operators as $operator_name => $operator_label) : ?>
                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <select id="wcbef-bulk-edit-form-cross-sells" multiple data-field="value" class="wcbef-get-products-ajax wcbef-select2"></select>
                                    </div>
                                </div>
                                <div class="wcbef-tab-content-item" data-content="custom-fields">
                                    <?php if (!empty($meta_fields)) : ?>
                                        <?php foreach ($meta_fields as $custom_field) : ?>
                                            <?php $field_id = "wcbef-bulk-edit-form-custom-field-" . esc_attr($custom_field['key']); ?>
                                            <div class="wcbef-bulk-edit-form-group" data-type="custom_fields" data-taxonomy="<?php echo esc_attr($custom_field['key']); ?>">
                                                <div>
                                                    <label><?php echo esc_html($custom_field['title']); ?></label>
                                                    <?php if (in_array($custom_field['main_type'], \wcbef\classes\repositories\Meta_Field::get_fields_name_have_operator()) || ($custom_field['main_type'] == \wcbef\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == \wcbef\classes\repositories\Meta_Field::STRING_TYPE)) : ?>
                                                        <select title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" data-field="operator">
                                                            <?php if (!empty($edit_text_operators)) : ?>
                                                                <?php foreach ($edit_text_operators as $operator_name => $operator_label) : ?>
                                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input type="text" data-field="value" id="<?php echo esc_attr($field_id); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ..." title="<?php echo esc_attr($custom_field['title']); ?>">
                                                        <?php include "variable.php"; ?>
                                                    <?php elseif ($custom_field['main_type'] == \wcbef\classes\repositories\Meta_Field::TEXTINPUT && $custom_field['sub_type'] == \wcbef\classes\repositories\Meta_Field::NUMBER) : ?>
                                                        <select title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>" data-field="operator">
                                                            <?php if (!empty($edit_number_operators)) : ?>
                                                                <?php foreach ($edit_number_operators as $operator_name => $operator_label) : ?>
                                                                    <option value="<?php echo esc_attr($operator_name); ?>"><?php echo esc_html($operator_label); ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input type="number" class="wcbef-input-md" data-field="value" id="<?php echo esc_attr($field_id); ?>" title="<?php echo esc_attr($custom_field['title']); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ...">
                                                    <?php elseif ($custom_field['main_type'] == \wcbef\classes\repositories\Meta_Field::CHECKBOX) : ?>
                                                        <select id="<?php echo esc_attr($field_id); ?>" data-field="value" title="<?php esc_html_e('Select', WBEBL_NAME); ?> <?php echo esc_attr($custom_field['title']); ?>">
                                                            <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                                            <option value="yes"><?php esc_html_e('Yes', WBEBL_NAME); ?></option>
                                                            <option value="no"><?php esc_html_e('No', WBEBL_NAME); ?></option>
                                                        </select>
                                                    <?php elseif ($custom_field['main_type'] == \wcbef\classes\repositories\Meta_Field::CALENDAR) : ?>
                                                        <input type="text" class="wcbef-input-md wcbef-datepicker" data-field="value" id="<?php echo esc_attr($field_id); ?>" title="<?php echo esc_attr($custom_field['title']); ?>" placeholder="<?php echo esc_attr($custom_field['title']); ?> ...">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="wcbef-alert wcbef-alert-warning">
                                            <span><?php esc_html_e('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', WBEBL_NAME); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wcbef-modal-footer">
                    <button type="button" class="wcbef-button wcbef-button-lg wcbef-button-blue" id="wcbef-bulk-edit-form-do-bulk-edit">
                        <?php esc_html_e('Do Bulk Edit', WBEBL_NAME); ?>
                    </button>
                    <button type="button" class="wcbef-button wcbef-button-lg wcbef-button-white" id="wcbef-bulk-edit-form-reset">
                        <?php esc_html_e('Reset Form', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>