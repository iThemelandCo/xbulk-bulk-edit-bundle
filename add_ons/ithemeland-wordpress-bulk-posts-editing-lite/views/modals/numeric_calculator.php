<div class="wpbel-modal" id="wpbel-modal-numeric-calculator">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-sm">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Calculator', WBEBL_NAME); ?> - <span id="wpbel-modal-numeric-calculator-item-title" class="wpbel-modal-product-title"></span></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <select id="wpbel-numeric-calculator-operator" title="<?php esc_html_e('Select Operator', WBEBL_NAME); ?>">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="replace"><?php esc_html_e('replace', WBEBL_NAME); ?></option>
                        </select>
                        <input type="number" placeholder="<?php esc_html_e('Enter Value ...', WBEBL_NAME); ?>" id="wpbel-numeric-calculator-value" title="<?php esc_html_e('Value', WBEBL_NAME); ?>">
                    </div>
                </div>
                <div class="wpbel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-field-type="" data-toggle="modal-close" class="wpbel-button wpbel-button-blue wpbel-edit-action-numeric-calculator">
                        <?php esc_html_e('Apply Changes', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>