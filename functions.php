<?php

function theme_enqueue_styles() {
    wp_enqueue_script('custom_script', get_stylesheet_directory_uri().'/custom_script.js');
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [] );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 20 );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

add_filter ('add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout() {
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}


// Display Terms and conditions in admin
add_action( 'woocommerce_admin_order_data_after_billing_address', 'display_terms_admin', 10, 1 );
function display_terms_admin( $order ) {
    $terms = $order->get_meta( 'terms', true );
	$terms_status = ( $terms == 'on' ? __('accepted') : __('undefined') );
    echo '<p><strong>'.__('Terms & conditions').':</strong> ' . $terms_status . '</p>';
}

add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', 'custom_terms_and_conditions_checkbox_text' );
function custom_terms_and_conditions_checkbox_text( $text ){
    $text = get_option( 'woocommerce_checkout_terms_and_conditions_checkbox_text', sprintf( __( 'I have read and agree to the monitoring %s', 'woocommerce' ), '[terms]' ) );

    return $text;
}

//add_action( 'woocommerce_checkout_after_terms_and_conditions', 'custom_html_after_terms');

function custom_html_after_terms() {
  echo '<script>jQuery (".woocommerce-terms-and-conditions-link").on("click", function(e) { e.preventDefault(); jQuery(".tc_box").slideDown({ start: function () { jQuery(this).css({ display: "flex" }) } }); });</script>';
  echo '<script>jQuery (".tc_accept").on("click", function() { jQuery(".woocommerce-form__input.woocommerce-form__input-checkbox.input-checkbox").prop("checked", true); jQuery(".tc_box").slideUp(); });</script>';
}
/* Terms and Conditions */

//Add custom fields
add_action('woocommerce_checkout_after_customer_details', 'get_monitoring_info', 10);
function get_monitoring_info() {
    echo '<div class="woocommerce-content-box full-width checkout_customer_details">';
        echo '<h3>Customer details</h3>';
            woocommerce_form_field( 'customer_age', array(
                'type'          => 'date',
                'class'         => array('form-row form-row-wide checkout_customer_field'),
                'label'         => __('Wearer Date of Birth')
            ));
            woocommerce_form_field( 'allergies', array(
                'type'          => 'text',
                'class'         => array('form-row form-row-wide checkout_customer_field'),
                'label'         => __('Allergies')
            ));
            woocommerce_form_field( 'medical_history', array(
                'type'          => 'textarea',
                'class'         => array('form-row form-row-wide checkout_customer_field'),
                'label'         => __('Medical history')
            ));
            woocommerce_form_field( 'entry_direction', array(
                'type'          => 'text',
                'class'         => array('form-row form-row-wide checkout_customer_field'),
                'label'         => __('Entry Directions')
            ));
    echo '</div>';
}


//Display custom fields in admin
add_action( 'woocommerce_admin_order_data_after_notes', 'display_customer_fields',10,2 );
function display_customer_fields( $order ) {
   
    echo '<div class="postbox">';
    echo '' . esc_html__( 'Wearer Date of Birth' ) . ': ' . esc_html( $order->get_meta( 'customer_age', true ) ) . '';
    echo '' . esc_html__( 'Allergies' ) . ': ' . esc_html( $order->get_meta( 'allergies', true ) ) . '';
    echo '' . esc_html__( 'Medical History' ) . ': ' . esc_html( $order->get_meta( 'medical_history', true ) ) . '';
    echo '' . esc_html__( 'Entry Directions' ) . ': ' . esc_html( $order->get_meta( 'entry_direction', true ) ) . '';
   
    echo '</div>';
}


//Save custom fields to the database
add_action( 'woocommerce_checkout_update_order_meta', 'update_customer_fields' );
function update_customer_fields($order_id) {
    $no_option = " ";
    $order = wc_get_order( $order_id );
    if ( ! empty( $_POST['customer_age'] ) ) {
        $order->update_meta_data( 'customer_age', sanitize_text_field( $_POST['customer_age'] ) );
    } else {
        $order->update_meta_data('customer_age', sanitize_text_field($no_option) );
    }
    
    if ( ! empty( $_POST['allergies'] ) ) {
        $order->update_meta_data( 'allergies', sanitize_text_field( $_POST['allergies'] ) );
    } else {
        $order->update_meta_data( 'allergies', sanitize_text_field($no_option) );
    }
    
    if ( ! empty( $_POST['medical_history'] ) ) {
        
        $order->update_meta_data( 'medical_history', sanitize_textarea_field( $_POST['medical_history'] ) );
       
    } else {
        $order->update_meta_data( 'medical_history', sanitize_textarea_field($no_option) );
        
    }
    
    if ( ! empty( $_POST['entry_direction'] ) ) {
        
        $order->update_meta_data( 'entry_direction', sanitize_textarea_field( $_POST['entry_direction'] ) );
        
    } else {
        $order->update_meta_data( 'entry_direction', sanitize_text_field($no_option) );
    }
    
    if ($_POST['terms']) {
        $order->update_meta_data( 'terms', esc_attr($_POST['terms']));
    }
    $order->save_meta_data();
}

/* Admin Email */
add_filter( 'woocommerce_email_order_meta_fields', 'custom_woocommerce_email_order_meta_fields', 10, 3 );
function custom_woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
    if ( $sent_to_admin ) {
        $fields[ 'customer_age' ] = array(
            'label' => __( 'Wearer Date of Birth'),
            'value' => get_post_meta( $order->id, 'customer_age', true ),
        );

        $fields[ 'Allergies' ] = array(
            'label' => __( 'Allergies'),
            'value' => get_post_meta( $order->id, 'allergies', true ),
        );
   
        $fields[ 'medical_history' ] = array(
            'label' => __( 'Medical History'),
            'value' => get_post_meta( $order->id, 'medical_history', true ),
        );
    
        $fields[ 'entry_direction' ] = array(
            'label' => __( 'Entry Direction'),
            'value' => get_post_meta( $order->id, 'entry_direction', true ),
        );
        
        $fields[ '_terms' ] = array(
            'label' => __( 'Accepted terms and conditions? '),
            'value' => get_post_meta( $order->id, 'terms', true ),
        );
        
    }
    return $fields;
}

/* Additional Fields*/
/* Shop page hooks */
add_action( 'woocommerce_shop_loop_item_title', 'display_tags', 5 );
function display_tags() {
    global $product;
    if(has_term('professionally-monitored', 'product_tag')) {
    echo '<div class="display_tag"><span>Professionally monitored</span></div>';
    } else if(has_term('family-and-friends', 'product_tag')) {
        echo '<div class="display_tag"><span>Family and friends</span></div>';
    }
}
/* Shop page hooks*/

/* Pre Order */
// add_filter('woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text', 10, 2);
// add_filter('woocommerce_product_single_add_to_cart_text', 'custom_add_to_cart_text', 10, 2);
function custom_add_to_cart_text($text, $product) {
    // Replace '123' with your product ID
    if ($product->get_id() == 1999) {
        return 'Pre Order'; // Replace with your desired text
    }
    return $text;
}
/* Pre Order */

/* Get form */

/* Get form */
function lla_load_activecampaign_script() {?>
    <script>
        // script from Active campaign
        (function(e,t,o,n,p,r,i){e.visitorGlobalObjectAlias=n;e[e.visitorGlobalObjectAlias]=e[e.visitorGlobalObjectAlias]||function(){(e[e.visitorGlobalObjectAlias].q=e[e.visitorGlobalObjectAlias].q||[]).push(arguments)};e[e.visitorGlobalObjectAlias].l=(new Date).getTime();r=t.createElement("script");r.src=o;r.async=true;i=t.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)})(window,document,"https://diffuser-cdn.app-us1.com/diffuser/diffuser.js","vgo");
        vgo('setAccount', '255452379');
        vgo('setTrackByDefault', true);
        vgo('process');
    </script>
       <?php
    }
add_action( 'wp_footer', 'lla_load_activecampaign_script', 100 );

add_action( 'init', 'lla_set_custom_timezone' );

function lla_set_custom_timezone() {
    date_default_timezone_set( 'Australia/Sydney' );
}

add_action('init', function () {
    if (class_exists('GFCommon')) {
        require_once get_stylesheet_directory() . '/inc/gform-customisation.php';
    }
});