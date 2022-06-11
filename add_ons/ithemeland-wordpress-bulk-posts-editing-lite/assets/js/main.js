jQuery(document).ready(function ($) {
    "use strict";

    var navigationTopOffset;
    if ($('#wpbel-bulk-edit-navigation').length) {
        navigationTopOffset = $("#wpbel-bulk-edit-navigation").offset().top;
    }

    if ($.fn.datepicker) {
        $(".wpbel-datepicker").datepicker({ dateFormat: "yy/mm/dd" });
    }

    // Select2
    if ($.fn.select2) {
        let wpbelSelect2 = $(".wpbel-select2");
        if (wpbelSelect2.length) {
            wpbelSelect2.select2({
                placeholder: "Select ..."
            });
        }
    }

    if ($.fn.scrollbar) {
        $("#wpbel-items-table").scrollbar({
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
    wpbelOpenTab($('.wpbel-tabs-list li a[data-content="' + currentTab + '"]'));
    if ($("#wpbel-bulk-edit-navigation").length > 0) {
        navigationTopOffset = ($("#wpbel-bulk-edit-navigation").offset().top > 300) ? $("#wpbel-bulk-edit-navigation").offset().top : 300;
    }

    // Tabs
    $(document).on("click", ".wpbel-tabs-list li a", function (event) {
        if ($(this).attr('data-disabled') !== 'true') {
            event.preventDefault();
            window.location.hash = $(this).attr('data-content');
            wpbelOpenTab($(this));
            if ($("#wpbel-bulk-edit-navigation").length > 0) {
                navigationTopOffset = ($("#wpbel-bulk-edit-navigation").offset().top > 300) ? $("#wpbel-bulk-edit-navigation").offset().top : 300;
            }
        }
    });

    $(window).scroll(function () {
        if ($('a[data-content=bulk-edit]').hasClass('selected')) {
            if ($(window).scrollTop() >= navigationTopOffset) {
                $("#wpbel-bulk-edit-navigation").css({
                    position: "fixed",
                    top: "32px",
                    "z-index": 15000,
                    width: $("#wpbel-items-table").width()
                });
            } else {
                $("#wpbel-bulk-edit-navigation").css({
                    position: "static",
                    width: "100%"
                });
            }
        }
    });

    // Filter Form (Show & Hide)
    $(".wpbel-filter-form-toggle").on("click", function () {
        if ($("#wpbel-filter-form-content").attr("data-visibility") === "visible") {
            wpbelFilterFormClose();
        } else {
            wpbelFilterFormOpen();
        }

        if ($("#wpbel-filter-form").css("position") === "static") {
            setTimeout(function () {
                navigationTopOffset = $("#wpbel-bulk-edit-navigation").offset().top;
            }, 300);
        }
    });

    // Modal
    $(document).on("click", "[data-toggle=modal]", function () {
        $($(this).attr("data-target")).fadeIn();
        $($(this).attr("data-target") + " .wpbel-modal-box").fadeIn();
        $("#wpbel-last-modal-opened").val($(this).attr("data-target"));

        // set height for modal body
        let titleHeight = $($(this).attr("data-target") + " .wpbel-modal-box .wpbel-modal-title").height();
        let footerHeight = $($(this).attr("data-target") + " .wpbel-modal-box .wpbel-modal-footer").height();
        $($(this).attr("data-target") + " .wpbel-modal-box .wpbel-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wpbel-modal-box").height()) - parseInt(titleHeight + footerHeight + 150) + "px"
        });

        $($(this).attr("data-target") + " .wpbel-modal-box-lg .wpbel-modal-body").css({
            "max-height": parseInt($($(this).attr("data-target") + " .wpbel-modal-box").height()) - parseInt(titleHeight + footerHeight + 120) + "px"
        });
    });

    $(document).on("click", "[data-toggle=modal-close]", function () {
        wpbelCloseModal();
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            wpbelCloseModal();
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
        }
    });

    // Color Picker Style
    $(document).on("change", "input[type=color]", function () {
        this.parentNode.style.backgroundColor = this.value;
    });

    $(document).on('click', '#wpbel-full-screen', function () {
        if ($('#adminmenuback').css('display') === 'block') {
            $('#adminmenuback, #adminmenuwrap').hide();
            $('#wpcontent, #wpfooter').css({ "margin-left": 0 });
        } else {
            $('#adminmenuback, #adminmenuwrap').show();
            $('#wpcontent, #wpfooter').css({ "margin-left": "160px" });
        }
    });

    // Select Items (Checkbox) in table
    $(document).on("change", ".wpbel-check-item-main", function () {
        let checkbox_items = $(".wpbel-check-item");
        if ($(this).prop("checked") === true) {
            checkbox_items.prop("checked", true);
            $("#wpbel-items-list tr").addClass("wpbel-tr-selected");
            checkbox_items.each(function () {
                $("#wpbel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            });
            wpbelShowSelectionTools();
            $("#wpbel-export-only-selected-items").prop("disabled", false);
        } else {
            checkbox_items.prop("checked", false);
            $("#wpbel-items-list tr").removeClass("wpbel-tr-selected");
            $("#wpbel-export-items-selected").html("");
            wpbelHideSelectionTools();
            $("#wpbel-export-only-selected-items").prop("disabled", true);
            $("#wpbel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("change", ".wpbel-check-item", function () {
        if ($(this).prop("checked") === true) {
            $("#wpbel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            if ($(".wpbel-check-item:checked").length === $(".wpbel-check-item").length) {
                $(".wpbel-check-item-main").prop("checked", true);
            }
            $(this).closest("tr").addClass("wpbel-tr-selected");
        } else {
            $("#wpbel-export-items-selected").find("input[value=" + $(this).val() + "]").remove();
            $(this).closest("tr").removeClass("wpbel-tr-selected");
            $(".wpbel-check-item-main").prop("checked", false);
        }

        // Disable and enable "Only Selected items" in "Import/Export"
        if ($(".wpbel-check-item:checkbox:checked").length > 0) {
            $("#wpbel-export-only-selected-items").prop("disabled", false);
            wpbelShowSelectionTools();
        } else {
            wpbelHideSelectionTools();
            $("#wpbel-export-only-selected-items").prop("disabled", true);
            $("#wpbel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("click", "#wpbel-bulk-edit-unselect", function () {
        $("input.wpbel-check-item").prop("checked", false);
        $("input.wpbel-check-item-main").prop("checked", false);
        wpbelHideSelectionTools();
    });

    // Start "Column Profile"
    $(document).on("change", "#wpbel-column-profiles-choose", function () {
        $('#wpbel-column-profile-select-all').prop('checked', false).attr('data-profile-name', $(this).val());
        $('.wpbel-column-profile-select-all span').text('Select All');
        $(".wpbel-column-profile-fields").hide();
        $(".wpbel-column-profile-fields[data-content=" + $(this).val() + "]").show();
        $("#wpbel-column-profiles-apply").attr("data-preset-key", $(this).val());
        if ($.inArray($(this).val(), ["default", "variations", "stock", "prices", 'attachments']) === -1) {
            $("#wpbel-column-profiles-update-changes").show();
        } else {
            $("#wpbel-column-profiles-update-changes").hide();
        }
    });

    $(document).on("keyup", "#wpbel-column-profile-search", function () {
        let wcbeSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wpbel-column-profile-fields ul li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wcbeSearchFieldValue) > -1);
        });
    });

    $(document).on('change', '#wpbel-column-profile-select-all', function () {
        if ($(this).prop('checked') === true) {
            $(this).closest('label').find('span').text('Unselect');
            $('.wpbel-column-profile-fields[data-content=' + $(this).attr('data-profile-name') + '] input:checkbox:visible').prop('checked', true);
        } else {
            $(this).closest('label').find('span').text('Select All');
            $('.wpbel-column-profile-fields[data-content=' + $(this).attr('data-profile-name') + '] input:checkbox').prop('checked', false);
        }
    });
    // End "Column Profile"

    // Calculator for numeric TD
    $(document).on(
        {
            mouseenter: function () {
                $(this)
                    .children(".wpbel-calculator")
                    .show();
            },
            mouseleave: function () {
                $(this)
                    .children(".wpbel-calculator")
                    .hide();
            }
        },
        "td[data-content-type=regular_price], td[data-content-type=sale_price], td[data-content-type=numeric]"
    );

    // delete items button
    $(document).on("click", ".wpbel-bulk-edit-delete-item", function () {
        $(this).find(".wpbel-bulk-edit-delete-item-buttons").slideToggle(200);
    });

    $('#wp-admin-bar-root-default').append('<li id="wp-admin-bar-wpbel-col-view"></li>');

    $(document).on(
        {
            mouseenter: function () {
                $('#wp-admin-bar-wpbel-col-view').html('#' + $(this).attr('data-item-id') + ' | ' + $(this).attr('data-item-title') + ' [<span class="wpbel-col-title">' + $(this).attr('data-col-title') + '</span>] ');
            },
            mouseleave: function () {
                $('#wp-admin-bar-wpbel-col-view').html('');
            }
        },
        "#wpbel-items-list td"
    );

    $(document).on("click", ".wpbel-open-uploader", function (e) {
        let target = $(this).attr("data-target");
        let type = $(this).attr("data-type");
        let mediaUploader;
        let wpbelNewImageElementID = $(this).attr("data-id");
        let wcbeProductID = $(this).attr("data-item-id");
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
                case "inline-edit":
                    $("#" + wpbelNewImageElementID).val(attachment[0].url);
                    $("[data-image-preview-id=" + wpbelNewImageElementID + "]").html("<img src='" + attachment[0].url + "' alt='' />");
                    $("button[data-field=_thumbnail_id][data-item-id=" + wcbeProductID + "][data-button-type=save]").attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "inline-edit-gallery":
                    attachment.forEach(function (item) {
                        $("div[data-gallery-id=wpbel-gallery-items-" + wcbeProductID + "]").append('<div class="wpbel-inline-edit-gallery-item"><img src="' + item.url + '" alt=""><input type="hidden" class="wpbel-inline-edit-gallery-image-ids" value="' + item.id + '"></div>');
                    });
                    break;
            }
        });
        mediaUploader.open();
    });

    $(document).on("click", ".wpbel-inline-edit-gallery-image-item-delete", function () {
        $(this).closest("div").remove();
    });

    $(document).on("change", ".wpbel-column-manager-check-all-fields-btn input:checkbox", function () {
        if ($(this).prop("checked")) {
            $(this).closest("label").find("span").addClass("selected").text("Unselect");
            $(".wpbel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible").each(function () {
                $(this).find("input:checkbox").prop("checked", true);
            });
        } else {
            $(this).closest("label").find("span").removeClass("selected").text("Select All");
            $(".wpbel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible input:checked").prop("checked", false);
        }
    });

    $(document).on("keyup", ".wpbel-column-manager-search-field", function () {
        let wcbeSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wpbel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] ul li[data-added=false]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wcbeSearchFieldValue) > -1);
        });
    });

    $(document).on("click", ".wpbel-column-manager-remove-field", function () {
        $(".wpbel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] li[data-name=" + $(this).attr("data-name") + "]").attr("data-added", "false").show();
        $(this).closest(".wpbel-column-manager-right-item").remove();
        if ($('.wpbel-column-manager-added-fields-wrapper .wpbel-column-manager-right-item').length < 1) {
            $('.wpbel-column-manager-empty-text').show();
        }
    });

    if ($.fn.sortable) {
        let wcbeColumnManagerFields = $(".wpbel-column-manager-added-fields .items");
        wcbeColumnManagerFields.sortable({
            handle: ".wpbel-column-manager-field-sortable-btn",
            cancel: ""
        });
        wcbeColumnManagerFields.disableSelection();

        let wcbeMetaFieldItems = $(".wpbel-meta-fields-right");
        wcbeMetaFieldItems.sortable({
            handle: ".wpbel-meta-field-item-sortable-btn",
            cancel: ""
        });
        wcbeMetaFieldItems.disableSelection();
    }

    $(document).on('click', '.wpbel-modal', function (e) {
        if ($(e.target).hasClass('wpbel-modal') || $(e.target).hasClass('wpbel-modal-container') || $(e.target).hasClass('wpbel-modal-box')) {
            wpbelCloseModal();
        }
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).val() === "number_formula") {
            $(this).closest("div").find("input[type=number]").attr("type", "text");
        }
    });

    $(document).on('change', '#wpbel-filter-form-content [data-field=value], #wpbel-filter-form-content [data-field=from], #wpbel-filter-form-content [data-field=to]', function () {
        wpbelCheckFilterFormChanges();
    });

    $(document).on('change', 'input[type=number][data-field=to]', function () {
        let from = $(this).closest('.wpbel-form-group').find('input[type=number][data-field=from]');
        if (parseFloat($(this).val()) < parseFloat(from.val())) {
            from.val('').addClass('wpbel-input-danger').focus();
        }
    });

    $(document).on('change', 'input[type=number][data-field=from]', function () {
        let to = $(this).closest('.wpbel-form-group').find('input[type=number][data-field=to]');
        if (parseFloat($(this).val()) > parseFloat(to.val())) {
            $(this).val('').addClass('wpbel-input-danger');
        } else {
            $(this).removeClass('wpbel-input-danger')
        }
    });

    $(document).on('change', '#wpbel-switcher', function () {
        wpbelLoadingStart();
        $('#wpbel-switcher-form').submit();
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
        let wpbelKeyCode = event.keyCode ? event.keyCode : event.which;
        let reload_posts = true;
        if (wpbelKeyCode === 13) {
            let PostIds;
            let postsChecked = $("input.wpbel-check-item:checkbox:checked");
            let bindEdit = $("#wpbel-inline-edit-bind");
            if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
                PostIds = postsChecked.map(function (i) {
                    return $(this).val();
                }).get();
                PostIds[postsChecked.length] = $(this).attr("data-item-id");
            } else {
                PostIds = [];
                PostIds[0] = $(this).attr("data-item-id");
            }
            let wpbelField;
            if ($(this).attr("data-field-type")) {
                wpbelField = [
                    $(this).attr("data-field-type"),
                    $(this).attr("data-field")
                ];
            } else {
                wpbelField = $(this).attr("data-field");
            }

            let wpbelValue = $(this).val();
            $(this).closest("span").html($(this).val());
            wpbelInlineEdit(PostIds, wpbelField, wpbelValue, reload_posts);
        }
    });

    // fetch post data by click to bulk edit button
    $(document).on("click", "#wpbel-bulk-edit-bulk-edit-btn", function () {
        if ($(this).attr("data-fetch-post") === "yes") {
            let postID = $("input.wpbel-check-item:checkbox:checked");
            if (postID.length === 1) {
                wpbelGetPostData(postID.val());
            } else {
                wpbelResetBulkEditForm();
            }
        }
    });

    $(document).on("change", ".wpbel-inline-edit-action", function () {
        let wpbelField;
        let reload_posts = true;
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-inline-edit-bind");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }
        if ($(this).attr("data-field-type")) {
            wpbelField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wpbelField = $(this).attr("data-field");
        }
        let wpbelValue;

        if ($(this).attr("type") === "checkbox") {
            wpbelValue = $(this).prop("checked") ? "yes" : "no";
        } else {
            wpbelValue = $(this).val();
        }

        wpbelInlineEdit(PostIds, wpbelField, wpbelValue, reload_posts);
    });

    $(document).on("click", ".wpbel-inline-edit-clear-date", function () {
        let wpbelField;
        let reload_posts = true;
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-inline-edit-bind");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }

        if ($(this).attr("data-field-type")) {
            wpbelField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wpbelField = $(this).attr("data-field");
        }

        wpbelInlineEdit(PostIds, wpbelField, '', reload_posts);
    });

    $(document).on("click", ".wpbel-bulk-edit-delete-action", function () {
        let deleteType = $(this).attr('data-delete-type');
        let postIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        postIds = postsChecked.map(function () {
            return $(this).val();
        }).get();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (postIds.length > 0) {
                    wpbelDeletePost(postIds, deleteType);
                } else {
                    swal({
                        title: "Please Select Post !",
                        type: "warning"
                    });
                }
            }
        });
    });

    $(document).on("click", "#wpbel-bulk-edit-duplicate-start", function () {
        let postIDs = $("input.wpbel-check-item:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wpbelDuplicatePost(postIDs, parseInt($("#wpbel-bulk-edit-duplicate-number").val()));
    });

    $(document).on("click", "#wpbel-create-new-item", function () {
        let count = $("#wpbel-new-item-count").val();
        let postType = ($('#wpbel-new-item-select-custom-post')) ? $('#wpbel-new-item-select-custom-post').val() : null;
        wpbelCreateNewPost(count, postType);
    });

    $(document).on("click", "#wpbel-column-profiles-save-as-new-preset", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                let presetKey = $("#wpbel-column-profiles-choose").val();
                let items = $(".wpbel-column-profile-fields[data-content=" + presetKey + "] input:checkbox:checked").map(function () {
                    return $(this).val();
                }).get();
                wpbelSaveColumnProfile(presetKey, items, "save_as_new");
            }
        });
    });

    $(document).on("click", "#wpbel-column-profiles-update-changes", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                let presetKey = $("#wpbel-column-profiles-choose").val();
                let items = $(".wpbel-column-profile-fields[data-content=" + presetKey + "] input:checkbox:checked").map(function () {
                    return $(this).val();
                }).get();
                wpbelSaveColumnProfile(presetKey, items, "update_changes");
            }
        });
    });

    $(document).on("click", ".wpbel-bulk-edit-filter-profile-load", function () {
        wpbelLoadFilterProfile($(this).val());
        if ($(this).val() !== "default") {
            $("#wpbel-bulk-edit-reset-filter").show();
        }
        $(".wpbel-filter-profiles-items tr").removeClass("wpbel-filter-profile-loaded");
        $(this).closest("tr").addClass("wpbel-filter-profile-loaded");
    });

    $(document).on("click", ".wpbel-bulk-edit-filter-profile-delete", function () {
        let presetKey = $(this).val();
        let item = $(this).closest("tr");
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
            confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wpbelDeleteFilterProfile(presetKey);
                if (item.hasClass('wpbel-filter-profile-loaded')) {
                    $('.wpbel-filter-profiles-items tbody tr:first-child').addClass('wpbel-filter-profile-loaded');
                    $('.wpbel-filter-profile-use-always-item[value=default]').prop('checked', true);
                    $('#wpbel-bulk-edit-reset-filter').trigger('click');
                }
                if (item.length > 0) {
                    item.remove();
                }
            }
        });
    });

    $(document).on("change", "input.wpbel-filter-profile-use-always-item", function () {
        if ($(this).val() !== "default") {
            $("#wpbel-bulk-edit-reset-filter").show();
        } else {
            $("#wpbel-bulk-edit-reset-filter").hide();
        }
        wpbelFilterProfileChangeUseAlways($(this).val());
    });

    $(document).on("click", ".wpbel-filter-form-action", function (e) {
        let data = wpbelGetCurrentFilterData();
        let page;
        let action = $(this).attr("data-search-action");
        if (action === "pagination") {
            page = $(this).attr("data-index");
        }
        if (action === "quick_search") {
            wpbelResetFilterForm();
        }
        if (action === "pro_search") {
            $('#wpbel-bulk-edit-reset-filter').show();
            wpbelResetQuickSearchForm();
            $(".wpbel-filter-profiles-items tr").removeClass("wpbel-filter-profile-loaded");
            $('input.wpbel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
            wpbelFilterProfileChangeUseAlways("default");
        }
        wpbelPostsFilter(data, action, null, page);
    });

    $(document).on("click", "#wpbel-filter-form-reset", function () {
        wpbelResetFilters();
    });

    $(document).on("click", "#wpbel-bulk-edit-reset-filter", function () {
        wpbelResetFilters();
    });

    $(document).on("change", "#wpbel-quick-search-field", function () {
        let options = $("#wpbel-quick-search-operator option");
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
    $(document).on("change", "#wpbel-quick-per-page", function () {
        wpbelChangeCountPerPage($(this).val());
    });

    $(document).on("click", ".wpbel-edit-action-with-button", function () {
        let reload_posts = true;
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-inline-edit-bind");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }

        let wpbelField;
        if ($(this).attr("data-field-type")) {
            wpbelField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wpbelField = $(this).attr("data-field");
        }
        let wpbelValue;
        switch ($(this).attr("data-content-type")) {
            case "textarea":
                wpbelValue = tinymce.get("wpbel-text-editor").getContent();
                break;
            case "select_post":
                wpbelValue = $('#wpbel-select-post-value').val();
                break;
            case "image":
                wpbelValue = $(this).attr("data-image-id");
                break;
        }
        wpbelInlineEdit(PostIds, wpbelField, wpbelValue, reload_posts);
    });

    $(document).on("click", ".wpbel-load-text-editor", function () {
        let postId = $(this).attr("data-item-id");
        let field = $(this).attr("data-field");
        let fieldType = $(this).attr("data-field-type");
        $('#wpbel-modal-text-editor-item-title').text($(this).attr('data-item-name'));
        $("#wpbel-text-editor-apply").attr("data-field", field).attr("data-field-type", fieldType).attr("data-item-id", postId);
        $.ajax({
            url: WPBEL_DATA.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "wpbel_get_text_editor_content",
                post_id: postId,
                field: field,
                field_type: fieldType
            },
            success: function (response) {
                if (response.success) {
                    tinymce.get("wpbel-text-editor").setContent(response.content);
                    tinymce.execCommand('mceFocus', false, 'wpbel-text-editor');
                }
            },
            error: function () { }
        });
    });

    $(document).on("click", ".wpbel-inline-edit-taxonomy-save", function () {
        let reload = true;
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-inline-edit-bind");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }
        let field = $(this).attr("data-field");
        let data = $("#wpbel-modal-taxonomy-" + field + "-" + $(this).attr("data-item-id") + " input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wpbelUpdatePostTaxonomy(PostIds, field, data, reload);
    });

    $(document).on("click", "#wpbel-create-new-post-taxonomy", function () {
        if ($("#wpbel-new-post-category-name").val() !== "") {
            let taxonomyInfo = {
                name: $("#wpbel-new-post-taxonomy-name").val(),
                slug: $("#wpbel-new-post-taxonomy-slug").val(),
                parent: $("#wpbel-new-post-taxonomy-parent").val(),
                description: $("#wpbel-new-post-taxonomy-description").val(),
                post_id: $(this).attr("data-item-id")
            };
            wpbelAddPostTaxonomy(taxonomyInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: "Taxonomy Name is required !",
                type: "warning"
            });
        }
    });

    //Search
    $(document).on("keyup", ".wpbel-search-in-list", function () {
        let wpbelSearchValue = this.value.toLowerCase().trim();
        $($(this).attr("data-id") + " .wpbel-post-items-list li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wpbelSearchValue) > -1);
        });
    });

    $(document).on("click", "#wpbel-create-new-post-attribute", function () {
        if ($("#wpbel-new-post-attribute-name").val() !== "") {
            let attributeInfo = {
                name: $("#wpbel-new-post-attribute-name").val(),
                slug: $("#wpbel-new-post-attribute-slug").val(),
                description: $("#wpbel-new-post-attribute-description").val(),
                post_id: $(this).attr("data-item-id")
            };
            wpbelAddPostAttribute(attributeInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: "Attribute Name is required !",
                type: "warning"
            });
        }
    });

    $(document).on('click', 'button[data-target="#wpbel-modal-select-post"]', function () {
        $('#wpbel-modal-select-post-item-title').text($(this).attr('data-item-name'));
        $('#wpbel-modal-select-post .wpbel-edit-action-with-button').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        let posts = $('#wpbel-select-post-value');
        if (posts.length > 0) {
            posts.val(($(this).attr('data-parent-id')) ? $(this).attr('data-parent-id') : 0).change();
        }
    });

    $(document).on('click', '#wpbel-modal-select-files-add-file-item', function () {
        wpbelAddNewFileItem();
    });

    $(document).on('click', 'button[data-toggle=modal][data-target="#wpbel-modal-select-files"]', function () {
        $('#wpbel-modal-select-files-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr(('data-field')));
        $('#wpbel-modal-select-files-item-title').text($(this).attr('data-item-name'));
        wpbelGetPostFiles($(this).attr('data-item-id'));
    });

    $(document).on('click', '.wpbel-inline-edit-file-remove-item', function () {
        $(this).closest('.wpbel-modal-select-files-file-item').remove();
    });

    if ($.fn.sortable) {
        let wpbelSelectFiles = $(".wpbel-inline-select-files");
        wpbelSelectFiles.sortable({
            handle: ".wpbel-select-files-sortable-btn",
            cancel: ""
        });
        wpbelSelectFiles.disableSelection();
    }

    $(document).on("change", ".wpbel-bulk-edit-form-variable", function () {
        let newVal = $(this).val() ? $(this).closest("div").find("input[type=text]").val() + "{" + $(this).val() + "}" : "";
        $(this).closest("div").find("input[type=text]").val(newVal).change();
    });

    $(document).on("change", "select[data-field=operator]", function () {
        let id = $(this).closest(".wpbel-form-group").find("label").attr("for");
        if ($(this).val() === "text_replace") {
            $(this).closest(".wpbel-form-group").append('<div class="wpbel-bulk-edit-form-extra-field"><select id="' + id + '-sensitive"><option value="yes">Same Case</option><option value="no">Ignore Case</option></select><input type="text" id="' + id + '-replace" placeholder="Text ..."><select class="wpbel-bulk-edit-form-variable" title="Select Variable" data-field="variable"><option value="">Variable</option><option value="title">Title</option><option value="id">ID</option><option value="sku">SKU</option><option value="menu_order">Menu Order</option><option value="parent_id">Parent ID</option><option value="parent_title">Parent Title</option><option value="parent_sku">Parent SKU</option><option value="regular_price">Regular Price</option><option value="sale_price">Sale Price</option></select></div>');
        } else if ($(this).val() === "number_round") {
            $(this).closest(".wpbel-form-group").append('<div class="wpbel-bulk-edit-form-extra-field"><select id="' + id + '-round-item"><option value="5">5</option><option value="10">10</option><option value="19">19</option><option value="29">29</option><option value="39">39</option><option value="49">49</option><option value="59">59</option><option value="69">69</option><option value="79">79</option><option value="89">89</option><option value="99">99</option></select></div>');
        } else {
            $(this).closest(".wpbel-form-group").find(".wpbel-bulk-edit-form-extra-field").remove();
        }
        if ($(this).val() === "number_clear") {
            $(this).closest(".wpbel-form-group").find('input[data-field=value]').prop('disabled', true);
        } else {
            $(this).closest(".wpbel-form-group").find('input[data-field=value]').prop('disabled', false);
        }
        changedTabs($(this));
    });

    $("#wpbel-modal-bulk-edit .wpbel-tab-content-item").on("change", "[data-field=value]", function () {
        changedTabs($(this));
    });

    $(document).on("change", ".wpbel-date-from", function () {
        let field_to = $('#' + $(this).attr('data-to-id'));
        field_to.val("");
        field_to.datepicker("destroy");
        field_to.datepicker({
            dateFormat: "yy/mm/dd",
            minDate: $(this).val()
        });
    });

    var sortType = 'desc'
    $(document).on('click', '.wpbel-sortable-column', function () {
        if (sortType === 'desc') {
            sortType = 'asc';
            $(this).find('i.wpbel-sortable-column-icon').text('d');
        } else {
            sortType = 'desc';
            $(this).find('i.wpbel-sortable-column-icon').text('u');
        }
        wpbelSortByColumn($(this).attr('data-column-name'), sortType);
    });

    $(document).on("click", "#wpbel-history-filter-apply", function () {
        let filters = {
            operation: $("#wpbel-history-filter-operation").val(),
            author: $("#wpbel-history-filter-author").val(),
            fields: $("#wpbel-history-filter-fields").val(),
            date: {
                from: $("#wpbel-history-filter-date-from").val(),
                to: $("#wpbel-history-filter-date-to").val()
            }
        };
        wpbelHistoryFilter(filters);
    });

    $(document).on("click", "#wpbel-history-filter-reset", function () {
        $(".wpbel-history-filter-fields input").val("");
        $(".wpbel-history-filter-fields select").val("").change();
        wpbelHistoryFilter();
    });

    $(document).on("change", ".wpbel-meta-fields-main-type", function () {
        if ($(this).val() !== "textinput") {
            $(".wpbel-meta-fields-sub-type[data-id=" + $(this).attr("data-id") + "]").hide();
        } else {
            $(".wpbel-meta-fields-sub-type[data-id=" + $(this).attr("data-id") + "]").show();
        }
    });

    $(document).on("click", "#wpbel-bulk-edit-form-reset", function () {
        wpbelResetBulkEditForm();
        $("nav.wpbel-tabs-navbar li a").removeClass("wpbel-tab-changed");
    });

    $(document).on("click", "#wpbel-filter-form-save-preset", function () {
        let presetName = $("#wpbel-filter-form-save-preset-name").val();
        if (presetName !== "") {
            let data = wpbelGetProSearchData();
            wpbelSaveFilterPreset(data, presetName);
        } else {
            swal({
                title: "Preset name is required !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wpbel-bulk-edit-form-do-bulk-edit", function (e) {
        let postIDs;
        let filterData;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");

        let taxonomies = [];
        let i = 0;
        let j = 0;
        $(".wpbel-bulk-edit-form-group[data-type=taxonomy]").each(function () {
            if ($(this).find("select[data-field=value]").val() != null) {
                taxonomies[i++] = {
                    field: $(this).attr("data-taxonomy"),
                    operator: $(this).find("select[data-field=operator]").val(),
                    value: $(this).find("select[data-field=value]").val()
                };
            }
        });

        let data = {
            post_title: {
                value: $("#wpbel-bulk-edit-form-post-title").val(),
                replace: $("#wpbel-bulk-edit-form-post-title-replace").val(),
                sensitive: $("#wpbel-bulk-edit-form-post-title-sensitive").val(),
                operator: $("#wpbel-bulk-edit-form-post-title-operator").val()
            },
            post_status: {
                value: $("#wpbel-bulk-edit-form-post-post-status").val()
            },
            taxonomy: taxonomies,
        };

        if (postsChecked.length > 0) {
            postIDs = postsChecked.map(function () {
                return $(this).val();
            }).get();
            wpbelCloseModal();
            wpbelPostsBulkEdit(postIDs, data, filterData);
        } else {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbel-button wpbel-button-lg wpbel-button-white",
                confirmButtonClass: "wpbel-button wpbel-button-lg wpbel-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    wpbelCloseModal();
                    wpbelPostsBulkEdit(postIDs, data, filterData);
                }
            }
            );
            filterData = wpbelGetCurrentFilterData();
        }
    });

    // keypress: Enter
    $(document).on("keypress", function (e) {
        if (e.keyCode === 13) {
            if ($("#wpbel-filter-form-content").attr("data-visibility") === "visible" || ($('#wpbel-quick-search-text').val() !== '' && $($('#wpbel-last-modal-opened').val()).css('display') !== 'block' && $('.wpbel-tabs-list a[data-content=bulk-edit]').hasClass('selected'))) {
                wpbelReloadPosts();
            }
            if ($("#wpbel-modal-new-post-taxonomy").css("display") === "block") {
                $("#wpbel-create-new-post-taxonomy").trigger("click");
            }
            if ($("#wpbel-modal-new-item").css("display") === "block") {
                $("#wpbel-create-new-item").trigger("click");
            }
            if ($("#wpbel-modal-post-duplicate").css("display") === "block") {
                $("#wpbel-bulk-edit-duplicate-start").trigger("click");
            }
        }
    });

    let query;
    $(".wpbel-get-posts-ajax").select2({
        ajax: {
            type: "post",
            delay: 800,
            url: WPBEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wpbel_get_posts_name",
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Post Name ...",
        minimumInputLength: 3
    });

    $(document).on("click", ".wpbel-inline-edit-attribute-save", function () {
        let reload = true;
        let PostIds;
        let postsChecked = $("input.wpbel-item-id:checkbox:checked");
        let bindEdit = $("#wpbel-inline-edit-bind");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }
        let field = $(this).attr("data-field");
        let data = $("#wpbel-modal-attribute-" + field + "-" + $(this).attr("data-item-id") + " input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wpbelUpdatePostAttribute(PostIds, field, data, reload);
    });


    $(document).on("click", ".wpbel-inline-edit-add-new-taxonomy", function () {
        $("#wpbel-create-new-post-taxonomy").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id"));
        $('#wpbel-modal-new-post-taxonomy-post-title').text($(this).attr('data-item-name'));
        wpbelGetTaxonomyParentSelectBox($(this).attr("data-field"));
        $("#wpbel-modal-new-post-taxonomy input").val('');
        $("#wpbel-modal-new-post-taxonomy select").val('').change();
        $("#wpbel-modal-new-post-taxonomy textarea").val('');
    });

    $(document).on("click", ".wpbel-inline-edit-add-new-attribute", function () {
        $("#wpbel-create-new-post-attribute").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id"));
        $('#wpbel-modal-new-post-attribute-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", 'button.wpbel-calculator[data-target="#wpbel-modal-numeric-calculator"]', function () {
        let btn = $("#wpbel-modal-numeric-calculator .wpbel-edit-action-numeric-calculator");
        btn.attr("data-item-id", $(this).attr("data-item-id"));
        btn.attr("data-field", $(this).attr("data-field"));
        btn.attr("data-field-type", $(this).attr("data-field-type"));
        if ($(this).attr('data-field') === 'download_limit' || $(this).attr('data-field') === 'download_expiry') {
            $('#wpbel-modal-numeric-calculator #wpbel-numeric-calculator-type').val('n').change().hide();
            $('#wpbel-modal-numeric-calculator #wpbel-numeric-calculator-round').val('').change().hide();
        } else {
            $('#wpbel-modal-numeric-calculator #wpbel-numeric-calculator-type').show();
            $('#wpbel-modal-numeric-calculator #wpbel-numeric-calculator-round').show();
        }
        $('#wpbel-modal-numeric-calculator-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", ".wpbel-edit-action-numeric-calculator", function () {
        let postID = $(this).attr("data-item-id");
        let PostIds;
        let postsChecked = $("input.wpbel-check-item:checkbox:checked");
        let bindEdit = $("#wpbel-inline-edit-bind");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked.map(function (i) {
                return $(this).val();
            }).get();
            PostIds[postsChecked.length] = postID;
        } else {
            PostIds = [];
            PostIds[0] = postID;
        }

        let wpbelField;
        if ($(this).attr("data-field-type")) {
            wpbelField = [$(this).attr("data-field-type"), $(this).attr("data-field")];
        } else {
            wpbelField = $(this).attr("data-field");
        }

        let values = {
            operator: $("#wpbel-numeric-calculator-operator").val(),
            value: $("#wpbel-numeric-calculator-value").val(),
            operator_type: $("#wpbel-numeric-calculator-type").val(),
            roundItem: $("#wpbel-numeric-calculator-round").val()
        };

        wpbelEditByCalculator(PostIds, wpbelField, values);
    });

    $(document).on('keyup', 'input[type=number][data-field=download_limit], input[type=number][data-field=download_expiry]', function () {
        if ($(this).val() < -1) {
            $(this).val(-1);
        }
    });

    $(document).on('click', '#wpbel-quick-search-reset', function () {
        wpbelResetFilters()
    });

    $(document).on('click', '[data-target="#wpbel-modal-new-item"]', function () {
        let title;
        let description;
        switch ($(this).attr('data-post-type')) {
            case 'post':
                title = "New Post";
                description = "Enter how many new post(s) to create!";
                break;
            case 'page':
                title = "New Page";
                description = "Enter how many new page(s) to create!";
                break;
            case 'custom_post':
                title = "New Custom Post Item";
                description = "Enter how many new custom post(s) to create!";
                break;
        }
        $('#wpbel-new-item-title').html(title);
        $('#wpbel-new-item-description').html(description);
    });

    wpbelGetDefaultFilterProfilePosts();
    wpbelSetTipsyTooltip();
});
