jQuery(document).ready(function ($) {
    "use strict";

    var navigationTopOffset;
    if ($('#wccbef-bulk-edit-navigation').length) {
        navigationTopOffset = $("#wccbef-bulk-edit-navigation").offset().top;
    }

    $(document).on('click', '.wccbef-timepicker, .wccbef-datetimepicker, .wccbef-datepicker', function () {
        $(this).attr('data-val', $(this).val());
    });

    wccbefReInitDatePicker();

    // Select2
    if ($.fn.select2) {
        let wccbefSelect2 = $(".wccbef-select2");
        if (wccbefSelect2.length) {
            wccbefSelect2.select2({
                placeholder: "Select ..."
            });
        }
    }

    if ($.fn.scrollbar) {
        $("#wccbef-items-table").scrollbar({
            autoScrollSize: false,
            scrollx: $(".external-scroll_x"),
        });
    }

    let mainTabs = [
        'bulk-edit',
        'column-manager',
        'meta-fields',
        'history',
        'import-export',
        'settings',
    ]
    let currentTab = (window.location.hash && $.inArray(window.location.hash.split('#')[1], mainTabs) !== -1) ? window.location.hash.split('#')[1] : 'bulk-edit';
    window.location.hash = currentTab;
    wccbefOpenTab($('.wccbef-tabs-list li a[data-content="' + currentTab + '"]'));
    if ($("#wccbef-bulk-edit-navigation").length > 0) {
        navigationTopOffset = ($("#wccbef-bulk-edit-navigation").offset().top > 300) ? $("#wccbef-bulk-edit-navigation").offset().top : 300;
    }

    // Tabs
    $(document).on("click", ".wccbef-tabs-list li a", function (event) {
        if ($(this).attr('data-disabled') !== 'true') {
            event.preventDefault();
            window.location.hash = $(this).attr('data-content');
            wccbefOpenTab($(this));
            if ($("#wccbef-bulk-edit-navigation").length > 0) {
                navigationTopOffset = ($("#wccbef-bulk-edit-navigation").offset().top > 300) ? $("#wccbef-bulk-edit-navigation").offset().top : 300;
            }
        }
    });

    $(window).scroll(function () {
        if ($('a[data-content=bulk-edit]').hasClass('selected')) {
            let top = ($(window).width() > 768) ? "32px" : "0";
            if ($(window).scrollTop() >= navigationTopOffset) {
                $("#wccbef-bulk-edit-navigation").css({
                    position: "fixed",
                    top: top,
                    "z-index": 9988,
                    width: $("#wccbef-items-table").width()
                });
            } else {
                $("#wccbef-bulk-edit-navigation").css({
                    position: "static",
                    width: "100%"
                });
            }
        }
    });

    // Filter Form (Show & Hide)
    $(".wccbef-filter-form-toggle").on("click", function () {
        if ($("#wccbef-filter-form-content").attr("data-visibility") === "visible") {
            wccbefFilterFormClose();
        } else {
            wccbefFilterFormOpen();
        }

        if ($("#wccbef-filter-form").css("position") === "static") {
            setTimeout(function () {
                navigationTopOffset = $("#wccbef-bulk-edit-navigation").offset().top;
            }, 300);
        }
    });

    // Modal
    $(document).on("click", "[data-toggle=modal]", function () {
        $($(this).attr("data-target")).fadeIn();
        $($(this).attr("data-target") + " .wccbef-modal-box").fadeIn();
        $("#wccbef-last-modal-opened").val($(this).attr("data-target"));

        // set height for modal body
        let titleHeight = $($(this).attr("data-target") + " .wccbef-modal-box .wccbef-modal-title").height();
        let footerHeight = $($(this).attr("data-target") + " .wccbef-modal-box .wccbef-modal-footer").height();
        $($(this).attr("data-target") + " .wccbef-modal-box .wccbef-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wccbef-modal-box").height()) - parseInt(titleHeight + footerHeight + 150) + "px"
        });

        $($(this).attr("data-target") + " .wccbef-modal-box-lg .wccbef-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wccbef-modal-box").height()) - parseInt(titleHeight + footerHeight + 120) + "px"
        });
    });

    $(document).on("click", "[data-toggle=modal-close]", function () {
        wccbefCloseModal();
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            wccbefCloseModal();
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });

            if ($("#wccbef-filter-form-content").css("display") === "block") {
                $("#wccbef-bulk-edit-filter-form-close-button").trigger("click");
            }
        }
    });

    $(document).on('click', '#wccbef-full-screen', function () {
        if ($('#adminmenuback').css('display') === 'block') {
            $('#adminmenuback, #adminmenuwrap').hide();
            $('#wpcontent, #wpfooter').css({ "margin-left": 0 });
        } else {
            $('#adminmenuback, #adminmenuwrap').show();
            $('#wpcontent, #wpfooter').css({ "margin-left": "160px" });
        }
    });

    // Select Items (Checkbox) in table
    $(document).on("change", ".wccbef-check-item-main", function () {
        let checkbox_items = $(".wccbef-check-item");
        if ($(this).prop("checked") === true) {
            checkbox_items.prop("checked", true);
            $("#wccbef-items-list tr").addClass("wccbef-tr-selected");
            checkbox_items.each(function () {
                $("#wccbef-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            });
            wccbefShowSelectionTools();
            $("#wccbef-export-only-selected-items").prop("disabled", false);
        } else {
            checkbox_items.prop("checked", false);
            $("#wccbef-items-list tr").removeClass("wccbef-tr-selected");
            $("#wccbef-export-items-selected").html("");
            wccbefHideSelectionTools();
            $("#wccbef-export-only-selected-items").prop("disabled", true);
            $("#wccbef-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("change", ".wccbef-check-item", function () {
        if ($(this).prop("checked") === true) {
            $("#wccbef-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            if ($(".wccbef-check-item:checked").length === $(".wccbef-check-item").length) {
                $(".wccbef-check-item-main").prop("checked", true);
            }
            $(this).closest("tr").addClass("wccbef-tr-selected");
        } else {
            $("#wccbef-export-items-selected").find("input[value=" + $(this).val() + "]").remove();
            $(this).closest("tr").removeClass("wccbef-tr-selected");
            $(".wccbef-check-item-main").prop("checked", false);
        }

        // Disable and enable "Only Selected items" in "Import/Export"
        if ($(".wccbef-check-item:checkbox:checked").length > 0) {
            $("#wccbef-export-only-selected-items").prop("disabled", false);
            wccbefShowSelectionTools();
        } else {
            wccbefHideSelectionTools();
            $("#wccbef-export-only-selected-items").prop("disabled", true);
            $("#wccbef-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("click", "#wccbef-bulk-edit-unselect", function () {
        $("input.wccbef-check-item").prop("checked", false);
        $("input.wccbef-check-item-main").prop("checked", false);
        wccbefHideSelectionTools();
    });

    // Start "Column Profile"
    $(document).on("change", "#wccbef-column-profiles-choose", function () {
        $('#wccbef-column-profile-select-all').prop('checked', false).attr('data-profile-name', $(this).val());
        $('.wccbef-column-profile-select-all span').text('Select All');
        $(".wccbef-column-profile-fields").hide();
        $(".wccbef-column-profile-fields[data-content=" + $(this).val() + "]").show();
        $("#wccbef-column-profiles-apply").attr("data-preset-key", $(this).val());
        if (defaultPresets && $.inArray($(this).val(), defaultPresets) === -1) {
            $("#wccbef-column-profiles-update-changes").show();
        } else {
            $("#wccbef-column-profiles-update-changes").hide();
        }
    });

    $(document).on("keyup", "#wccbef-column-profile-search", function () {
        let wccbefSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wccbef-column-profile-fields ul li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wccbefSearchFieldValue) > -1);
        });
    });

    $(document).on('change', '#wccbef-column-profile-select-all', function () {
        if ($(this).prop('checked') === true) {
            $(this).closest('label').find('span').text('Unselect');
            $('.wccbef-column-profile-fields[data-content=' + $(this).attr('data-profile-name') + '] input:checkbox:visible').prop('checked', true);
        } else {
            $(this).closest('label').find('span').text('Select All');
            $('.wccbef-column-profile-fields[data-content=' + $(this).attr('data-profile-name') + '] input:checkbox').prop('checked', false);
        }
        $(".wccbef-column-profile-save-dropdown").show();
    });
    // End "Column Profile"

    // Calculator for numeric TD
    $(document).on(
        {
            mouseenter: function () {
                $(this)
                    .children(".wccbef-calculator")
                    .show();
            },
            mouseleave: function () {
                $(this)
                    .children(".wccbef-calculator")
                    .hide();
            }
        },
        "td[data-content-type=numeric]"
    );

    // delete items button
    $(document).on("click", ".wccbef-bulk-edit-delete-item", function () {
        $(this).find(".wccbef-bulk-edit-delete-item-buttons").slideToggle(200);
    });

    $(document).on("change", ".wccbef-column-profile-fields input:checkbox", function () {
        $(".wccbef-column-profile-save-dropdown").show();
    });

    $(document).on("click", ".wccbef-column-profile-save-dropdown", function () {
        $(this).find(".wccbef-column-profile-save-dropdown-buttons").slideToggle(200);
    });

    $('#wp-admin-bar-root-default').append('<li id="wp-admin-bar-wccbef-col-view"></li>');

    $(document).on(
        {
            mouseenter: function () {
                $('#wp-admin-bar-wccbef-col-view').html('#' + $(this).attr('data-item-id') + ' | ' + $(this).attr('data-item-title') + ' [<span class="wccbef-col-title">' + $(this).attr('data-col-title') + '</span>] ');
            },
            mouseleave: function () {
                $('#wp-admin-bar-wccbef-col-view').html('');
            }
        },
        "#wccbef-items-list td"
    );

    $(document).on("click", ".wccbef-open-uploader", function (e) {
        let target = $(this).attr("data-target");
        let element = $(this).closest('div');
        let type = $(this).attr("data-type");
        let mediaUploader;
        let wccbefNewImageElementID = $(this).attr("data-id");
        let wccbefProductID = $(this).attr("data-item-id");
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
                    $("#url-" + wccbefNewImageElementID).val(attachment[0].url);
                    break;
                case "inline-file-custom-field":
                    $("#wccbef-file-url").val(attachment[0].url);
                    $('#wccbef-file-id').val(attachment[0].id)
                    break;
                case "inline-edit":
                    $("#" + wccbefNewImageElementID).val(attachment[0].url);
                    $("[data-image-preview-id=" + wccbefNewImageElementID + "]").html("<img src='" + attachment[0].url + "' alt='' />");
                    $("#wccbef-modal-image button[data-item-id=" + wccbefProductID + "][data-button-type=save]").attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "inline-edit-gallery":
                    attachment.forEach(function (item) {
                        $("div[data-gallery-id=wccbef-gallery-items-" + wccbefProductID + "]").append('<div class="wccbef-inline-edit-gallery-item"><img src="' + item.url + '" alt=""><input type="hidden" class="wccbef-inline-edit-gallery-image-ids" value="' + item.id + '"></div>');
                    });
                    break;
                case "bulk-edit-image":
                    element.find(".wccbef-bulk-edit-form-item-image").val(attachment[0].id);
                    element.find(".wccbef-bulk-edit-form-item-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="wccbef-bulk-edit-form-remove-image">x</button></div>');
                    break;
                case "bulk-edit-file":
                    element.find(".wccbef-bulk-edit-form-item-file").val(attachment[0].id);
                    break;
                case "bulk-edit-gallery":
                    attachment.forEach(function (item) {
                        $("#wccbef-bulk-edit-form-item-gallery").append('<input type="hidden" value="' + item.id + '">');
                        $("#wccbef-bulk-edit-form-item-gallery-preview").append('<div><img src="' + item.url + '" width="43" height="43" alt=""><button type="button" data-id="' + item.id + '" class="wccbef-bulk-edit-form-remove-gallery-item">x</button></div>');
                    });
                    break;
            }
        });
        mediaUploader.open();
    });

    $(document).on("change", ".wccbef-column-manager-check-all-fields-btn input:checkbox", function () {
        if ($(this).prop("checked")) {
            $(this).closest("label").find("span").addClass("selected").text("Unselect");
            $(".wccbef-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible").each(function () {
                $(this).find("input:checkbox").prop("checked", true);
            });
        } else {
            $(this).closest("label").find("span").removeClass("selected").text("Select All");
            $(".wccbef-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible input:checked").prop("checked", false);
        }
    });

    $(document).on("click", ".wccbef-column-manager-add-field", function () {
        let fieldName = [];
        let fieldLabel = [];
        let action = $(this).attr("data-action");
        let checked = $(".wccbef-column-manager-available-fields[data-action=" + action + "] input[data-type=field]:checkbox:checked");
        if (checked.length > 0) {
            $('.wccbef-column-manager-empty-text').hide();
            if (action === 'new') {
                $('.wccbef-column-manager-added-fields-wrapper .wccbef-box-loading').show();
            } else {
                $('#wccbef-modal-column-manager-edit-preset .wccbef-box-loading').show();
            }
            checked.each(function (i) {
                fieldName[i] = $(this).attr("data-name");
                fieldLabel[i] = $(this).val();
            });
            wccbefColumnManagerAddField(fieldName, fieldLabel, action);
        }
    });

    $(".wccbef-column-manager-delete-preset").on("click", function () {
        var $this = $(this);
        $("#wccbef_column_manager_delete_preset_key").val($this.val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wccbef-button wccbef-button-lg wccbef-button-white",
            confirmButtonClass: "wccbef-button wccbef-button-lg wccbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wccbef-column-manager-delete-preset-form").submit();
            }
        }
        );
    });

    $(document).on("keyup", ".wccbef-column-manager-search-field", function () {
        let wccbefSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wccbef-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] ul li[data-added=false]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wccbefSearchFieldValue) > -1);
        });
    });

    $(document).on("click", ".wccbef-column-manager-remove-field", function () {
        $(".wccbef-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] li[data-name=" + $(this).attr("data-name") + "]").attr("data-added", "false").show();
        $(this).closest(".wccbef-column-manager-right-item").remove();
        if ($('.wccbef-column-manager-added-fields-wrapper .wccbef-column-manager-right-item').length < 1) {
            $('.wccbef-column-manager-empty-text').show();
        }
    });

    if ($.fn.sortable) {
        let wccbefColumnManagerFields = $(".wccbef-column-manager-added-fields .items");
        wccbefColumnManagerFields.sortable({
            handle: ".wccbef-column-manager-field-sortable-btn",
            cancel: ""
        });
        wccbefColumnManagerFields.disableSelection();

        let wccbefMetaFieldItems = $(".wccbef-meta-fields-right");
        wccbefMetaFieldItems.sortable({
            handle: ".wccbef-meta-field-item-sortable-btn",
            cancel: ""
        });
        wccbefMetaFieldItems.disableSelection();
    }

    $(document).on("click", "#wccbef-add-meta-field-manual", function () {
        $(".wccbef-meta-fields-empty-text").hide();
        let input = $("#wccbef-meta-fields-manual_key_name");
        wccbefAddMetaKeysManual(input.val());
        input.val("");
    });

    $(document).on("click", ".wccbef-meta-field-remove", function () {
        $(this).closest(".wccbef-meta-fields-right-item").remove();
        if ($(".wccbef-meta-fields-right-item").length < 1) {
            $(".wccbef-meta-fields-empty-text").show();
        }
    });

    $(document).on('click', '.wccbef-modal', function (e) {
        if ($(e.target).hasClass('wccbef-modal') || $(e.target).hasClass('wccbef-modal-container') || $(e.target).hasClass('wccbef-modal-box')) {
            wccbefCloseModal();
        }
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).val() === "number_formula") {
            $(this).closest("div").find("input[type=number]").attr("type", "text");
        }
    });

    $(document).on('change', '#wccbef-filter-form-content [data-field=value], #wccbef-filter-form-content [data-field=from], #wccbef-filter-form-content [data-field=to]', function () {
        wccbefCheckFilterFormChanges();
    });

    $(document).on('change', 'input[type=number][data-field=to]', function () {
        let from = $(this).closest('.wccbef-form-group').find('input[type=number][data-field=from]');
        if (parseFloat($(this).val()) < parseFloat(from.val())) {
            from.val('').addClass('wccbef-input-danger').focus();
        }
    });

    $(document).on('change', 'input[type=number][data-field=from]', function () {
        let to = $(this).closest('.wccbef-form-group').find('input[type=number][data-field=to]');
        if (parseFloat($(this).val()) > parseFloat(to.val())) {
            $(this).val('').addClass('wccbef-input-danger');
        } else {
            $(this).removeClass('wccbef-input-danger')
        }
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
                case "select":
                case "password":
                case "url":
                case "email":
                    $(this).children("span").html("<textarea data-item-id='" + $(this).attr("data-item-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "'>" + $(this).text().trim() + "</textarea>").children("textarea").focus().select();
                    break;
                case "numeric":
                case "regular_price":
                case "sale_price":
                    $(this).children("span").html("<input type='number' min='-1' data-item-id='" + $(this).attr("data-item-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "' value='" + $(this).text().trim() + "'>").children("input[type=number]").focus().select();
                    break;
            }
        }
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
        let wccbefKeyCode = event.keyCode ? event.keyCode : event.which;
        let reload_coupons = true;
        if (wccbefKeyCode === 13) {
            let CouponIds;
            let couponsChecked = $("input.wccbef-check-item:checkbox:checked");
            let bindEdit = $("#wccbef-inline-edit-bind");
            if (bindEdit.prop("checked") === true && couponsChecked.length > 0) {
                CouponIds = couponsChecked.map(function (i) {
                    return $(this).val();
                }).get();
                CouponIds[couponsChecked.length] = $(this).attr("data-item-id");
            } else {
                CouponIds = [];
                CouponIds[0] = $(this).attr("data-item-id");
            }
            let wccbefField;
            if ($(this).attr("data-field-type") != '') {
                wccbefField = [
                    $(this).attr("data-field-type"),
                    $(this).attr("data-field")
                ];
            } else {
                wccbefField = $(this).attr("data-field");
            }

            let wccbefValue = $(this).val();
            $(this).closest("span").html($(this).val());
            wccbefInlineEdit(CouponIds, wccbefField, wccbefValue, reload_coupons);
        }
    });

    // fetch coupon data by click to bulk edit button
    $(document).on("click", "#wccbef-bulk-edit-bulk-edit-btn", function () {
        if ($(this).attr("data-fetch-coupon") === "yes") {
            let couponID = $("input.wccbef-check-item:checkbox:checked");
            if (couponID.length === 1) {
                wccbefGetCouponData(couponID.val());
            } else {
                wccbefResetBulkEditForm();
            }
        }
    });

    $(document).on('click', '.wccbef-inline-edit-color-action', function () {
        $(this).closest('td').find('input.wccbef-inline-edit-action').trigger('change');
    });

    $(document).on("change", ".wccbef-inline-edit-action", function (e) {
        let $this = $(this);
        setTimeout(function () {
            if ($('div.xdsoft_datetimepicker:visible').length > 0) {
                e.preventDefault();
                return false;
            }

            if ($this.hasClass('wccbef-datepicker') || $this.hasClass('wccbef-timepicker') || $this.hasClass('wccbef-datetimepicker')) {
                if ($this.attr('data-val') == $this.val()) {
                    e.preventDefault();
                    return false;
                }
            }

            let wccbefField;
            let reload_coupons = true;
            let CouponIds = [];
            let couponsChecked = $("input.wccbef-check-item:checkbox:checked");
            let bindEdit = $("#wccbef-inline-edit-bind");
            if (bindEdit.prop("checked") === true && couponsChecked.length > 0) {
                couponsChecked.each(function (i) {
                    if ($(this).val() !== $this.attr("data-item-id")) {
                        CouponIds.push($(this).val());
                    }
                });
            }

            CouponIds.push($this.attr("data-item-id"));
            if ($this.attr("data-field-type")) {
                wccbefField = [$this.attr("data-field-type"), $this.attr("data-field")];
            } else {
                wccbefField = $this.attr("data-field");
            }
            let wccbefValue;
            switch ($this.closest('td').attr("data-content-type")) {
                case 'checkbox_dual_mode':
                    wccbefValue = $this.prop("checked") ? "yes" : "no";
                    break;
                case 'checkbox':
                    let checked = [];
                    $this.closest('td').find('input[type=checkbox]:checked').each(function () {
                        checked.push($(this).val());
                    });
                    wccbefValue = checked;
                    break;
                default:
                    wccbefValue = $this.val();
                    break;
            }

            wccbefInlineEdit(CouponIds, wccbefField, wccbefValue, reload_coupons);
        }, 250)
    });

    $(document).on("click", ".wccbef-inline-edit-clear-date", function () {
        let wccbefField;
        let reload_coupons = true;
        let CouponIds;
        let couponsChecked = $("input.wccbef-check-item:checkbox:checked");
        let bindEdit = $("#wccbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && couponsChecked.length > 0) {
            CouponIds = couponsChecked.map(function (i) {
                return $(this).val();
            }).get();
            CouponIds[couponsChecked.length] = $(this).attr("data-item-id");
        } else {
            CouponIds = [];
            CouponIds[0] = $(this).attr("data-item-id");
        }

        if ($(this).attr("data-field-type")) {
            wccbefField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wccbefField = $(this).attr("data-field");
        }

        wccbefInlineEdit(CouponIds, wccbefField, '', reload_coupons);
    });

    $(document).on("click", ".wccbef-edit-action-price-calculator", function () {
        let couponID = $(this).attr("data-item-id");
        let CouponIds;
        let couponsChecked = $("input.wccbef-check-item:checkbox:checked");
        let bindEdit = $("#wccbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && couponsChecked.length > 0) {
            CouponIds = couponsChecked.map(function (i) {
                return $(this).val();
            }).get();
            CouponIds[couponsChecked.length] = couponID;
        } else {
            CouponIds = [];
            CouponIds[0] = couponID;
        }

        let wccbefField = $(this).attr("data-field");
        let values = {
            operator: $("#wccbef-" + wccbefField + "-calculator-operator-" + couponID).val(),
            value: $("#wccbef-" + wccbefField + "-calculator-value-" + couponID).val(),
            operator_type: $("#wccbef-" + wccbefField + "-calculator-type-" + couponID).val(),
            roundItem: $("#wccbef-" + wccbefField + "-calculator-round-" + couponID).val()
        };

        wccbefEditByCalculator(CouponIds, wccbefField, values);
    });

    $(document).on("click", ".wccbef-bulk-edit-delete-action", function () {
        let deleteType = $(this).attr('data-delete-type');
        let CouponIds;
        let couponsChecked = $("input.wccbef-check-item:checkbox:checked");
        CouponIds = couponsChecked.map(function () {
            return $(this).val();
        }).get();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wccbef-button wccbef-button-lg wccbef-button-white",
            confirmButtonClass: "wccbef-button wccbef-button-lg wccbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (CouponIds.length > 0) {
                    wccbefDeleteCoupon(CouponIds, deleteType);
                } else {
                    swal({
                        title: "Please Select Coupon !",
                        type: "warning"
                    });
                }
            }
        });
    });

    $(document).on("click", "#wccbef-bulk-edit-duplicate-start", function () {
        let couponIDs = $("input.wccbef-check-item:checkbox:checked").map(function () {
            if ($(this).attr('data-item-type') === 'variation') {
                swal({
                    title: "Duplicate for variations coupon is disabled!",
                    type: "warning"
                });
                return false;
            }
            return $(this).val();
        }).get();
        wccbefDuplicateCoupon(couponIDs, parseInt($("#wccbef-bulk-edit-duplicate-number").val()));
    });

    $(document).on("click", "#wccbef-create-new-item", function () {
        let count = $("#wccbef-new-item-count").val();
        wccbefCreateNewCoupon(count);
    });

    $(document).on("click", "#wccbef-column-profiles-save-as-new-preset", function () {
        let presetKey = $("#wccbef-column-profiles-choose").val();
        let items = $(".wccbef-column-profile-fields[data-content=" + presetKey + "] input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wccbefSaveColumnProfile(presetKey, items, "save_as_new");
    });

    $(document).on("click", "#wccbef-column-profiles-update-changes", function () {
        let presetKey = $("#wccbef-column-profiles-choose").val();
        let items = $(".wccbef-column-profile-fields[data-content=" + presetKey + "] input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wccbefSaveColumnProfile(presetKey, items, "update_changes");
    });

    $(document).on("click", ".wccbef-bulk-edit-filter-profile-load", function () {
        wccbefLoadFilterProfile($(this).val());
        if ($(this).val() !== "default") {
            $("#wccbef-bulk-edit-reset-filter").show();
        }
        $(".wccbef-filter-profiles-items tr").removeClass("wccbef-filter-profile-loaded");
        $(this).closest("tr").addClass("wccbef-filter-profile-loaded");
    });

    $(document).on("click", ".wccbef-bulk-edit-filter-profile-delete", function () {
        let presetKey = $(this).val();
        let item = $(this).closest("tr");
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wccbef-button wccbef-button-lg wccbef-button-white",
            confirmButtonClass: "wccbef-button wccbef-button-lg wccbef-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wccbefDeleteFilterProfile(presetKey);
                if (item.hasClass('wccbef-filter-profile-loaded')) {
                    $('.wccbef-filter-profiles-items tbody tr:first-child').addClass('wccbef-filter-profile-loaded').find('input[type=radio]').prop('checked', true);
                    $('#wccbef-bulk-edit-reset-filter').trigger('click');
                }
                item.remove();
            }
        });
    });

    $(document).on("change", "input.wccbef-filter-profile-use-always-item", function () {
        if ($(this).val() !== "default") {
            $("#wccbef-bulk-edit-reset-filter").show();
        } else {
            $("#wccbef-bulk-edit-reset-filter").hide();
        }
        wccbefFilterProfileChangeUseAlways($(this).val());
    });

    $(document).on("click", ".wccbef-filter-form-action", function (e) {
        let data = wccbefGetCurrentFilterData();
        let page;
        let action = $(this).attr("data-search-action");
        if (action === "pagination") {
            page = $(this).attr("data-index");
        }
        if (action === "quick_search") {
            wccbefResetFilterForm();
        }
        if (action === "pro_search") {
            $('#wccbef-bulk-edit-reset-filter').show();
            wccbefResetQuickSearchForm();
            $(".wccbef-filter-profiles-items tr").removeClass("wccbef-filter-profile-loaded");
            $('input.wccbef-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
            wccbefFilterProfileChangeUseAlways("default");
        }
        wccbefCouponsFilter(data, action, null, page);
    });

    $(document).on("click", "#wccbef-filter-form-reset", function () {
        wccbefResetFilters();
    });

    $(document).on("click", "#wccbef-bulk-edit-reset-filter", function () {
        wccbefResetFilters();
    });

    $(document).on("change", "#wccbef-quick-search-field", function () {
        let options = $("#wccbef-quick-search-operator option");
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

    // Quick Per Page
    $("#wccbef-quick-per-page").on("change", function () {
        wccbefChangeCountPerPage($(this).val());
    });

    $(document).on("click", ".wccbef-edit-action-with-button", function () {
        let reload_coupons = true;
        let CouponIds;
        let couponsChecked = $("input.wccbef-check-item:checkbox:checked");
        let bindEdit = $("#wccbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && couponsChecked.length > 0) {
            CouponIds = couponsChecked.map(function (i) {
                return $(this).val();
            }).get();
            CouponIds[couponsChecked.length] = $(this).attr("data-item-id");
        } else {
            CouponIds = [];
            CouponIds[0] = $(this).attr("data-item-id");
        }

        let wccbefField;
        if ($(this).attr("data-field-type")) {
            wccbefField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wccbefField = $(this).attr("data-field");
        }
        let wccbefValue;
        switch ($(this).attr("data-content-type")) {
            case "textarea":
                wccbefValue = tinymce.get("wccbef-text-editor").getContent();
                break;
            case "select_coupons":
                wccbefValue = $('#wccbef-select-coupons-value').val();
                break;
            case "select_files":
                let names = $('.wccbef-inline-edit-file-name').map(function () {
                    return $(this).val();
                }).get();

                let urls = $('.wccbef-inline-edit-file-url').map(function () {
                    return $(this).val();
                }).get();

                wccbefValue = {
                    files_name: names,
                    files_url: urls,
                };
                break;
            case "file":
                wccbefValue = $('#wccbef-modal-file #wccbef-file-id').val();
                break;
            case "image":
                wccbefValue = $(this).attr("data-image-id");
                break;
            case "gallery":
                wccbefValue = $("div[data-gallery-id=wccbef-gallery-items-" + $(this).attr("data-item-id") + "] input.wccbef-inline-edit-gallery-image-ids").map(function () {
                    return $(this).val();
                }).get();
                break;
        }

        wccbefInlineEdit(CouponIds, wccbefField, wccbefValue, reload_coupons);
    });

    $(document).on("click", ".wccbef-load-text-editor", function () {
        let couponId = $(this).attr("data-item-id");
        let field = $(this).attr("data-field");
        let fieldType = $(this).attr("data-field-type");
        $('#wccbef-modal-text-editor-item-title').text($(this).attr('data-item-name'));
        $("#wccbef-text-editor-apply").attr("data-field", field).attr("data-field-type", fieldType).attr("data-item-id", couponId);
        $.ajax({
            url: WCCBEF_DATA.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "wccbef_get_text_editor_content",
                coupon_id: couponId,
                field: field,
                field_type: fieldType
            },
            success: function (response) {
                if (response.success) {
                    tinymce.get("wccbef-text-editor").setContent(response.content);
                    tinymce.execCommand('mceFocus', false, 'wccbef-text-editor');
                }
            },
            error: function () { }
        });
    });

    //Search
    $(document).on("keyup", ".wccbef-search-in-list", function () {
        let wccbefSearchValue = this.value.toLowerCase().trim();
        $($(this).attr("data-id") + " .wccbef-coupon-items-list li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wccbefSearchValue) > -1);
        });
    });

    $(document).on('click', 'button[data-target="#wccbef-modal-select-coupons"]', function () {
        let childrenIds = $(this).attr('data-children-ids').split(',');
        $('#wccbef-modal-select-coupons-item-title').text($(this).attr('data-item-name'));
        $('#wccbef-modal-select-coupons .wccbef-edit-action-with-button').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        let coupons = $('#wccbef-select-coupons-value');
        if (coupons.length > 0) {
            coupons.val(childrenIds).change();
        }
    });

    $(document).on('click', '#wccbef-modal-select-files-add-file-item', function () {
        wccbefAddNewFileItem();
    });

    $(document).on('click', 'button[data-toggle=modal][data-target="#wccbef-modal-select-files"]', function () {
        $('#wccbef-modal-select-files-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr(('data-field')));
        $('#wccbef-modal-select-files-item-title').text($(this).closest('td').attr('data-col-title'));
        wccbefGetCouponFiles($(this).attr('data-item-id'));
    });

    $(document).on('click', '.wccbef-inline-edit-file-remove-item', function () {
        $(this).closest('.wccbef-modal-select-files-file-item').remove();
    });

    $(document).on("change", "select[data-field=operator]", function () {
        let id = $(this).closest(".wccbef-form-group").find("label").attr("for");
        if ($(this).val() === "text_replace") {
            $(this).closest(".wccbef-form-group").append('<div class="wccbef-bulk-edit-form-extra-field"><select id="' + id + '-sensitive"><option value="yes">Same Case</option><option value="no">Ignore Case</option></select><input type="text" id="' + id + '-replace" placeholder="Text ..."><select class="wccbef-bulk-edit-form-variable" title="Select Variable" data-field="variable"><option value="">Variable</option><option value="title">Title</option><option value="id">ID</option><option value="sku">SKU</option><option value="menu_coupon">Menu Coupon</option><option value="parent_id">Parent ID</option><option value="parent_title">Parent Title</option><option value="parent_sku">Parent SKU</option><option value="regular_price">Regular Price</option><option value="sale_price">Sale Price</option></select></div>');
        } else if ($(this).val() === "number_round") {
            $(this).closest(".wccbef-form-group").append('<div class="wccbef-bulk-edit-form-extra-field"><select id="' + id + '-round-item"><option value="5">5</option><option value="10">10</option><option value="19">19</option><option value="29">29</option><option value="39">39</option><option value="49">49</option><option value="59">59</option><option value="69">69</option><option value="79">79</option><option value="89">89</option><option value="99">99</option></select></div>');
        } else {
            $(this).closest(".wccbef-form-group").find(".wccbef-bulk-edit-form-extra-field").remove();
        }
        if ($(this).val() === "number_clear") {
            $(this).closest(".wccbef-form-group").find('input[data-field=value]').prop('disabled', true);
        } else {
            $(this).closest(".wccbef-form-group").find('input[data-field=value]').prop('disabled', false);
        }
        changedTabs($(this));
    });

    $("#wccbef-modal-bulk-edit .wccbef-tab-content-item").on("change", "[data-field=value]", function () {
        changedTabs($(this));
    });

    $(document).on("change", ".wccbef-date-from", function () {
        let field_to = $('#' + $(this).attr('data-to-id'));
        let datepicker = true;
        let timepicker = false;
        let format = 'Y/m/d';

        if ($(this).hasClass('wccbef-datetimepicker')) {
            timepicker = true;
            format = 'Y/m/d H:i'
        }

        if ($(this).hasClass('wccbef-timepicker')) {
            datepicker = false;
            timepicker = true;
            format = 'H:i'
        }

        field_to.val("");
        field_to.datetimepicker("destroy");
        field_to.datetimepicker({
            format: format,
            datepicker: datepicker,
            timepicker: timepicker,
            minDate: $(this).val(),
        });
    });

    $(document).on("click", ".wccbef-bulk-edit-form-remove-image", function () {
        $(this).closest("div").remove();
        $("#wccbef-bulk-edit-form-coupon-image").val("");
    });

    $(document).on("click", ".wccbef-bulk-edit-form-remove-gallery-item", function () {
        $(this).closest("div").remove();
        $("#wccbef-bulk-edit-form-coupon-gallery input[value=" + $(this).attr("data-id") + "]").remove();
    });

    var sortType = 'DESC'
    $(document).on('click', '.wccbef-sortable-column', function () {
        if (sortType === 'DESC') {
            sortType = 'ASC';
            $(this).find('i.wccbef-sortable-column-icon').text('d');
        } else {
            sortType = 'DESC';
            $(this).find('i.wccbef-sortable-column-icon').text('u');
        }
        wccbefSortByColumn($(this).attr('data-column-name'), sortType);
    });

    $(document).on("click", ".wccbef-column-manager-edit-field-btn", function () {
        $('#wccbef-modal-column-manager-edit-preset .wccbef-box-loading').show();
        let presetKey = $(this).val();
        $('#wccbef-modal-column-manager-edit-preset .items').html('');
        $("#wccbef-column-manager-edit-preset-key").val(presetKey);
        $("#wccbef-column-manager-edit-preset-name").val($(this).attr("data-preset-name"));
        wccbefColumnManagerFieldsGetForEdit(presetKey);
    });

    $(document).on("click", "#wccbef-get-meta-fields-by-coupon-id", function () {
        $(".wccbef-meta-fields-empty-text").hide();
        let input = $("#wccbef-add-meta-fields-coupon-id");
        wccbefAddMetaKeysByCouponID(input.val());
        input.val("");
    });

    $(document).on("click", "#wccbef-bulk-edit-undo", function () {
        wccbefHistoryUndo();
    });

    $(document).on("click", "#wccbef-bulk-edit-redo", function () {
        wccbefHistoryRedo();
    });

    $(document).on("change", ".wccbef-meta-fields-main-type", function () {
        if ($(this).val() === "textinput") {
            $(".wccbef-meta-fields-sub-type[data-id=" + $(this).attr("data-id") + "]").show();
        } else {
            $(".wccbef-meta-fields-sub-type[data-id=" + $(this).attr("data-id") + "]").hide();
        }
    });

    $(document).on("click", "#wccbef-bulk-edit-form-reset", function () {
        wccbefResetBulkEditForm();
        $("nav.wccbef-tabs-navbar li a").removeClass("wccbef-tab-changed");
    });

    $(document).on("click", "#wccbef-filter-form-save-preset", function () {
        let presetName = $("#wccbef-filter-form-save-preset-name").val();
        if (presetName !== "") {
            let data = wccbefGetProSearchData();
            wccbefSaveFilterPreset(data, presetName);
        } else {
            swal({
                title: "Preset name is required !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wccbef-bulk-edit-form-do-bulk-edit", function (e) {
        let couponIDs;
        let filterData;
        let couponsChecked = $("input.wccbef-check-item:checkbox:checked");
        let custom_fields = [];
        $(".wccbef-tab-content-item[data-content=custom_fields] .wccbef-form-group").each(function () {
            let item = $(this);
            if (item.find("[data-field=value]").val() != null) {
                custom_fields.push({
                    field: item.attr("data-name"),
                    operator: item.find("select[data-field=operator]").val(),
                    value: item.find("[data-field=value]").val()
                });
            }
        });

        let data = {
            post_title: {
                operator: $("#wccbef-bulk-edit-form-coupon-title-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-title").val()
            },
            post_excerpt: {
                operator: $("#wccbef-bulk-edit-form-coupon-description-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-description").val()
            },
            post_date: {
                value: $("#wccbef-bulk-edit-form-coupon-date").val()
            },
            post_status: {
                value: $("#wccbef-bulk-edit-form-coupon-status").val()
            },
            discount_type: {
                value: $("#wccbef-bulk-edit-form-coupon-discount-type").val()
            },
            coupon_amount: {
                operator: $("#wccbef-bulk-edit-form-coupon-amount-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-amount").val()
            },
            free_shipping: {
                value: $("#wccbef-bulk-edit-form-coupon-free-shipping").val()
            },
            date_expires: {
                value: $("#wccbef-bulk-edit-form-coupon-expire-date").val()
            },
            minimum_amount: {
                operator: $("#wccbef-bulk-edit-form-coupon-minimum-amount-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-minimum-amount").val()
            },
            maximum_amount: {
                operator: $("#wccbef-bulk-edit-form-coupon-maximum-amount-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-maximum-amount").val()
            },
            individual_use: {
                value: $("#wccbef-bulk-edit-form-coupon-individual-use").val()
            },
            exclude_sale_items: {
                value: $("#wccbef-bulk-edit-form-coupon-exclude-sale-items").val()
            },
            product_ids: {
                operator: $("#wccbef-bulk-edit-form-coupon-products-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-products").val()
            },
            exclude_product_ids: {
                operator: $("#wccbef-bulk-edit-form-coupon-exclude-products-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-exclude-products").val()
            },
            product_categories: {
                operator: $("#wccbef-bulk-edit-form-coupon-product-categories-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-product-categories").val()
            },
            exclude_product_categories: {
                operator: $("#wccbef-bulk-edit-form-coupon-exclude-product-categories-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-exclude-product-categories").val()
            },
            customer_email: {
                value: $("#wccbef-bulk-edit-form-coupon-customer-email").val()
            },
            usage_limit: {
                operator: $("#wccbef-bulk-edit-form-coupon-usage-limit-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-usage-limit").val()
            },
            limit_usage_to_x_items: {
                operator: $("#wccbef-bulk-edit-form-coupon-limit-usage-to-x-items-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-limit-usage-to-x-items").val()
            },
            usage_limit_per_user: {
                operator: $("#wccbef-bulk-edit-form-coupon-usage-limit-per-user-operator").val(),
                value: $("#wccbef-bulk-edit-form-coupon-usage-limit-per-user").val()
            },
            custom_field: custom_fields
        };

        if (couponsChecked.length > 0) {
            couponIDs = couponsChecked.map(function () {
                return $(this).val();
            }).get();
            wccbefCloseModal();
            wccbefCouponsBulkEdit(couponIDs, data, filterData);
        } else {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wccbef-button wccbef-button-lg wccbef-button-white",
                confirmButtonClass: "wccbef-button wccbef-button-lg wccbef-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    wccbefCloseModal();
                    wccbefCouponsBulkEdit(couponIDs, data, filterData);
                }
            }
            );
            filterData = wccbefGetCurrentFilterData();
        }
    });

    $(document).on('click', '[data-target="#wccbef-modal-new-item"]', function () {
        $('#wccbef-new-item-title').html("New Coupon");
        $('#wccbef-new-item-description').html("Enter how many new coupon(s) to create!");
    });

    // keypress: Enter
    $(document).on("keypress", function (e) {
        if (e.keyCode === 13) {
            if ($("#wccbef-filter-form-content").attr("data-visibility") === "visible") {
                wccbefReloadCoupons();
                $("#wccbef-bulk-edit-reset-filter").show();
            }
            if ($('#wccbef-quick-search-text').val() !== '' && $($('#wccbef-last-modal-opened').val()).css('display') !== 'block' && $('.wccbef-tabs-list a[data-content=bulk-edit]').hasClass('selected')) {
                wccbefReloadCoupons();
                $('#wccbef-quick-search-reset').show();
            }
            if ($("#wccbef-modal-new-coupon-taxonomy").css("display") === "block") {
                $("#wccbef-create-new-coupon-taxonomy").trigger("click");
            }
            if ($("#wccbef-modal-new-item").css("display") === "block") {
                $("#wccbef-create-new-item").trigger("click");
            }
            if ($("#wccbef-modal-item-duplicate").css("display") === "block") {
                $("#wccbef-bulk-edit-duplicate-start").trigger("click");
            }

            let metaFieldManualInput = $("#wccbef-meta-fields-manual_key_name");
            let metaFieldCouponId = $("#wccbef-add-meta-fields-coupon-id");
            if (metaFieldManualInput.val() !== "") {
                $(".wccbef-meta-fields-empty-text").hide();
                wccbefAddMetaKeysManual(metaFieldManualInput.val());
                metaFieldManualInput.val("");
            }
            if (metaFieldCouponId.val() !== "") {
                $(".wccbef-meta-fields-empty-text").hide();
                wccbefAddMetaKeysByCouponID(metaFieldCouponId.val());
                metaFieldCouponId.val("");
            }
        }
    });

    $(document).on("click", 'button.wccbef-calculator[data-target="#wccbef-modal-numeric-calculator"]', function () {
        let btn = $("#wccbef-modal-numeric-calculator .wccbef-edit-action-numeric-calculator");
        btn.attr("data-item-id", $(this).attr("data-item-id"));
        btn.attr("data-field", $(this).attr("data-field"));
        btn.attr("data-field-type", $(this).attr("data-field-type"));
        if ($(this).attr('data-field') === 'download_limit' || $(this).attr('data-field') === 'download_expiry') {
            $('#wccbef-modal-numeric-calculator #wccbef-numeric-calculator-type').val('n').change().hide();
            $('#wccbef-modal-numeric-calculator #wccbef-numeric-calculator-round').val('').change().hide();
        } else {
            $('#wccbef-modal-numeric-calculator #wccbef-numeric-calculator-type').show();
            $('#wccbef-modal-numeric-calculator #wccbef-numeric-calculator-round').show();
        }
        $('#wccbef-modal-numeric-calculator-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", ".wccbef-edit-action-numeric-calculator", function () {
        let couponID = $(this).attr("data-item-id");
        let CouponIds;
        let couponsChecked = $("input.wccbef-check-item:checkbox:checked");
        let bindEdit = $("#wccbef-inline-edit-bind");
        if (bindEdit.prop("checked") === true && couponsChecked.length > 0) {
            CouponIds = couponsChecked.map(function (i) {
                return $(this).val();
            }).get();
            CouponIds[couponsChecked.length] = couponID;
        } else {
            CouponIds = [];
            CouponIds[0] = couponID;
        }

        let wccbefField;
        if ($(this).attr("data-field-type")) {
            wccbefField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wccbefField = $(this).attr("data-field");
        }

        let values = {
            operator: $("#wccbef-numeric-calculator-operator").val(),
            value: $("#wccbef-numeric-calculator-value").val(),
            operator_type: $("#wccbef-numeric-calculator-type").val(),
            roundItem: $("#wccbef-numeric-calculator-round").val()
        };

        wccbefEditByCalculator(CouponIds, wccbefField, values);
    });

    $(document).on('click', '#wccbef-quick-search-button', function () {
        if ($('#wccbef-quick-search-text').val() !== '') {
            $('#wccbef-quick-search-reset').show();
        }
    });

    $(document).on('click', '#wccbef-quick-search-reset', function () {
        wccbefResetFilters()
    });

    $(document).on(
        {
            mouseenter: function () {
                $(this).addClass('wccbef-disabled-column');
            },
            mouseleave: function () {
                $(this).removeClass('wccbef-disabled-column');
            }
        },
        "td[data-editable=no]"
    );

    $(document).on('click', '.wccbef-bulk-edit-status-filter-item', function () {
        $('.wccbef-bulk-edit-status-filter-item').removeClass('active');
        $(this).addClass('active');
        if ($(this).attr('data-status') === 'all') {
            $('#wccbef-filter-form-reset').trigger('click');
        } else {
            $('#wccbef-filter-form-coupon-status').val($(this).attr('data-status')).change();
            setTimeout(function () {
                $('#wccbef-filter-form-get-coupons').trigger('click');
            }, 250);
        }
    });

    $(document).on('click', '.wccbef-coupon-products-button', function () {
        let couponId = $(this).attr('data-item-id');
        let field = $(this).attr('data-field');
        $('#wccbef-modal-products-item-title').text($(this).attr('data-item-name'));
        $('#wccbef-modal-products-items').html('').val('').change();
        $('.wccbef-modal-products-save-changes').attr('data-item-id', couponId).attr('data-field', field);
        wccbefGetCouponProducts(couponId, field);
    });

    $(document).on('click', '.wccbef-coupon-categories-button', function () {
        let couponId = $(this).attr('data-item-id');
        let field = $(this).attr('data-field');
        $('#wccbef-modal-categories-item-title').text($(this).attr('data-item-name'));
        $('#wccbef-modal-categories-items').html('').val('').change();
        $('.wccbef-modal-categories-save-changes').attr('data-item-id', couponId).attr('data-field', field);
        wccbefGetCouponCategories(couponId, field);
    });

    $(document).on('click', '.wccbef-coupon-used-in-button', function () {
        let couponCode = $(this).attr('data-item-name');
        $('#wccbef-modal-used-in-item-title').text(" - " + $(this).attr('data-item-name'));
        $('#wccbef-modal-coupon-used-in-items').html('');
        wccbefGetCouponUsedIn(couponCode);
    });

    $(document).on('click', '.wccbef-coupon-used-by-button', function () {
        let couponId = $(this).attr('data-item-id');
        $('#wccbef-modal-used-by-item-title').text(" - " + $(this).attr('data-item-name'));
        $('#wccbef-modal-coupon-used-by-items').html('');
        wccbefGetCouponUsedBy(couponId);
    });

    $(document).on('click', '.wccbef-modal-products-save-changes', function () {
        wccbefInlineEdit([$(this).attr('data-item-id')], $(this).attr('data-field'), $('#wccbef-modal-products-items').val(), true);
    });

    $(document).on('click', '.wccbef-modal-categories-save-changes', function () {
        wccbefInlineEdit([$(this).attr('data-item-id')], $(this).attr('data-field'), $('#wccbef-modal-categories-items').val(), true);
    });

    wccbefSetTipsyTooltip();
    wccbefGetProducts();
    wccbefGetCategories();
    wccbefGetDefaultFilterProfileCoupons();
});