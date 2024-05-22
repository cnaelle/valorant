<?php

/**
 * Banner Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'banner-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'banner';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$bg_color = get_field('gutenberg_bg_color') ?: '#00293d';
$bg_img = get_field('gutenberg_bg_img') ?: '';
$title = get_field('gutenberg_title') ?: '';
$title_color = get_field('gutenberg_title_color') ?: '#fff';
$sub_title = get_field('gutenberg_sub_title') ?: '';
$sub_title_color = get_field('gutenberg_sub_title_color') ?: '#bbd2fe';
$bouton = get_field('gutenberg_button');
$bouton_group = get_field('gutenberg_button_group') ?: '';



?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
  <div class="my-10" style="background-color: <?php echo $bg_color ?>; background-image:url(<?php echo $bg_img ?>); background-size:cover; background-repeat:no repeat;">
    <div class="max-w-2xl mx-auto py-16 px-4 sm:py-20 sm:px-6 lg:px-8 text-center">
      <h2 class="!text-3xl sm:!text-4xl font-extrabold" style="color:<?php echo $title_color ?>">
        <?php echo $title ?>
      </h2>
      <p class="mt-4 !text-lg leading-6" style="color:<?php echo $sub_title_color ?>"><?php echo $sub_title ?></p>
      <?php if (!empty($bouton)) : ?>
        <a href="<?php echo $bouton_group['gutenberg_btn_link'] ?>" <?php if (!empty($bouton_group['gutenberg_target'])) : ?> target="_blank" <?php endif; ?> style="transition: all 0.3s ease-in-out; background-color: <?php echo $bouton_group['gutenberg_btn_color'] ?> ; color: <?php echo $bouton_group['gutenberg_btn_text_color'] ?>" class="mt-8 no-underline inline-flex items-center justify-center px-5 py-2 <?php echo $bouton_group['gutenberg_btn_size'] ?> font-semibold <?php echo $bouton_group['gutenberg_btn_style'] ?> hover:opacity-80">
          <?php if (!empty($bouton_group['gutenberg_btn_icon'])) : ?>
            <i class="<?php echo $bouton_group['gutenberg_btn_icon'] ?> <?php if ($bouton_group['gutenberg_icon_position'] === 'icon_right') : ?> ml-2 order-2 <?php else : ?> mr-2 <?php endif; ?>"></i>
          <?php endif ?>
          <?php echo $bouton_group['gutenberg_btn_text'] ?>
        </a>
      <?php endif ?>
    </div>
  </div>
</div>
