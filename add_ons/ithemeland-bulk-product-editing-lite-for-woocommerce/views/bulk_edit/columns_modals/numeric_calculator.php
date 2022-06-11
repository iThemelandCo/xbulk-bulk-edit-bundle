<div class="wcbef-modal" id="wcbef-modal-numeric-calculator">
    <div class="wcbef-modal-container">
        <div class="wcbef-modal-box wcbef-modal-box-sm">
            <div class="wcbef-modal-content">
                <div class="wcbef-modal-title">
                    <h2><?php esc_html_e('Calculator', WBEBL_NAME); ?> - <span id="wcbef-modal-numeric-calculator-product-title" class="wcbef-modal-product-title"></span></h2>
                    <button type="button" class="wcbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wcbef-modal-body">
                    <div class="wcbef-wrap">
                        <select id="wcbef-numeric-calculator-operator" title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="replace"><?php esc_html_e('replace', WBEBL_NAME); ?></option>
                        </select>
                        <input type="number" placeholder="<?php esc_html_e('Enter Value ...', WBEBL_NAME); ?>" id="wcbef-numeric-calculator-value" title="<?php esc_html_e('Value', WBEBL_NAME); ?>">
                    </div>
                </div>
                <div class="wcbef-modal-footer">
                    <button type="button" data-product-id="" data-field="" data-field-type="" data-toggle="modal-close" class="wcbef-button wcbef-button-blue wcbef-edit-action-numeric-calculator">
                        <?php esc_html_e('Apply Changes', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>