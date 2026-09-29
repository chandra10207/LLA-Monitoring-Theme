<?php
/*
Template Name: Monitoring Setup Details 
URI: /livelife-alarm-setup-details/?setup_key=jv1aaOzHbAqGnPLMyfcgSac6&order_number=AU132131&quote_number=AU132131
*/

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
  exit('Direct script access denied.');
}
$website_key = "";
if (defined('WOO_SETUP_KEY') && !empty(WOO_SETUP_KEY)) {
  $website_key = WOO_SETUP_KEY;
}
$setup_key = isset($_GET['setup_key']) ? sanitize_text_field($_GET['setup_key']) : '';
$seq_order_id = isset($_GET['order_number']) ? sanitize_text_field($_GET['order_number']) : '';
$seq_quote_id = isset($_GET['quote_number']) ? sanitize_text_field($_GET['quote_number']) : '';
$device_type = isset($_GET['D']) ? sanitize_text_field($_GET['D']) : '';
$website_order_id = isset($_GET['WO']) ? sanitize_text_field($_GET['WO']) : '';
$is_monitoring = isset($_GET['monitoring']) ? sanitize_text_field($_GET['monitoring']) : '';

$referrer_source = isset($_GET['RSOURCE']) ? sanitize_text_field($_GET['RSOURCE']) : '';
$woo_ref_code = isset($_GET['WOOREF']) ? sanitize_text_field($_GET['WOOREF']) : '';

$monitoring_setup_form_id = '2';
$family_setup_form_id = '2';


if (defined('WOO_MONITORING_SETUP_FORM_ID') && !empty(WOO_MONITORING_SETUP_FORM_ID)) {
  $monitoring_setup_form_id = WOO_MONITORING_SETUP_FORM_ID;
}

/* BTN URL Addition Start*/
$quote_btn_url = '';

if (!empty($referrer_source) && defined('SF_WOO_QUOTE_APEX_URL')) {

  $quote_btn_url =  SF_WOO_QUOTE_APEX_URL;

  $sf_query_args = [];
  if (!empty($seq_quote_id)) {
    $sf_query_args['quote_number'] = $seq_quote_id;
  }
  if (!empty($woo_ref_code)) {
    $sf_query_args['key'] = $woo_ref_code;
  }

  if (!empty($quote_btn_url) && !empty($sf_query_args)) {
    $quote_btn_url = add_query_arg($sf_query_args, $quote_btn_url);
  }
}

// echo '<!-- Quote Button URL: ' . $quote_btn_url . ' -->';
// die;
/* BTN URL Addition End*/


if (!empty($website_order_id)) {
  $seq_order_id = $website_order_id;
}

if ($setup_key !== $website_key) {
  echo 'Access denied.';
  exit;
}

if (!empty($device_type)) {
  $device_name = $device_type;
} else {
  $device_name = 'alarm';
}

if (empty($seq_order_id) && empty($seq_quote_id)) {
  echo 'Access denied.';
  exit;
}

get_header(); ?>

<main id="site-content" role="main">


  <?php
  if (have_posts()):
    while (have_posts()):
      the_post(); ?>
      <article <?php post_class(); ?>>
        <div class="entry-content thank_you container">
          <div class="lla_page">
            <?php // the_content(); 
            ?>
          </div>
        </div>
      </article>
  <?php
    endwhile;
  else:
    echo '<p>No content found.</p>';
  endif;
  ?>
</main>




<section class="lla-form-container">

  <div class="container">
   


    <?php
/*
    if (!empty($seq_quote_id)) {
      echo '<h2 class="success-message">Additional monitoring details for quote: ' . $seq_quote_id . '</h2>';
    }
     elseif (!empty($seq_order_id)) {
      echo '<h2 class="success-message">Additional monitoring details for order: ' . $seq_order_id . '</h2>';
    }
    else{
       echo '<h2 class="success-message">Additional monitoring details </h2>';
    }
*/
    ?>

        <?php

    if (!empty($seq_order_id)) {
      echo '<h2 class="success-message">Complete your alarm setup for order - ' . $seq_order_id . '</h2>';
    }


    if (!empty($seq_quote_id)) {
      echo '<h2 class="success-message">Complete your alarm setup </h2>';
    }

    ?>

        <p class="lla-form-description">
      To program your
      <?php echo $device_name; ?>, we need information about the wearer and their emergency contacts.
      <?php if (empty($quote_btn_url)) { ?>
        Please fill in the details below.
      <?php } ?>

    </p>

    <div class="lla-setup-form-wrapper lsfw <?php echo $referrer_source; ?>">

      <div id="lla-setup-form-container" class="lla-custom-gform-container">
        <?php

        if ($is_monitoring == "1") {
          echo do_shortcode('[gravityform id="' . $monitoring_setup_form_id . '" title="false" ajax="true" field_values="order_number=' . $seq_order_id . '&amp;quote_number=' . $seq_quote_id . '" ]');
        } else {
          echo do_shortcode('[gravityform id="' . $family_setup_form_id . '" title="false" ajax="true" field_values="order_number=' . $seq_order_id . '&amp;quote_number=' . $seq_quote_id . '" ]');
        }



        ?>

      </div>

    </div>

  </div>

</section>




<script>
  document.addEventListener('change', function(e) {

    if (!e.target.matches('input[name="input_56"]')) {
      return;
    }

    const field = document.querySelector('.lla-preferred-contact-radios');

    if (!field) {
      console.log('Field not found');
      return;
    }

    console.log('Selected value:', e.target.value);
    console.log('Field:', field);

    field.classList.toggle('open', e.target.value === '0');

  });
</script>

<?php get_footer(); ?>