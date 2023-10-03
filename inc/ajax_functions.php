<?php 

add_action('wp_ajax_portrait_add_slide_ajax', 'portrait_add_slide_ajax_callback');
add_action('wp_ajax_nopriv_portrait_add_slide_ajax', 'portrait_add_slide_ajax_callback');
function portrait_add_slide_ajax_callback() {

  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'portrait_block_card')) {
    $result['status'] = false;
    wp_die(json_encode($result));
  }

  $fields = array(
    'current_ids' => $_POST['current_ids'],
    'cat_id' => sanitize_text_field(htmlspecialchars($_POST['cat_id']))
  );
  $fields['current_ids'] = array_map('intval', $fields['current_ids']);

  if (empty($fields['cat_id'])){
    $result['status'] = false;
    wp_die(json_encode($result));
  }

  $args = array(
    'post_type' => 'portrait',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'post__not_in' => $fields['current_ids'],
    'orderby' => 'rand',
    'tax_query' => array(
      array(
        'taxonomy' => 'category',
        'field' => 'id',
        'terms' => $fields['cat_id']
      )
    )
  ); 
  $portrait = new WP_Query( $args );
  $post_ids = wp_list_pluck( $portrait->posts, 'ID' );
  $fields['current_ids'][] = $post_ids[0];

  ob_start();
  while($portrait->have_posts()) { $portrait->the_post(); 
    get_template_part('template-parts/card/portrait'); 
  }
  $html = ob_get_contents();
  ob_end_clean();
  wp_reset_postdata();

  $result = array('status' => true, 'current_ids' => $fields['current_ids'], 'html' => $html);
  wp_die(json_encode($result));
}


add_action('wp_ajax_portrait_add_modal_slide_ajax', 'portrait_add_modal_slide_ajax_callback');
add_action('wp_ajax_nopriv_portrait_add_modal_slide_ajax', 'portrait_add_modal_slide_ajax_callback');
function portrait_add_modal_slide_ajax_callback() {

  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'portrait_block_card')) {
    $result['status'] = false;
    wp_die(json_encode($result));
  }

  $fields = array(
    'current_ids' => $_POST['current_ids'],
    'cat_id' => sanitize_text_field(htmlspecialchars($_POST['cat_id']))
  );
  $fields['current_ids'] = array_map('intval', $fields['current_ids']);

  if (empty($fields['cat_id'])){
    $result['status'] = false;
    wp_die(json_encode($result));
  }

  $args = array(
    'post_type' => 'portrait',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'post__not_in' => $fields['current_ids'],
    'orderby' => 'rand',
    'tax_query' => array(
      array(
        'taxonomy' => 'category',
        'field' => 'id',
        'terms' => $fields['cat_id']
      )
    )
  ); 
  $portrait = new WP_Query( $args );
  $post_ids = wp_list_pluck( $portrait->posts, 'ID' );
  $fields['current_ids'][] = $post_ids[0];

  while($portrait->have_posts()) { $portrait->the_post(); 
    $fields_acf = get_fields(get_the_id()); 
    $images = '';
    foreach ($fields_acf['images'] as $image) { 
      $images .= '<img src="'. $image .'" alt="'. get_the_title() .'">'; 
    }
    $data = array(
      'images'      => $images,
      'name'        => get_the_title(),
      'text'        => strval($fields_acf['text']),
      'age'         => $fields_acf['age'],
      'city'        => $fields_acf['city'],
      'id'          => get_the_id()
    );
  }
  wp_reset_postdata();

  $result = array('status' => true, 'fields' => $fields, 'data' => $data);
  wp_die(json_encode($result));
}

add_action('wp_ajax_portrait_load_more_ajax', 'portrait_load_more_ajax_callback');
add_action('wp_ajax_nopriv_portrait_load_more_ajax', 'portrait_load_more_ajax_callback');
function portrait_load_more_ajax_callback() {

  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'portrait_block_card_load_more')) {
    $result['status'] = false;
    wp_die(json_encode($result));
  }
  
  $fields = array(
    'current_ids' => $_POST['current_ids'],
    'max' => intval($_POST['max']),
    'count' => intval($_POST['count']),
    'last' => false
  );
  $fields['current_ids'] = array_map('intval', $fields['current_ids']);
  $fields['current_ids'][] = 1;

  $args = array(
      'type'     => 'portrait',
      'taxonomy' => 'category',
      'orderby' => 'name',
      'order'   => 'ASC',
      'exclude' => $fields['current_ids'],
      'hide_empty' => false,
      'number'  => 20,
  ); $categories = get_categories($args); 
  foreach ($categories as $category) {
    array_push($fields['current_ids'], $category->term_id);
  }
  ob_start();
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
  $html = ob_get_contents();
  ob_end_clean();

  $fields['count'] += count($categories);

  if ($fields['count'] >= $fields['max']) {
    $fields['last'] = true;
  }

  $result = array('status' => true, 'last' => $fields['last'], 'html' => $html, 'fields' => $fields);
  wp_die(json_encode($result));
}