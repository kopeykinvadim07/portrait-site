<?php
/**
 * The template for displaying 404 pages (Not Found)
 */
get_header(); ?>

<div id="primary" class="content-area">
  <div id="content" class="site-content" role="main">

    <div class="page-wrapper" style="padding: 130px 0;text-align: center">
        <div class="page-content">
          <h1 style="font-size: 60px; margin-bottom: 40px;"><?php _e('404'); ?></h1>
          <h2><?php _e( 'Page not found' ); ?></h2>
        </div><!-- .page-content -->
    </div><!-- .page-wrapper -->

  </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer();?>