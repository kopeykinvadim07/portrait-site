<?php
/**
 * Block Name: portrait-block
 */

  $class = 'portrait-block portrait-block-' . $block['id'];
  $className = '.portrait-block-' . $block['id'];
  if (!empty($block['className'])){
    $class .= ' ' . $block['className'];
  }
  $id_block = '';
  if( !empty($block['anchor']) ) {
    $id_block = 'id=' . $block['anchor'];
  }

  $fields = get_fields();
  $blockStyle = get_style_acf_gut_block($fields['settings_section'], $className);

?>

<section <?php echo esc_attr($id_block); ?> class="<?php echo $class;  ?>" >
    <div class="portrait-block__wrap">
    <?php
      $args = array(
        'type'     => 'portrait',
        'taxonomy' => 'category',
        'orderby' => 'name',
        'order'   => 'ASC',
        'exclude' => 1,
        'hide_empty' => false,
        'number'  => 20,
    ); $categories = get_categories($args); 
    foreach ($categories as $category) {
      $args = array(
        'post_type' => 'portrait',
        'post_status' => 'publish',
        'posts_per_page' => 2,
        'orderby' => 'rand',
        'tax_query' => array(
          array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => $category->term_id
          )
        )
      ); 
      $portrait = new WP_Query( $args );
      $post_ids = wp_list_pluck( $portrait->posts, 'ID' );

      if($portrait->have_posts()) {
        ?><div class="portrait-block__card<?php echo ($portrait->found_posts <= 2) ? ' max':''; ?>" data-cat-id="<?php echo $category->term_id; ?>" data-current-ids="<?php echo htmlspecialchars(json_encode($post_ids), ENT_QUOTES, 'UTF-8'); ?>" data-post-count="<?php echo $portrait->found_posts; ?>" data-nonce="<?php echo wp_create_nonce('portrait_block_card'); ?>">
          <div class="portrait-block__slider"><?php
            while($portrait->have_posts()) { $portrait->the_post(); 
              get_template_part('template-parts/card/portrait');
            }
        ?></div>
        <div class="portrait-block__country"><?php echo $category->name; ?></div>
          <button class="portrait-block__rand<?php echo ($portrait->found_posts <= 1) ? ' one':''; ?>">
            <div class="portrait-block__rand-row"><span>1</span> of <?php echo $portrait->found_posts;?></div>
          </button>
        </div><?php
      } else { 
        ?><div class="portrait-block__card"><?php
          get_template_part('template-parts/card/upload-portrait', null, array('name' => $category->name));
        ?></div><?php
      }
      wp_reset_postdata();
    }
    ?>
  </div>
  <?php 
  $categoryIDArray = [];
  foreach ($categories as $category) {
    array_push($categoryIDArray, $category->term_id);
  }
  $numTerms = wp_count_terms( 'category', array(
      'hide_empty'=> false,
      'parent'    => 0
  ) );
  ?>
  <?php if (count($categories) < ($numTerms - 1)) : ?>
    <button type="button" class="portrait-block__load-more" data-count="<?php echo count($categories); ?>" data-max="<?php echo $numTerms - 1; ?>" data-cats="<?php echo htmlspecialchars(json_encode($categoryIDArray), ENT_QUOTES, 'UTF-8'); ?>" data-nonce="<?php echo wp_create_nonce('portrait_block_card_load_more'); ?>">Load more countries</button>
  <?php endif; ?>

  <!-- Modal -->
  <div class="modal fade read-more-modal" id="readMore" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><svg width="53" height="53" viewBox="0 0 53 53" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_7_1574)"><path d="M27.9729 26.4937L38.695 15.7717C39.0947 15.3579 39.0833 14.6984 38.6695 14.2987C38.2658 13.9088 37.6258 13.9088 37.2221 14.2987L26.5 25.0208L15.7779 14.2987C15.3641 13.8991 14.7047 13.9106 14.305 14.3243C13.9151 14.728 13.9151 15.368 14.305 15.7717L25.027 26.4937L14.305 37.2158C13.8983 37.6226 13.8983 38.282 14.305 38.6887C14.7118 39.0954 15.3712 39.0954 15.7779 38.6887L26.5 27.9667L37.222 38.6887C37.6288 39.0954 38.2882 39.0954 38.695 38.6887C39.1016 38.2819 39.1016 37.6225 38.695 37.2158L27.9729 26.4937Z" fill="white"/></g><defs><clipPath id="clip0_7_1574"><rect width="25" height="25" fill="white" transform="translate(14 14)"/></clipPath></defs></svg></span>
        </button>
        <div class="modal-body">
          <div class="read-more-modal__content" data-nonce="<?php echo wp_create_nonce('portrait_block_card'); ?>"></div>
          <div class="read-more-modal__count"><span class="read-more-modal__current">1</span> of <span class="read-more-modal__max">1</span></div>
        </div>
      </div>
    </div>
  </div>

  <style>
    <?php echo $blockStyle; ?>   
  </style>
</section>
<?php 
