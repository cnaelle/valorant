<?php

/**
 * Social Icons Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'social-icons-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'social-icons';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$icons = get_field('gutenberg_icons') ?: '';

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

  <?php if (!empty($icons)) : ?>
    <?php foreach ($icons as $icon) : ?>
      <a href="<?php echo $icon['url'] ?>" target="_blank" class="no-underline inline-flex items-center px-2 py-2 text-3xl hover:opacity-80" style="transition: all 0.3s ease-in-out; color: <?php echo $icon['color'] ?>">
        <i class="<?php echo $icon['icon'] ?>"></i>
      </a>
    <?php endforeach; ?>

  <?php else : ?>
    Ajoutez votre première icône
  <?php endif; ?>



</div>
