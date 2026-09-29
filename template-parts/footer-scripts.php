
<?php if (
    is_page_template('page-templates/gform-setup-details.php')
) { ?>
    <script defer type="text/javascript"
        src="<?php echo get_template_directory_uri() . '/js/setup-address-autocomplete.js' ?>?ver=<?php echo wp_get_theme()->get('Version') ?>"
        id="lla-setup-address-autocomplete-custom-js"></script>
<?php } ?>
