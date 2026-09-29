<?php 

$ver = wp_get_theme()->get('Version');


if (
    is_page_template('page-templates/gform-setup-details.php')
) {

    $ver = wp_get_theme()->get('Version');
    $gform_css_url = get_stylesheet_directory_uri() . "/css/gform.css?ver={$ver}";

?>

    <link rel="preload" href="<?php echo esc_url($gform_css_url); ?>" as="style" />
    <link rel="stylesheet" href="<?php echo esc_url($gform_css_url); ?>" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="<?php echo esc_url($gform_css_url); ?>" />
    </noscript>


<?php }?>

<?php if (
    is_page_template('page-templates/gform-setup-details.php')
) { ?>
    <script defer type="text/javascript"
        src="<?php echo get_stylesheet_directory_uri() . '/js/setup-address-autocomplete.js' ?>?ver=<?php echo wp_get_theme()->get('Version') ?>"
        id="lla-setup-address-autocomplete-custom-js"></script>
<?php } ?>