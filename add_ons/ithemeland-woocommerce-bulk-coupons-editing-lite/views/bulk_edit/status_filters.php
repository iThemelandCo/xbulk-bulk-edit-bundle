<?php if (!empty($coupon_statuses) && is_array($coupon_statuses) && !empty($coupon_counts_by_status) && is_array($coupon_counts_by_status)) : ?>
    <ul>
        <li><a href="javascript:;" data-status="all" class="wccbef-bulk-edit-status-filter-item all"><?php esc_html_e('All', WBEBL_NAME); ?> (<?php echo isset($coupon_counts_by_status['all']) ? intval($coupon_counts_by_status['all']) : 0 ?>)</a></li>
        <?php foreach ($coupon_statuses as $status_key => $status_label) : ?>
            <?php if (isset($coupon_counts_by_status[$status_key])) : ?>
                <li><abbr>|</abbr></li>
                <li><a href="javascript:;" data-status="<?php echo esc_attr($status_key); ?>" class="wccbef-bulk-edit-status-filter-item <?php echo esc_attr($status_key); ?>"><?php echo esc_html($status_label . ' (' . $coupon_counts_by_status[$status_key] . ')'); ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>