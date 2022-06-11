"use strict";


function wpbelOpenTab(item) {
    let wpbelTabItem = item;
    let wpbelParentContent = wpbelTabItem.closest(".wpbel-tabs-list");
    let wpbelParentContentID = wpbelParentContent.attr("data-content-id");
    let wpbelDataBox = wpbelTabItem.attr("data-content");
    wpbelParentContent.find("li a.selected").removeClass("selected");
    wpbelTabItem.addClass("selected");
    jQuery("#" + wpbelParentContentID).children("div.selected").removeClass("selected");
    jQuery("#" + wpbelParentContentID + " div[data-content=" + wpbelDataBox + "]").addClass("selected");
    if (jQuery(this).attr("data-type") === "main-tab") {
        wpbelFilterFormClose();
    }
}

function wpbelCloseModal() {
    let lastModalOpened = jQuery('#wpbel-last-modal-opened');
    if (lastModalOpened.val() !== '') {
        jQuery(lastModalOpened.val() + ' .wpbel-modal-box').fadeOut();
        jQuery(lastModalOpened.val()).fadeOut();
        lastModalOpened.val('');
    } else {
        jQuery('.wpbel-modal-box').fadeOut();
        jQuery('.wpbel-modal').fadeOut();
    }
}

function wpbelPaginationLoadingStart() {
    jQuery('.wpbel-pagination-loading').show();
}

function wpbelPaginationLoadingEnd() {
    jQuery('.wpbel-pagination-loading').hide();
}

function wpbelLoadingStart() {
    jQuery('#wpbel-loading').removeClass('wpbel-loading-error').removeClass('wpbel-loading-success').text('Loading ...').slideDown(300);
}

function wpbelLoadingSuccess(message = 'Success !') {
    jQuery('#wpbel-loading').removeClass('wpbel-loading-error').addClass('wpbel-loading-success').text(message).delay(1500).slideUp(200);
}

function wpbelLoadingError(message = 'Error !') {
    jQuery('#wpbel-loading').removeClass('wpbel-loading-success').addClass('wpbel-loading-error').text(message).delay(1500).slideUp(200);
}

function wpbelSetColorPickerTitle() {
    jQuery('.wpbel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wpbel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wpbelSetTipsyTooltip();
    });
}

function wpbelFilterFormClose() {
    if (jQuery('#wpbel-filter-form-content').attr('data-visibility') === 'visible') {
        jQuery('.wpbel-filter-form-icon').addClass('lni-chevron-down').removeClass('lni lni-chevron-up');
        jQuery('#wpbel-filter-form-content').slideUp(200).attr('data-visibility', 'hidden');
    }
}

function wpbelFilterFormOpen() {
    if (jQuery('#wpbel-filter-form-content').attr('data-visibility') === 'hidden') {
        jQuery('.wpbel-filter-form-icon').removeClass('lni lni-chevron-down').addClass('lni lni-chevron-up');
        jQuery('#wpbel-filter-form-content').slideDown(200).attr('data-visibility', 'visible');
    }
}

function wpbelSetTipsyTooltip() {
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

function wpbelHideSelectionTools() {
    jQuery('.wpbel-bulk-edit-form-selection-tools').hide();
}

function wpbelShowSelectionTools() {
    jQuery('.wpbel-bulk-edit-form-selection-tools').show();
}

function wpbelSetColorPickerTitle() {
    jQuery('.wpbel-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wpbel-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        wpbelSetTipsyTooltip();
    });
}

function wpbelColumnManagerAddField(fieldName, fieldLabel, action) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wpbel_column_manager_add_field',
            field_name: fieldName,
            field_label: fieldLabel,
            field_action: action
        },
        success: function (response) {
            jQuery('.wpbel-box-loading').hide();
            jQuery('.wpbel-column-manager-added-fields[data-action=' + action + '] .items').append(response);
            fieldName.forEach(function (name) {
                jQuery('.wpbel-column-manager-available-fields[data-action=' + action + '] input:checkbox[data-name=' + name + ']').prop('checked', false).closest('li').attr('data-added', 'true').hide();
            });
            jQuery('.wpbel-color-picker').wpColorPicker();
            jQuery('.wpbel-column-manager-check-all-fields-btn[data-action=' + action + '] input:checkbox').prop('checked', false);
            jQuery('.wpbel-column-manager-check-all-fields-btn[data-action=' + action + '] span').removeClass('selected').text('Select All');
            setTimeout(function () {
                wpbelSetColorPickerTitle();
            }, 250);
        },
        error: function () {
        }
    })
}

function wpbelAddMetaKeysManual(meta_key_name) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wpbel_add_meta_keys_manual',
            meta_key_name: meta_key_name,
        },
        success: function (response) {
            jQuery('#wpbel-meta-fields-items').append(response);
            wpbelLoadingSuccess();
        },
        error: function () {
            wpbelLoadingError();
        }
    })
}

function wpbelCheckFilterFormChanges() {
    let isChanged = false;
    jQuery('#wpbel-filter-form-content [data-field=value]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#wpbel-filter-form-content [data-field=from]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#wpbel-filter-form-content [data-field=to]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });

    jQuery('#filter-form-changed').val(isChanged);

    if (isChanged === true) {
        jQuery('#wpbel-bulk-edit-reset-filter').show();
    }
}

function wpbelInlineEdit(postsIDs, field, value, reload = false) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_inline_edit',
            posts_ids: postsIDs,
            field: field,
            value: value
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    wpbelReloadPosts(response.edited_ids);
                } else {
                    wpbelLoadingSuccess('Success !')
                }
                jQuery('.wpbel-history-items tbody').html(response.history_items);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    })
}

function wpbelReloadPosts(edited_ids = [], current_page = wpbelGetCurrentPage()) {
    let data = wpbelGetCurrentFilterData();
    wpbelPostsFilter(data, data.search_type, edited_ids, current_page);
}

function wpbelPostsFilter(data, action, edited_ids = null, page = wpbelGetCurrentPage()) {
    if (action === 'pagination') {
        wpbelPaginationLoadingStart();
    } else {
        wpbelLoadingStart();
    }
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_posts_filter',
            filter_data: data,
            current_page: page,
            search_action: action,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                switch (action) {
                    case 'prp_search':
                        jQuery('#wpbel-bulk-edit-reset-filter').show();
                        break;
                    case 'quick_search':
                        jQuery('#wpbel-quick-search-reset').show();
                        break;
                }
                wpbelSetPostsList(response.posts_list, response.pagination, response.posts_count, edited_ids)
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelSetPostsList(postsList, pagination, count, edited_ids = null) {
    jQuery('#wpbel-items-table').html(postsList);
    jQuery('.wpbel-items-pagination').html(pagination);
    let currentPage = wpbelGetCurrentPage();
    let countPerPage = jQuery('#wpbel-quick-per-page').val();
    let showingTo = parseInt(currentPage * countPerPage);
    let showingFrom = parseInt(showingTo - countPerPage) + 1;
    showingTo = (showingTo < count) ? showingTo : count;
    jQuery('.wpbel-items-count').html("Showing " + showingFrom + " to " + showingTo + " of " + count + " entries");

    if (jQuery.fn.datepicker) {
        jQuery('.wpbel-datepicker').datepicker({ dateFormat: 'yy/mm/dd' });
    }

    if (edited_ids && edited_ids.length > 0) {
        jQuery('tr').removeClass('wpbel-item-edited');
        edited_ids.forEach(function (postID) {
            jQuery('tr[data-item-id=' + postID + ']').addClass('wpbel-item-edited');
            jQuery('input[value=' + postID + ']').prop('checked', true);
        });
        wpbelShowSelectionTools();
    }
    wpbelSetTipsyTooltip();
    setTimeout(function () {
        let maxHeightScrollWrapper = jQuery('.scroll-wrapper > .scroll-content').css('max-height');
        jQuery('.scroll-wrapper > .scroll-content').css({
            'max-height': (parseInt(maxHeightScrollWrapper) + 5)
        });
    }, 500);
}

function wpbelGetPostData(postID) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_post_data',
            post_id: postID
        },
        success: function (response) {
            if (response.success) {
                wpbelSetPostDataBulkEditForm(response.post_data);
            } else {

            }
        },
        error: function () {

        }
    });
}

function wpbelSetPostDataBulkEditForm(postData) {
    jQuery('#wpbel-bulk-edit-form-post-title').val(postData.post_title);
    jQuery('#wpbel-bulk-edit-form-post-post-status').val(postData.post_status).change();
    jQuery('#wpbel-bulk-edit-form-attr-category').val(postData.category).change();
    jQuery('#wpbel-bulk-edit-form-attr-post-tag').val(postData.post_tag).change();
}

function wpbelEditByCalculator(postIDs, field, values) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_edit_by_calculator',
            post_ids: postIDs,
            field: field,
            operator: values.operator,
            value: values.value,
            operator_type: values.operator_type,
            round_item: values.roundItem,
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts(response.edited_ids);
            }
            jQuery('.wpbel-history-items tbody').html(response.history_items);
        },
        error: function () {
            wpbelLoadingError();
        }
    })
}

function wpbelDeletePost(postIDs, deleteType) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_delete_posts',
            post_ids: postIDs,
            delete_type: deleteType,
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts(response.edited_ids, wpbelGetCurrentPage());
                wpbelHideSelectionTools();
                jQuery('.wpbel-history-items tbody').html(response.history_items);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelDuplicatePost(postIDs, duplicateNumber) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_duplicate_post',
            post_ids: postIDs,
            duplicate_number: duplicateNumber
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts([], wpbelGetCurrentPage());
                wpbelCloseModal();
                wpbelHideSelectionTools();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelCreateNewPost(count = 1, postType = null) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_create_new_post',
            count: count,
            post_type: postType
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts(response.post_ids, 1);
                wpbelCloseModal();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelGetAllCombinations(attributes_arr) {
    var combinations = [], args = attributes_arr, max = args.length - 1;
    helper([], 0);

    function helper(arr, i) {
        for (let j = 0; j < args[i][1].length; j++) {
            let a = arr.slice(0);
            a.push([args[i][0], args[i][1][j]]);
            if (i === max) {
                combinations.push(a);
            } else {
                helper(a, i + 1);
            }
        }
    }

    return combinations;
}

function wpbelLoadFilterProfile(presetKey) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_load_filter_profile',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wpbelResetFilterForm();
                setFilterValues(response.filter_data);
                wpbelLoadingSuccess();
                wpbelSetPostsList(response.posts_list, response.pagination, response.posts_count);
                wpbelCloseModal();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelDeleteFilterProfile(presetKey) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_delete_filter_profile',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelFilterProfileChangeUseAlways(presetKey) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_filter_profile_change_use_always',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
            } else {
                wpbelLoadingError()
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelGetCurrentFilterData() {
    return (jQuery('#wpbel-quick-search-text').val()) ? wpbelGetQuickSearchData() : wpbelGetProSearchData()
}

function wpbelResetQuickSearchForm() {
    jQuery('.wpbel-top-nav-filters-search input').val('');
    jQuery('.wpbel-top-nav-filters-search select').prop('selectedIndex', 0);
    jQuery('#wpbel-quick-search-reset').hide();
}

function wpbelResetFilterForm() {
    jQuery('#wpbel-filter-form-content input').val('');
    jQuery('#wpbel-filter-form-content select').prop('selectedIndex', 0);
    jQuery('#wpbel-filter-form-content .wpbel-select2').val(null).trigger('change');

}

function wpbelResetFilters() {
    wpbelResetFilterForm();
    wpbelResetQuickSearchForm();
    jQuery(".wpbel-filter-profiles-items tr").removeClass("wpbel-filter-profile-loaded");
    jQuery('input.wpbel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
    jQuery("#wpbel-bulk-edit-reset-filter").hide();
    wpbelFilterProfileChangeUseAlways("default");
    let data = wpbelGetCurrentFilterData();
    wpbelPostsFilter(data, "pro_search");
    jQuery('#wpbel-bulk-edit-reset-filter').hide();
}

function wpbelChangeCountPerPage(countPerPage) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_change_count_per_page',
            count_per_page: countPerPage,
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts([], 1);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelUpdatePostTaxonomy(post_ids, field, data, reload) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_update_post_taxonomy',
            post_ids: post_ids,
            field: field,
            values: data
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    wpbelReloadPosts(post_ids);
                } else {
                    wpbelLoadingSuccess();
                }
                jQuery('.wpbel-history-items tbody').html(response.history_items);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelAddPostTaxonomy(taxonomyInfo, taxonomyName) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_add_post_taxonomy',
            taxonomy_info: taxonomyInfo,
            taxonomy_name: taxonomyName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-modal-taxonomy-' + taxonomyName + '-' + taxonomyInfo.post_id + ' .wpbel-post-items-list').html(response.taxonomy_items);
                wpbelLoadingSuccess();
                wpbelCloseModal()
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelAddPostAttribute(attributeInfo, attributeName) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_add_post_attribute',
            attribute_info: attributeInfo,
            attribute_name: attributeName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-modal-attribute-' + attributeName + '-' + attributeInfo.post_id + ' .wpbel-post-items-list').html(response.attribute_items);
                wpbelLoadingSuccess();
                wpbelCloseModal()
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelAddNewFileItem() {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_add_new_file_item',
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-modal-select-files .wpbel-inline-select-files').prepend(response.file_item);
                wpbelSetTipsyTooltip();
            }
        },
        error: function () {

        }
    });
}

function wpbelGetPostFiles(postID) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_post_files',
            post_id: postID,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-modal-select-files .wpbel-inline-select-files').html(response.files);
                wpbelSetTipsyTooltip();
            } else {
                jQuery('#wpbel-modal-select-files .wpbel-inline-select-files').html('');
            }
        },
        error: function () {
            jQuery('#wpbel-modal-select-files .wpbel-inline-select-files').html('');
        }
    });
}

function changedTabs(item) {
    let change = false;
    let tab = jQuery('nav.wpbel-tabs-navbar a[data-content=' + item.closest('.wpbel-tab-content-item').attr('data-content') + ']');
    item.closest('.wpbel-tab-content-item').find('[data-field=operator]').each(function () {
        if (jQuery(this).val() === 'text_remove_duplicate') {
            change = true;
            return false;
        }
    });
    item.closest('.wpbel-tab-content-item').find('[data-field=value]').each(function () {
        if (jQuery(this).val()) {
            change = true;
            return false;
        }
    });
    if (change === true) {
        tab.addClass('wpbel-tab-changed');
    } else {
        tab.removeClass('wpbel-tab-changed');
    }
}

function wpbelGetQuickSearchData() {
    return {
        search_type: 'quick_search',
        quick_search_text: jQuery('#wpbel-quick-search-text').val(),
        quick_search_field: jQuery('#wpbel-quick-search-field').val(),
        quick_search_operator: jQuery('#wpbel-quick-search-operator').val(),
    };
}

function wpbelSortByColumn(columnName, sortType) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_sort_by_column',
            filter_data: wpbelGetCurrentFilterData(),
            column_name: columnName,
            sort_type: sortType,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                wpbelSetPostsList(response.posts_list, response.pagination, response.posts_count)
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelHistoryFilter(filters = null) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_history_filter',
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                if (response.history_items) {
                    jQuery('.wpbel-history-items tbody').html(response.history_items);
                } else {
                    jQuery('.wpbel-history-items tbody').html("<td colspan='4'><span>Not Found!</span></td>");
                }
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelGetCurrentPage() {
    return jQuery('.wpbel-top-nav-filters .wpbel-top-nav-filters-paginate a.current').attr('data-index');
}

function wpbelGetDefaultFilterProfilePosts() {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_default_filter_profile_posts',
        },
        success: function (response) {
            if (response.success) {
                setFilterValues(response.filter_data);
                wpbelSetPostsList(response.posts_list, response.pagination, response.posts_count)
            }
        },
        error: function () {
        }
    });
}

function setFilterValues(filterData) {
    if (filterData) {
        jQuery.each(filterData, function (key, values) {
            if (key === 'post_attributes' || key === 'post_custom_fields') {
                jQuery.each(values, function (key, val) {
                    if (val.operator) {
                        jQuery('#wpbel-filter-form .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=operator]').val(val.operator).change();
                    }
                    if (val.value) {
                        jQuery('#wpbel-filter-form .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=value]').val(val.value).change();
                    }
                    if (val.value[0]) {
                        jQuery('#wpbel-filter-form .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=from]').val(val.value[0]);
                    }
                    if (val.value[1]) {
                        jQuery('#wpbel-filter-form .wpbel-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=to]').val(val.value[1]);
                    }
                });
            } else {
                if (values instanceof Object) {
                    if (values.operator) {
                        jQuery('#wpbel-filter-form .wpbel-form-group[data-name=' + key + ']').find('[data-field=operator]').val(values.operator).change();
                    }
                    if (values.value) {
                        jQuery('#wpbel-filter-form .wpbel-form-group[data-name=' + key + ']').find('[data-field=value]').val(values.value).change();
                    }
                    if (values.from) {
                        jQuery('#wpbel-filter-form .wpbel-form-group[data-name=' + key + ']').find('[data-field=from]').val(values.from).change();
                    }
                    if (values.to) {
                        jQuery('#wpbel-filter-form .wpbel-form-group[data-name=' + key + ']').find('[data-field=to]').val(values.to);
                    }
                } else {
                    jQuery('#wpbel-filter-form .wpbel-form-group[data-name=' + key + ']').find('[data-field=value]').val(values);
                }
            }
        });
        wpbelCheckFilterFormChanges();
    }
}

function checkedCurrentCategory(id, categoryIds) {
    categoryIds.forEach(function (value) {
        jQuery(id + ' input[value=' + value + ']').prop('checked', 'checked');
    });
}

function wpbelSaveFilterPreset(data, presetName) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_save_filter_preset',
            filter_data: data,
            preset_name: presetName
        },
        success: function (response) {
            if (response.success) {
                wpbelLoadingSuccess();
                jQuery('#wpbel-modal-filter-profiles').find('tbody').append(response.new_item);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelResetBulkEditForm() {
    jQuery('#wpbel-modal-bulk-edit input').val('').change();
    jQuery('#wpbel-modal-bulk-edit select').prop('selectedIndex', 0).change();
    jQuery('#wpbel-modal-bulk-edit .wpbel-select2').val(null).trigger('change');
}

function wpbelGetProSearchData() {
    let data;
    let taxonomies = [];
    let i = 0;
    let j = 0;
    jQuery('.wpbel-form-group[data-type=taxonomy]').each(function () {
        if (jQuery(this).find('select[data-field=value]').val() !== null) {
            taxonomies[i++] = {
                taxonomy: jQuery(this).attr('data-taxonomy'),
                operator: jQuery(this).find('select[data-field=operator]').val(),
                value: jQuery(this).find('select[data-field=value]').val()
            }
        }
    });

    data = {
        search_type: 'pro_search',
        post_ids: {
            operator: jQuery('#wpbel-filter-form-post-ids-operator').val(),
            parent_only: (jQuery('#wpbel-filter-form-post-ids-parent-only').prop('checked') === true) ? 'yes' : 'no',
            value: jQuery('#wpbel-filter-form-post-ids').val(),
        },
        post_title: {
            operator: jQuery('#wpbel-filter-form-post-title-operator').val(),
            value: jQuery('#wpbel-filter-form-post-title').val()
        },
        post_content: {
            operator: jQuery('#wpbel-filter-form-post-content-operator').val(),
            value: jQuery('#wpbel-filter-form-post-content').val()
        },
        taxonomies: taxonomies,
    };
    return data;
}

function wpbelPostsBulkEdit(postIDs, data, filterData) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_posts_bulk_edit',
            post_ids: postIDs,
            new_data: data,
            current_page: wpbelGetCurrentPage(),
            filter_data: filterData
        },
        success: function (response) {
            if (response.success) {
                wpbelReloadPosts(response.post_ids);
                jQuery('.wpbel-history-items tbody').html(response.history_items);
                if (jQuery.fn.datepicker) {
                    jQuery('.wpbel-datepicker').datepicker({ dateFormat: 'yy/mm/dd' });
                }
                let wpbelTextEditors = jQuery('input[name="wpbel-editors[]"]');
                if (wpbelTextEditors.length > 0) {
                    wpbelTextEditors.each(function () {
                        tinymce.execCommand('mceRemoveEditor', false, jQuery(this).val());
                        tinymce.execCommand('mceAddEditor', false, jQuery(this).val());
                    })
                }
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelUpdatePostAttribute(post_ids, field, data, reload) {
    wpbelLoadingStart();
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_update_post_attribute',
            post_ids: post_ids,
            field: field,
            values: data
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    wpbelReloadPosts(post_ids);
                } else {
                    wpbelLoadingSuccess('Success !');
                }
                jQuery('.wpbel-history-items tbody').html(response.history_items);
            } else {
                wpbelLoadingError();
            }
        },
        error: function () {
            wpbelLoadingError();
        }
    });
}

function wpbelGetTaxonomyParentSelectBox(taxonomy) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_taxonomy_parent_select_box',
            taxonomy: taxonomy,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wpbel-new-post-taxonomy-parent').html(response.options);
            }
        },
        error: function () {
        }
    });
}

function getAttributeValues(name, target) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_attribute_values',
            attribute_name: name
        },
        success: function (response) {
            if (response.success) {
                jQuery(target).append(response.attribute_item);
                jQuery('.wpbel-select2-ajax').select2();
            } else {

            }
        },
        error: function () {

        }
    });
}

function getAttributeValuesForDelete(name, target) {
    jQuery.ajax({
        url: WPBEL_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wpbel_get_attribute_values_for_delete',
            attribute_name: name
        },
        success: function (response) {
            if (response.success) {
                jQuery(target).append(response.attribute_item);
            } else {

            }
        },
        error: function () {

        }
    });
}