jQuery(document).ready(function ($) {
    "use strict";
    var navigationTopOffset = $("#wcbef-bulk-edit-navigation").offset().top;

    // Tabs
    $(document).on("click", ".wcbef-tabs-list li a", function (event) {
        event.preventDefault();
        let wcbefTabItem = $(this);
        let wcbefParentContent = wcbefTabItem.closest(".wcbef-tabs-list");
        let wcbefParentContentID = wcbefParentContent.attr("data-content-id");
        let wcbefDataBox = wcbefTabItem.attr("data-content");
        wcbefParentContent.find("li a.selected").removeClass("selected");
        wcbefTabItem.addClass("selected");
        $("#" + wcbefParentContentID).children("div.selected").removeClass("selected");
        $("#" + wcbefParentContentID + " div[data-content=" + wcbefDataBox + "]").addClass("selected");
        if ($(this).attr("data-type") === "main-tab") {
            filterFormClose();
        }
        navigationTopOffset = ($("#wcbef-bulk-edit-navigation").offset().top > 300) ? $("#wcbef-bulk-edit-navigation").offset().top : 300;
    });

    // Filter Form (Show & Hide)
    $(".wcbef-filter-form-toggle").on("click", function () {
        if ($("#wcbef-filter-form-content").attr("data-visibility") === "visible") {
            filterFormClose();
        } else {
            filterFormOpen();
        }

        if ($("#wcbef-filter-form").css("position") === "static") {
            setTimeout(function () {
                navigationTopOffset = $("#wcbef-bulk-edit-navigation").offset().top;
            }, 300);
        }
    });

    // Select2
    let wcbefSelect2 = $(".wcbef-select2");
    if (wcbefSelect2.length > 0) {
        wcbefSelect2.select2({
            placeholder: "Select ..."
        });
    }

    // Select Products (Checkbox)
    $(document).on("change", ".wcbef-check-item-main", function () {
        let checkbox_items = $(".wcbef-check-item");
        if ($(this).prop("checked") === true) {
            checkbox_items.prop("checked", true);
            $("#wcbef-products-list tr").addClass("wcbef-tr-selected");
            checkbox_items.each(function () {
                $("#wcbef-export-product-selected").append("<input type='hidden' name='product_ids[]' value='" + $(this).val() + "'>");
            });
            showProductSelectionTools();
            $("#wcbef-export-only-selected-products").prop("disabled", false);
        } else {
            checkbox_items.prop("checked", false);
            $("#wcbef-products-list tr").removeClass("wcbef-tr-selected");
            $("#wcbef-export-product-selected").html("");
            hideProductSelectionTools();
            $("#wcbef-export-only-selected-products").prop("disabled", true);
            $("#wcbef-export-all-products-in-table").prop("checked", true);
        }
    });

    $(document).on("change", ".wcbef-check-item", function () {
        if ($(this).prop("checked") === true) {
            $("#wcbef-export-product-selected").append("<input type='hidden' name='product_ids[]' value='" + $(this).val() + "'>");
            if ($(".wcbef-check-item:checked").length === $(".wcbef-check-item").length) {
                $(".wcbef-check-item-main").prop("checked", true);
            }
            $(this).closest("tr").addClass("wcbef-tr-selected");
        } else {
            $("#wcbef-export-product-selected").find("input[value=" + $(this).val() + "]").remove();
            $(this).closest("tr").removeClass("wcbef-tr-selected");
            $(".wcbef-check-item-main").prop("checked", false);
        }
    });

    $(document).on("change", ".wcbef-product-id", function () {
        if ($(".wcbef-product-id:checkbox:checked").length > 0) {
            $("#wcbef-export-only-selected-products").prop("disabled", false);
            showProductSelectionTools();
        } else {
            hideProductSelectionTools();
            $("#wcbef-export-only-selected-products").prop("disabled", true);
            $("#wcbef-export-all-products-in-table").prop("checked", true);
        }
    });

    // Modal
    $(document).on("click", "[data-toggle=modal]", function () {
        $($(this).attr("data-target")).fadeIn();
        $($(this).attr("data-target") + " .wcbef-modal-box").fadeIn();
        $("#wcbef-last-modal-opened").val($(this).attr("data-target"));

        // set height for modal body
        let titleHeight = $($(this).attr("data-target") + " .wcbef-modal-box .wcbef-modal-title").height();
        let footerHeight = $($(this).attr("data-target") + " .wcbef-modal-box .wcbef-modal-footer").height();
        $($(this).attr("data-target") + " .wcbef-modal-box .wcbef-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wcbef-modal-box").height()) - parseInt(titleHeight + footerHeight + 150) + "px"
        });

        $($(this).attr("data-target") + " .wcbef-modal-box-lg .wcbef-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wcbef-modal-box").height()) - parseInt(titleHeight + footerHeight + 120) + "px"
        });
    });

    $(document).on("click", "[data-toggle=modal-close]", function () {
        closeModal();
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            closeModal();
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
        }
    });

    // Color Picker Style
    $(document).on("change", "input[type=color]", function () {
        this.parentNode.style.backgroundColor = this.value;
    });

    // Quick Per Page
    $("#wcbef-quick-per-page").on("change", function () {
        wcbefChangeCountPerPage($(this).val());
    });

    // Inline edit
    $(document).on("click", "td[data-action=inline-editable]", function (e) {
        if ($(e.target).attr("data-type") !== "edit-mode" && $(e.target).find("[data-type=edit-mode]").length === 0) {
            // Close All Inline Edit
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
            // Open Clicked Inline Edit
            switch ($(this).attr("data-content-type")) {
                case "text":
                    $(this).children("span").html("<textarea data-product-id='" + $(this).attr("data-product-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "'>" + $(this).text().trim() + "</textarea>").children("textarea").focus().select();
                    break;
                case "multi_select_attribute":
                    if ($(e.target).attr("class") !== "select2-selection__rendered" && $(e.target).attr("class") !== "select2-search__field" && $(e.target).attr("class") !== "wcbef-inline-edit-select2-cancel") {
                        let select_item = $("#wcbef-select2-attribute-" + $(this).attr("data-product-id") + "-" + $(this).attr("data-field"));
                        $(this).find("span").hide();
                        select_item.show();
                        select_item.find("select").select2({ placeholder: "Select ..." });
                    }
                    break;
                case "numeric":
                case "regular_price":
                case "sale_price":
                    $(this).children("span").html("<input type='number' min='-1' data-product-id='" + $(this).attr("data-product-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "' value='" + $(this).text().trim() + "'>").children("input[type=number]").focus().select();
                    break;
            }
        }
    });

    $(document).on("click", ".wcbef-inline-edit-select2-cancel", function () {
        $($(this).attr("data-target")).hide();
        $($(this).attr("data-target") + "-default-value").show();
    });

    $(document).on("click", ".wcbef-inline-edit-taxonomy-save", function () {
        let reload = true;
        let ProductIds;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");
        let bindEdit = $("#wcbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && productsChecked.length > 0) {
            ProductIds = productsChecked.map(function (i) {
                return $(this).val();
            }).get();
            ProductIds[productsChecked.length] = $(this).attr("data-product-id");
        } else {
            ProductIds = [];
            ProductIds[0] = $(this).attr("data-product-id");
        }
        let field = $(this).attr("data-field");
        let data = $("#wcbef-modal-taxonomy-" + field + "-" + $(this).attr("data-product-id") + " input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wcbefUpdateProductTaxonomyAjax(ProductIds, field, data, reload);
    });

    $(document).on("click", ".wcbef-inline-edit-attribute-save", function () {
        let reload = true;
        let ProductIds;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");
        let bindEdit = $("#wcbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && productsChecked.length > 0) {
            ProductIds = productsChecked.map(function (i) {
                return $(this).val();
            }).get();
            ProductIds[productsChecked.length] = $(this).attr("data-product-id");
        } else {
            ProductIds = [];
            ProductIds[0] = $(this).attr("data-product-id");
        }
        let field = $(this).attr("data-field");
        let data = $("#wcbef-modal-attribute-" + field + "-" + $(this).attr("data-product-id") + " input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wcbefUpdateProductAttributeAjax(ProductIds, field, data, reload);
    });

    // Discard Save
    $(document).on("click", function (e) {
        if ($(e.target).attr("data-action") !== "inline-editable" && $(e.target).attr("data-type") !== "edit-mode") {
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
        }
    });

    // Save Inline Edit By Enter Key
    $(document).on("keypress", "[data-type=edit-mode]", function (event) {
        let wcbefKeyCode = event.keyCode ? event.keyCode : event.which;
        let reload_products = true;
        if (wcbefKeyCode === 13) {
            let ProductIds;
            let productsChecked = $("input.wcbef-product-id:checkbox:checked");
            let bindEdit = $("#wcbef-inline-edit-bind");
            if (bindEdit.prop("checked") === true && productsChecked.length > 0) {
                ProductIds = productsChecked.map(function (i) {
                    return $(this).val();
                }).get();
                ProductIds[productsChecked.length] = $(this).attr("data-product-id");
            } else {
                ProductIds = [];
                ProductIds[0] = $(this).attr("data-product-id");
            }
            let wcbefField;
            if ($(this).attr("data-field-type")) {
                wcbefField = [
                    $(this).attr("data-field-type"),
                    $(this).attr("data-field")
                ];
            } else {
                wcbefField = $(this).attr("data-field");
            }

            let wcbefValue = $(this).val();
            $(this).closest("span").html($(this).val());
            wcbefInlineEditAjax(ProductIds, wcbefField, wcbefValue, reload_products);
        }
    });

    $(document).on("click", ".wcbef-open-uploader", function (e) {
        let target = $(this).attr("data-target");
        let type = $(this).attr("data-type");
        let mediaUploader;
        let wcbefNewImageElementID = $(this).attr("data-id");
        let wcbefProductID = $(this).attr("data-product-id");
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        if (type === "single") {
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: "Choose Image",
                button: {
                    text: "Choose Image"
                },
                multiple: false
            });
        } else {
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: "Choose Images",
                button: {
                    text: "Choose Images"
                },
                multiple: true
            });
        }

        mediaUploader.on("select", function () {
            let attachment = mediaUploader.state().get("selection").toJSON();
            switch (target) {
                case "inline-file":
                    $("#url-" + wcbefNewImageElementID).val(attachment[0].url);
                    break;
                case "inline-edit":
                    $("#" + wcbefNewImageElementID).val(attachment[0].url);
                    $("[data-image-preview-id=" + wcbefNewImageElementID + "]").html("<img src='" + attachment[0].url + "' alt='' />");
                    $("button[data-field=_thumbnail_id][data-product-id=" + wcbefProductID + "][data-button-type=save]").attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "inline-edit-gallery":
                    attachment.forEach(function (item) {
                        $("div[data-gallery-id=wcbef-gallery-items-" + wcbefProductID + "]").append('<div class="wcbef-inline-edit-gallery-item"><img src="' + item.url + '" alt=""><input type="hidden" class="wcbef-inline-edit-gallery-image-ids" value="' + item.id + '"></div>');
                    });
                    break;
                case "bulk-edit-image":
                    $("#wcbef-bulk-edit-form-product-image").val(attachment[0].id);
                    $("#wcbef-bulk-edit-form-product-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="wcbef-bulk-edit-form-remove-image">x</button></div>');
                    break;
                case "bulk-edit-gallery":
                    attachment.forEach(function (item) {
                        $("#wcbef-bulk-edit-form-product-gallery").append('<input type="hidden" value="' + item.id + '">');
                        $("#wcbef-bulk-edit-form-product-gallery-preview").append('<div><img src="' + item.url + '" width="43" height="43" alt=""><button type="button" data-id="' + item.id + '" class="wcbef-bulk-edit-form-remove-gallery-item">x</button></div>');
                    });
                    break;
            }
        });
        mediaUploader.open();
    });

    $(document).on("click", ".wcbef-bulk-edit-form-remove-image", function () {
        $(this).closest("div").remove();
        $("#wcbef-bulk-edit-form-product-image").val("");
    });

    $(document).on("click", ".wcbef-bulk-edit-form-remove-gallery-item", function () {
        $(this).closest("div").remove();
        $("#wcbef-bulk-edit-form-product-gallery input[value=" + $(this).attr("data-id") + "]").remove();
    });

    // set checked for current product category,taxonomy
    $(document).on("click", ".wcbef-is-category-modal", function () {
        checkedCurrentCategory($(this).attr("data-target"), $(this).attr("data-category-ids").split(","));
    });

    //Search
    $(document).on("keyup", ".wcbef-search-in-list", function () {
        let wcbefSearchValue = this.value.toLowerCase().trim();
        $($(this).attr("data-id") + " .wcbef-product-items-list li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wcbefSearchValue) > -1);
        });
    });

    $(document).on("keyup", ".wcbef-column-manager-search-field", function () {
        let wcbefSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wcbef-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] ul li[data-added=false]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wcbefSearchFieldValue) > -1);
        });
    });

    $(document).on("keyup", "#wcbef-column-profile-search", function () {
        let wcbefSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wcbef-column-profile-fields ul li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wcbefSearchFieldValue) > -1);
        });
    });

    // Calculator for numeric TD
    $(document).on(
        {
            mouseenter: function () {
                $(this)
                    .children(".wcbef-calculator")
                    .show();
            },
            mouseleave: function () {
                $(this)
                    .children(".wcbef-calculator")
                    .hide();
            }
        },
        "td[data-content-type=regular_price], td[data-content-type=sale_price], td[data-content-type=numeric]"
    );

    $(".wcbef-datepicker").datepicker({ dateFormat: "yy/mm/dd" });

    $(document).on("change", ".wcbef-inline-edit-action", function () {
        let wcbefField;
        let reload_products = true;
        let ProductIds;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");
        let bindEdit = $("#wcbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && productsChecked.length > 0) {
            ProductIds = productsChecked.map(function (i) {
                return $(this).val();
            }).get();
            ProductIds[productsChecked.length] = $(this).attr("data-product-id");
        } else {
            ProductIds = [];
            ProductIds[0] = $(this).attr("data-product-id");
        }
        if ($(this).attr("data-field-type")) {
            wcbefField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wcbefField = $(this).attr("data-field");
        }
        let wcbefValue;

        if ($(this).attr("type") === "checkbox") {
            wcbefValue = $(this).prop("checked") ? "yes" : "no";
        } else {
            wcbefValue = $(this).val();
        }

        wcbefInlineEditAjax(ProductIds, wcbefField, wcbefValue, reload_products);
    });

    $(document).on("click", ".wcbef-inline-edit-clear-date", function () {
        let wcbefField;
        let reload_products = true;
        let ProductIds;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");
        let bindEdit = $("#wcbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && productsChecked.length > 0) {
            ProductIds = productsChecked.map(function (i) {
                return $(this).val();
            }).get();
            ProductIds[productsChecked.length] = $(this).attr("data-product-id");
        } else {
            ProductIds = [];
            ProductIds[0] = $(this).attr("data-product-id");
        }

        if ($(this).attr("data-field-type")) {
            wcbefField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wcbefField = $(this).attr("data-field");
        }

        wcbefInlineEditAjax(ProductIds, wcbefField, '', reload_products);
    });

    $(document).on("click", ".wcbef-edit-action-price-calculator", function () {
        let productID = $(this).attr("data-product-id");
        let ProductIds;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");
        let bindEdit = $("#wcbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && productsChecked.length > 0) {
            ProductIds = productsChecked.map(function (i) {
                return $(this).val();
            }).get();
            ProductIds[productsChecked.length] = productID;
        } else {
            ProductIds = [];
            ProductIds[0] = productID;
        }

        let wcbefField = $(this).attr("data-field");
        let values = {
            operator: $("#wcbef-" + wcbefField + "-calculator-operator-" + productID).val(),
            value: $("#wcbef-" + wcbefField + "-calculator-value-" + productID).val(),
            operator_type: $("#wcbef-" + wcbefField + "-calculator-type-" + productID).val(),
            roundItem: $("#wcbef-" + wcbefField + "-calculator-round-" + productID).val()
        };

        wcbefEditByCalculatorAjax(ProductIds, wcbefField, values);
    });

    $(document).on("click", ".wcbef-edit-action-with-button", function () {
        let reload_products = true;
        let ProductIds;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");
        let bindEdit = $("#wcbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && productsChecked.length > 0) {
            ProductIds = productsChecked.map(function (i) {
                return $(this).val();
            }).get();
            ProductIds[productsChecked.length] = $(this).attr("data-product-id");
        } else {
            ProductIds = [];
            ProductIds[0] = $(this).attr("data-product-id");
        }

        let wcbefField;
        if ($(this).attr("data-field-type")) {
            wcbefField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wcbefField = $(this).attr("data-field");
        }
        let wcbefValue;
        switch ($(this).attr("data-content-type")) {
            case "textarea":
                wcbefValue = tinymce.get("wcbef-text-editor").getContent();
                break;
            case "select_products":
                wcbefValue = $('#wcbef-select-products-value').val();
                break;
            case "select_files":
                let names = $('.wcbef-inline-edit-file-name').map(function () {
                    return $(this).val();
                }).get();

                let urls = $('.wcbef-inline-edit-file-url').map(function () {
                    return $(this).val();
                }).get();

                wcbefValue = {
                    files_name: names,
                    files_url: urls,
                };
                break;
            case "image":
                wcbefValue = $(this).attr("data-image-id");
                break;
            case "gallery":
                wcbefValue = $("div[data-gallery-id=wcbef-gallery-items-" + $(this).attr("data-product-id") + "] input.wcbef-inline-edit-gallery-image-ids").map(function () {
                    return $(this).val();
                }).get();
                break;
        }
        wcbefInlineEditAjax(ProductIds, wcbefField, wcbefValue, reload_products);
    });

    $(document).on("click", "#wcbef-get-meta-fields-by-product-id", function () {
        $(".wcbef-meta-fields-empty-text").hide();
        let input = $("#wcbef-add-meta-fields-product-id");
        wcbefAddMetaKeysByProductIDAjax(input.val());
        input.val("");
    });

    $(document).on("click", "#wcbef-add-meta-field-manual", function () {
        $(".wcbef-meta-fields-empty-text").hide();
        let input = $("#wcbef-meta-fields-manual_key_name");
        wcbefAddMetaKeysManualAjax(input.val());
        input.val("");
    });

    $(document).on("click", ".wcbef-meta-field-remove", function () {
        $(this).closest(".wcbef-meta-fields-right-item").remove();
        if ($(".wcbef-meta-fields-right-item").length < 1) {
            $(".wcbef-meta-fields-empty-text").show();
        }
    });

    $(document).on("change", ".wcbef-meta-fields-main-type", function () {
        if ($(this).val() !== "textinput") {
            $(".wcbef-meta-fields-sub-type[data-id=" + $(this).attr("data-id") + "]").hide();
        } else {
            $(".wcbef-meta-fields-sub-type[data-id=" + $(this).attr("data-id") + "]").show();
        }
    });

    // Drag and drop items
    let wcbefMetaFieldItems = $(".wcbef-meta-fields-right");
    wcbefMetaFieldItems.sortable({
        handle: ".wcbef-meta-field-item-sortable-btn",
        cancel: ""
    });
    wcbefMetaFieldItems.disableSelection();

    let wcbefColumnManagerFields = $(".wcbef-column-manager-added-fields");
    wcbefColumnManagerFields.sortable({
        handle: ".wcbef-column-manager-field-sortable-btn",
        cancel: ""
    });
    wcbefColumnManagerFields.disableSelection();

    let wcbefSelectFiles = $(".wcbef-inline-select-files");
    wcbefSelectFiles.sortable({
        handle: ".wcbef-select-files-sortable-btn",
        cancel: ""
    });
    wcbefColumnManagerFields.disableSelection();

    // add new field in column manager
    $(document).on("click", ".wcbef-column-manager-add-field", function () {
        let fieldName = [];
        let fieldLabel = [];
        let action = $(this).attr("data-action");
        let checked = $(".wcbef-column-manager-available-fields[data-action=" + action + "] input:checkbox:checked");
        if (checked.length > 0) {
            $('.wcbef-column-manager-empty-text').hide();
            checked.each(function (i) {
                fieldName[i] = $(this).attr("data-name");
                fieldLabel[i] = $(this).val();
            });
            wcbefColumnManagerAddFieldAjax(fieldName, fieldLabel, action);
        }
    });

    $(document).on("click", ".wcbef-column-manager-remove-field", function () {
        $(".wcbef-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] li[data-name=" + $(this).attr("data-name") + "]").attr("data-added", "false").show();
        $(this).closest(".wcbef-column-manager-right-item").remove();
        if ($('.wcbef-column-manager-right-item').length < 1) {
            $('.wcbef-column-manager-empty-text').show();
        }
    });

    $(document).on("click", ".wcbef-column-manager-edit-field-btn", function () {
        let presetKey = $(this).val();
        $("#wcbef-column-manager-edit-preset-key").val(presetKey);
        $("#wcbef-column-manager-edit-preset-name").val(
            $(this).attr("data-preset-name")
        );
        wcbefColumnManagerFieldsGetForEditAjax(presetKey);
    });

    $(document).on("change", ".wcbef-column-manager-check-all-fields-btn input:checkbox", function () {
        if ($(this).prop("checked")) {
            $(this).closest("label").find("span").addClass("selected").text("Unselect");
            $(".wcbef-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible").each(function () {
                $(this).find("input:checkbox").prop("checked", true);
            });
        } else {
            $(this).closest("label").find("span").removeClass("selected").text("Select All");
            $(".wcbef-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible input:checked").prop("checked", false);
        }
    });

    $(".wcbef-column-manager-delete-preset").on("click", function () {
        var $this = $(this);
        $("#wcbef_column_manager_delete_preset_key").val($this.val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
            confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wcbef-column-manager-delete-preset-form").submit();
            }
        }
        );
    });

    $("#wcbef-column-manager-add-new-preset").on("submit", function (e) {
        if ($(this).find(".wcbef-column-manager-added-fields .wcbef-column-manager-right-item").length < 1) {
            e.preventDefault();
            swal({
                title: "Please Add Columns !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wcbef-filter-form-reset", function () {
        reset_filter_form();
        reset_quick_search_form();
        $(".wcbef-filter-profiles-items tr").removeClass(
            "wcbef-filter-profile-loaded"
        );
        $('input.wcbef-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
        $("#wcbef-bulk-edit-reset-filter").hide();
        wcbefFilterProfileChangeUseAlways("default");
        let data = get_current_filer_data();
        wcbefProductsFilterAjax(data, "pro_search");
    });

    $(document).on("click", "#wcbef-bulk-edit-form-reset", function () {
        reset_bulk_edit_form();
        $("nav.wcbef-tabs-navbar li a").removeClass("wcbef-tab-changed");
    });

    $(document).on("click", "#wcbef-filter-form-save-preset", function () {
        let presetName = $("#wcbef-filter-form-save-preset-name").val();
        if (presetName !== "") {
            let data = pro_search_data();
            wcbefSaveFilterPresetAjax(data, presetName);
        } else {
            swal({
                title: "Preset name is required !",
                type: "warning"
            });
        }
    });

    $(document).on("click", ".wcbef-filter-form-action", function (e) {
        let data = get_current_filer_data();
        let page;
        let action = $(this).attr("data-search-action");
        if (action === "pagination") {
            page = $(this).attr("data-index");
        }
        if (action === "quick_search") {
            reset_filter_form();
        }
        if (action === "pro_search") {
            $('#wcbef-bulk-edit-reset-filter').show();
            reset_quick_search_form();
            $(".wcbef-filter-profiles-items tr").removeClass("wcbef-filter-profile-loaded");
            $('input.wcbef-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
            wcbefFilterProfileChangeUseAlways("default");
        }
        wcbefProductsFilterAjax(data, action, null, page);
    });

    $(document).on("change", "#wcbef-quick-search-field", function () {
        let options = $("#wcbef-quick-search-operator option");
        switch ($(this).val()) {
            case "title":
                options.each(function () {
                    $(this).closest("select").prop("selectedIndex", 0);
                    $(this).prop("disabled", false);
                });
                break;
            case "id":
                options.each(function () {
                    $(this).closest("select").prop("selectedIndex", 1);
                    if ($(this).attr("value") === "exact") {
                        $(this).prop("disabled", false);
                    } else {
                        $(this).prop("disabled", true);
                    }
                });
                break;
        }
    });

    $(document).on("click", "#wcbef-bulk-edit-form-do-bulk-edit", function (e) {
        let productIDs;
        let filterData;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");

        let gallery_images = $("#wcbef-bulk-edit-form-product-gallery input").map(function () {
            return $(this).val();
        }).get();

        let taxonomies = [];
        let custom_fields = [];
        let i = 0;
        let j = 0;
        $(".wcbef-bulk-edit-form-group[data-type=attribute]").each(function () {
            if ($(this).find("select[data-field=value]").val() != null) {
                taxonomies[i++] = {
                    taxonomy: $(this).attr("data-taxonomy"),
                    operator: $(this).find("select[data-field=operator]").val(),
                    value: $(this).find("select[data-field=value]").val()
                };
            }
        });
        $(".wcbef-bulk-edit-form-group[data-type=custom_fields]").each(function () {
            if ($(this).find("input[data-field=value]").val() != null) {
                custom_fields[j++] = {
                    field: $(this).attr("data-taxonomy"),
                    operator: $(this).find("select[data-field=operator]").val(),
                    value: $(this).find("input[data-field=value]").val()
                };
            }
        });

        let data = {
            post_title: {
                value: $("#wcbef-bulk-edit-form-product-title").val(),
                replace: $("#wcbef-bulk-edit-form-product-title-replace").val(),
                sensitive: $("#wcbef-bulk-edit-form-product-title-sensitive").val(),
                operator: $("#wcbef-bulk-edit-form-product-title-operator").val()
            },
            post_slug: {
                value: $("#wcbef-bulk-edit-form-product-slug").val(),
                replace: $("#wcbef-bulk-edit-form-product-slug-replace").val(),
                sensitive: $("#wcbef-bulk-edit-form-product-slug-sensitive").val(),
                operator: $("#wcbef-bulk-edit-form-product-slug-operator").val()
            },
            sku: {
                value: $("#wcbef-bulk-edit-form-product-sku").val(),
                replace: $("#wcbef-bulk-edit-form-product-sku-replace").val(),
                sensitive: $("#wcbef-bulk-edit-form-product-sku-sensitive").val(),
                operator: $("#wcbef-bulk-edit-form-product-sku-operator").val()
            },
            post_content: {
                value: $("#wcbef-bulk-edit-form-product-description").val(),
                replace: $("#wcbef-bulk-edit-form-product-description-replace").val(),
                sensitive: $("#wcbef-bulk-edit-form-product-description-sensitive").val(),
                operator: $("#wcbef-bulk-edit-form-product-description-operator").val()
            },
            post_excerpt: {
                value: $("#wcbef-bulk-edit-form-product-short-description").val(),
                replace: $("#wcbef-bulk-edit-form-product-short-description-replace").val(),
                sensitive: $("#wcbef-bulk-edit-form-product-short-description-sensitive").val(),
                operator: $("#wcbef-bulk-edit-form-product-short-description-operator").val()
            },
            purchase_note: {
                value: $("#wcbef-bulk-edit-form-product-purchase-note").val(),
                replace: $("#wcbef-bulk-edit-form-product-purchase-note-replace").val(),
                sensitive: $("#wcbef-bulk-edit-form-product-purchase-note-sensitive").val(),
                operator: $("#wcbef-bulk-edit-form-product-purchase-note-operator").val()
            },
            menu_order: {
                value: $("#wcbef-bulk-edit-form-product-menu-order").val()
            },
            sold_individually: {
                value: $("#wcbef-bulk-edit-form-product-sold-individually").val()
            },
            reviews_allowed: {
                value: $("#wcbef-bulk-edit-form-product-enable-reviews").val()
            },
            post_status: {
                value: $("#wcbef-bulk-edit-form-product-product-status").val()
            },
            catalog_visibility: {
                value: $("#wcbef-bulk-edit-form-product-catalog-visibility").val()
            },
            post_date: {
                value: $("#wcbef-bulk-edit-form-product-date-created").val()
            },
            post_author: {
                value: $("#wcbef-bulk-edit-form-product-author").val()
            },
            _thumbnail_id: {
                value: $("#wcbef-bulk-edit-form-product-image").val()
            },
            gallery: {
                value: gallery_images
            },
            product_cat: {
                value: $("#wcbef-bulk-edit-form-categories").val(),
                operator: $("#wcbef-bulk-edit-form-categories-operator").val()
            },
            product_tag: {
                value: $("#wcbef-bulk-edit-form-tags").val(),
                operator: $("#wcbef-bulk-edit-form-tags-operator").val()
            },
            taxonomy: taxonomies,
            custom_field: custom_fields,
            regular_price: {
                value: $("#wcbef-bulk-edit-form-regular-price").val(),
                round_item: $("#wcbef-bulk-edit-form-regular-price-round-item").val(),
                operator: $("#wcbef-bulk-edit-form-regular-price-operator").val()
            },
            sale_price: {
                value: $("#wcbef-bulk-edit-form-sale-price").val(),
                round_item: $("#wcbef-bulk-edit-form-sale-price-round-item").val(),
                operator: $("#wcbef-bulk-edit-form-sale-price-operator").val()
            },
            date_on_sale_from: {
                value: $("#wcbef-bulk-edit-form-sale-date-from").val()
            },
            date_on_sale_to: {
                value: $("#wcbef-bulk-edit-form-sale-date-to").val()
            },
            tax_status: {
                value: $("#wcbef-bulk-edit-form-tax-status").val()
            },
            tax_class: {
                value: $("#wcbef-bulk-edit-form-tax-class").val()
            },
            shipping_class: {
                value: $("#wcbef-bulk-edit-form-shipping-class").val()
            },
            width: {
                value: $("#wcbef-bulk-edit-form-width").val(),
                operator: $("#wcbef-bulk-edit-form-width-operator").val()
            },
            height: {
                value: $("#wcbef-bulk-edit-form-height").val(),
                operator: $("#wcbef-bulk-edit-form-height-operator").val()
            },
            length: {
                value: $("#wcbef-bulk-edit-form-length").val(),
                operator: $("#wcbef-bulk-edit-form-length-operator").val()
            },
            weight: {
                value: $("#wcbef-bulk-edit-form-weight").val(),
                operator: $("#wcbef-bulk-edit-form-weight-operator").val()
            },
            manage_stock: {
                value: $("#wcbef-bulk-edit-form-manage-stock").val()
            },
            stock_status: {
                value: $("#wcbef-bulk-edit-form-stock-status").val()
            },
            stock_quantity: {
                value: $("#wcbef-bulk-edit-form-stock-quantity").val(),
                operator: $("#wcbef-bulk-edit-form-stock-quantity-operator").val()
            },
            backorders: {
                value: $("#wcbef-bulk-edit-form-backorders").val()
            },
            download_limit: {
                value: $("#wcbef-bulk-edit-form-download-limit").val()
            },
            download_expiry: {
                value: $("#wcbef-bulk-edit-form-download-expiry").val()
            },
            product_url: {
                value: $("#wcbef-bulk-edit-form-product-url").val()
            },
            button_text: {
                value: $("#wcbef-bulk-edit-form-button-text").val()
            },
            upsell_ids: {
                value: $("#wcbef-bulk-edit-form-upsells").val(),
                operator: $("#wcbef-bulk-edit-form-upsells-operator").val()
            },
            cross_sell_ids: {
                value: $("#wcbef-bulk-edit-form-cross-sells").val(),
                operator: $("#wcbef-bulk-edit-form-cross-sells-operator").val()
            },
            product_type: {
                value: $("#wcbef-bulk-edit-form-product-type").val()
            },
            featured: {
                value: $("#wcbef-bulk-edit-form-featured").val()
            },
            virtual: {
                value: $("#wcbef-bulk-edit-form-virtual").val()
            },
            downloadable: {
                value: $("#wcbef-bulk-edit-form-downloadable").val()
            }
        };

        if (productsChecked.length > 0) {
            productIDs = productsChecked.map(function () {
                return $(this).val();
            }).get();
            closeModal();
            wcbefProductsBulkEditAjax(productIDs, data, filterData);
        } else {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
                confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    closeModal();
                    wcbefProductsBulkEditAjax(productIDs, data, filterData);
                }
            }
            );
            filterData = get_current_filer_data();
        }
    });

    // keypress: Enter
    $(document).on("keypress", function (e) {
        if (e.keyCode === 13) {
            if ($("#wcbef-filter-form-content").attr("data-visibility") === "visible" || ($('#wcbef-quick-search-text').val() !== '' && $($('#wcbef-last-modal-opened').val()).css('display') !== 'block' && $('.wcbef-tabs-list a[data-content=bulk-edit]').hasClass('selected'))) {
                reload_products();
                $("#wcbef-bulk-edit-reset-filter").show();
            }

            if ($("#wcbef-modal-new-product").css("display") === "block") {
                $("#wcbef-create-new-product").trigger("click");
            }
            if ($("#wcbef-modal-product-duplicate").css("display") === "block") {
                $("#wcbef-bulk-edit-duplicate-start").trigger("click");
            }

            let metaFieldManualInput = $("#wcbef-meta-fields-manual_key_name");
            let metaFieldProductId = $("#wcbef-add-meta-fields-product-id");
            if (metaFieldManualInput.val() !== "") {
                $(".wcbef-meta-fields-empty-text").hide();
                wcbefAddMetaKeysManualAjax(metaFieldManualInput.val());
                metaFieldManualInput.val("");
            }
            if (metaFieldProductId.val() !== "") {
                $(".wcbef-meta-fields-empty-text").hide();
                wcbefAddMetaKeysByProductIDAjax(metaFieldProductId.val());
                metaFieldProductId.val("");
            }
        }
    });

    let query;
    $(".wcbef-get-products-ajax").select2({
        ajax: {
            type: "post",
            delay: 800,
            url: WCBEF_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wcbef_get_products_name",
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Product Name ...",
        minimumInputLength: 3
    });

    $(document).on("change", "select[data-field=operator]", function () {
        let id = $(this).closest(".wcbef-filters-form-group").find("label").attr("for");
        if ($(this).val() === "text_replace") {
            $(this).closest(".wcbef-filters-form-group").append('<div class="wcbef-bulk-edit-form-extra-field"><select id="' + id + '-sensitive"><option value="yes">Same Case</option><option value="no">Ignore Case</option></select><input type="text" id="' + id + '-replace" placeholder="Text ..."><select class="wcbef-bulk-edit-form-variable" title="Select Variable" data-field="variable"><option value="">Variable</option><option value="title">Title</option><option value="id">ID</option><option value="sku">SKU</option><option value="menu_order">Menu Order</option><option value="parent_id">Parent ID</option><option value="parent_title">Parent Title</option><option value="parent_sku">Parent SKU</option><option value="regular_price">Regular Price</option><option value="sale_price">Sale Price</option></select></div>');
        } else if ($(this).val() === "number_round") {
            $(this).closest(".wcbef-filters-form-group").append('<div class="wcbef-bulk-edit-form-extra-field"><select id="' + id + '-round-item"><option value="5">5</option><option value="10">10</option><option value="19">19</option><option value="29">29</option><option value="39">39</option><option value="49">49</option><option value="59">59</option><option value="69">69</option><option value="79">79</option><option value="89">89</option><option value="99">99</option></select></div>');
        } else {
            $(this).closest(".wcbef-filters-form-group").find(".wcbef-bulk-edit-form-extra-field").remove();
        }
        if ($(this).val() === "number_clear") {
            $(this).closest(".wcbef-filters-form-group").find('input[data-field=value]').prop('disabled', true);
        } else {
            $(this).closest(".wcbef-filters-form-group").find('input[data-field=value]').prop('disabled', false);
        }
        changedTabs($(this));
    });

    $(document).on("click", "#wcbef-create-new-product", function () {
        let count = $("#wcbef-new-product-count").val();
        wcbefCreateNewProductAjax(count);
    });

    // Set Tooltip
    setTipsyTooltip();

    $(document).on("select2:select", "#wcbef-variation-bulk-edit-attributes", function (e) {
        getAttributeValues(e.params.data.id, "#wcbef-variation-bulk-edit-attributes-added");
    });

    $(document).on("select2:select", "#wcbef-variation-bulk-edit-delete-attributes", function (e) {
        getAttributeValuesForDelete(e.params.data.id, "#wcbef-variation-bulk-edit-delete-attributes-added");
    });

    $(document).on("select2:unselect", "#wcbef-variation-bulk-edit-attributes", function (e) {
        $("div[data-id=wcbef-variation-bulk-edit-attribute-item-" + e.params.data.id + "]").remove();
        $(".wcbef-variation-bulk-edit-attribute-item[data-id=" + e.params.data.id + "]").remove();
    });

    $(document).on("select2:unselect", "#wcbef-variation-bulk-edit-delete-attributes", function (e) {
        $("div[data-id=wcbef-variation-bulk-edit-delete-attribute-item-" + e.params.data.id + "]").remove();
    });

    $(document).on("click", "#wcbef-variation-bulk-edit-generate", function () {
        let attributes = [];
        let currents = [];
        $(".wcbef-variation-bulk-edit-current-item-name").each(function () {
            currents.push($(this).find("span").text());
        });

        $(".wcbef-variation-bulk-edit-attribute-item").each(function () {
            if ($(this).find("select").val()) {
                attributes.push([$(this).find("select").attr("data-attribute-name"), $(this).find("select").val()]);
            }
        });

        let combinations = getAllCombinations(attributes);

        if (combinations.length > 0) {
            $(".wcbef-variation-bulk-edit-current-items").html("");
            combinations.forEach(function (value) {
                let variation = value.map(function (val) {
                    return val[1];
                });
                $(".wcbef-variation-bulk-edit-current-items").append('<div class="wcbef-variation-bulk-edit-current-item"><label class="wcbef-variation-bulk-edit-current-item-name"><input type="checkbox" name="variation_item[]" checked="checked" value="' + value.join("&&") + '"><span>' + variation.join(" | ") + '</span></label><button type="button" class="wcbef-button wcbef-button-flat wcbef-variation-bulk-edit-current-item-sortable-btn" title="Drag"><i class="lni lni-menu"></i></button><div class="wcbef-variation-bulk-edit-current-item-radio"><input type="radio" name="default_variation" value="' + value.join("&&") + '" title="Set as default"></div></div>');
                $("#wcbef-variation-bulk-edit-do-bulk-variations").prop("disabled", false);
            });
        }
        setTipsyTooltip();
    });

    $(document).on("click", ".wcbef-bulk-edit-variations", function () {
        // set sortable
        let variationCurrentItems = $(".wcbef-variation-bulk-edit-current-items");
        variationCurrentItems.sortable({
            handle: ".wcbef-variation-bulk-edit-current-item-sortable-btn",
            cancel: ""
        });
        variationCurrentItems.disableSelection();

        // get product variations
        let productID = $("input.wcbef-product-id:checkbox:checked");
        if (productID.length === 1) {
            variationCurrentItems.html("Loading");
            wcbefGetProductVariationsAjax(productID.val());
            $("#wcbef-variation-single-delete-variations").show();
            $("#wcbef-variations-multiple-products-delete-variation").hide();
            $("#wcbef-variation-attaching-variable-id").val(productID.val()).change();
            $("#wcbef-variation-attaching-get-variations").prop("disabled", false).trigger("click");
        } else if (productID.length > 1) {
            $("#wcbef-variation-bulk-edit-attributes-added").html("");
            $("#wcbef-variation-bulk-edit-attributes").val("").change();
            $(".wcbef-variation-bulk-edit-individual-items").html("");
            $(".wcbef-variation-bulk-edit-current-items").html("");
            $("#wcbef-variation-single-delete-items").html("");
            $("#wcbef-variation-single-delete-variations").hide();
            $("#wcbef-variations-multiple-products-delete-variation").show();
            $("#wcbef-variation-bulk-edit-do-bulk-variations").attr("disabled", "disabled");
            $("#wcbef-variation-bulk-edit-manual-add").attr("disabled", "disabled");
            $("#wcbef-variation-bulk-edit-generate").attr("disabled", "disabled");
            $("#wcbef-variation-attaching-variable-id").val("").change();
            $("#wcbef-variation-attaching-get-variations").prop("disabled", false);
            $("#wcbef-variations-attaching-product-variations").html("");
        } else if (productID.length < 1) {
            variationCurrentItems.html("");
            $("#wcbef-variation-bulk-edit-attributes-added").html("");
            $("#wcbef-variation-bulk-edit-attributes").val("").change();
            $(".wcbef-variation-bulk-edit-individual-items").html("");
            $(".wcbef-variation-bulk-edit-current-items").html("");
            $("#wcbef-variation-single-delete-items").html("");
            $("#wcbef-variation-single-delete-variations").hide();
            $("#wcbef-variation-bulk-edit-do-bulk-variations").attr("disabled", "disabled");
            $("#wcbef-variation-bulk-edit-manual-add").attr("disabled", "disabled");
            $("#wcbef-variation-bulk-edit-generate").attr("disabled", "disabled");
            $("#wcbef-variations-multiple-products-delete-variation").show();
            $("#wcbef-variation-attaching-variable-id").val("").change();
            $("#wcbef-variation-attaching-get-variations").attr("disabled", "disabled");
            $("#wcbef-variations-attaching-product-variations").html("");
        }
    });

    $(document).on("click", "#wcbef-variation-bulk-edit-do-bulk-variations", function () {
        let ProductIds;
        let defaultVariation = $(".wcbef-variation-bulk-edit-current-item .wcbef-variation-bulk-edit-current-item-radio input:radio:checked[name=default_variation]").val();
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");
        let attributes = [];
        $(".wcbef-variation-bulk-edit-attribute-item").each(function () {
            let selectItem = $(this).find("select");
            let ids = selectItem.select2().find(":selected").map(function () {
                return $(this).attr("data-id");
            }).toArray();
            if (selectItem.val() != null) {
                attributes.push([$(this).find("select").attr("data-attribute-name"), ids]);
            }
        });

        let variations = [];
        $('input:checkbox:checked[name="variation_item[]"]').each(function () {
            variations.push([$(this).val(), $(this).attr("data-id")]);
        });

        if (productsChecked.length > 0) {
            let notVariable = 0;
            productsChecked.each(function () {
                if ($(this).attr("data-product-type") !== "variable") {
                    notVariable++;
                }
            });
            if (variations.length > 0) {
                ProductIds = productsChecked.map(function () {
                    return $(this).val();
                }).get();
                if (notVariable > 0) {
                    swal({
                        title: notVariable + " selected products is not variable! Do you want to change products type?",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
                        confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
                        confirmButtonText: "Yes, I'm sure !",
                        closeOnConfirm: true
                    }, function (isConfirm) {
                        if (isConfirm === true) {
                            wcbefSetProductsVariations(ProductIds, attributes, variations, defaultVariation);
                        }
                    });
                } else {
                    wcbefSetProductsVariations(ProductIds, attributes, variations, defaultVariation);
                }
            } else {
                swal({
                    title: "variation is required !",
                    type: "warning"
                });
            }
        } else {
            swal({
                title: "Select product is required !",
                type: "warning"
            });
        }
    });

    $(document).on("change", "input:radio[name=create_variation_mode]", function () {
        if ($(this).attr("data-mode") === "all_combination") {
            $("#wcbef-variation-bulk-edit-individual").hide();
            $("#wcbef-variation-bulk-edit-generate").show();
        } else {
            $("#wcbef-variation-bulk-edit-generate").hide();
            $("#wcbef-variation-bulk-edit-individual").show();
        }
    }
    );

    $(document).on("select2:select", ".wcbef-select2-ajax", function (e) {
        if ($(".wcbef-variation-bulk-edit-individual-items div[data-id=" + $(this).attr("id") + "]").length === 0) {
            $(".wcbef-variation-bulk-edit-individual-items").append('<div data-id="' + $(this).attr("id") + '"><select class="wcbef-variation-bulk-edit-manual-item" data-attribute-name="' + $(this).attr("data-attribute-name") + '"></select></div>');
        }
        $(".wcbef-variation-bulk-edit-individual-items div[data-id=" + $(this).attr("id") + "]").find("select").append('<option value="' + e.params.data.id + '">' + e.params.data.id + "</option>");
        $("#wcbef-variation-bulk-edit-manual-add").prop("disabled", false);
        $("#wcbef-variation-bulk-edit-generate").prop("disabled", false);
    });

    $(document).on("select2:unselect", ".wcbef-select2-ajax", function (e) {
        $(".wcbef-variation-bulk-edit-individual-items div[data-id=" + $(this).attr("id") + "]").find("option[value=" + e.params.data.id + "]").remove();
        if ($(".wcbef-variation-bulk-edit-attribute-item").find(".select2-selection__choice").length === 0) {
            $("#wcbef-variation-bulk-edit-manual-add").attr("disabled", "disabled");
            $("#wcbef-variation-bulk-edit-generate").attr("disabled", "disabled");
        }
        if ($(this).val() === null) {
            $("div[data-id=wcbef-variation-bulk-edit-attribute-item-" + $(this).attr("data-attribute-name") + "]").remove();
        }
    });

    $(document).on("click", "#wcbef-variation-bulk-edit-manual-add", function () {
        let attributes = [];
        let currents = [];
        $(".wcbef-variation-bulk-edit-current-item-name").each(function () {
            currents.push($(this).find("span").text());
        });

        $(".wcbef-variation-bulk-edit-manual-item").each(function () {
            if ($(this).val()) {
                attributes.push([$(this).attr("data-attribute-name"), $(this).val()]);
            }
        });

        let label = attributes.map(function (val) {
            return val[1];
        });

        // generate if not exist
        if (jQuery.inArray(label.join(" | "), currents) === -1) {
            $(".wcbef-variation-bulk-edit-current-items").append('<div class="wcbef-variation-bulk-edit-current-item"><label class="wcbef-variation-bulk-edit-current-item-name"><input type="checkbox" name="variation_item[]" checked="checked" value="' + attributes.join("&&") + '"><span>' + label.join(" | ") + '</span></label><button type="button" class="wcbef-button wcbef-button-flat wcbef-variation-bulk-edit-current-item-sortable-btn" title="Drag"><i class="lni lni-menu"></i></button><div class="wcbef-variation-bulk-edit-current-item-radio"><input type="radio" name="default_variation" title="Set as default"></div></div>');
            $("#wcbef-variation-bulk-edit-do-bulk-variations").prop("disabled", false);
        }

        setTipsyTooltip();
    });

    $(document).on("change", "input:radio[name=delete_variation_mode]", function () {
        if ($(this).attr("data-mode") === "delete_all") {
            $("#wcbef-variation-delete-single-delete").hide();
            $("#wcbef-variation-delete-delete-all").show();
        } else {
            $("#wcbef-variation-delete-delete-all").hide();
            $("#wcbef-variation-delete-single-delete").show();
        }
    });

    $(document).on("click", "#wcbef-variation-delete-all", function () {
        let deleteType = "all_variations";
        let ProductIds;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked[data-product-type=variable]");
        if (productsChecked.length > 0) {
            ProductIds = productsChecked.map(function () {
                return $(this).val();
            }).get();
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
                confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm === true) {
                    wcbefDeleteProductsVariations(
                        ProductIds,
                        deleteType,
                        "all_variations"
                    );
                }
            });
        } else {
            swal({
                title: "Select variable product is required !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wcbef-variation-delete-selected", function () {
        let deleteType = "single_product";
        let ProductIds;
        let variations;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked[data-product-type=variable]");
        if (productsChecked.length > 0) {
            ProductIds = productsChecked.map(function () {
                return $(this).val();
            }).get();
            variations = $("#wcbef-variation-single-delete-items input:checkbox:checked").map(function () {
                return $(this).val();
            }).get();
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
                confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm === true) {
                    wcbefDeleteProductsVariations(ProductIds, deleteType, variations);
                }
            });
        } else {
            swal({
                title: "Select variable product is required !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wcbef-variation-delete-selected-variation", function () {
        let deleteType = "multiple_product";
        let ProductIds;
        let variations = [];
        let attributeName;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked[data-product-type=variable]");
        if (productsChecked.length > 0) {
            ProductIds = productsChecked.map(function () {
                return $(this).val();
            }).get();

            $("#wcbef-variation-bulk-edit-delete-attributes-added select").each(function () {
                attributeName = "attribute_pa_" + encodeURIComponent($(this).attr("data-name"));
                attributeName = attributeName.toLowerCase();
                variations.push({ [attributeName]: $(this).val() });
            });

            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
                confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm === true) {
                    wcbefDeleteProductsVariations(ProductIds, deleteType, variations);
                }
            });
        } else {
            swal({
                title: "Select variable product is required !",
                type: "warning"
            });
        }
    });

    $(document).on("change", "#wcbef-variation-single-delete-items input:checkbox", function () {
        if ($("#wcbef-variation-single-delete-items input:checkbox:checked").length > 0) {
            $("#wcbef-variation-delete-selected").prop("disabled", false);
        } else {
            $("#wcbef-variation-delete-selected").attr("disabled", "disabled");
        }
    });

    $(document).on("change", "#wcbef-variation-bulk-edit-attributes", function () {
        if ($(this).val() === null) {
            $("#wcbef-variation-bulk-edit-generate").attr("disabled", "disabled");
            $("#wcbef-variation-bulk-edit-manual-add").attr("disabled", "disabled");
        }
    });

    $(document).on("change", "#wcbef-bulk-edit-show-variations", function () {
        if ($(this).prop("checked") === true) {
            $("tr[data-product-type=variation]").show();
            showVariationSelectionTools();
        } else {
            $("tr[data-product-type=variation]").hide();
            hideVariationSelectionTools();
        }
    });

    if ($("#wcbef-bulk-edit-show-variations").prop("checked") === true) {
        $("tr[data-product-type=variation]").show();
        showVariationSelectionTools();
    } else {
        $("tr[data-product-type=variation]").hide();
        hideVariationSelectionTools();
    }

    $(document).on("change", "#wcbef-bulk-edit-select-all-variations", function () {
        if ($(this).prop("checked") === true) {
            $("input.wcbef-check-item[data-product-type=variation]").prop("checked", true);
        } else {
            $("input.wcbef-check-item[data-product-type=variation]").prop("checked", false);
        }
    });

    $(document).on("click", "#wcbef-bulk-edit-unselect", function () {
        $("input.wcbef-check-item").prop("checked", false);
        $("input.wcbef-check-item-main").prop("checked", false);
        hideProductSelectionTools();
    });

    $(document).on("click", ".wcbef-bulk-edit-delete-action", function () {
        let deleteType = $(this).attr('data-delete-type');
        let ProductIds;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");
        ProductIds = productsChecked.map(function () {
            return $(this).val();
        }).get();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
            confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (ProductIds.length > 0) {
                    wcbefDeleteProductAjax(ProductIds, deleteType);
                } else {
                    swal({
                        title: "Please Select Product !",
                        type: "warning"
                    });
                }
            }
        });
    });

    $(document).on("click", "#wcbef-bulk-edit-duplicate-start", function () {
        let productIDs = $("input.wcbef-product-id:checkbox:checked").map(function () {
            if ($(this).attr('data-product-type') === 'variation') {
                swal({
                    title: "Dublicate for variations product is disabled!",
                    type: "warning"
                });
                return false;
            }
            return $(this).val();
        }).get();
        wcbefDuplicateProductAjax(productIDs, parseInt($("#wcbef-bulk-edit-duplicate-number").val()));
    });

    $(document).on("click", ".wcbef-inline-edit-add-new-taxonomy", function () {
        $("#wcbef-create-new-product-taxonomy").attr("data-field", $(this).attr("data-field")).attr("data-product-id", $(this).attr("data-product-id"));
        $('#wcbef-modal-new-product-taxonomy-product-title').text($(this).attr('data-product-name'));
        wcbefGetTaxonomyParentSelectBoxAjax($(this).attr("data-field"));
    });

    $(document).on("click", "#wcbef-create-new-product-taxonomy", function () {
        if ($("#wcbef-new-product-category-name").val() !== "") {
            let taxonomyInfo = {
                name: $("#wcbef-new-product-taxonomy-name").val(),
                slug: $("#wcbef-new-product-taxonomy-slug").val(),
                parent: $("#wcbef-new-product-taxonomy-parent").val(),
                description: $("#wcbef-new-product-taxonomy-description").val(),
                product_id: $(this).attr("data-product-id")
            };
            wcbefAddProductTaxonomyAjax(taxonomyInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: "Taxonomy Name is required !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wcbef-create-new-product-attribute", function () {
        if ($("#wcbef-new-product-attribute-name").val() !== "") {
            let attributeInfo = {
                name: $("#wcbef-new-product-attribute-name").val(),
                slug: $("#wcbef-new-product-attribute-slug").val(),
                description: $("#wcbef-new-product-attribute-description").val(),
                product_id: $(this).attr("data-product-id")
            };
            wcbefAddProductAttributeAjax(attributeInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: "Attribute Name is required !",
                type: "warning"
            });
        }
    });

    $(document).on("click", ".wcbef-inline-edit-add-new-attribute", function () {
        $("#wcbef-create-new-product-attribute").attr("data-field", $(this).attr("data-field")).attr("data-product-id", $(this).attr("data-product-id"));
        $('#wcbef-modal-new-product-attribute-product-title').text($(this).attr('data-product-name'));
    });

    $(document).on("click", ".wcbef-bulk-edit-filter-profile-load", function () {
        wcbefLoadFilterProfileAjax($(this).val());
        if ($(this).val() !== "default") {
            $("#wcbef-bulk-edit-reset-filter").show();
        }
        $(".wcbef-filter-profiles-items tr").removeClass("wcbef-filter-profile-loaded");
        $(this).closest("tr").addClass("wcbef-filter-profile-loaded");
    });

    $(document).on("click", ".wcbef-bulk-edit-filter-profile-delete", function () {
        let presetKey = $(this).val();
        let item = $(this).closest("tr");
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
            confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wcbefDeleteFilterProfile(presetKey);
                item.remove();
            }
        });
    });

    $(document).on("change", "#wcbef-column-profiles-choose", function () {
        $('#wcbef-column-profile-select-all').prop('checked', false);
        $('.wcbef-column-profile-select-all span').text('Select All');
        $(".wcbef-column-profile-fields").hide();
        $(".wcbef-column-profile-fields[data-content=" + $(this).val() + "]").show();
        $("#wcbef-column-profiles-apply").attr("data-preset-key", $(this).val());
        if ($.inArray($(this).val(), ["default1", "default2", "default3"]) === -1) {
            $("#wcbef-column-profiles-update-changes").show();
        } else {
            $("#wcbef-column-profiles-update-changes").hide();
        }
    });

    $(document).on("click", "#wcbef-column-profiles-save-as-new-preset", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
            confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                let presetKey = $("#wcbef-column-profiles-choose").val();
                let items = $(
                    ".wcbef-column-profile-fields[data-content=" +
                    presetKey +
                    "] input:checkbox:checked"
                )
                    .map(function () {
                        return $(this).val();
                    })
                    .get();
                wcbefSaveColumnProfileAjax(presetKey, items, "save_as_new");
            }
        });
    });

    $(document).on("click", "#wcbef-column-profiles-update-changes", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
            confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                let presetKey = $("#wcbef-column-profiles-choose").val();
                let items = $(
                    ".wcbef-column-profile-fields[data-content=" +
                    presetKey +
                    "] input:checkbox:checked"
                )
                    .map(function () {
                        return $(this).val();
                    })
                    .get();
                wcbefSaveColumnProfileAjax(presetKey, items, "update_changes");
            }
        });
    });

    $(document).on("click", ".wcbef-load-text-editor", function () {
        let productId = $(this).attr("data-product-id");
        let field = $(this).attr("data-field");
        let fieldType = $(this).attr("data-field-type");
        $('#wcbef-modal-text-editor-product-title').text($(this).attr('data-product-name'));
        $("#wcbef-text-editor-apply").attr("data-field", field).attr("data-field-type", fieldType).attr("data-product-id", productId);
        $.ajax({
            url: WCBEF_DATA.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "wcbef_get_text_editor_content",
                product_id: productId,
                field: field,
                field_type: fieldType
            },
            success: function (response) {
                if (response.success) {
                    tinymce.get("wcbef-text-editor").setContent(response.content);
                }
            },
            error: function () { }
        });
    });

    $(document).on("click", ".wcbef-inline-edit-gallery-image-item-delete", function () {
        $(this).closest("div").remove();
    });

    $(document).on("click", "#wcbef-history-clear-all-btn", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
            confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wcbef-history-clear-all").submit();
            }
        });
    });

    $(document).on("click", ".wcbef-history-delete-item", function () {
        $("#wcbef-history-clicked-id").attr("name", "delete").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
            confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wcbef-history-items").submit();
            }
        });
    });

    $(document).on("click", ".wcbef-history-revert-item", function () {
        $("#wcbef-history-clicked-id").attr("name", "revert").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wcbef-button wcbef-button-lg wcbef-button-white",
            confirmButtonClass: "wcbef-button wcbef-button-lg wcbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wcbef-history-items").submit();
            }
        });
    });

    $(document).on("click", "#wcbef-bulk-edit-undo", function () {
        wcbefHistoryUndo();
    });

    $(document).on("click", "#wcbef-bulk-edit-redo", function () {
        wcbefHistoryRedo();
    });

    $(document).on("click", "#wcbef-history-filter-apply", function () {
        let filters = {
            operation: $("#wcbef-history-filter-operation").val(),
            author: $("#wcbef-history-filter-author").val(),
            fields: $("#wcbef-history-filter-fields").val(),
            date: {
                from: $("#wcbef-history-filter-date-from").val(),
                to: $("#wcbef-history-filter-date-to").val()
            }
        };
        wcbefHistoryFilter(filters);
    });

    $(document).on("click", "#wcbef-history-filter-reset", function () {
        $(".wcbef-history-filter-fields input").val("");
        $(".wcbef-history-filter-fields select").val("").change();
        wcbefHistoryFilter();
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() >= navigationTopOffset) {
            $("#wcbef-bulk-edit-navigation").css({
                position: "fixed",
                top: "32px",
                "z-index": 15000,
                width: $("#wcbef-products-table").width()
            });
        } else {
            $("#wcbef-bulk-edit-navigation").css({
                position: "static",
                width: "100%"
            });
        }
    });

    $(document).on("change", ".wcbef-column-profile-fields input:checkbox", function () {
        let saveBtn = $("#wcbef-column-profiles-save-changes");
        if (saveBtn.prop("disabled") === true) {
            saveBtn.prop("disabled", false);
        }
    });

    $(document).on("click", "#wcbef-bulk-edit-reset-filter", function () {
        reset_filter_form();
        reset_quick_search_form();
        let data = get_current_filer_data();
        $(".wcbef-filter-profiles-items tr").removeClass("wcbef-filter-profile-loaded");
        $('input.wcbef-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr").addClass("wcbef-filter-profile-loaded");
        wcbefFilterProfileChangeUseAlways("default");
        wcbefProductsFilterAjax(data, "pro_search");
        $(this).hide();
    });

    $(document).on("change", "input.wcbef-filter-profile-use-always-item", function () {
        if ($(this).val() !== "default") {
            $("#wcbef-bulk-edit-reset-filter").show();
        } else {
            $("#wcbef-bulk-edit-reset-filter").hide();
        }
        wcbefFilterProfileChangeUseAlways($(this).val());
    });

    $(document).on("click", ".wcbef-bulk-edit-delete-product", function () {
        $(this).find(".wcbef-bulk-edit-delete-product-buttons").slideToggle(200);
    });

    $(document).on("click", 'button.wcbef-calculator[data-target="#wcbef-modal-numeric-calculator"]', function () {
        let btn = $("#wcbef-modal-numeric-calculator .wcbef-edit-action-numeric-calculator");
        btn.attr("data-product-id", $(this).attr("data-product-id"));
        btn.attr("data-field", $(this).attr("data-field"));
        btn.attr("data-field-type", $(this).attr("data-field-type"));
        if ($(this).attr('data-field') === 'download_limit' || $(this).attr('data-field') === 'download_expiry') {
            $('#wcbef-modal-numeric-calculator #wcbef-numeric-calculator-type').val('n').change().hide();
            $('#wcbef-modal-numeric-calculator #wcbef-numeric-calculator-round').val('').change().hide();
        } else {
            $('#wcbef-modal-numeric-calculator #wcbef-numeric-calculator-type').show();
            $('#wcbef-modal-numeric-calculator #wcbef-numeric-calculator-round').show();
        }
        $('#wcbef-modal-numeric-calculator-product-title').text($(this).attr('data-product-name'));
    });

    $(document).on("click", ".wcbef-edit-action-numeric-calculator", function () {
        let productID = $(this).attr("data-product-id");
        let ProductIds;
        let productsChecked = $("input.wcbef-product-id:checkbox:checked");
        let bindEdit = $("#wcbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && productsChecked.length > 0) {
            ProductIds = productsChecked.map(function (i) {
                return $(this).val();
            }).get();
            ProductIds[productsChecked.length] = productID;
        } else {
            ProductIds = [];
            ProductIds[0] = productID;
        }

        let wcbefField;
        if ($(this).attr("data-field-type")) {
            wcbefField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wcbefField = $(this).attr("data-field");
        }

        let values = {
            operator: $("#wcbef-numeric-calculator-operator").val(),
            value: $("#wcbef-numeric-calculator-value").val(),
            operator_type: $("#wcbef-numeric-calculator-type").val(),
            roundItem: $("#wcbef-numeric-calculator-round").val()
        };

        wcbefPriceEditByCalculatorAjax(ProductIds, wcbefField, values);
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).val() === "number_formula") {
            $(this).closest("div").find("input[type=number]").attr("type", "text");
        }
    });

    $(document).on("change", ".wcbef-bulk-edit-form-variable", function () {
        let newVal = $(this).val() ? $(this).closest("div").find("input[type=text]").val() + "{" + $(this).val() + "}" : "";
        $(this).closest("div").find("input[type=text]").val(newVal).change();
    });

    $("#wcbef-modal-bulk-edit .wcbef-tab-content-item").on("change", "[data-field=value]", function () {
        changedTabs($(this));
    });

    $(document).on("click", "#wcbef-bulk-edit-bulk-edit-btn", function () {
        // get product variations
        if ($(this).attr("data-fetch-product") === "yes") {
            let productID = $("input.wcbef-product-id:checkbox:checked");
            if (productID.length === 1) {
                wcbefGetProductDataAjax(productID.val());
            } else {
                reset_bulk_edit_form();
            }
        }
    });

    $(document).on("change", "#wcbef-variations-attaching-attributes", function () {
        getAttributeValuesForAttach($(this).val());
    });

    $(document).on("keyup", "#wcbef-variation-attaching-variable-id", function () {
        if ($(this).val() !== "") {
            $("#wcbef-variation-attaching-get-variations").prop("disabled", false);
        } else {
            $("#wcbef-variation-attaching-get-variations").attr("disabled", "disabled");
        }
    });

    $(document).on("click", "#wcbef-variation-attaching-get-variations", function () {
        getProductVariationsForAttach($("#wcbef-variation-attaching-variable-id").val(), $("#wcbef-variations-attaching-attributes").val(), $("#wcbef-variations-attaching-attribute-item").val());
    });

    $(document).on("change", ".wcbef-date-from", function () {
        let field_to = $('#' + $(this).attr('data-to-id'));
        field_to.val("");
        field_to.datepicker("destroy");
        field_to.datepicker({
            dateFormat: "yy/mm/dd",
            minDate: $(this).val()
        });
    });

    $("#wcbef-products-table").scrollbar({
        autoScrollSize: false,
        scrollx: $(".external-scroll_x"),
    });

    $(document).on('click', '#wcbef-full-screen', function () {
        if ($('#adminmenuback').css('display') === 'block') {
            $('#adminmenuback, #adminmenuwrap').hide();
            $('#wpcontent, #wpfooter').css({ "margin-left": 0 });
        } else {
            $('#adminmenuback, #adminmenuwrap').show();
            $('#wpcontent, #wpfooter').css({ "margin-left": "160px" });
        }
    });

    $(document).on('click', 'button[data-target="#wcbef-modal-select-products"]', function () {
        let childrenIds = $(this).attr('data-children-ids').split(',');
        let products = $('#wcbef-select-products-value option');
        $('#wcbef-modal-select-products-product-title').text($(this).attr('data-product-name'));
        $('#wcbef-modal-select-products .wcbef-edit-action-with-button').attr('data-product-id', $(this).attr('data-product-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        if (products.length > 0) {
            products.each(function () {
                if ($.inArray($(this).val(), childrenIds) !== -1) {
                    $(this).prop('selected', true).change();
                } else {
                    $(this).prop('selected', false).change();
                }
            });
        }
    });

    $(document).on('click', '#wcbef-modal-select-files-add-file-item', function () {
        wcbefAddNewFileItem();
    });

    $(document).on('keyup', 'input[type=number][data-field=download_limit], input[type=number][data-field=download_expiry]', function () {
        if ($(this).val() < -1) {
            $(this).val(-1);
        }
    });

    $(document).on('click', 'button[data-toggle=modal][data-target="#wcbef-modal-select-files"]', function () {
        $('#wcbef-modal-select-files-apply').attr('data-product-id', $(this).attr('data-product-id')).attr('data-field', $(this).attr(('data-field')));
        $('#wcbef-modal-select-files-product-title').text($(this).attr('data-product-name'));
        wcbefGetProductFiles($(this).attr('data-product-id'));
    })

    $(document).on('click', '.wcbef-inline-edit-file-remove-item', function () {
        $(this).closest('.wcbef-modal-select-files-file-item').remove();
    });

    $(document).on('change', '#wcbef-column-profile-select-all', function () {
        if ($(this).prop('checked') === true) {
            $(this).closest('label').find('span').text('Unselect');
            $('.wcbef-column-profile-fields[data-content=' + $(this).attr('data-profile-name') + '] input:checkbox:visible').prop('checked', true);
        } else {
            $(this).closest('label').find('span').text('Select All');
            $('.wcbef-column-profile-fields[data-content=' + $(this).attr('data-profile-name') + '] input:checkbox').prop('checked', false);
        }
    });

    $(document).on('click', '.wcbef-modal', function (e) {
        if ($(e.target).hasClass('wcbef-modal') || $(e.target).hasClass('wcbef-modal-container') || $(e.target).hasClass('wcbef-modal-box')) {
            closeModal();
        }
    });

    $(document).on('click', '#wcbef-variation-attaching-start-attaching', function () {
        let productId = $('#wcbef-variation-attaching-variable-id').val();
        let attribuyeKey = $('#wcbef-variations-attaching-attributes').val();
        let variationId = [];
        let attributeItem = [];
        $('#wcbef-variations-attaching-product-variations .wcbef-variation-bulk-edit-current-item').map(function () {
            variationId.push($(this).find('input[type=hidden][name="variation_id[]"]').val());
            attributeItem.push($(this).find('select[name="attribute_item[]"]').val());
        });
        wcbefVariationAttaching(productId, attribuyeKey, variationId, attributeItem)
    });

    $(document).on(
        {
            mouseenter: function () {
                $('#wp-admin-bar-wcbef-col-view').html('#' + $(this).attr('data-product-id') + ' | ' + $(this).attr('data-product-title') + ' [<span class="wcbef-col-title">' + $(this).attr('data-col-title') + '</span>] ');
            },
            mouseleave: function () {
                $('#wp-admin-bar-wcbef-col-view').html('');
            }
        },
        "#wcbef-products-list td"
    );

    var sortType = 'desc'
    $(document).on('click', '.wcbef-sortable-column', function () {
        if (sortType === 'desc') {
            sortType = 'asc';
            $(this).find('i.wcbef-sortable-column-icon').text('d');
        } else {
            sortType = 'desc';
            $(this).find('i.wcbef-sortable-column-icon').text('u');
        }
        wcbefSortByColumn($(this).attr('data-column-name'), sortType);
    });

    $('#wp-admin-bar-root-default').append('<li id="wp-admin-bar-wcbef-col-view"></li>');

    wcbefGetDefaultFilterProfileProducts();
});
