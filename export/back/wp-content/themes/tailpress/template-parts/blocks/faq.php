<?php

// Create id attribute allowing for custom "anchor" value.
$id = 'faq-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'faq';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$faq = get_field('gutenberg_faq') ?: '';
?>


<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
  <?php if (!empty($faq)) : ?>
    <?php foreach ($faq as $faq) : ?>

      <div class="mb-2">
        <div class="question font-medium rounded text-lg px-3 py-2 flex flex-nowrap justify-between items-center space-x-4 text-gray-800 mt-2 cursor-pointer text-black bg-gray-50 hover:bg-gray-100">
          <div class="flex-auto"><?php echo $faq['question'] ?></div>
          <div class="pr-2 mt-1">
            <div style="">
              <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down w-5 h-5">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </div>
          </div>
        </div>
        <div class="reponse p-3 mb-3" style="display: none;"><?php echo $faq['reponse'] ?></div>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <p>Ajoutez votre première question</p>
  <?php endif; ?>