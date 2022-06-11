<div class="wobef-modal" id="wobef-modal-order-shipping">
    <div class="wobef-modal-container">
        <div class="wobef-modal-box wobef-modal-box-sm">
            <div class="wobef-modal-content">
                <div class="wobef-modal-title">
                    <h2><?php esc_html_e('Order', WBEBL_NAME); ?> <span id="wobef-modal-order-shipping-item-title" class="wobef-modal-item-title"></span></h2>
                    <button type="button" class="wobef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wobef-modal-body">
                    <div class="wobef-wrap">
                        <div class="wobef-col-full wobef-mb20">
                            <h3><?php esc_html_e('Shipping', WBEBL_NAME); ?></h3>
                            <a href="javascript:;" class="wobef-modal-load-shipping-address" data-target="#wobef-modal-order-shipping" data-order-field="customer-user-id" data-customer-id=""><?php esc_html_e('Load shipping address', WBEBL_NAME); ?></a>
                            <span> | </span>
                            <a href="javascript:;" href="javascript:;" class="wobef-modal-load-billing-address" data-target="#wobef-modal-order-shipping" data-order-field="customer-user-id" data-customer-id=""><?php esc_html_e('Copy billing address', WBEBL_NAME); ?></a>
                        </div>
                        <div class="wobef-col-half">
                            <div class="wobef-mb10">
                                <label for="order-shipping-modal-first-name"><?php esc_html_e('First Name', WBEBL_NAME); ?></label>
                                <input type="text" id="order-shipping-modal-first-name" data-order-field="first-name">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-shipping-modal-address-1"><?php esc_html_e('Address 1', WBEBL_NAME); ?></label>
                                <input type="text" id="order-shipping-modal-address-1" data-order-field="address-1">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-shipping-modal-city"><?php esc_html_e('City', WBEBL_NAME); ?></label>
                                <input type="text" id="order-shipping-modal-city" data-order-field="city">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-shipping-modal-country"><?php esc_html_e('Country', WBEBL_NAME); ?></label>
                                <select type="text" id="order-shipping-modal-country" class="wobef-order-country" data-state-target="#wobef-modal-order-shipping-state" data-order-field="country">
                                    <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                    <?php if (!empty($shipping_countries) && is_array($shipping_countries)) : ?>
                                        <?php foreach ($shipping_countries as $shipping_country_key => $shipping_country_label) : ?>
                                            <option value="<?php echo esc_attr($shipping_country_key); ?>"><?php echo esc_html($shipping_country_label); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="wobef-col-half">
                            <div class="wobef-mb10">
                                <label for="order-shipping-modal-last-name"><?php esc_html_e('Last Name', WBEBL_NAME); ?></label>
                                <input type="text" id="order-shipping-modal-last-name" data-order-field="last-name">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-shipping-modal-address-2"><?php esc_html_e('Address 2', WBEBL_NAME); ?></label>
                                <input type="text" id="order-shipping-modal-address-2" data-order-field="address-2">
                            </div>
                            <div class="wobef-mb10">
                                <label for="wobef-modal-order-shipping-state"><?php esc_html_e('State', WBEBL_NAME); ?></label>
                                <select id="wobef-modal-order-shipping-state" data-order-field="state">
                                    <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                </select>
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-shipping-modal-postcode"><?php esc_html_e('Postcode', WBEBL_NAME); ?></label>
                                <input type="text" id="order-shipping-modal-postcode" data-order-field="postcode">
                            </div>
                        </div>
                        <div class="wobef-col-full wobef-mb20">
                            <div class="wobef-mb10">
                                <label for="order-shipping-modal-company"><?php esc_html_e('Company', WBEBL_NAME); ?></label>
                                <input type="text" id="order-shipping-modal-company" data-order-field="company">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-shipping-modal-customer-note"><?php esc_html_e('Customer Note', WBEBL_NAME); ?></label>
                                <textarea id="order-shipping-modal-customer-note" data-order-field="customer-note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wobef-modal-footer">
                    <button type="button" class="wobef-button wobef-button-blue wobef-modal-order-shipping-save-changes-button" data-toggle="modal-close">
                        <?php esc_html_e('Save Changes', WBEBL_NAME); ?>
                    </button>
                    <button type="button" class="wobef-button wobef-button-gray wobef-float-right" data-toggle="modal-close">
                        <?php esc_html_e('Close', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>