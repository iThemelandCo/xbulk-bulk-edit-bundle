<div class="wobef-modal" id="wobef-modal-order-details">
    <div class="wobef-modal-container">
        <div class="wobef-modal-box wobef-modal-box-sm">
            <div class="wobef-modal-content">
                <div class="wobef-modal-title">
                    <h2><?php esc_html_e('Order', WBEBL_NAME); ?> <span id="wobef-modal-order-details-item-title" class="wobef-modal-item-title"></span></h2>
                    <button type="button" class="wobef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                    <div class="wobef-order-details-status" data-order-field="status"></div>
                </div>
                <div class="wobef-modal-body">
                    <div class="wobef-wrap">
                        <div class="wobef-col-half">
                            <h3><?php esc_html_e('Billing Details', WBEBL_NAME); ?></h3>
                            <div class="wobef-mb20">
                                <span data-order-field="billing-address-index"></span>
                            </div>
                            <div class="wobef-mb20">
                                <div><strong><?php esc_html_e('Email', WBEBL_NAME); ?></strong></div>
                                <div data-order-field="billing-email"></div>
                            </div>
                            <div class="wobef-mb20">
                                <div><strong><?php esc_html_e('Phone', WBEBL_NAME); ?></strong></div>
                                <div data-order-field="billing-phone"></div>
                            </div>
                            <div class="wobef-mb20">
                                <div><strong><?php esc_html_e('Payment Via', WBEBL_NAME); ?></strong></div>
                                <span data-order-field="payment-via"></span>
                            </div>
                        </div>
                        <div class="wobef-col-half">
                            <h3><?php esc_html_e('Shipping Details', WBEBL_NAME); ?></h3>
                            <div class="wobef-mb20">
                                <span data-order-field="shipping-address-index"></span>
                            </div>
                            <div class="wobef-mb20">
                                <div><strong><?php esc_html_e('Shipping Method', WBEBL_NAME); ?></strong></div>
                                <span data-order-field="shipping-method"></span>
                            </div>
                        </div>
                        <div class="wobef-order-details-items">
                            <table>
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Product', WBEBL_NAME); ?></th>
                                        <th><?php esc_html_e('Quantity', WBEBL_NAME); ?></th>
                                        <th><?php esc_html_e('Tax', WBEBL_NAME); ?></th>
                                        <th><?php esc_html_e('Total', WBEBL_NAME); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="wobef-modal-footer">
                    <button type="button" class="wobef-button wobef-button-blue" data-toggle="modal-close">
                        <?php esc_html_e('Close', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>