<div class="wccbef-wrap">
    <div class="wccbef-tab-middle-content">
        <div class="wccbef-alert wccbef-alert-default">
            <span><?php esc_html_e('List of your changes and possible to roll back to the previous data', WBEBL_NAME); ?></span>
        </div>
        <div class="wccbef-alert wccbef-alert-danger">
            <span class="wccbef-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
            <a href="<?php echo esc_url(WCCBEF_UPGRADE_URL); ?>"><?php echo esc_html(WCCBEF_UPGRADE_TEXT); ?></a>
        </div>
        <?php if (!empty($flush_message) && is_array($flush_message) && $flush_message['hash'] == 'history') : ?>
            <?php include WCCBEF_VIEWS_DIR . "alerts/flush_message.php"; ?>
        <?php endif; ?>
        <div class="wccbef-history-filter">
            <div class="wccbef-history-filter-fields">
                <div class="wccbef-history-filter-field-item">
                    <label for="wccbef-history-filter-operation"><?php esc_html_e('Operation', WBEBL_NAME); ?></label>
                    <select id="wccbef-history-filter-operation">
                        <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        <?php if (!empty($history_types = \wccbef\classes\repositories\History::get_operation_types())) : ?>
                            <?php foreach ($history_types as $history_type_key => $history_type_label) : ?>
                                <option value="<?php echo esc_attr($history_type_key); ?>"><?php echo esc_html($history_type_label); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="wccbef-history-filter-field-item">
                    <label for="wccbef-history-filter-author"><?php esc_html_e('Author', WBEBL_NAME); ?></label>
                    <select id="wccbef-history-filter-author">
                        <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        <?php if (!empty($users)) : ?>
                            <?php foreach ($users as $user_item) : ?>
                                <option value="<?php echo esc_attr($user_item->ID); ?>"><?php echo esc_html($user_item->user_login); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="wccbef-history-filter-field-item">
                    <label for="wccbef-history-filter-fields"><?php esc_html_e('Fields', WBEBL_NAME); ?></label>
                    <input type="text" id="wccbef-history-filter-fields" placeholder="for example: ID">
                </div>
                <div class="wccbef-history-filter-field-item wccbef-history-filter-field-date">
                    <label><?php esc_html_e('Date', WBEBL_NAME); ?></label>
                    <input type="text" id="wccbef-history-filter-date-from" class="wccbef-datepicker wccbef-date-from" data-to-id="wccbef-history-filter-date-to" placeholder="<?php esc_html_e('From ...', WBEBL_NAME); ?>">
                    <input type="text" id="wccbef-history-filter-date-to" class="wccbef-datepicker" placeholder="<?php esc_html_e('To ...', WBEBL_NAME); ?>">
                </div>
            </div>
            <div class="wccbef-history-filter-buttons">
                <div class="wccbef-history-filter-buttons-left">
                    <button type="button" class="wccbef-button wccbef-button-lg wccbef-button-blue" disabled="disabled">
                        <i class="lni lni-funnel"></i>
                        <span><?php esc_html_e('Apply Filters', WBEBL_NAME); ?></span>
                    </button>
                    <button type="button" class="wccbef-button wccbef-button-lg wccbef-button-gray" disabled="disabled">
                        <i class="lni lni-spinner-arrow"></i>
                        <span><?php esc_html_e('Reset Filters', WBEBL_NAME); ?></span>
                    </button>
                </div>
                <div class="wccbef-history-filter-buttons-right">
                    <button type="button" name="clear_all" value="1" disabled="disabled" class="wccbef-button wccbef-button-lg wccbef-button-red">
                        <i class="lni lni-trash"></i>
                        <span><?php esc_html_e('Clear History', WBEBL_NAME); ?></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="wccbef-history-items">
            <h3><?php esc_html_e('Column(s)', WBEBL_NAME); ?></h3>
            <div class="wccbef-table-border-radius">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php esc_html_e('History Name', WBEBL_NAME); ?></th>
                            <th><?php esc_html_e('Author', WBEBL_NAME); ?></th>
                            <th class="wccbef-mw125"><?php esc_html_e('Date Modified', WBEBL_NAME); ?></th>
                            <th class="wccbef-mw250"><?php esc_html_e('Actions', WBEBL_NAME); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'history_items.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>