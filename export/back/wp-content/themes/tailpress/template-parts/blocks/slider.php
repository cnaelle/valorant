<?php

// Create id attribute allowing for custom "anchor" value.
$id = 'slider-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'slider';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$slides = get_field('gutenberg_slides') ?: '';

?>
<section class="<?php echo esc_attr($className); ?>">

  <!-- Swiper -->
  <div class="swiper <?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>">
    <div class="swiper-wrapper">
      <?php if (!empty($slides)) : ?>
        <?php foreach ($slides as $slide) : ?>
          <div class="swiper-slide"><img src="<?php echo $slide['image']['url'] ?>" alt="<?php echo $slide['image']['alt'] ?>"></div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>
<?php else : ?>
  <p>Ajoutez votre première image</p>
<?php endif; ?>
</section>
