<div class="wccbef-modal" id="wccbef-modal-text-editor">
    <div class="wccbef-modal-container">
        <div class="wccbef-modal-box wccbef-modal-box-lg">
            <div class="wccbef-modal-content">
                <div class="wccbef-modal-title">
                    <h2><?php esc_html_e('Content Edit', WBEBL_NAME); ?> - <span id="wccbef-modal-text-editor-item-title" class="wccbef-modal-item-title"></span></h2>
                    <button type="button" class="wccbef-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wccbef-modal-body">
                    <div class="wccbef-wrap">
                        <?php wp_editor("", 'wccbef-text-editor'); ?>
                    </div>
                </div>
                <div class="wccbef-modal-footer">
                    <button type="button" data-field="" data-item-id="" data-content-type="textarea" id="wccbef-text-editor-apply" class="wccbef-button wccbef-button-blue wccbef-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', WBEBL_NAME); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>