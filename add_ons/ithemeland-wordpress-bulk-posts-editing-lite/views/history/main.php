<div class="wpbel-wrap">
    <div class="wpbel-tab-middle-content">
        <div class="wpbel-alert wpbel-alert-default">
            <span><?php esc_html_e('List of your changes and possible to roll back to the previous data', WBEBL_NAME); ?></span>
        </div>
        <div class="wpbel-alert wpbel-alert-danger">
            <span class="wpbel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
            <a href="<?php echo esc_url(WPBEL_UPGRADE_URL); ?>"><?php echo esc_html(WPBEL_UPGRADE_TEXT); ?></a>
        </div>
        <div class="wpbel-history-filter">
            <div class="wpbel-history-filter-fields">
                <div class="wpbel-history-filter-field-item">
                    <label for="wpbel-history-filter-operation"><?php esc_html_e('Operation', WBEBL_NAME); ?></label>
                    <select id="wpbel-history-filter-operation">
                        <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        <?php if (!empty($history_types = \wpbel\classes\repositories\History::get_operation_types())) : ?>
                            <?php foreach ($history_types as $history_type_key => $history_type_label) : ?>
                                <option value="<?php echo esc_attr($history_type_key); ?>"><?php echo esc_html($history_type_label); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="wpbel-history-filter-field-item">
                    <label for="wpbel-history-filter-author"><?php esc_html_e('Author', WBEBL_NAME); ?></label>
                    <select id="wpbel-history-filter-author">
                        <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        <?php if (!empty($users)) : ?>
                            <?php foreach ($users as $user_item) : ?>
                                <option value="<?php echo esc_attr($user_item->ID); ?>"><?php echo esc_html($user_item->user_login); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="wpbel-history-filter-field-item">
                    <label for="wpbel-history-filter-fields"><?php esc_html_e('Fields', WBEBL_NAME); ?></label>
                    <input type="text" id="wpbel-history-filter-fields" placeholder="for example: post_title,post_content,post_status">
                </div>
                <div class="wpbel-history-filter-field-item wpbel-history-filter-field-date">
                    <label><?php esc_html_e('Date', WBEBL_NAME); ?></label>
                    <input type="text" id="wpbel-history-filter-date-from" class="wpbel-datepicker wpbel-date-from" data-to-id="wpbel-history-filter-date-to" placeholder="<?php esc_html_e('From ...', WBEBL_NAME); ?>">
                    <input type="text" id="wpbel-history-filter-date-to" class="wpbel-datepicker" placeholder="<?php esc_html_e('To ...', WBEBL_NAME); ?>">
                </div>
            </div>
            <div class="wpbel-history-filter-buttons">
                <div class="wpbel-history-filter-buttons-left">
                    <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-blue" id="wpbel-history-filter-apply">
                        <i class="lni lni-funnel"></i>
                        <span><?php esc_html_e('Apply Filters', WBEBL_NAME); ?></span>
                    </button>
                    <button type="button" class="wpbel-button wpbel-button-lg wpbel-button-gray" id="wpbel-history-filter-reset">
                        <i class="lni lni-spinner-arrow"></i>
                        <span><?php esc_html_e('Reset Filters', WBEBL_NAME); ?></span>
                    </button>
                </div>
                <div class="wpbel-history-filter-buttons-right">
                    <button type="button" name="clear_all" value="1" id="wpbel-history-clear-all-btn" class="wpbel-button wpbel-button-lg wpbel-button-red" disabled="disabled">
                        <i class="lni lni-trash"></i>
                        <span><?php esc_html_e('Clear History', WBEBL_NAME); ?></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="wpbel-history-items">
            <h3><?php esc_html_e('Column(s)', WBEBL_NAME); ?></h3>
            <div class="wpbel-table-border-radius">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php esc_html_e('History Name', WBEBL_NAME); ?></th>
                            <th><?php esc_html_e('Author', WBEBL_NAME); ?></th>
                            <th class="wpbel-mw125"><?php esc_html_e('Date Modified', WBEBL_NAME); ?></th>
                            <th class="wpbel-mw250"><?php esc_html_e('Actions', WBEBL_NAME); ?></th>
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