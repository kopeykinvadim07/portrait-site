<?php 
  $fields = get_fields(get_the_id()); 
  $text = strval($fields['text']);
  $images = '';
  foreach ($fields['images'] as $image) { 
    $images .= '<img src="'. $image .'" alt="'. get_the_title() .'">'; 
  }
  $data = array(
    'images'      => $images,
    'name'        => get_the_title(),
    'text'        => $text,
    'age'         => $fields['age'],
    'city'        => $fields['city'],
    'id'          => get_the_id()
  );
?>

<div class="portrait-card">
  <div class="portrait-card__images">
    <?php echo $images; ?>
  </div>
  <div class="portrait-card__hover">
    <div class="portrait-card__row">
      <div class="portrait-card__name"><?php echo get_the_title(); ?></div>
    </div>
    <div class="portrait-card__city"><?php echo $fields['city']; ?></div>
    <button class="portrait-card__read-story" data-portrait="<?php echo htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8'); ?>"><i></i></button>
  </div>
</div>
