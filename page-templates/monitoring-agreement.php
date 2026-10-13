<?php
/*
Template Name: Monitoring Agreement
URI: https://livelife-monitoring-au.local/monitoring-agreement-new/
*/

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
  exit('Direct script access denied.');
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
            <?php the_content(); 
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

<?php get_footer(); ?>