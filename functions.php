<?php

add_action(
    'after_setup_theme',
    function() {
        add_theme_support( 'html5', [ 'script', 'style' ] );

        // Add support for title tag
        add_theme_support( 'title-tag' );

        // Add support for post thumbnails
        add_theme_support( 'post-thumbnails' );

        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );
    }
);

get_template_part('inc/custom_fields');
get_template_part('inc/remove_emoji');
get_template_part('inc/enqueue_scripts');
get_template_part('inc/ajax_functions');

add_action('after_setup_theme', function(){
    register_nav_menus( array(
        'header_menu' => 'Header menu',
        'footer_menu1' => 'Footer menu 1',
        'footer_menu2' => 'Footer menu 2',
        'footer_menu3' => 'Footer menu 3',
    ) );
    register_sidebar( array(
        'name'          => 'Footer col 1',
        'id'            => 'footer_col_1',
        'before_widget' => '<div class="footer-col-menu">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => 'Footer col 2',
        'id'            => 'footer_col_2',
        'before_widget' => '<div class="footer-col-menu">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => 'Footer col 3',
        'id'            => 'footer_col_3',
        'before_widget' => '<div class="footer-col-menu">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
});

add_filter('gutenberg_can_edit_portrait', '__return_false', 10);

// function cf7_country(){
//    $output = "<select name='cf7_country' id='cf7_country' onchange='document.getElementById(\"cf7_country\").value=this.value;'>";
//     $args = array(
//         'type'     => 'portrait',
//         'taxonomy' => 'category',
//         'orderby' => 'name',
//         'order'   => 'ASC',
//         'exclude' => 1,
//         'hide_empty' => false,
//         'number'  => 20
//     ); $categories = get_categories($args); 
//     foreach ( $categories as $category) {
//         $output .= "<option value='$categories->name'> $categories->name </option>";
//     } 

//     $output .= "</select>";
// return $output;
// }

// wpcf7_add_shortcode('cf7_country', 'cf7_country', true);

//[cf7_country retreat class:form-control id:cf7_country]
add_action( 'wpcf7_init', 'custom_cf7_country' );
function custom_cf7_country() {
    wpcf7_add_form_tag( 'cf7_country', 'custom_cf7_country_handler', array( 'name-attr' => true ) );
}

function custom_cf7_country_handler( $tag ) {
    $atts = array();
    $atts['name'] = $tag->name;
    $atts['class'] = $tag->get_class_option();
    $atts['id'] = $tag->get_id_option();
    $atts = wpcf7_format_atts( $atts );
    $html = '<select ' . $atts . '>';
    $args = array(
        'type'     => 'portrait',
        'taxonomy' => 'category',
        'orderby' => 'name',
        'order'   => 'ASC',
        'exclude' => 1,
        'hide_empty' => false,
    ); $categories = get_categories($args); 
    foreach ( $categories as $category):
        $html .= '<option value="' . $category->name . '">' . $category->name . '</option>';
    endforeach;
    $html .= '</select>';
    return $html;
}

add_shortcode('author_card', 'author_card_fun');
function author_card_fun( $atts, $content){

    wp_enqueue_style( 'portrait-block-shortcode' );
    do_action('enqueue_slick');

    $atts = shortcode_atts( array(
        'post_id' => 136
    ), $atts );


    $args = array(
        'post_type' => 'portrait',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'p'       => $atts['post_id']
    ); 
    $portrait = new WP_Query( $args );
    ob_start();

    ?><div class="portrait-block"><?php

    if($portrait->have_posts()) {
        while($portrait->have_posts()) { $portrait->the_post();
            ?><div class="portrait-block__card portrait-block__single-card"><?php
                get_template_part('template-parts/card/portrait');
            ?></div><?php
        }
    }
    ?><!-- Modal -->
    <div class="modal fade read-more-modal" id="readMore" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><svg width="53" height="53" viewBox="0 0 53 53" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_7_1574)"><path d="M27.9729 26.4937L38.695 15.7717C39.0947 15.3579 39.0833 14.6984 38.6695 14.2987C38.2658 13.9088 37.6258 13.9088 37.2221 14.2987L26.5 25.0208L15.7779 14.2987C15.3641 13.8991 14.7047 13.9106 14.305 14.3243C13.9151 14.728 13.9151 15.368 14.305 15.7717L25.027 26.4937L14.305 37.2158C13.8983 37.6226 13.8983 38.282 14.305 38.6887C14.7118 39.0954 15.3712 39.0954 15.7779 38.6887L26.5 27.9667L37.222 38.6887C37.6288 39.0954 38.2882 39.0954 38.695 38.6887C39.1016 38.2819 39.1016 37.6225 38.695 37.2158L27.9729 26.4937Z" fill="white"/></g><defs><clipPath id="clip0_7_1574"><rect width="25" height="25" fill="white" transform="translate(14 14)"/></clipPath></defs></svg></span>
            </button>
            <div class="modal-body">
            <div class="read-more-modal__content" data-nonce="<?php echo wp_create_nonce('portrait_block_card'); ?>"></div>
            </div>
        </div>
        </div>
    </div>
    
    </div><?php

    $output = ob_get_contents();
    ob_end_clean();

    
    return $output;
}

