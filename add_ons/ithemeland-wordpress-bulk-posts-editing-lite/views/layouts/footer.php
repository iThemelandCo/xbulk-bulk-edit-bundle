<script>
    <?php
    if (isset($el_check)) {
        $el_check = ($el_check) ? 'true' : 'false';
    } else {
        $el_check = 'true';
    }
    ?>
    let el_check = "<?php echo esc_html($el_check); ?>";
    if (el_check == 'false') {
        window.location.hash = "purchase-verification";
    }
</script>
</div>