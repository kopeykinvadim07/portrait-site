<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php bloginfo('name'); if (!is_front_page() ){ echo ' |'; } ?>
    <?php is_front_page() ? '' : wp_title(''); ?></title>
    <?php wp_head(); ?>
    <?php if( is_single() ) { echo '<meta property="og:image" content="'. get_the_post_thumbnail_url(get_the_ID(),'full')   .'" />'; } ?>
  </head>
  <body <?php body_class(); ?>>

    <header class="header">
      <div class="container">
        <div class="header__wrap">
          <input type="checkbox" id="burger_btn" class="hamburger-btn__input">
          <label for="burger_btn" class="hamburger-btn__label">
            <div class="hamburger-btn">
              <span class="bar bar1"></span>
              <span class="bar bar2"></span>
              <span class="bar bar3"></span>
              <span class="bar bar4"></span>
            </div>
          </label>
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
        </div>
      </div>
		</header>
    <header class="sub-header">
      <h2 class="sub-header__title">A Global Gallery of Compassion and Community</h2>
    </header>
