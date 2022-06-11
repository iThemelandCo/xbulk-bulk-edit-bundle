<div class="wobef-modal" id="wobef-modal-order-billing">
    <div class="wobef-modal-container">
        <div class="wobef-modal-box wobef-modal-box-sm">
            <div class="wobef-modal-content">
                <div class="wobef-modal-title">
                    <h2><?php esc_html_e('Order', WBEBL_NAME); ?> <span id="wobef-modal-order-billing-item-title" class="wobef-modal-item-title"></span></h2>
                    <button type="button" class="wobef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wobef-modal-body">
                    <div class="wobef-wrap">
                        <div class="wobef-col-full wobef-mb20">
                            <h3><?php esc_html_e('Billing', WBEBL_NAME); ?></h3>
                            <a href="javascript:;" data-target="#wobef-modal-order-billing" data-order-field="customer-user-id" data-customer-id="" class="wobef-modal-load-billing-address"><?php esc_html_e('Load billing address', WBEBL_NAME); ?></a>
                        </div>
                        <div class="wobef-col-half">
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-first-name"><?php esc_html_e('First Name', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-first-name" data-order-field="first-name">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-address-1"><?php esc_html_e('Address 1', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-address-1" data-order-field="address-1">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-city"><?php esc_html_e('City', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-city" data-order-field="city">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-country"><?php esc_html_e('Country', WBEBL_NAME); ?></label>
                                <select id="order-billing-modal-country" class="wobef-order-country" data-state-target="#wobef-modal-order-billing-state" data-order-field="country">
                                    <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                    <?php if (!empty($shipping_countries) && is_array($shipping_countries)) : ?>
                                        <?php foreach ($shipping_countries as $shipping_country_key => $shipping_country_label) : ?>
                                            <option value="<?php echo esc_attr($shipping_country_key); ?>"><?php echo esc_html($shipping_country_label); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-email"><?php esc_html_e('Email', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-email" data-order-field="email">
                            </div>
                        </div>
                        <div class="wobef-col-half">
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-last-name"><?php esc_html_e('Last Name', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-last-name" data-order-field="last-name">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-address-2"><?php esc_html_e('Address 2', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-address-2" data-order-field="address-2">
                            </div>
                            <div class="wobef-mb10">
                                <label for="wobef-modal-order-billing-state"><?php esc_html_e('State', WBEBL_NAME); ?></label>
                                <select id="wobef-modal-order-billing-state" data-order-field="state">
                                    <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                </select>
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-postcode"><?php esc_html_e('Postcode', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-postcode" data-order-field="postcode">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-phone"><?php esc_html_e('Phone', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-phone" data-order-field="phone">
                            </div>
                        </div>
                        <div class="wobef-col-full wobef-mb20">
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-company"><?php esc_html_e('Company', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-company" data-order-field="company">
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-payment-method"><?php esc_html_e('Payment Method', WBEBL_NAME); ?></label>
                                <select id="order-billing-modal-payment-method" data-order-field="payment-method">
                                    <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                                    <?php if (!empty($payment_methods) && is_array($payment_methods)) : ?>
                                        <?php foreach ($payment_methods as $payment_method_key => $payment_method_title) : ?>
                                            <option value="<?php echo esc_attr($payment_method_key); ?>"><?php echo esc_html($payment_method_title); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <option value="other"><?php esc_html_e('Other', WBEBL_NAME); ?></option>
                                </select>
                            </div>
                            <div class="wobef-mb10">
                                <label for="order-billing-modal-transaction-id"><?php esc_html_e('Transaction ID', WBEBL_NAME); ?></label>
                                <input type="text" id="order-billing-modal-transaction-id" data-order-field="transaction-id">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wobef-modal-footer">
                    <button type="button" class="wobef-button wobef-button-blue wobef-modal-order-billing-save-changes-button" data-toggle="modal-close">
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