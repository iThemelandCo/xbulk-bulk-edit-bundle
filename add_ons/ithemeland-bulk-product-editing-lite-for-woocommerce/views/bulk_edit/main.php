<?php include_once "filter_form.php";?>
<div class="wcbef-wrap">
    <div class="wcbef-tab-middle-content wcbef-mt64">
        <?php include_once "top_navigation.php";?>
        <div class="wcbef-table" id="wcbef-products-table">
            <?php include_once "products.php";?>
        </div>
        <div class="external-scroll_wrapper">
            <div class="external-scroll_x">
                <div class="scroll-element_outer">
                    <div class="scroll-element_size"></div>
                    <div class="scroll-element_track"></div>
                    <div class="scroll-bar"></div>
                </div>
            </div>
        </div>
        <div class="wcbef-products-pagination wcbef-mt-10">
            <?php include 'pagination.php';?>
        </div>
        <div class="wcbef-products-count wcbef-mt-10">

        </div>
    </div>
</div>
<input type="hidden" id="wcbef-last-modal-opened" value="">
<?php include_once "bulk_edit_form.php";?>
<?php include_once "variations.php";?>
<?php include_once "new_product.php";?>
<?php include_once "columns_modals/select_products.php";?>
<?php include_once "columns_modals/select_files.php";?>
<?php include_once "columns_modals/new_product_taxonomy.php";?>
<?php include_once "columns_modals/new_product_attribute.php";?>
<?php include_once "columns_modals/text_editor.php";?>
<?php include_once "columns_modals/numeric_calculator.php";?>
<?php include_once "duplicate.php";?>
<?php include_once "filter_profiles.php";?>
<?php include_once "column_profiles.php";?>
