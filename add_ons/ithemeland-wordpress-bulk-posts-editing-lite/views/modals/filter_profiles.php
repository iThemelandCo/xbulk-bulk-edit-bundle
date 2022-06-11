<div class="wpbel-modal" id="wpbel-modal-filter-profiles">
    <div class="wpbel-modal-container">
        <div class="wpbel-modal-box wpbel-modal-box-lg">
            <div class="wpbel-modal-content">
                <div class="wpbel-modal-title">
                    <h2><?php esc_html_e('Filter Profiles', WBEBL_NAME); ?></h2>
                    <button type="button" class="wpbel-modal-close" data-toggle="modal-close">
                        <i class="lni lni-close"></i>
                    </button>
                </div>
                <div class="wpbel-modal-body">
                    <div class="wpbel-wrap">
                        <div class="wpbel-filter-profiles-items wpbel-pb30">
                            <div class="wpbel-table-border-radius">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Profile Name', WBEBL_NAME); ?></th>
                                            <th><?php esc_html_e('Date Modified', WBEBL_NAME); ?></th>
                                            <th><?php esc_html_e('Use Always', WBEBL_NAME); ?></th>
                                            <th><?php esc_html_e('Actions', WBEBL_NAME); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($filters_preset)) : ?>
                                            <?php foreach ($filters_preset as $filter_item) : ?>
                                                <?php include "filter_profile_item.php"; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>