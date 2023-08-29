<?php 

function wp_my_theme_enq() {

  wp_register_style( 'portrait-block-shortcode', get_template_directory_uri() . '/template-parts/blocks/portrait-block/portrait-block.css',array(),false,false);
  wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/src/libs/bootstrap/css/bootstrap.min.css',array(),false,false);
  wp_enqueue_style( 'style', get_template_directory_uri() . '/src/css/style.css',array(),false,false);

  wp_enqueue_script( 'my-jquery', get_template_directory_uri() . '/src/libs/jquery-3.2.1.min.js',array(),false,true);
//   
  wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/src/libs/bootstrap/js/bootstrap.min.js',array(),false,true);
  wp_enqueue_script( 'my-common', get_template_directory_uri() . '/src/js/common.js',array('bootstrap-js'),false ,true);

  wp_localize_script( 'my-common', 'myajax',
      array(
          'url' => admin_url('admin-ajax.php')
      )
  );

}
add_action( 'wp_enqueue_scripts', 'wp_my_theme_enq', 9999 );

add_action('enqueue_slick', 'enqueue_slick');
function enqueue_slick() {
  wp_enqueue_style( 'slick', get_template_directory_uri() . '/src/libs/slick/slick.css',array(),false,false);
  wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/src/libs/slick/slick-theme.css',array(),false,false);
  wp_enqueue_script( 'slick', get_template_directory_uri() . '/src/libs/slick/slick.min.js',array('my-jquery'),false,true);
}

add_action('enqueue_inputmask', 'enqueue_inputmask');
function enqueue_inputmask() {
  wp_enqueue_script( 'inputmask', get_template_directory_uri() . '/src/libs/jquery.inputmask.min.js',array('my-jquery'),false,true);
}