<?php if (!empty($file_id)) : ?>
    <div class="wcbef-modal-select-files-file-item">
        <button type="button" class="wcbef-button wcbef-button-flat wcbef-select-files-sortable-btn" title="<?php esc_html_e('Drag', WBEBL_NAME); ?>">
            <i class="lni lni-menu"></i>
        </button>
        <input type="text" class="wcbef-inline-edit-file-name" placeholder="File Name ..." value="<?php echo !empty($file_item) ? esc_attr($file_item->get_name()) : ''; ?>">
        <input type="text" class="wcbef-inline-edit-file-url wcbef-w60p" id="url-<?php echo esc_attr($file_id); ?>" name="file_url" placeholder="File Url ..." value="<?php echo !empty($file_item) ? esc_attr($file_item->get_file()) : ''; ?>">
        <button type="button" class="wcbef-button wcbef-button-white wcbef-open-uploader wcbef-inline-edit-choose-file" data-type="single" data-target="inline-file" data-id="<?php echo esc_attr($file_id); ?>"><?php esc_html_e('Choose File', WBEBL_NAME); ?></button>
        <button type="button" class="wcbef-button wcbef-button-white wcbef-inline-edit-file-remove-item">x</button>
    </div>
<?php endif; ?>