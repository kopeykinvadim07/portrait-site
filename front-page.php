<?php get_header(); ?>
<main class="clearfix">

  <?php
    if ( have_posts() ) { while ( have_posts() ) {
      	the_post();
        the_content();
      }
    }
   ?>

</main>
<?php get_footer(); ?>