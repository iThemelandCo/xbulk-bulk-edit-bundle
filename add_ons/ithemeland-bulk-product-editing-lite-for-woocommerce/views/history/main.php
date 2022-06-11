<div class="wcbef-wrap">
    <div class="wcbef-tab-middle-content">
        <div class="wcbef-alert wcbef-alert-default">
            <span><?php esc_html_e('List of your changes and possible to roll back to the previous data', WBEBL_NAME); ?></span>
        </div>
        <div class="wcbef-alert wcbef-alert-danger">
            <span class="wcbef-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
            <a href="<?php echo esc_url(WCBEF_UPGRADE_URL); ?>"><?php echo esc_html(WCBEF_UPGRADE_TEXT); ?></a>
        </div>
        <div class="wcbef-history-filter">
            <div class="wcbef-history-filter-fields">
                <div class="wcbef-history-filter-field-item">
                    <label for="wcbef-history-filter-operation"><?php esc_html_e('Operation', WBEBL_NAME); ?></label>
                    <select id="wcbef-history-filter-operation">
                        <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        <?php if (!empty($history_types = \wcbef\classes\repositories\History::get_operation_types())) : ?>
                            <?php foreach ($history_types as $history_type_key => $history_type_label) : ?>
                                <option value="<?php echo esc_attr($history_type_key); ?>"><?php echo esc_html($history_type_label); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="wcbef-history-filter-field-item">
                    <label for="wcbef-history-filter-author"><?php esc_html_e('Author', WBEBL_NAME); ?></label>
                    <select id="wcbef-history-filter-author">
                        <option value=""><?php esc_html_e('Select', WBEBL_NAME); ?></option>
                        <?php if (!empty($users)) : ?>
                            <?php foreach ($users as $user_item) : ?>
                                <option value="<?php echo esc_attr($user_item->ID); ?>"><?php echo esc_html($user_item->user_login); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="wcbef-history-filter-field-item">
                    <label for="wcbef-history-filter-fields"><?php esc_html_e('Fields', WBEBL_NAME); ?></label>
                    <input type="text" id="wcbef-history-filter-fields" placeholder="for example: post_title,post_content,post_status">
                </div>
                <div class="wcbef-history-filter-field-item wcbef-history-filter-field-date">
                    <label><?php esc_html_e('Date', WBEBL_NAME); ?></label>
                    <input type="text" id="wcbef-history-filter-date-from" class="wcbef-datepicker wcbef-date-from" data-to-id="wcbef-history-filter-date-to" placeholder="<?php esc_html_e('From ...', WBEBL_NAME); ?>">
                    <input type="text" id="wcbef-history-filter-date-to" class="wcbef-datepicker" placeholder="<?php esc_html_e('To ...', WBEBL_NAME); ?>">
                </div>
            </div>
            <div class="wcbef-history-filter-buttons">
                <div class="wcbef-history-filter-buttons-left">
                    <button type="button" class="wcbef-button wcbef-button-lg wcbef-button-blue" id="wcbef-history-filter-apply">
                        <i class="lni lni-funnel"></i>
                        <span><?php esc_html_e('Apply Filters', WBEBL_NAME); ?></span>
                    </button>
                    <button type="button" class="wcbef-button wcbef-button-lg wcbef-button-gray" id="wcbef-history-filter-reset">
                        <i class="lni lni-spinner-arrow"></i>
                        <span><?php esc_html_e('Reset Filters', WBEBL_NAME); ?></span>
                    </button>
                </div>
                <div class="wcbef-history-filter-buttons-right">
                    <input type="hidden" name="action" value="wcbef_clear_all_history">
                    <button type="button" class="wcbef-button wcbef-button-lg wcbef-button-red" disabled="disabled">
                        <i class="lni lni-trash"></i>
                        <span><?php esc_html_e('Clear History', WBEBL_NAME); ?></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="wcbef-history-items">
            <h3><?php esc_html_e('Column(s)', WBEBL_NAME); ?></h3>
            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wcbef-history-items">
                <input type="hidden" name="action" value="wcbef_history_action">
                <input type="hidden" name="" value="" id="wcbef-history-clicked-id">
                <div class="wcbef-table-border-radius">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php esc_html_e('History Name', WBEBL_NAME); ?></th>
                                <th><?php esc_html_e('Author', WBEBL_NAME); ?></th>
                                <th class="wcbef-mw125"><?php esc_html_e('Date Modified', WBEBL_NAME); ?></th>
                                <th class="wcbef-mw250"><?php esc_html_e('Actions', WBEBL_NAME); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include 'history_items.php'; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>