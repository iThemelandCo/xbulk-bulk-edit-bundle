"use strict";


function wccbefOpenTab(item) {
    let wccbefTabItem = item;
    let wccbefParentContent = wccbefTabItem.closest(".wccbef-tabs-list");
    let wccbefParentContentID = wccbefParentContent.attr("data-content-id");
    let wccbefDataBox = wccbefTabItem.attr("data-content");
    wccbefParentContent.find("li a.selected").removeClass("selected");
    wccbefTabItem.addClass("selected");
    jQuery("#" + wccbefParentContentID).children("div.selected").removeClass("selected");
    jQuery("#" + wccbefParentContentID + " div[data-content=" + wccbefDataBox + "]").addClass("selected");
    if (item.attr("data-type") === "main-tab") {
        wccbefFilterFormClose();
    }
}

function wccbefCloseModal() {
    let lastModalOpened = jQuery('#wccbef-last-modal-opened');
    if (lastModalOpened.val() !== '') {
        jQuery(lastModalOpened.val() + ' .wccbef-modal-box').fadeOut();
        jQuery(lastModalOpened.val()).fadeOut();
        lastModalOpened.val('');
    } else {
        jQuery('.wccbef-modal-box').fadeOut();
        jQuery('.wccbef-modal').fadeOut();
    }
}

function wccbefReInitColorPicker() {
    if (jQuery('.wccbef-color-picker').length > 0) {
        jQuery('.wccbef-color-picker').wpColorPicker();
    }
    if (jQuery('.wccbef-color-picker-field').length > 0) {
        jQuery('.wccbef-color-picker-field').wpColorPicker();
    }
}

function wccbefReInitDatePicker() {
    if (jQuery.fn.datetimepicker) {
        jQuery('.wccbef-datepicker').datetimepicker('destroy');
        jQuery('.wccbef-timepicker').datetimepicker('destroy');
        jQuery('.wccbef-datetimepicker').datetimepicker('destroy');

        jQuery('.wccbef-datepicker').datetimepicker({
            timepicker: false,
            format: 'Y/m/d',
            scrollMonth: false,
            scrollInput: false
        });


        jQuery('.wccbef-timepicker').datetimepicker({
            datepicker: false,
            format: 'H:i',
            scrollMonth: false,
            scrollInput: false
        });

        jQuery('.wccbef-datetimepicker').datetimepicker({
            format: 'Y/m/d H:i',
            scrollMonth: false,
            scrollInput: false
        });
    }

}

function wccbefPaginationLoadingStart() {
    jQuery('.wccbef-pagination-loading').show();
}

function wccbefPaginationLoadingEnd() {
    jQuery('.wccbef-pagination-loading').hide();
}

function wccbefLoadingStart() {
    jQuery('#wccbef-loading').removeClass('wccbef-loading-error').removeClass('wccbef-loading-success').text('Loading ...').slideDown(300);
}

function wccbefLoadingSuccess(message = 'Success !') {
    jQuery('#wccbef-loading').removeClass('wccbef-loading-error').addClass('wccbef-loading-success').text(message).delay(1500).slideUp(200);
}

function wccbefLoadingError(message = 'Error !') {
    jQuery('#wccbef-loading').removeClass('wccbef-loading-success').addClass('wccbef-loading-error').text(message).delay(1500).slideUp(200);
}

function wccbefSetColorPickerTitle() {
    jQuery('.wccbef-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wccbef-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wccbefSetTipsyTooltip();
    });
}

function wccbefFilterFormClose() {
    if (jQuery('#wccbef-filter-form-content').attr('data-visibility') === 'visible') {
        jQuery('.wccbef-filter-form-icon').addClass('lni-chevron-down').removeClass('lni lni-chevron-up');
        jQuery('#wccbef-filter-form-content').slideUp(200).attr('data-visibility', 'hidden');
    }
}

function wccbefFilterFormOpen() {
    if (jQuery('#wccbef-filter-form-content').attr('data-visibility') === 'hidden') {
        jQuery('.wccbef-filter-form-icon').removeClass('lni lni-chevron-down').addClass('lni lni-chevron-up');
        jQuery('#wccbef-filter-form-content').slideDown(200).attr('data-visibility', 'visible');
    }
}

function wccbefSetTipsyTooltip() {
    jQuery('[title]').tipsy({
        html: true,
        arrowWidth: 10, //arrow css border-width * 2, default is 5 * 2
        attr: 'data-tipsy',
        cls: null,
        duration: 150,
        offset: 7,
        position: 'top-center',
        trigger: 'hover',
        onShow: null,
        onHide: null
    });
}

function wccbefCheckUndoRedoStatus(reverted, history) {
    if (reverted) {
        wccbefEnableRedo();
    } else {
        wccbefDisableRedo();
    }
    if (history) {
        wccbefEnableUndo();
    } else {
        wccbefDisableUndo();
    }
}

function wccbefDisableUndo() {
    jQuery('#wccbef-bulk-edit-undo').attr('disabled', 'disabled');
}

function wccbefEnableUndo() {
    jQuery('#wccbef-bulk-edit-undo').prop('disabled', false);
}

function wccbefDisableRedo() {
    jQuery('#wccbef-bulk-edit-redo').attr('disabled', 'disabled');
}

function wccbefEnableRedo() {
    jQuery('#wccbef-bulk-edit-redo').prop('disabled', false);
}

function wccbefHideSelectionTools() {
    jQuery('.wccbef-bulk-edit-form-selection-tools').hide();
}

function wccbefShowSelectionTools() {
    jQuery('.wccbef-bulk-edit-form-selection-tools').show();
}

function wccbefSetColorPickerTitle() {
    jQuery('.wccbef-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wccbef-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wccbefSetTipsyTooltip();
    });
}

function wccbefColumnManagerAddField(fieldName, fieldLabel, action) {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wccbef_column_manager_add_field',
            field_name: fieldName,
            field_label: fieldLabel,
            field_action: action
        },
        success: function (response) {
            jQuery('.wccbef-box-loading').hide();
            jQuery('.wccbef-column-manager-added-fields[data-action=' + action + '] .items').append(response);
            fieldName.forEach(function (name) {
                jQuery('.wccbef-column-manager-available-fields[data-action=' + action + '] input:checkbox[data-name=' + name + ']').prop('checked', false).closest('li').attr('data-added', 'true').hide();
            });
            wccbefReInitColorPicker();
            jQuery('.wccbef-column-manager-check-all-fields-btn[data-action=' + action + '] input:checkbox').prop('checked', false);
            jQuery('.wccbef-column-manager-check-all-fields-btn[data-action=' + action + '] span').removeClass('selected').text('Select All');
            setTimeout(function () {
                wccbefSetColorPickerTitle();
            }, 250);
        },
        error: function () {
        }
    })
}

function wccbefAddMetaKeysManual(meta_key_name) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wccbef_add_meta_keys_manual',
            meta_key_name: meta_key_name,
        },
        success: function (response) {
            jQuery('#wccbef-meta-fields-items').append(response);
            wccbefLoadingSuccess();
        },
        error: function () {
            wccbefLoadingError();
        }
    })
}

function wccbefAddACFMetaField(field_name, field_label, field_type) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wccbef_add_acf_meta_field',
            field_name: field_name,
            field_label: field_label,
            field_type: field_type
        },
        success: function (response) {
            jQuery('#wccbef-meta-fields-items').append(response);
            wccbefLoadingSuccess();
        },
        error: function () {
            wccbefLoadingError();
        }
    })
}

function wccbefCheckFilterFormChanges() {
    let isChanged = false;
    jQuery('#wccbef-filter-form-content [data-field=value]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#wccbef-filter-form-content [data-field=from]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#wccbef-filter-form-content [data-field=to]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });

    jQuery('#filter-form-changed').val(isChanged);

    if (isChanged === true) {
        jQuery('#wccbef-bulk-edit-reset-filter').show();
    } else {
        jQuery('.wccbef-top-nav-status-filter a[data-status="all"]').addClass('active');
    }
}

function wccbefGetCheckedItem() {
    let itemIds;
    let itemsChecked = jQuery("input.wccbef-check-item:checkbox:checked");
    if (itemsChecked.length > 0) {
        itemIds = itemsChecked.map(function (i) {
            return jQuery(this).val();
        }).get();
    }

    return itemIds;
}

function wccbefInlineEdit(couponsIDs, field, value, reload = false) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_inline_edit',
            coupons_ids: couponsIDs,
            field: field,
            value: value
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    wccbefReloadCoupons(response.edited_ids);
                } else {
                    wccbefLoadingSuccess('Success !')
                }
                wccbefCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wccbef-history-items tbody').html(response.history_items);
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    })
}

function wccbefReloadCoupons(edited_ids = [], current_page = wccbefGetCurrentPage()) {
    let data = wccbefGetCurrentFilterData();
    wccbefCouponsFilter(data, 'pro_search', edited_ids, current_page);
}

function wccbefCouponsFilter(data, action, edited_ids = null, page = wccbefGetCurrentPage()) {
    // clear selected coupons in export tab
    jQuery('#wccbef-export-items-selected').html('');

    if (action === 'pagination') {
        wccbefPaginationLoadingStart();
    } else {
        wccbefLoadingStart();
    }
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_coupons_filter',
            filter_data: data,
            current_page: page,
            search_action: action,
        },
        success: function (response) {
            if (response.success) {
                wccbefLoadingSuccess();
                wccbefSetCouponsList(response, edited_ids)
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefSetCouponsList(response, edited_ids = null) {
    jQuery('#wccbef-items-table').html(response.coupons_list);
    jQuery('.wccbef-items-pagination').html(response.pagination);
    jQuery('.wccbef-top-nav-status-filter').html(response.status_filters);

    let currentPage = wccbefGetCurrentPage();
    let countPerPage = jQuery('#wccbef-quick-per-page').val();
    let showingTo = parseInt(currentPage * countPerPage);
    let showingFrom = parseInt(showingTo - countPerPage) + 1;
    showingTo = (showingTo < response.coupons_count) ? showingTo : response.coupons_count;
    jQuery('.wccbef-items-count').html("Showing " + showingFrom + " to " + showingTo + " of " + response.coupons_count + " entries");

    jQuery('.wccbef-bulk-edit-status-filter-item').removeClass('active');
    let statusFilter = (jQuery('#wccbef-filter-form-coupon-status').val()) ? jQuery('#wccbef-filter-form-coupon-status').val() : 'all';
    if (jQuery.isArray(statusFilter)) {
        statusFilter.forEach(function (val) {
            jQuery('.wccbef-bulk-edit-status-filter-item[data-status="' + val + '"]').addClass('active');
        });
    } else {
        jQuery('.wccbef-bulk-edit-status-filter-item[data-status="' + statusFilter + '"]').addClass('active');
    }

    wccbefReInitDatePicker();
    wccbefReInitColorPicker();

    if (edited_ids && edited_ids.length > 0) {
        jQuery('tr').removeClass('wccbef-item-edited');
        edited_ids.forEach(function (couponID) {
            jQuery('tr[data-item-id=' + couponID + ']').addClass('wccbef-item-edited');
            jQuery('input[value=' + couponID + ']').prop('checked', true);
        });
        wccbefShowSelectionTools();
    }

    wccbefSetTipsyTooltip();
    setTimeout(function () {
        let maxHeightScrollWrapper = jQuery('.scroll-wrapper > .scroll-content').css('max-height');
        jQuery('.scroll-wrapper > .scroll-content').css({
            'max-height': (parseInt(maxHeightScrollWrapper) + 5)
        });

        let actionColumn = jQuery('td.wccbef-action-column');
        if (actionColumn.length > 0) {
            actionColumn.each(function () {
                jQuery(this).css({
                    "min-width": (parseInt(jQuery(this).find('a').length) * 45)
                })
            });
        }
    }, 500);
}

function wccbefGetCouponData(couponID) {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_get_coupon_data',
            coupon_id: couponID
        },
        success: function (response) {
            if (response.success) {
                wccbefSetCouponDataBulkEditForm(response.coupon_data);
            } else {

            }
        },
        error: function () {

        }
    });
}

function wccbefEditByCalculator(couponIDs, field, values) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_edit_by_calculator',
            coupon_ids: couponIDs,
            field: field,
            operator: values.operator,
            value: values.value,
            operator_type: values.operator_type,
            round_item: values.roundItem,
        },
        success: function (response) {
            if (response.success) {
                wccbefReloadCoupons(response.edited_ids);
            }
            wccbefCheckUndoRedoStatus(response.reverted, response.history_items);
            jQuery('.wccbef-history-items tbody').html(response.history_items);
        },
        error: function () {
            wccbefLoadingError();
        }
    })
}

function wccbefDeleteCoupon(couponIDs, deleteType) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_delete_coupons',
            coupon_ids: couponIDs,
            delete_type: deleteType,
        },
        success: function (response) {
            if (response.success) {
                wccbefReloadCoupons(response.edited_ids, wccbefGetCurrentPage());
                wccbefHideSelectionTools();
                wccbefCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wccbef-history-items tbody').html(response.history_items);
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefDuplicateCoupon(couponIDs, duplicateNumber) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_duplicate_coupon',
            coupon_ids: couponIDs,
            duplicate_number: duplicateNumber
        },
        success: function (response) {
            if (response.success) {
                wccbefReloadCoupons([], wccbefGetCurrentPage());
                wccbefCloseModal();
                wccbefHideSelectionTools();
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefCreateNewCoupon(count = 1) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_create_new_coupon',
            count: count
        },
        success: function (response) {
            if (response.success) {
                wccbefReloadCoupons(response.coupon_ids, 1);
                wccbefCloseModal();
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefSaveColumnProfile(presetKey, items, type) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_save_column_profile',
            preset_key: presetKey,
            items: items,
            type: type
        },
        success: function (response) {
            if (response.success) {
                wccbefLoadingSuccess('Success !');
                location.href = location.href.replace(location.hash, "");
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefLoadFilterProfile(presetKey) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_load_filter_profile',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wccbefResetFilterForm();
                setTimeout(function () {
                    setFilterValues(response);
                }, 500);
                wccbefLoadingSuccess();
                wccbefSetCouponsList(response);
                wccbefCloseModal();
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefDeleteFilterProfile(presetKey) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_delete_filter_profile',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wccbefLoadingSuccess();
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefFilterProfileChangeUseAlways(presetKey) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_filter_profile_change_use_always',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wccbefLoadingSuccess();
            } else {
                wccbefLoadingError()
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefGetCurrentFilterData() {
    return (jQuery('#wccbef-quick-search-text').val()) ? wccbefGetQuickSearchData() : wccbefGetProSearchData()
}

function wccbefResetQuickSearchForm() {
    jQuery('.wccbef-top-nav-filters-search input').val('');
    jQuery('.wccbef-top-nav-filters-search select').prop('selectedIndex', 0);
    jQuery('#wccbef-quick-search-reset').hide();
    jQuery('#wccbef-quick-search-field').trigger('change');
}

function wccbefResetFilterForm() {
    jQuery('#wccbef-filter-form-content input').val('');
    jQuery('#wccbef-filter-form-content select').prop('selectedIndex', 0);
    jQuery('#wccbef-filter-form-content .wccbef-select2').val(null).trigger('change');
    jQuery('#wccbef-filter-form-content .wccbef-select2-products').html('').val(null).trigger('change');
    jQuery('#wccbef-filter-form-content .wccbef-select2-categories').html('').val(null).trigger('change');
    jQuery('#wccbef-filter-form-content .wccbef-select2-products').html('').val(null).trigger('change');
    jQuery('#wccbef-filter-form-content .wccbef-select2-categories').html('').val(null).trigger('change');
    jQuery('.wccbef-bulk-edit-status-filter-item').removeClass('active');
    jQuery('.wccbef-bulk-edit-status-filter-item[data-status="all"]').addClass('active');
}

function wccbefResetFilters() {
    wccbefResetFilterForm();
    wccbefResetQuickSearchForm();
    jQuery(".wccbef-filter-profiles-items tr").removeClass("wccbef-filter-profile-loaded");
    jQuery('input.wccbef-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
    jQuery("#wccbef-bulk-edit-reset-filter").hide();
    wccbefFilterProfileChangeUseAlways("default");
    let data = wccbefGetCurrentFilterData();
    wccbefCouponsFilter(data, "pro_search");
    jQuery('#wccbef-bulk-edit-reset-filter').hide();
}

function wccbefChangeCountPerPage(countPerPage) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_change_count_per_page',
            count_per_page: countPerPage,
        },
        success: function (response) {
            if (response.success) {
                wccbefReloadCoupons([], 1);
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function changedTabs(item) {
    let change = false;
    let tab = jQuery('nav.wccbef-tabs-navbar a[data-content=' + item.closest('.wccbef-tab-content-item').attr('data-content') + ']');
    item.closest('.wccbef-tab-content-item').find('[data-field=operator]').each(function () {
        if (jQuery(this).val() === 'text_remove_duplicate') {
            change = true;
            return false;
        }
    });
    item.closest('.wccbef-tab-content-item').find('[data-field=value]').each(function () {
        if (jQuery(this).val()) {
            change = true;
            return false;
        }
    });
    if (change === true) {
        tab.addClass('wccbef-tab-changed');
    } else {
        tab.removeClass('wccbef-tab-changed');
    }
}

function wccbefGetQuickSearchData() {
    return {
        search_type: 'quick_search',
        quick_search_text: jQuery('#wccbef-quick-search-text').val(),
        quick_search_field: jQuery('#wccbef-quick-search-field').val(),
        quick_search_operator: jQuery('#wccbef-quick-search-operator').val(),
    };
}

function wccbefSortByColumn(columnName, sortType) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_sort_by_column',
            filter_data: wccbefGetCurrentFilterData(),
            column_name: columnName,
            sort_type: sortType,
        },
        success: function (response) {
            if (response.success) {
                wccbefLoadingSuccess();
                wccbefSetCouponsList(response)
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefColumnManagerFieldsGetForEdit(presetKey) {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_column_manager_get_fields_for_edit',
            preset_key: presetKey
        },
        success: function (response) {
            jQuery('#wccbef-modal-column-manager-edit-preset .wccbef-box-loading').hide();
            jQuery('.wccbef-column-manager-added-fields[data-action=edit] .items').html(response.html);
            setTimeout(function () {
                wccbefSetColorPickerTitle();
            }, 250);
            jQuery('.wccbef-column-manager-available-fields[data-action=edit] li').each(function () {
                if (jQuery.inArray(jQuery(this).attr('data-name'), response.fields.split(',')) !== -1) {
                    jQuery(this).attr('data-added', 'true').hide();
                } else {
                    jQuery(this).attr('data-added', 'false').show();
                }
            });
            jQuery('.wccbef-color-picker').wpColorPicker();
        },
    })
}

function wccbefAddMetaKeysByCouponID(couponID) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wccbef_add_meta_keys_by_coupon_id',
            coupon_id: couponID,
        },
        success: function (response) {
            jQuery('#wccbef-meta-fields-items').append(response);
            wccbefLoadingSuccess();
        },
        error: function () {
            wccbefLoadingError();
        }
    })
}

function wccbefHistoryUndo() {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_history_undo',
        },
        success: function (response) {
            if (response.success) {
                wccbefCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wccbef-history-items tbody').html(response.history_items);
                wccbefReloadCoupons(response.coupon_ids);
            }
        },
        error: function () {

        }
    });
}

function wccbefHistoryRedo() {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_history_redo',
        },
        success: function (response) {
            if (response.success) {
                wccbefCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wccbef-history-items tbody').html(response.history_items);
                wccbefReloadCoupons(response.coupon_ids);
            }
        },
        error: function () {

        }
    });
}

function wccbefHistoryFilter(filters = null) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_history_filter',
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wccbefLoadingSuccess();
                if (response.history_items) {
                    jQuery('.wccbef-history-items tbody').html(response.history_items);
                } else {
                    jQuery('.wccbef-history-items tbody').html("<td colspan='4'><span>Not Found!</span></td>");
                }
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefGetCurrentPage() {
    return jQuery('.wccbef-top-nav-filters .wccbef-top-nav-filters-paginate a.current').attr('data-index');
}

function wccbefGetDefaultFilterProfileCoupons() {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_get_default_filter_profile_coupons',
        },
        success: function (response) {
            if (response.success) {
                setTimeout(function () {
                    setFilterValues(response);
                }, 500);
                wccbefSetCouponsList(response)
            }
        },
        error: function () {
        }
    });
}

function setFilterValues(response) {
    let filterData = response.filter_data;
    if (filterData) {
        jQuery('.wccbef-top-nav-status-filter a').removeClass('active');
        jQuery.each(filterData, function (key, values) {
            if (values instanceof Object) {
                if (values.operator) {
                    jQuery('#wccbef-filter-form .wccbef-form-group[data-name=' + key + ']').find('[data-field=operator]').val(values.operator).change();
                }
                if (values.value) {
                    switch (key) {
                        case 'post_status':
                            if (values.value[0]) {
                                jQuery('.wccbef-top-nav-status-filter a[data-status="' + values.value[0] + '"]').addClass('active');
                                jQuery('#wccbef-filter-form-coupon-status').val(values.value).change();
                            } else {
                                jQuery('.wccbef-top-nav-status-filter a[data-status="all"]').addClass('active');
                            }
                            break;
                        case 'product_ids':
                            if (values.value.length > 0) {
                                values.value.forEach(function (key) {
                                    if (response.product_ids[key]) {
                                        jQuery('#wccbef-filter-form-coupon-products').append("<option value='" + key + "' selected='selected'>" + response.product_ids[key] + "</option>");
                                    }
                                });
                            }
                            break;
                        case 'exclude_product_ids':
                            if (values.value.length > 0) {
                                values.value.forEach(function (key) {
                                    if (response.exclude_product_ids[key]) {
                                        jQuery('#wccbef-filter-form-coupon-exclude-products').append("<option value='" + key + "' selected='selected'>" + response.exclude_product_ids[key] + "</option>");
                                    }
                                });
                            }
                            break;
                        case 'product_categories':
                            if (values.value.length > 0) {
                                values.value.forEach(function (key) {
                                    if (response.product_categories[key]) {
                                        jQuery('#wccbef-filter-form-coupon-product-categories').append("<option value='" + key + "' selected='selected'>" + response.product_categories[key] + "</option>");
                                    }
                                });
                            }
                            break;
                        case 'exclude_product_categories':
                            if (values.value.length > 0) {
                                values.value.forEach(function (key) {
                                    if (response.exclude_product_categories[key]) {
                                        jQuery('#wccbef-filter-form-coupon-exclude-product-categories').append("<option value='" + key + "' selected='selected'>" + response.exclude_product_categories[key] + "</option>");
                                    }
                                });
                            }
                            break;
                        default:
                            jQuery('#wccbef-filter-form .wccbef-form-group[data-name=' + key + ']').find('[data-field=value]').val(values.value).change();
                    }
                }
                if (values.from) {
                    jQuery('#wccbef-filter-form .wccbef-form-group[data-name=' + key + ']').find('[data-field=from]').val(values.from).change();
                }
                if (values.to) {
                    jQuery('#wccbef-filter-form .wccbef-form-group[data-name=' + key + ']').find('[data-field=to]').val(values.to);
                }
            } else {
                jQuery('#wccbef-filter-form .wccbef-form-group[data-name=' + key + ']').find('[data-field=value]').val(values);
            }
        });
        wccbefCheckFilterFormChanges();
    }
}

function wccbefSaveFilterPreset(data, presetName) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_save_filter_preset',
            filter_data: data,
            preset_name: presetName
        },
        success: function (response) {
            if (response.success) {
                wccbefLoadingSuccess();
                jQuery('#wccbef-modal-filter-profiles').find('tbody').append(response.new_item);
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefResetBulkEditForm() {
    jQuery('#wccbef-modal-bulk-edit input').val('').change();
    jQuery('#wccbef-modal-bulk-edit select').prop('selectedIndex', 0).change();
    jQuery('#wccbef-modal-bulk-edit textarea').val('');
    jQuery('#wccbef-modal-bulk-edit .wccbef-select2').val(null).trigger('change');
    jQuery('#wccbef-modal-bulk-edit .wccbef-select2-products').html('').val(null).trigger('change');
    jQuery('#wccbef-modal-bulk-edit .wccbef-select2-categories').html('').val(null).trigger('change');
    jQuery('#wccbef-modal-bulk-edit .wccbef-select2-products').html('').val(null).trigger('change');
    jQuery('#wccbef-modal-bulk-edit .wccbef-select2-categories').html('').val(null).trigger('change');
}

function wccbefGetProSearchData() {
    let data;
    let custom_fields = [];
    let j = 0;
    jQuery('.wccbef-tab-content-item[data-content=filter_custom_fields] .wccbef-form-group').each(function () {
        if (jQuery(this).find('input').length === 2) {
            let dataFieldType;
            let values = jQuery(this).find('input').map(function () {
                dataFieldType = jQuery(this).attr('data-field-type');
                if (jQuery(this).val()) {
                    return jQuery(this).val()
                }
            }).get();
            custom_fields[j++] = {
                type: 'from-to-' + dataFieldType,
                taxonomy: jQuery(this).attr('data-name'),
                value: values
            }
        } else if (jQuery(this).find('input[data-field=value]').length === 1) {
            if (jQuery(this).find('input[data-field=value]').val() != null) {
                custom_fields[j++] = {
                    type: 'text',
                    taxonomy: jQuery(this).attr('data-name'),
                    operator: jQuery(this).find('select[data-field=operator]').val(),
                    value: jQuery(this).find('input[data-field=value]').val()
                }
            }
        } else if (jQuery(this).find('select[data-field=value]').length === 1) {
            if (jQuery(this).find('select[data-field=value]').val() != null) {
                custom_fields[j++] = {
                    type: 'select',
                    taxonomy: jQuery(this).attr('data-name'),
                    value: jQuery(this).find('select[data-field=value]').val()
                }
            }
        }
    });

    data = {
        search_type: 'pro_search',
        coupon_ids: {
            operator: jQuery('#wccbef-filter-form-coupon-ids-operator').val(),
            value: jQuery('#wccbef-filter-form-coupon-ids').val(),
        },
        post_title: {
            operator: jQuery('#wccbef-filter-form-coupon-title-operator').val(),
            value: jQuery('#wccbef-filter-form-coupon-title').val(),
        },
        post_excerpt: {
            operator: jQuery('#wccbef-filter-form-coupon-description-operator').val(),
            value: jQuery('#wccbef-filter-form-coupon-description').val(),
        },
        post_date: {
            from: jQuery('#wccbef-filter-form-coupon-date-from').val(),
            to: jQuery('#wccbef-filter-form-coupon-date-to').val(),
        },
        post_modified: {
            from: jQuery('#wccbef-filter-form-coupon-modified-date-from').val(),
            to: jQuery('#wccbef-filter-form-coupon-modified-date-to').val(),
        },
        post_status: {
            value: jQuery('#wccbef-filter-form-coupon-status').val(),
        },
        discount_type: {
            value: jQuery('#wccbef-filter-form-coupon-discount-type').val(),
        },
        coupon_amount: {
            from: jQuery('#wccbef-filter-form-coupon-amount-from').val(),
            to: jQuery('#wccbef-filter-form-coupon-amount-to').val(),
        },
        free_shipping: {
            value: jQuery('#wccbef-filter-form-coupon-free-shipping').val(),
        },
        individual_use: {
            value: jQuery('#wccbef-filter-form-coupon-individual-use').val(),
        },
        exclude_sale_items: {
            value: jQuery('#wccbef-filter-form-coupon-exclude-sale-items').val(),
        },
        date_expires: {
            from: jQuery('#wccbef-filter-form-coupon-expiry-date-from').val(),
            to: jQuery('#wccbef-filter-form-coupon-expiry-date-to').val(),
        },
        minimum_amount: {
            from: jQuery('#wccbef-filter-form-coupon-minimum-amount-from').val(),
            to: jQuery('#wccbef-filter-form-coupon-minimum-amount-to').val(),
        },
        maximum_amount: {
            from: jQuery('#wccbef-filter-form-coupon-maximum-amount-from').val(),
            to: jQuery('#wccbef-filter-form-coupon-maximum-amount-to').val(),
        },
        product_ids: {
            operator: jQuery('#wccbef-filter-form-coupon-products-operator').val(),
            value: jQuery('#wccbef-filter-form-coupon-products').val(),
        },
        exclude_product_ids: {
            operator: jQuery('#wccbef-filter-form-coupon-exclude-products-operator').val(),
            value: jQuery('#wccbef-filter-form-coupon-exclude-products').val(),
        },
        product_categories: {
            operator: jQuery('#wccbef-filter-form-coupon-product-categories-operator').val(),
            value: jQuery('#wccbef-filter-form-coupon-product-categories').val(),
        },
        exclude_product_categories: {
            operator: jQuery('#wccbef-filter-form-coupon-exclude-product-categories-operator').val(),
            value: jQuery('#wccbef-filter-form-coupon-exclude-product-categories').val(),
        },
        customer_email: {
            operator: jQuery('#wccbef-filter-form-coupon-customer-email-operator').val(),
            value: jQuery('#wccbef-filter-form-coupon-customer-email').val(),
        },
        usage_limit: {
            from: jQuery('#wccbef-filter-form-coupon-usage-limit-from').val(),
            to: jQuery('#wccbef-filter-form-coupon-usage-limit-to').val(),
        },
        limit_usage_to_x_items: {
            from: jQuery('#wccbef-filter-form-coupon-limit-usage-to-x-items-from').val(),
            to: jQuery('#wccbef-filter-form-coupon-limit-usage-to-x-items-to').val(),
        },
        usage_limit_per_user: {
            from: jQuery('#wccbef-filter-form-coupon-usage-limit-per-user-from').val(),
            to: jQuery('#wccbef-filter-form-coupon-usage-limit-per-user-to').val(),
        },
        custom_fields: custom_fields,
    };
    return data;
}

function wccbefCouponsBulkEdit(couponIDs, data, filterData) {
    wccbefLoadingStart();
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_coupons_bulk_edit',
            coupon_ids: couponIDs,
            new_data: data,
            current_page: wccbefGetCurrentPage(),
            filter_data: filterData
        },
        success: function (response) {
            if (response.success) {
                wccbefReloadCoupons(response.coupon_ids);
                wccbefCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wccbef-history-items tbody').html(response.history_items);
                wccbefReInitDatePicker();
                wccbefReInitColorPicker();
                let wccbefTextEditors = jQuery('input[name="wccbef-editors[]"]');
                if (wccbefTextEditors.length > 0) {
                    wccbefTextEditors.each(function () {
                        tinymce.execCommand('mceRemoveEditor', false, jQuery(this).val());
                        tinymce.execCommand('mceAddEditor', false, jQuery(this).val());
                    })
                }
            } else {
                wccbefLoadingError();
            }
        },
        error: function () {
            wccbefLoadingError();
        }
    });
}

function wccbefClearInputs(element) {
    element.find('input').val('');
    element.find('textarea').val('');
    element.find('select option:first').prop('selected', true);
}

function wccbefGetProducts() {
    let query;
    jQuery(".wccbef-select2-products").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WCCBEF_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wccbef_get_products",
                    search: params.term,
                };
                return query;
            },
        },
        minimumInputLength: 1
    });
}

function wccbefGetCategories() {
    let query;
    jQuery(".wccbef-select2-categories").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WCCBEF_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wccbef_get_categories",
                    search: params.term,
                };
                return query;
            },
        },
        minimumInputLength: 1
    });
}

function wccbefGetCouponProducts(couponId, field) {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_get_coupon_products',
            coupon_id: couponId,
            field: field
        },
        success: function (response) {
            if (response.success && response.coupon_products) {
                jQuery.each(response.coupon_products, function (id) {
                    jQuery('#wccbef-modal-products-items').append('<option value="' + id + '" selected>' + response.coupon_products[id] + '</option>');
                })
            }
        },
        error: function () {
        }
    });
}

function wccbefGetCouponCategories(couponId, field) {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_get_coupon_categories',
            coupon_id: couponId,
            field: field
        },
        success: function (response) {
            if (response.success && response.product_categories) {
                jQuery.each(response.product_categories, function (id) {
                    jQuery('#wccbef-modal-categories-items').append('<option value="' + id + '" selected>' + response.product_categories[id] + '</option>');
                })
            }
        },
        error: function () {
        }
    });
}

function wccbefGetCouponUsedIn(couponCode) {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_get_coupon_used_in',
            coupon_code: couponCode,
        },
        success: function (response) {
            if (response.success && response.orders.length !== 0) {
                jQuery.each(response.orders, function (id) {
                    jQuery('#wccbef-modal-coupon-used-in-items').append('<li><a target="_blank" href="' + response.orders[id] + '">Order #' + id + '</a></li>');
                })
            } else {
                jQuery('#wccbef-modal-coupon-used-in-items').append('<span class="wccbef-red-text">This coupon has not been used in any order.</span>');
            }
        },
        error: function () {
        }
    });
}

function wccbefGetCouponUsedBy(couponId) {
    jQuery.ajax({
        url: WCCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wccbef_get_coupon_used_by',
            coupon_id: couponId,
        },
        success: function (response) {
            if (response.users) {
                jQuery.each(response.users, function (id) {
                    jQuery('#wccbef-modal-coupon-used-by-items').append('<li><a target="_blank" href="' + response.users[id].link + '">' + response.users[id].name + '</a></li>');
                })
            } else {
                jQuery('#wccbef-modal-coupon-used-by-items').append('<span class="wccbef-red-text">This coupon has not been used by any user.</span>');
            }
        },
        error: function () {
        }
    });
}