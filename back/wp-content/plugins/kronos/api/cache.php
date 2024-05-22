<?php

// Cache WooCommerce endpoints
function wprc_add_woocommerce_endpoint($allowed_endpoints)
{
    if (!isset($allowed_endpoints['wc/v3']) || !in_array('products', $allowed_endpoints['wc/v3'])) {
        $allowed_endpoints['wc/v3'][] = 'products';
    }
    return $allowed_endpoints;
}
add_filter('wp_rest_cache/allowed_endpoints', 'wprc_add_woocommerce_endpoint', 10, 1);

// Cache custom Kronos endpoints
function wprc_add_kronos_allowed_endpoints($allowed_endpoints)
{
  
  // "get-product-attribute-filters" endpoint
  if (!isset($allowed_endpoints['wp/v2']) || !in_array('get-product-attribute-filters', $allowed_endpoints['wp/v2'])) {
    $allowed_endpoints['wp/v2'][] = 'get-product-attribute-filters';
  }
  //? "get-taxonomy-tree" endpoint
  if (!isset($allowed_endpoints['wp/v2']) || !in_array('get-taxonomy-tree', $allowed_endpoints['wp/v2'])) {
    $allowed_endpoints['wp/v2'][] = 'get-taxonomy-tree';
  }
  
  //? Formidable Forms endpoints
  $forms = FrmForm::getAll();
  foreach($forms as $form) {
    if (!isset($allowed_endpoints['frm/v2']) || !in_array("forms/$form->id/fields", $allowed_endpoints['frm/v2'])) {
      $allowed_endpoints['frm/v2'][] = "forms/$form->id/fields";
    }
  }

    return $allowed_endpoints;
}
add_filter('wp_rest_cache/allowed_endpoints', 'wprc_add_kronos_allowed_endpoints', 10, 1);