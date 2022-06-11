"use strict";

function closeModal() {
    let lastModalOpened = jQuery('#wcbef-last-modal-opened');
    if (lastModalOpened.val() !== '') {
        jQuery(lastModalOpened.val() + ' .wcbef-modal-box').fadeOut();
        jQuery(lastModalOpened.val()).fadeOut();
        lastModalOpened.val('');
    } else {
        jQuery('.wcbef-modal-box').fadeOut();
        jQuery('.wcbef-modal').fadeOut();
    }
}

function checkedCurrentCategory(id, categoryIds) {
    categoryIds.forEach(function (value) {
        jQuery(id + ' input[value=' + value + ']').prop('checked', 'checked');
    });
}

function wcbefInlineEditAjax(productsIDs, field, value, reload = false) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_inline_edit',
            products_ids: productsIDs,
            field: field,
            value: value
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    reload_products(response.edited_ids);
                } else {
                    wcbefLoadingSuccess('Success !')
                }
                checkUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbef-history-items tbody').html(response.history_items);
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    })
}

function reload_products(edited_ids = [], current_page = get_current_page()) {
    let data = get_current_filer_data();
    wcbefProductsFilterAjax(data, 'pro_search', edited_ids, current_page);
}

function wcbefEditByCalculatorAjax(productIDs, field, values) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_edit_by_calculator',
            product_ids: productIDs,
            field: field,
            operator: values.operator,
            value: values.value,
            operator_type: values.operator_type,
            round_item: values.roundItem,
        },
        success: function (response) {
            if (response.success) {
                reload_products(response.edited_ids);
            }
            checkUndoRedoStatus(response.reverted, response.history_items);
            jQuery('.wcbef-history-items tbody').html(response.history_items);
        },
        error: function () {
            wcbefLoadingError();
        }
    })
}

function wcbefPaginationLoadingStart() {
    jQuery('.wcbef-pagination-loading').show();
}

function wcbefPaginationLoadingEnd() {
    jQuery('.wcbef-pagination-loading').hide();
}

function wcbefLoadingStart() {
    jQuery('#wcbef-loading').removeClass('wcbef-loading-error').removeClass('wcbef-loading-success').text('Loading ...').slideDown(300);
}

function wcbefLoadingSuccess(message) {
    jQuery('#wcbef-loading').removeClass('wcbef-loading-error').addClass('wcbef-loading-success').text(message).delay(1500).slideUp(200);
}

function wcbefLoadingError() {
    jQuery('#wcbef-loading').removeClass('wcbef-loading-success').addClass('wcbef-loading-error').text('Error !').delay(1500).slideUp(200);
}

function wcbefAddMetaKeysByProductIDAjax(productID) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wcbef_add_meta_keys_by_product_id',
            product_id: productID,
        },
        success: function (response) {
            jQuery('#wcbef-meta-fields-items').append(response);
            wcbefLoadingSuccess("Success !");
        },
        error: function () {
            wcbefLoadingError();
        }
    })
}

function wcbefAddMetaKeysManualAjax(meta_key_name) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wcbef_add_meta_keys_manual',
            meta_key_name: meta_key_name,
        },
        success: function (response) {
            jQuery('#wcbef-meta-fields-items').append(response);
            wcbefLoadingSuccess("Success !");
        },
        error: function () {
            wcbefLoadingError();
        }
    })
}

function wcbefColumnManagerAddFieldAjax(fieldName, fieldLabel, action) {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'html',
        data: {
            action: 'wcbef_column_manager_add_field',
            field_name: fieldName,
            field_label: fieldLabel,
            field_action: action
        },
        success: function (response) {
            jQuery('.wcbef-column-manager-added-fields[data-action=' + action + ']').append(response);
            fieldName.forEach(function (name) {
                jQuery('.wcbef-column-manager-available-fields[data-action=' + action + '] input:checkbox[data-name=' + name + ']').prop('checked', false).closest('li').attr('data-added', 'true').hide();
            });
            jQuery('.wcbef-color-picker').wpColorPicker();
            jQuery('.wcbef-column-manager-check-all-fields-btn[data-action=' + action + '] input:checkbox').prop('checked', false);
            jQuery('.wcbef-column-manager-check-all-fields-btn[data-action=' + action + '] span').removeClass('selected').text('Select All');
            setTimeout(function () {
                wcbefSetColorPickerTitle();
            }, 250);
        },
        error: function () {
        }
    })
}

function wcbefColumnManagerFieldsGetForEditAjax(presetKey) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_column_manager_get_fields_for_edit',
            preset_key: presetKey
        },
        success: function (response) {
            jQuery('.wcbef-column-manager-added-fields[data-action=edit]').html(response.html);
            setTimeout(function () {
                wcbefSetColorPickerTitle();
            }, 250)
            jQuery('.wcbef-column-manager-available-fields[data-action=edit] li').each(function () {
                if (jQuery.inArray(jQuery(this).attr('data-name'), response.fields.split(',')) !== -1) {
                    jQuery(this).attr('data-added', 'true').hide();
                } else {
                    jQuery(this).attr('data-added', 'false').show();
                }
            });
            wcbefLoadingSuccess("Success !");
            jQuery('.wcbef-color-picker').wpColorPicker();
        },
        error: function () {
            wcbefLoadingError();
        }
    })
}

function wcbefSetColorPickerTitle() {
    jQuery('.wcbef-column-manager-right-item .wp-picker-container').each(function () {
        let title = jQuery(this).find('.wcbef-column-manager-color-field input').attr('title');
        jQuery(this).attr('title', title);
        setTipsyTooltip();
    });
}

function reset_filter_form() {
    jQuery('#wcbef-filter-form-content input').val('');
    jQuery('#wcbef-filter-form-content select').prop('selectedIndex', 0);
    jQuery('#wcbef-filter-form-content .wcbef-select2').val(null).trigger('change');
}

function reset_bulk_edit_form() {
    jQuery('#wcbef-modal-bulk-edit input').val('').change();
    jQuery('#wcbef-modal-bulk-edit select').prop('selectedIndex', 0).change();
    jQuery('#wcbef-modal-bulk-edit .wcbef-select2').val(null).trigger('change');
}

function reset_quick_search_form() {
    jQuery('.wcbef-top-nav-filters-search input').val('');
    jQuery('.wcbef-top-nav-filters-search select').prop('selectedIndex', 0);
}

function wcbefSaveFilterPresetAjax(data, presetName) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_save_filter_preset',
            filter_data: data,
            preset_name: presetName
        },
        success: function (response) {
            if (response.success) {
                wcbefLoadingSuccess("Success !");
                jQuery('#wcbef-modal-filter-profiles').find('tbody').append(response.new_item);
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefProductsFilterAjax(data, action, edited_ids = null, page = get_current_page()) {
    if (action === 'pagination') {
        wcbefPaginationLoadingStart();
    } else {
        wcbefLoadingStart();
    }
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_products_filter',
            filter_data: data,
            current_page: page,
            search_action: action,
        },
        success: function (response) {
            if (response.success) {
                wcbefLoadingSuccess("Success !");
                wcbefPaginationLoadingEnd();
                setProductsList(response.products_list, response.pagination, response.products_count, edited_ids)
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function get_current_filer_data() {
    return (jQuery('#wcbef-quick-search-text').val() !== '') ? quick_search_data() : pro_search_data()
}

function quick_search_data() {
    return {
        search_type: 'quick_search',
        quick_search_text: jQuery('#wcbef-quick-search-text').val(),
        quick_search_field: jQuery('#wcbef-quick-search-field').val(),
        quick_search_operator: jQuery('#wcbef-quick-search-operator').val(),
    };
}

function pro_search_data() {
    let data;
    let attributes = [];
    let custom_fields = [];
    let i = 0;
    let j = 0;
    jQuery('.wcbef-filters-form-group[data-type=attribute]').each(function () {
        if (jQuery(this).find('select[data-field=value]').val() != null) {
            attributes[i++] = {
                taxonomy: jQuery(this).attr('data-taxonomy'),
                operator: jQuery(this).find('select[data-field=operator]').val(),
                value: jQuery(this).find('select[data-field=value]').val()
            }
        }
    });

    jQuery('.wcbef-filters-form-group[data-type=custom_fields]').each(function () {
        if (jQuery(this).find('input').length === 2) {
            let dataFieldType;
            let values = jQuery(this).find('input').map(function () {
                dataFieldType = jQuery(this).attr('data-field-type');
                return jQuery(this).val();
            }).get();
            custom_fields[j++] = {
                type: 'from-to-' + dataFieldType,
                taxonomy: jQuery(this).attr('data-taxonomy'),
                value: values
            }
        } else if (jQuery(this).find('input[data-field=value]').length === 1) {
            if (jQuery(this).find('input[data-field=value]').val() != null) {
                custom_fields[j++] = {
                    type: 'text',
                    taxonomy: jQuery(this).attr('data-taxonomy'),
                    operator: jQuery(this).find('select[data-field=operator]').val(),
                    value: jQuery(this).find('input[data-field=value]').val()
                }
            }
        } else if (jQuery(this).find('select[data-field=value]').length === 1) {
            if (jQuery(this).find('select[data-field=value]').val() != null) {
                custom_fields[j++] = {
                    type: 'select',
                    taxonomy: jQuery(this).attr('data-taxonomy'),
                    value: jQuery(this).find('select[data-field=value]').val()
                }
            }
        }
    });

    data = {
        search_type: 'pro_search',
        product_ids: {
            operator: jQuery('#wcbef-filter-form-product-ids-operator').val(),
            parent_only: (jQuery('#wcbef-filter-form-product-ids-parent-only').prop('checked') === true) ? 'yes' : 'no',
            value: jQuery('#wcbef-filter-form-product-ids').val(),
        },
        product_title: {
            operator: jQuery('#wcbef-filter-form-product-title-operator').val(),
            value: jQuery('#wcbef-filter-form-product-title').val()
        },
        product_content: {
            operator: jQuery('#wcbef-filter-form-product-content-operator').val(),
            value: jQuery('#wcbef-filter-form-product-content').val()
        },
        product_excerpt: {
            operator: jQuery('#wcbef-filter-form-product-excerpt-operator').val(),
            value: jQuery('#wcbef-filter-form-product-excerpt').val()
        },
        product_slug: {
            operator: jQuery('#wcbef-filter-form-product-slug-operator').val(),
            value: jQuery('#wcbef-filter-form-product-slug').val()
        },
        product_sku: {
            operator: jQuery('#wcbef-filter-form-product-sku-operator').val(),
            value: jQuery('#wcbef-filter-form-product-sku').val()
        },
        product_url: {
            operator: jQuery('#wcbef-filter-form-product-url-operator').val(),
            value: jQuery('#wcbef-filter-form-product-url').val()
        },
        product_categories: {
            operator: jQuery('#wcbef-filter-form-product-categories-operator').val(),
            value: jQuery('#wcbef-filter-form-product-categories').val()
        },
        product_tags: {
            operator: jQuery('#wcbef-filter-form-product-tags-operator').val(),
            value: jQuery('#wcbef-filter-form-product-tags').val()
        },
        product_attributes: attributes,
        product_custom_fields: custom_fields,
        product_regular_price: {
            from: jQuery('#wcbef-filter-form-product-regular-price-from').val(),
            to: jQuery('#wcbef-filter-form-product-regular-price-to').val()
        },
        product_sale_price: {
            from: jQuery('#wcbef-filter-form-product-sale-price-from').val(),
            to: jQuery('#wcbef-filter-form-product-sale-price-to').val()
        },
        product_width: {
            from: jQuery('#wcbef-filter-form-product-width-from').val(),
            to: jQuery('#wcbef-filter-form-product-width-to').val()
        },
        product_height: {
            from: jQuery('#wcbef-filter-form-product-height-from').val(),
            to: jQuery('#wcbef-filter-form-product-height-to').val()
        },
        product_length: {
            from: jQuery('#wcbef-filter-form-product-length-from').val(),
            to: jQuery('#wcbef-filter-form-product-length-to').val()
        },
        product_weight: {
            from: jQuery('#wcbef-filter-form-product-weight-from').val(),
            to: jQuery('#wcbef-filter-form-product-weight-to').val()
        },
        stock_quantity: {
            from: jQuery('#wcbef-filter-form-stock-quantity-from').val(),
            to: jQuery('#wcbef-filter-form-stock-quantity-to').val()
        },
        manage_stock: {
            value: jQuery('#wcbef-filter-form-manage-stock').val()
        },
        product_menu_order: {
            from: jQuery('#wcbef-filter-form-product-menu-order-from').val(),
            to: jQuery('#wcbef-filter-form-product-menu-order-to').val()
        },
        date_created: {
            from: jQuery('#wcbef-filter-form-date-created-from').val(),
            to: jQuery('#wcbef-filter-form-date-created-to').val()
        },
        sale_price_date_from: {
            value: jQuery('#wcbef-filter-form-product-sale-price-date-from').val(),
        },
        sale_price_date_to: {
            value: jQuery('#wcbef-filter-form-product-sale-price-date-to').val()
        },
        product_type: jQuery('#wcbef-filter-form-product-type').val(),
        product_status: jQuery('#wcbef-filter-form-product-status').val(),
        stock_status: jQuery('#wcbef-filter-form-stock-status').val(),
        featured: jQuery('#wcbef-filter-form-featured').val(),
        downloadable: jQuery('#wcbef-filter-form-downloadable').val(),
        backorders: jQuery('#wcbef-filter-form-backorders').val(),
        sold_individually: jQuery('#wcbef-filter-form-sold-individually').val(),
        author: jQuery('#wcbef-filter-form-author').val(),
        catalog_visibility: jQuery('#wcbef-filter-form-visibility').val(),
    };
    return data;
}

function wcbefProductsBulkEditAjax(productIDs, data, filterData) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_products_bulk_edit',
            product_ids: productIDs,
            new_data: data,
            current_page: get_current_page(),
            filter_data: filterData
        },
        success: function (response) {
            if (response.success) {
                reload_products(response.product_ids);
                checkUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbef-history-items tbody').html(response.history_items);
                jQuery('.wcbef-datepicker').datepicker({ dateFormat: 'yy/mm/dd' });
                let wcbefTextEditors = jQuery('input[name="wcbef-editors[]"]');
                if (wcbefTextEditors.length > 0) {
                    wcbefTextEditors.each(function () {
                        tinymce.execCommand('mceRemoveEditor', false, jQuery(this).val());
                        tinymce.execCommand('mceAddEditor', false, jQuery(this).val());
                    })
                }
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function get_current_page() {
    return jQuery('.wcbef-top-nav-filters .wcbef-top-nav-filters-paginate a.current').attr('data-index');
}

function wcbefCreateNewProductAjax(count = 1) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_create_new_product',
            count: count
        },
        success: function (response) {
            if (response.success) {
                reload_products(response.product_ids, 1);
                closeModal();
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function getAllCombinations(attributes_arr) {
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

function wcbefGetProductVariationsAjax(productID) {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_get_product_variations',
            product_id: productID
        },
        success: function (response) {
            if (response.success) {
                jQuery('.wcbef-variation-bulk-edit-current-items').html(response.variations);
                jQuery('#wcbef-variation-bulk-edit-attributes-added').html(response.attributes);
                jQuery('#wcbef-variation-bulk-edit-attributes').val(response.selected_items).change();
                jQuery('#wcbef-variation-single-delete-items').html(response.variations_single_delete);
                jQuery('.wcbef-variation-bulk-edit-individual-items').html(response.individual);
                jQuery('#wcbef-variation-bulk-edit-do-bulk-variations').prop('disabled', false);
                jQuery('#wcbef-variation-bulk-edit-manual-add').prop('disabled', false);
                jQuery('#wcbef-variation-bulk-edit-generate').prop('disabled', false);
                jQuery('.wcbef-select2-ajax').select2();
            } else {
                jQuery('.wcbef-variation-bulk-edit-current-items').html('');
                jQuery('#wcbef-variation-bulk-edit-attributes-added').html('');
                jQuery('#wcbef-variation-bulk-edit-attributes').val('').change();
                jQuery('#wcbef-variation-single-delete-items').html('');
                jQuery('.wcbef-variation-bulk-edit-individual-items').html('');
                jQuery('#wcbef-variation-bulk-edit-manual-add').attr('disabled', 'disabled');
                jQuery('#wcbef-variation-bulk-edit-generate').attr('disabled', 'disabled');
                jQuery('#wcbef-variation-bulk-edit-do-bulk-variations').attr('disabled', 'disabled');
            }
        },
        error: function () {
            jQuery('.wcbef-variation-bulk-edit-current-items').html('');
            jQuery('#wcbef-variation-bulk-edit-attributes-added').html('');
            jQuery('#wcbef-variation-bulk-edit-attributes').val('').change();
            jQuery('#wcbef-variation-single-delete-items').html('');
            jQuery('.wcbef-variation-bulk-edit-individual-items').html('');
            jQuery('#wcbef-variation-bulk-edit-manual-add').attr('disabled', 'disabled');
            jQuery('#wcbef-variation-bulk-edit-generate').attr('disabled', 'disabled');
            jQuery('#wcbef-variation-bulk-edit-do-bulk-variations').attr('disabled', 'disabled');
        }
    });
}

function setTipsyTooltip() {
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

function wcbefSetProductsVariations(productIDs, attributes, variations, default_variation) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_set_products_variations',
            product_ids: productIDs,
            attributes: attributes,
            variations: variations,
            default_variation: default_variation
        },
        success: function (response) {
            if (response.success) {
                closeModal();
                reload_products(productIDs)
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefDeleteProductsVariations(ProductIds, deleteType, variations) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_delete_products_variations',
            product_ids: ProductIds,
            delete_type: deleteType,
            variations: variations
        },
        success: function (response) {
            if (response.success) {
                closeModal();
                reload_products(ProductIds, get_current_page());
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefDeleteProductAjax(productIDs, deleteType) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_delete_products',
            product_ids: productIDs,
            delete_type: deleteType,
        },
        success: function (response) {
            if (response.success) {
                reload_products([], get_current_page());
                hideProductSelectionTools();
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefDuplicateProductAjax(productIDs, duplicateNumber) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_duplicate_product',
            product_ids: productIDs,
            duplicate_number: duplicateNumber
        },
        success: function (response) {
            if (response.success) {
                reload_products([], get_current_page());
                closeModal();
                hideProductSelectionTools();
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefUpdateProductAttributeAjax(product_ids, field, data, reload) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_update_product_attribute',
            product_ids: product_ids,
            field: field,
            values: data
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    reload_products(product_ids);
                } else {
                    wcbefLoadingSuccess('Success !');
                }
                checkUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbef-history-items tbody').html(response.history_items);
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefGetTaxonomyParentSelectBoxAjax(taxonomy) {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_get_taxonomy_parent_select_box',
            taxonomy: taxonomy,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbef-new-product-taxonomy-parent').html(response.options);
            }
        },
        error: function () {
        }
    });
}

function wcbefUpdateProductTaxonomyAjax(product_ids, field, data, reload) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_update_product_taxonomy',
            product_ids: product_ids,
            field: field,
            values: data
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    reload_products(product_ids);
                } else {
                    wcbefLoadingSuccess('Success !');
                }
                checkUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbef-history-items tbody').html(response.history_items);
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function hideProductSelectionTools() {
    jQuery('.wcbef-bulk-edit-form-select-tools').hide();
}

function showProductSelectionTools() {
    jQuery('.wcbef-bulk-edit-form-select-tools').show();
}

function hideVariationSelectionTools() {
    jQuery('#wcbef-bulk-edit-select-all-variations-tools').hide();
}

function showVariationSelectionTools() {
    jQuery('#wcbef-bulk-edit-select-all-variations-tools').show();
}

function wcbefAddProductAttributeAjax(attributeInfo, attributeName) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_add_product_attribute',
            attribute_info: attributeInfo,
            attribute_name: attributeName,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbef-modal-attribute-' + attributeName + '-' + attributeInfo.product_id + ' .wcbef-product-items-list').html(response.attribute_items);
                wcbefLoadingSuccess('Success !');
                closeModal()
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefAddProductTaxonomyAjax(taxonomyInfo, taxonomyName) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_add_product_taxonomy',
            taxonomy_info: taxonomyInfo,
            taxonomy_name: taxonomyName,
        },
        success: function (response) {
            if (response.success) {
                console.log(response);
                jQuery('#wcbef-modal-taxonomy-' + taxonomyName + '-' + taxonomyInfo.product_id + ' .wcbef-product-items-list').html(response.taxonomy_items);
                wcbefLoadingSuccess('Success !');
                closeModal()
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefLoadFilterProfileAjax(presetKey) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_load_filter_profile',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                reset_filter_form();
                setFilterValues(response.filter_data);
                wcbefLoadingSuccess("Success !");
                wcbefPaginationLoadingEnd();
                setProductsList(response.products_list, response.pagination, response.products_count);
                closeModal();
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function setFilterValues(filterData) {
    if (filterData) {
        jQuery.each(filterData, function (key, values) {
            if (key === 'product_attributes' || key === 'product_custom_fields') {
                jQuery.each(values, function (key, val) {
                    if (val.operator) {
                        jQuery('#wcbef-filter-form .wcbef-filters-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=operator]').val(val.operator).change();
                    }
                    if (val.value) {
                        jQuery('#wcbef-filter-form .wcbef-filters-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=value]').val(val.value).change();
                    }
                    if (val.value[0]) {
                        jQuery('#wcbef-filter-form .wcbef-filters-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=from]').val(val.value[0]);
                    }
                    if (val.value[1]) {
                        jQuery('#wcbef-filter-form .wcbef-filters-form-group[data-taxonomy=' + val.taxonomy + ']').find('[data-field=to]').val(val.value[1]);
                    }
                });
            } else {
                if (values instanceof Object) {
                    if (values.operator) {
                        jQuery('#wcbef-filter-form .wcbef-filters-form-group[data-name=' + key + ']').find('[data-field=operator]').val(values.operator).change();
                    }
                    if (values.value) {
                        jQuery('#wcbef-filter-form .wcbef-filters-form-group[data-name=' + key + ']').find('[data-field=value]').val(values.value).change();
                    }
                    if (values.from) {
                        jQuery('#wcbef-filter-form .wcbef-filters-form-group[data-name=' + key + ']').find('[data-field=from]').val(values.from).change();
                    }
                    if (values.to) {
                        jQuery('#wcbef-filter-form .wcbef-filters-form-group[data-name=' + key + ']').find('[data-field=to]').val(values.to);
                    }
                } else {
                    jQuery('#wcbef-filter-form .wcbef-filters-form-group[data-name=' + key + ']').find('[data-field=value]').val(values);
                }
            }
        });
    }
}

function setProductsList(productsList, pagination, count, edited_ids = null) {
    jQuery('#wcbef-products-table').html(productsList);
    jQuery('.wcbef-products-pagination').html(pagination);

    let currentPage = get_current_page();
    let countPerPage = jQuery('#wcbef-quick-per-page').val();
    let showingTo = parseInt(currentPage * countPerPage);
    let showingFrom = parseInt(showingTo - countPerPage) + 1;
    showingTo = (showingTo < count) ? showingTo : count;
    jQuery('.wcbef-products-count').html("Showing " + showingFrom + " to " + showingTo + " of " + count + " entries");

    jQuery('.wcbef-datepicker').datepicker({ dateFormat: 'yy/mm/dd' });
    if (edited_ids && edited_ids.length > 0) {
        jQuery('tr').removeClass('wcbef-product-edited');
        edited_ids.forEach(function (productID) {
            jQuery('tr[data-product-id=' + productID + ']').addClass('wcbef-product-edited');
            jQuery('input[value=' + productID + ']').prop('checked', true);
        });
        showProductSelectionTools();
    }
    if (jQuery('#wcbef-bulk-edit-show-variations').prop('checked') === true) {
        jQuery('tr[data-product-type=variation]').show();
    }
    setTipsyTooltip();
}

function wcbefDeleteFilterProfile(presetKey) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_delete_filter_profile',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wcbefLoadingSuccess('Success !');
                return true;
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefSaveColumnProfileAjax(presetKey, items, type) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_save_column_profile',
            preset_key: presetKey,
            items: items,
            type: type
        },
        success: function (response) {
            if (response.success) {
                wcbefLoadingSuccess('Success !');
                location.reload()
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function filterFormClose() {
    if (jQuery('#wcbef-filter-form-content').attr('data-visibility') === 'visible') {
        jQuery('.wcbef-filter-form-icon').addClass('lni-chevron-down').removeClass('lni lni-chevron-up');
        jQuery('#wcbef-filter-form-content').slideUp(200).attr('data-visibility', 'hidden');
    }
}

function filterFormOpen() {
    if (jQuery('#wcbef-filter-form-content').attr('data-visibility') === 'hidden') {
        jQuery('.wcbef-filter-form-icon').removeClass('lni lni-chevron-down').addClass('lni lni-chevron-up');
        jQuery('#wcbef-filter-form-content').slideDown(200).attr('data-visibility', 'visible');
    }
}

function wcbefHistoryUndo() {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_history_undo',
        },
        success: function (response) {
            if (response.success) {
                checkUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbef-history-items tbody').html(response.history_items);
                reload_products(response.product_ids);
            }
        },
        error: function () {

        }
    });
}

function wcbefHistoryRedo() {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_history_redo',
        },
        success: function (response) {
            if (response.success) {
                checkUndoRedoStatus(response.reverted, response.history_items);
                jQuery('.wcbef-history-items tbody').html(response.history_items);
                reload_products(response.product_ids);
            }
        },
        error: function () {

        }
    });
}

function checkUndoRedoStatus(reverted, history) {
    if (reverted) {
        enableRedo();
    } else {
        disableRedo();
    }
    if (history) {
        enableUndo();
    } else {
        disableUndo();
    }
}

function disableUndo() {
    jQuery('#wcbef-bulk-edit-undo').attr('disabled', 'disabled');
}

function enableUndo() {
    jQuery('#wcbef-bulk-edit-undo').prop('disabled', false);
}

function disableRedo() {
    jQuery('#wcbef-bulk-edit-redo').attr('disabled', 'disabled');
}

function enableRedo() {
    jQuery('#wcbef-bulk-edit-redo').prop('disabled', false);
}

function wcbefHistoryFilter(filters = null) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_history_filter',
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wcbefLoadingSuccess('Success !');
                if (response.history_items) {
                    jQuery('.wcbef-history-items tbody').html(response.history_items);
                } else {
                    jQuery('.wcbef-history-items tbody').html("<td colspan='4'><span>Not Found!</span></td>");
                }
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefChangeCountPerPage(countPerPage) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_change_count_per_page',
            count_per_page: countPerPage,
        },
        success: function (response) {
            if (response.success) {
                reload_products([], 1);
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefGetDefaultFilterProfileProducts() {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_get_default_filter_profile_products',
        },
        success: function (response) {
            if (response.success) {
                setFilterValues(response.filter_data);
                setProductsList(response.products_list, response.pagination, response.products_count)
            }
        },
        error: function () {
        }
    });
}

function select_products(productIds) {
    if (productIds.length > 0) {
        productIds.forEach(function (id) {
            jQuery('input.wcbef-product-id[value=' + id + ']').prop('checked', true);
        });
        showProductSelectionTools();
    }
}

function wcbefFilterProfileChangeUseAlways(presetKey) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_filter_profile_change_use_always',
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wcbefLoadingSuccess('Success !');
            } else {
                wcbefLoadingError()
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function changedTabs(item) {
    let change = false;
    let tab = jQuery('nav.wcbef-tabs-navbar a[data-content=' + item.closest('.wcbef-tab-content-item').attr('data-content') + ']');
    item.closest('.wcbef-tab-content-item').find('[data-field=operator]').each(function () {
        if (jQuery(this).val() === 'text_remove_duplicate') {
            change = true;
            return false;
        }
    });
    item.closest('.wcbef-tab-content-item').find('[data-field=value]').each(function () {
        if (jQuery(this).val()) {
            change = true;
            return false;
        }
    });
    if (change === true) {
        tab.addClass('wcbef-tab-changed');
    } else {
        tab.removeClass('wcbef-tab-changed');
    }
}

function wcbefGetProductDataAjax(productID) {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_get_product_data',
            product_id: productID
        },
        success: function (response) {
            if (response.success) {
                setProductDataBulkEditForm(response.product_data);
            } else {

            }
        },
        error: function () {

        }
    });
}

function setProductDataBulkEditForm(productData) {

    let reviews_allowed = (productData.reviews_allowed) ? 'yes' : 'no';
    let sold_individually = (productData.sold_individually) ? 'yes' : 'no';
    let manage_stock = (productData.manage_stock) ? 'yes' : 'no';
    let featured = (productData.featured) ? 'yes' : 'no';
    let virtual = (productData.virtual) ? 'yes' : 'no';
    let downloadable = (productData.downloadable) ? 'yes' : 'no';

    let attributes = jQuery('#wcbef-modal-bulk-edit .wcbef-bulk-edit-form-group[data-type=attribute]');
    if (attributes.length > 0) {
        let attribute_name = '';
        attributes.each(function () {
            attribute_name = jQuery(this).attr('data-taxonomy');
            if (productData.attribute[attribute_name]) {
                jQuery('#wcbef-modal-bulk-edit .wcbef-bulk-edit-form-group[data-type=attribute][data-taxonomy=' + attribute_name + ']').find('select[data-field=value]').val(productData.attribute[attribute_name]).change();
            }
        });
    }

    let custom_fields = jQuery('#wcbef-modal-bulk-edit .wcbef-bulk-edit-form-group[data-type=custom_fields]');
    if (custom_fields.length > 0) {
        let taxonomy_name = '';
        custom_fields.each(function () {
            taxonomy_name = jQuery(this).attr('data-taxonomy');
            if (productData.meta_field[taxonomy_name]) {
                jQuery('#wcbef-modal-bulk-edit .wcbef-bulk-edit-form-group[data-type=custom_fields][data-taxonomy=' + taxonomy_name + ']').find('[data-field=value]').val(productData.meta_field[taxonomy_name][0]).change();
            }
        });
    }

    jQuery('#wcbef-bulk-edit-form-product-title').val(productData.post_title);
    jQuery('#wcbef-bulk-edit-form-product-slug').val(productData.post_slug);
    jQuery('#wcbef-bulk-edit-form-product-sku').val(productData.sku);
    jQuery('#wcbef-bulk-edit-form-product-description').val(productData.post_content);
    jQuery('#wcbef-bulk-edit-form-product-short-description').val(productData.post_excerpt);
    jQuery('#wcbef-bulk-edit-form-product-purchase-note').val(productData.purchase_note);
    jQuery('#wcbef-bulk-edit-form-product-menu-order').val(productData.menu_order);
    jQuery('#wcbef-bulk-edit-form-product-sold-individually').val(sold_individually).change();
    jQuery('#wcbef-bulk-edit-form-product-enable-reviews').val(reviews_allowed).change();
    jQuery('#wcbef-bulk-edit-form-product-product-status').val(productData.post_status).change();
    jQuery('#wcbef-bulk-edit-form-product-catalog-visibility').val(productData.catalog_visibility).change();
    jQuery('#wcbef-bulk-edit-form-product-date-created').val(productData.post_date);
    jQuery('#wcbef-bulk-edit-form-product-author').val(productData.post_author).change();
    jQuery('#wcbef-bulk-edit-form-categories').val(productData.product_cat).change();
    jQuery('#wcbef-bulk-edit-form-tags').val(productData.product_tag).change();
    jQuery('#wcbef-bulk-edit-form-regular-price').val(productData.regular_price);
    jQuery('#wcbef-bulk-edit-form-sale-price').val(productData.sale_price);
    jQuery('#wcbef-bulk-edit-form-sale-date-from').val(productData.date_on_sale_from);
    jQuery('#wcbef-bulk-edit-form-sale-date-to').val(productData.date_on_sale_to);
    jQuery('#wcbef-bulk-edit-form-tax-status').val(productData.tax_status).change();
    jQuery('#wcbef-bulk-edit-form-tax-class').val(productData.tax_class).change();
    jQuery('#wcbef-bulk-edit-form-shipping-class').val(productData.shipping_class).change();
    jQuery('#wcbef-bulk-edit-form-width').val(productData.width);
    jQuery('#wcbef-bulk-edit-form-height').val(productData.height);
    jQuery('#wcbef-bulk-edit-form-length').val(productData.length);
    jQuery('#wcbef-bulk-edit-form-weight').val(productData.weight);
    jQuery('#wcbef-bulk-edit-form-manage-stock').val(manage_stock).change();
    jQuery('#wcbef-bulk-edit-form-stock-status').val(productData.stock_status).change();
    jQuery('#wcbef-bulk-edit-form-stock-quantity').val(productData.stock_quantity);
    jQuery('#wcbef-bulk-edit-form-backorders').val(productData.backorders).change();
    jQuery('#wcbef-bulk-edit-form-product-type').val(productData.product_type).change();
    jQuery('#wcbef-bulk-edit-form-featured').val(featured).change();
    jQuery('#wcbef-bulk-edit-form-virtual').val(virtual).change();
    jQuery('#wcbef-bulk-edit-form-downloadable').val(downloadable).change();
    jQuery('#wcbef-bulk-edit-form-download-limit').val(productData.download_limit);
    jQuery('#wcbef-bulk-edit-form-download-expiry').val(productData.download_expiry).change();
    jQuery('#wcbef-bulk-edit-form-product-url').val(productData.meta_field._product_url);
    jQuery('#wcbef-bulk-edit-form-button-text').val(productData.meta_field._button_text);
    jQuery('#wcbef-bulk-edit-form-upsells').val(productData.upsell_ids).change();
    jQuery('#wcbef-bulk-edit-form-cross-sells').val(productData.cross_sell_ids).change();
}

function getAttributeValues(name, target) {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_get_attribute_values',
            attribute_name: name
        },
        success: function (response) {
            if (response.success) {
                jQuery(target).append(response.attribute_item);
                jQuery('.wcbef-select2-ajax').select2();
            } else {

            }
        },
        error: function () {

        }
    });
}

function getAttributeValuesForDelete(name, target) {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_get_attribute_values_for_delete',
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

function getAttributeValuesForAttach(name) {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_get_attribute_values_for_attach',
            attribute_name: name
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbef-variation-attaching-attribute-items').html('<div class="wcbef-w40p wcbef-float-left"><select title="Select attribute" id="wcbef-variations-attaching-attribute-item" class="wcbef-w100p">' + response.attribute_items + '</select></div>');
                jQuery('.wcbef-variations-attaching-variation-attribute-item').html(response.attribute_items);
            } else {
                jQuery('#wcbef-variation-attaching-attribute-items').html('');
                jQuery('.wcbef-variations-attaching-variation-attribute-item').html('');
            }
        },
        error: function () {
            jQuery('#wcbef-variation-attaching-attribute-items').html('');
            jQuery('.wcbef-variations-attaching-variation-attribute-item').html('');
        }
    });
}

function getProductVariationsForAttach(productID, attribute, attributeItem) {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_get_product_variations_for_attach',
            product_id: productID,
            attribute: attribute,
            attribute_item: attributeItem
        },
        success: function (response) {
            if (response.success && response.variations) {
                jQuery('#wcbef-variations-attaching-product-variations').html(response.variations);
                jQuery('#wcbef-variation-attaching-start-attaching').prop('disabled', false);
            } else {
                jQuery('#wcbef-variation-attaching-start-attaching').attr('disabled', 'disabled');
                jQuery('#wcbef-variations-attaching-product-variations').html('<span class="wcbef-alert wcbef-alert-danger">The product has no variations !</span>');
            }
        },
        error: function () {
            jQuery('#wcbef-variation-attaching-start-attaching').attr('disabled', 'disabled');
            jQuery('#wcbef-variations-attaching-product-variations').html('');
        }
    });
}

function wcbefGetProductFiles(productID) {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_get_product_files',
            product_id: productID,
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbef-modal-select-files .wcbef-inline-select-files').html(response.files);
                setTipsyTooltip();
            } else {
                jQuery('#wcbef-modal-select-files .wcbef-inline-select-files').html('');
            }
        },
        error: function () {
            jQuery('#wcbef-modal-select-files .wcbef-inline-select-files').html('');
        }
    });
}

function wcbefVariationAttaching(productId, attributeKey, variationId, attributeItem) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_variation_attaching',
            attribute_key: attributeKey,
            variation_id: variationId,
            attribute_item: attributeItem
        },
        success: function (response) {
            if (response.success) {
                reload_products([productId]);
                closeModal();
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}

function wcbefAddNewFileItem() {
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_add_new_file_item',
        },
        success: function (response) {
            if (response.success) {
                jQuery('#wcbef-modal-select-files .wcbef-inline-select-files').prepend(response.file_item);
                setTipsyTooltip();
            }
        },
        error: function () {

        }
    });
}

function wcbefSortByColumn(columnName, sortType) {
    wcbefLoadingStart();
    jQuery.ajax({
        url: WCBEF_DATA.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'wcbef_sort_by_column',
            filter_data: get_current_filer_data(),
            column_name: columnName,
            sort_type: sortType,
        },
        success: function (response) {
            if (response.success) {
                wcbefLoadingSuccess("Success !");
                setProductsList(response.products_list, response.pagination, response.products_count)
            } else {
                wcbefLoadingError();
            }
        },
        error: function () {
            wcbefLoadingError();
        }
    });
}