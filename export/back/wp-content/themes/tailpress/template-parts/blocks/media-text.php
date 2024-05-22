<?php

/**
 * Media/text Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'media-text-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'media-text';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$media = get_field('gutenberg_media') ?: '';
$text = get_field('gutenberg_text') ?: 'Insérez votre texte';
$composition = get_field('gutenberg_composition') ?: '';
$style_img = get_field('gutenberg_style_img') ?: '';
$bouton = get_field('gutenberg_button') ?: '';
$bouton_group = get_field('gutenberg_button_group') ?: '';


?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
  <div class="py-8">
    <div class="grid grid-cols-12 lg:gap-8 lg:items-center">
      <div class="col-span-12 lg:col-span-7 p-2 <?php if ($composition === 'text-right') : ?> order-2 <?php endif; ?>">
        <p><?php echo $text; ?></p>
        <?php if (!empty($bouton)) : ?>
          <a href="<?php echo $bouton_group['gutenberg_btn_link'] ?>" <?php if (!empty($bouton_group['gutenberg_target'])) : ?> target="_blank" <?php endif; ?> style="transition: all 0.3s ease-in-out; background-color: <?php echo $bouton_group['gutenberg_btn_color'] ?> ; color: <?php echo $bouton_group['gutenberg_btn_text_color'] ?>" class="my-3 no-underline inline-flex items-center justify-center px-5 py-2 <?php echo $bouton_group['gutenberg_btn_size'] ?> font-semibold <?php echo $bouton_group['gutenberg_btn_style'] ?> hover:opacity-80">
            <?php if (!empty($bouton_group['gutenberg_btn_icon'])) : ?>
              <i class="<?php echo $bouton_group['gutenberg_btn_icon'] ?> <?php if ($bouton_group['gutenberg_icon_position'] === 'icon_right') : ?> ml-2 order-2 <?php else : ?> mr-2 <?php endif; ?>"></i>
            <?php endif ?>
            <?php echo $bouton_group['gutenberg_btn_text'] ?>
          </a>
        <?php endif ?>

      </div>
      <div class="col-span-12 lg:col-span-5 p-2 text-center">
        <img src=" <?php if (!empty($media['url'])) : ?> <?php echo $media['url']; ?> <?php else : ?> https://source.unsplash.com/1600x900/?nature,water <?php endif; ?>" alt="<?php if (!empty($media['alt'])) : ?> <?php echo $media['alt']; ?> <?php endif; ?>" class="<?php echo esc_attr($style_img); ?> <?php if ($style_img === 'rounded-full') : ?> h-80 w-80 mx-auto object-cover <?php else : ?> w-full <?php endif; ?>">
      </div>
    </div>
  </div>
