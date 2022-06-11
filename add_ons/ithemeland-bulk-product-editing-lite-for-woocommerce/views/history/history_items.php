<?php if (!empty($histories)) : ?>
    <?php $i = 1; ?>
    <?php foreach ($histories as $history) : ?>
        <?php $user_data = get_userdata(intval($history->user_id)); ?>
        <tr>
            <td><?php echo intval($i); ?></td>
            <td>
                <span class="wcbef-history-name wcbef-fw600">
                    <?php switch ($history->operation_type) {
                        case 'inline':
                            $item = (new wcbef\classes\repositories\History())->get_history_items($history->id);
                            echo (!empty($item[0]->post_title)) ? esc_html($item[0]->post_title) : 'Inline Operation';
                            break;
                        case 'bulk':
                            echo 'Bulk Operation';
                            break;
                    }
                    ?>
                </span>
                <?php
                $fields = '';
                if (is_array(unserialize($history->fields)) && !empty(unserialize($history->fields))) {
                    foreach (unserialize($history->fields) as $field) {
                        $fields .= "[" . esc_html($field) . "]";
                    }
                }
                ?>
                <span class="wcbef-history-text-sm"><?php echo esc_html($fields); ?></span>
            </td>
            <td class="wcbef-fw600"><?php echo (!empty($user_data)) ? esc_html($user_data->user_login) : ''; ?></td>
            <td class="wcbef-fw600"><?php echo esc_html(date('Y / m / d', strtotime($history->operation_date))); ?></td>
            <td>
                <button type="button" class="wcbef-button wcbef-button-blue wcbef-history-revert-item" disabled="disabled">
                    <i class="lni lni-spinner-arrow"></i>
                    <?php esc_html_e('Revert', WBEBL_NAME); ?>
                </button>
                <button type="button" class="wcbef-button wcbef-button-red wcbef-history-delete-item" disabled="disabled">
                    <i class="lni lni-trash"></i>
                    <?php esc_html_e('Delete', WBEBL_NAME); ?>
                </button>
            </td>
        </tr>
        <?php $i++; ?>
    <?php endforeach; ?>
<?php endif; ?>