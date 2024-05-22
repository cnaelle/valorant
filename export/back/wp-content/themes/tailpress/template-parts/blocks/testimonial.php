<?php

/**
 * Testimonial Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'testimonial-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'testimonial';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$quote = get_field('gutenberg_quote') ?: 'Votre citation ici';
$author = get_field('gutenberg_author') ?: 'Nom de l\'auteur';
$role = get_field('gutenberg_role') ?: 'Rôle de l\'auteur';
$image = get_field('gutenberg_image') ?: 295;

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
  <section class="overflow-hidden">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:py-20 pt-20 pb-12">

      <div class="lg:flex lg:items-center relative">
        <div class="hidden lg:block lg:flex-shrink-0 <?php if (!isset($image['url'])) : ?> md:w-2/12 <?php endif; ?>">
          <?php if (isset($image['url'])) : ?>
            <img class="h-60 w-60 rounded-full object-cover" src="<?php echo $image['url']; ?>" alt="<?php echo $author; ?>">
          <?php endif; ?>
        </div>

        <div class="lg:ml-10 relative">
          <svg class="absolute top-0 left-0 transform -translate-x-8 -translate-y-24 h-36 w-36 text-gray-200 opacity-50" stroke="currentColor" fill="none" viewBox="0 0 144 144" aria-hidden="true">
            <path stroke-width="2" d="M41.485 15C17.753 31.753 1 59.208 1 89.455c0 24.664 14.891 39.09 32.109 39.09 16.287 0 28.386-13.03 28.386-28.387 0-15.356-10.703-26.524-24.663-26.524-2.792 0-6.515.465-7.446.93 2.327-15.821 17.218-34.435 32.11-43.742L41.485 15zm80.04 0c-23.268 16.753-40.02 44.208-40.02 74.455 0 24.664 14.891 39.09 32.109 39.09 15.822 0 28.386-13.03 28.386-28.387 0-15.356-11.168-26.524-25.129-26.524-2.792 0-6.049.465-6.98.93 2.327-15.821 16.753-34.435 31.644-43.742L121.525 15z" />
          </svg>
          <blockquote class="relative">
            <div class="text-xl md:text-2xl md:leading-9 font-medium text-gray-900">
              <?php echo $quote; ?>
            </div>
            <footer class="mt-8">
              <div class="flex">
                <div class="lg:hidden flex-shrink-0">
                  <?php if (isset($image['url'])) : ?>
                    <img class="h-14 w-14 rounded-full object-cover" src="<?php echo $image['url']; ?>" alt="<?php echo $author; ?>">
                  <?php endif; ?>

                </div>
                <div class="ml-4 lg:ml-0">
                  <div class="text-xl font-medium text-gray-900"><?php echo $author; ?></div>
                  <div class="text-base font-medium text-branding-secondary"><?php echo $role; ?></div>
                </div>
              </div>
            </footer>
          </blockquote>
        </div>
      </div>
    </div>
  </section>
</div>
