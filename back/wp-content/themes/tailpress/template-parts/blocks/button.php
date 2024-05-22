<?php

/**
 * Button Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'button-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'button';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$btn_text = get_field('gutenberg_btn_text') ?: '';
$btn_link = get_field('gutenberg_btn_link') ?: '';
$target = get_field('gutenberg_target') ?: '';
$btn_size = get_field('gutenberg_btn_size') ?: '';
$btn_style = get_field('gutenberg_btn_style') ?: '';
$btn_color = get_field('gutenberg_btn_color') ?: '';
$btn_text_color = get_field('gutenberg_btn_text_color') ?: '';
$btn_icon = get_field('gutenberg_btn_icon') ?: '';
$icon_position = get_field('gutenberg_icon_position') ?: '';

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

  <a href="<?php echo $btn_link ?>" <?php if (!empty($target)) : ?> target="_blank" <?php endif; ?> class="no-underline inline-flex items-center px-4 py-2 <?php echo $btn_size ?> font-medium 
  <?php echo $btn_style ?> hover:opacity-80 mb-2" style="transition: all 0.3s ease-in-out; background-color: <?php echo $btn_color ?> ; color: <?php echo $btn_text_color ?>">
    <?php if (!empty($btn_icon)) : ?>
      <i class="<?php echo $btn_icon ?> <?php if ($icon_position === 'icon_right') : ?> ml-2 order-2 <?php else : ?> mr-2 <?php endif; ?>"></i>
    <?php endif ?>
    <?php echo $btn_text ?>
  </a>

</div>
