<footer class="header footer">
  <div class="container">
    <div class="header__wrap">
      <div class="header-menu">
        <?php wp_nav_menu( array(
          'theme_location'  => 'header_menu',
          'menu'            => 'menu',
          'container'       => 'div',
          'container_class' => '',
          'container_id'    => '',
          'menu_class'      => 'header-menu__wrap',
          'menu_id'         => '',
          'echo'            => true,
          'fallback_cb'     => 'wp_page_menu',
          'before'          => '',
          'after'           => '',
          'link_before'     => '<span>',
          'link_after'      => '</span>',
          'items_wrap'      => '<ul class="header-menu__wrap">%3$s</ul>',
          'depth'           => 0,
          'walker'          => '',
        ) );
        ?>
      </div>
      <?php $image = get_field('header_logo','option'); 
      if( !empty( $image ) ): ?>
        <div class="header__logo">
          <a href="/">
            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
          </a>
        </div>
      <?php endif; ?>
      <?php $link = get_field('link','option'); if( $link ): 
        $link_url = $link['url'];$link_title = $link['title'];$link_target = $link['target'] ? $link['target'] : '_self'; ?>
        <a class="header__link btn btn--white btn--upload" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><span><?php echo esc_html( $link_title ); ?></span></a>
      <?php endif; ?>
      
    </div>
  </div>
</footer>
</body>

<?php wp_footer(); ?>

</html>