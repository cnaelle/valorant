<?php

use Illuminate\Http\Request;
use WP_Rest_Yoast_Meta_Plugin\Frontend;

//? Cache WooCommerce endpoints
function wprc_add_woocommerce_endpoint($allowed_endpoints)
{
    if (!isset($allowed_endpoints['wc/v3']) || !in_array('products', $allowed_endpoints['wc/v3'])) {
        $allowed_endpoints['wc/v3'][] = 'products';
    }
    return $allowed_endpoints;
}
add_filter('wp_rest_cache/allowed_endpoints', 'wprc_add_woocommerce_endpoint', 10, 1);

//? Supports shipping method settings if available
add_filter('cocart_available_shipping_methods', 'kronos_cocart_available_shipping_methods', 90, 4);
function kronos_cocart_available_shipping_methods($available_methods, $chosen_shipping_methods, $rates, $recurring_cart_key)
{
    foreach ($available_methods as &$method) {
        $method_key = "woocommerce_" . str_replace(':', '_', $method['key']) . "_settings";
        $settings = get_option($method_key);
        if ($settings) {
            $method['settings'] = $settings;
        }
    }
    return $available_methods;
}

//? Check WooCommerce permissions depending on API endpoint and context
add_filter('woocommerce_rest_check_permissions', 'kronos_woocommerce_rest_check_permissions', 90, 4);
function kronos_woocommerce_rest_check_permissions($permission, $context, $object_id, $post_type)
{
    global $wp;
    $request = $_REQUEST;

    /*
        Allows some WooCoomerce API calls for guest customer
    */

    // List products
    if ($wp->request == 'wp-json/wc/v3/products' && $context == 'read') {
        return true;
    }
    // List product categories
    if ($wp->request == 'wp-json/wc/v3/products/categories' && $context == 'read') {
        return true;
    }
    // List customer orders (if logged in)
    else if ($wp->request == 'wp-json/wc/v3/orders' && $request->has('customer') && $request->customer == wp_get_current_user()->ID) {
        return true;
    }
    // List customer orders (bypass errors)
    else if ($wp->request == 'wp-json/wc/v3/orders' && $request->has('customer') && $context == 'read') {
        return true;
    }
    // Create a quote
    else if ($wp->request == 'wp-json/wc/v3/orders' && $context == 'create' && $request->has('status') && $request->status == 'quote') {
        return true;
    }
    // Read reports
    else if ($context == 'read' && $post_type == 'reports') {
        return true;
    }
    // Create a customer note
    else if (fnmatch('wp-json/wc/v3/orders/*/notes', $wp->request) && $request->cookies->has('woocommerce_cart_hash') && $context == 'create' && $post_type == 'shop_order' && $request->has('customer_note') && $request->customer_note) {
        return true;
    }
    // Edit current WooCommerce customer
    else if (fnmatch('wp-json/wc/v3/customers/' . wp_get_current_user()->ID, $wp->request) && $context == 'create' || $context == 'edit' || $context == 'delete' && $post_type == 'user') {
        return true;
    }
    // Create an order if user logged in
    else if (wp_get_current_user() && $wp->request == 'wp-json/wc/v3/orders' && $context == 'create') {
        return true;
    }
}

//? Register shipped status
add_action('init', 'wdm_register_shipped_order_status');
function wdm_register_shipped_order_status()
{
    register_post_status('wc-shipped', array(
        'label' => _x('Shipped', 'wdm'),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>')
    ));
}
/* Add Order action to Order action meta box */
add_action('woocommerce_order_actions', 'wdm_add_shipped_meta_box_actions');
function wdm_add_shipped_meta_box_actions($actions)
{
    $actions['wdm_shipped'] = __('Shipped', 'wdm');
    return $actions;
}
add_filter('wc_order_statuses', 'add_shipped_to_order_statuses');
/* Adds new Order status - Shipped in Order statuses*/
function add_shipped_to_order_statuses($order_statuses)
{
    $new_order_statuses = array();
    // add new order status after Completed
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-completed' === $key) {
            $new_order_statuses['wc-shipped'] = __('Shipped', 'wdm');
        }
    }
    return $new_order_statuses;
}

//? Register quote status
add_action('init', 'wdm_register_quote_order_status');
function wdm_register_quote_order_status()
{
    register_post_status('wc-quote', array(
        'label' => _x('Devis', 'wdm'),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Devis <span class="count">(%s)</span>', 'Devis <span class="count">(%s)</span>')
    ));
}
/* Add Order action to Order action meta box */
add_action('woocommerce_order_actions', 'wdm_add_quote_meta_box_actions');
function wdm_add_quote_meta_box_actions($actions)
{
    $actions['wdm_quote'] = __('Devis', 'wdm');
    return $actions;
}
add_filter('wc_order_statuses', 'add_quote_to_order_statuses');
/* Adds new Order status - Shipped in Order statuses*/
function add_quote_to_order_statuses($order_statuses)
{
    $new_order_statuses = array();
    // add new order status after Completed
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-completed' === $key) {
            $new_order_statuses['wc-quote'] = __('Devis', 'wdm');
        }
    }
    return $new_order_statuses;
}

add_filter('rest_post_dispatch', 'improve_search_json_results', 999, 3);
function improve_search_json_results($response, $server, $request)
{
    if ($request && $request->get_route() == '/wc/v3/orders') {
        foreach ($response->data as $key => $item) {
            foreach ($item['line_items'] as $key2 => $data) {
                $permalink = get_permalink($data['product_id']);
                $response->data[$key]['line_items'][$key2]['route']['path'] = parse_url($permalink)['path'];

                $response->data[$key]['line_items'][$key2]['product_price'] = [
                    'ht' => number_format(wc_get_price_excluding_tax(wc_get_product($response->data[$key]['line_items'][$key2]['product_id'])), 2, ',', ''),
                    'ttc' => number_format(wc_get_price_including_tax(wc_get_product($response->data[$key]['line_items'][$key2]['product_id'])), 2, ',', ''),
                ];
            }
        }
    }

    if ($request && str_contains($request->get_route(), '/wc/v3/orders') && fnmatch('/wc/v3/orders/*', $request->get_route())) {
        foreach ($response->data['line_items'] as &$data) {
            $permalink = get_permalink($data['product_id']);
            $data['route']['path'] = parse_url($permalink)['path'];

            $data['product_price'] = [
                'ht' => number_format(wc_get_price_excluding_tax(wc_get_product($data['product_id'])), 2, ',', ''),
                'ttc' => number_format(wc_get_price_including_tax(wc_get_product($data['product_id'])), 2, ',', ''),
            ];
        }
    }

    if ($request && $request->get_route() == '/wp/v2/search') {
        // Loop on data to add route to json returned
        foreach ($response->data as &$data) {
            $current_post_permalink = get_permalink($data['id']);
            $data['title'] = html_entity_decode($data['title']);
            $data['route'] = [
                'link' => $current_post_permalink,
                'path' => parse_url($current_post_permalink)['path'],
            ];
        }
    }
    return $response;
}

add_filter('cocart_cart_items', 'improve_cocart_cart_items_rest_api_response', 15, 4);
function improve_cocart_cart_items_rest_api_response($items, $item_key, $cart_item, $_product)
{
    $permalink = get_permalink($_product->id);
    $items[$item_key]['product_price'] = [
        'ht' => number_format(wc_get_price_excluding_tax($_product), 2, ',', ''),
        'ttc' => number_format(wc_get_price_including_tax($_product), 2, ',', ''),
    ];

    $items[$item_key]['route']['link'] = $permalink;
    $items[$item_key]['route']['path'] = parse_url($permalink)['path'];

    return $items;
}

add_filter('woocommerce_rest_prepare_product_cat', 'improve_woocommerce_product_categories_rest_api_response', 10, 3);
function improve_woocommerce_product_categories_rest_api_response($response, $item, $request)
{
    $permalink = get_term_link($response->data['id'], 'product_cat');
    $response->data['route']['link'] = $permalink;
    $response->data['route']['path'] = parse_url($permalink)['path'];
    return $response;
}

add_filter('woocommerce_rest_prepare_shop_order_object', 'improve_woocommerce_order_rest_api_response', 10, 3);
function improve_woocommerce_order_rest_api_response($response, $order, $request)
{
    $response->data['order_notes'] = wc_get_order($order->id)->get_customer_order_notes();
    $response->data['subtotal'] = wc_format_decimal(wc_get_order($order->id)->get_subtotal(), 2);
    return $response;
}

add_filter('woocommerce_rest_prepare_product_object', 'improve_wc_rest_api_product_response', 10, 3);
function improve_wc_rest_api_product_response($response, $product)
{
    $response->data['discount_percentage'] = $response->data['regular_price'] && $response->data['sale_price']
        ? '-' . strval(round(intval($response->data['regular_price']) / (intval($response->data['regular_price']) - intval($response->data['sale_price'])))) . '%'
        : null;

    $response->data['route']['link'] = $response->data['permalink'];
    $response->data['route']['path'] = parse_url($response->data['permalink'])['path'];

    $response->data['image']['sizes'] = [
        'thumbnail' => wp_get_attachment_image_url($response->data['images'][0]['id'], 'thumbnail'),
        'medium' => wp_get_attachment_image_url($response->data['images'][0]['id'], 'medium'),
        'large' => wp_get_attachment_image_url($response->data['images'][0]['id'], 'large'),
        'full_size' => wp_get_attachment_image_url($response->data['images'][0]['id'], 'full_size'),
    ];
    return $response;
}

add_filter('woocommerce_rest_prepare_product_object', 'custom_change_product_response', 20, 3);
add_filter('woocommerce_rest_prepare_product_variation_object', 'custom_change_product_response', 20, 3);

function custom_change_product_response($response, $object, $request)
{
    $variations = $response->data['variations'];
    $variations_res = array();
    $variations_array = array();

    $product_attributes = $object->get_attributes();

    $i = 0;
    foreach ($product_attributes as $key => $attribute) {
        $options = [];
        $terms = $attribute->get_terms();
        if ($terms && count($terms)) {
            foreach ($attribute->get_terms() as $term) {
                $options[] = [
                    'taxonomy_name' => get_taxonomy($term->taxonomy)->labels->singular_name,
                    'term_slug' => $term->taxonomy,
                    'label' => $term->name,
                    'value' => $term->slug,
                ];
            }
        }
        $response->data['attributes'][$i]['slug'] = $key;
        $response->data['attributes'][$i]['option_values'] = $options;
        $i++;
    }
    // If we are on a rest query on product and loading a single one, then process the breadcrumb
    if ($request->get_route() == '/wc/v3/products' && $request->get_param('slug')) {
        // Add path to breadcrumb items
        $listItems = [];

        $breadcrumbs = array_values(array_filter($response->data['yoast_head_json']['schema']['@graph'], function ($ar) {
            return ($ar['@type'] === 'BreadcrumbList');
        }))[0];

        $breadcrumbsKey = array_keys(array_filter($response->data['yoast_head_json']['schema']['@graph'], function ($ar) {
            return ($ar['@type'] === 'BreadcrumbList');
        }))[0];

        foreach ($breadcrumbs['itemListElement'] as $listItem) {
            $listItem['name'] = html_entity_decode($listItem['name']);
            $listItem['path'] = parse_url($listItem['item'])['path'];
            array_push($listItems, $listItem);
        }

        $response->data['breadcrumb'] = $listItems;
        $response->data['yoast_head_json']['schema']['@graph'][$breadcrumbsKey]['itemListElement'] = $listItems;
    }

    if (!empty($variations) && is_array($variations)) {
        foreach ($variations as $variation) {
            $variation_id = $variation;
            $variation = new WC_Product_Variation($variation_id);
            $variations_res['id'] = $variation_id;
            $variations_res['on_sale'] = $variation->is_on_sale();
            $variations_res['regular_price'] = (float)$variation->get_regular_price();
            $variations_res['sale_price'] = (float)$variation->get_sale_price();
            $variations_res['sku'] = $variation->get_sku();
            $variations_res['quantity'] = $variation->get_stock_quantity();
            if ($variations_res['quantity'] == null) {
                $variations_res['quantity'] = '';
            }
            $variations_res['stock'] = $variation->get_stock_quantity();
            $variations_res['route']['path'] = wc_get_permalink_structure()['product_base'] . "/$variation->sku";

            $attributes = array();
            // variation attributes
            foreach ($variation->get_variation_attributes() as $attribute_name => $attribute) {
                // taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`
                $attributes[] = [
                    'name'   => wc_attribute_label(str_replace('attribute_', '', $attribute_name), $variation),
                    'option' => $attribute,
                    'slug'   => str_replace('attribute_', '', wc_attribute_taxonomy_slug($attribute_name)),
                ];
            }
            sort($attributes);
            $variations_res['attributes'] = $attributes;
            $variations_array[] = $variations_res;
        }
    }
    $response->data['price_ht'] = wc_get_price_excluding_tax($object);
    $response->data['price_ttc'] = wc_get_price_including_tax($object);
    $response->data['product_variations'] = $variations_array;

    return $response;
}
/** Add filter variable to filter multi attributes taxonomy with key/value **/
add_filter('woocommerce_rest_product_object_query', function ($args, $request) {

    $params = $request->get_params();

    // Check if we have some custom query to do
    if (isset($params['filter'])) {

        // Initialize tax_query if not done yet
        if (!isset($args['tax_query'])) {
            $args['tax_query'] = ['relation' => 'AND'];
        }

        // Loop through all filters
        foreach ($params['filter'] as $attribute_name => $attribute_value) {
            // Explode the value, as there could be multiple slug for one taxonomy
            $term_slugs = explode(',', $attribute_value);
            $term_ids = [];
            foreach ($term_slugs as $term_slug) {
                $term = get_term_by('slug', $term_slug, $attribute_name, OBJECT);
                if ($term) {
                    $term_ids[] = $term->term_taxonomy_id;
                }
            }
            $args['tax_query'][] = [
                'taxonomy' => $attribute_name,
                'field' => 'term_id',
                'operator' => 'IN',
                'terms' => $term_ids,
            ];
        }
    }

    // Handle filtering by category slug
    if (isset($params['category_slug'])) {
        // Initialize tax_query if not done yet
        if (!isset($args['tax_query'])) {
            $args['tax_query'] = ['relation' => 'AND'];
        }

        $term_slugs = explode(',', $params['category_slug']);
        $term_ids = [];
        foreach ($term_slugs as $term_slug) {
            $term = get_term_by('slug', $term_slug, 'product_cat', OBJECT);
            if ($term) {
                $term_ids[] = $term->term_taxonomy_id;
            }
        }
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'operator' => 'IN',
            'terms' => $term_ids,
        ];
    }

    return $args;
}, 10, 2);

/** Expose all attribute taxonomy terms + prices **/
add_action('rest_api_init', function () {

    register_rest_route(
        'wp/v2',
        '/get-product-attribute-filters',
        array(
            'methods'  => 'GET',
            'callback' => 'get_product_attribute_filters'
        )
    );
});
function get_product_attribute_filters(WP_REST_Request $request)
{
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    $filters = [];

    if ($attribute_taxonomies) {
        foreach ($attribute_taxonomies as $tax) {
            if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                $filters[$tax->attribute_name] = get_terms(wc_attribute_taxonomy_name($tax->attribute_name), 'orderby=name&hide_empty=0');
            }
        }
    }

    $prices = get_filtered_price($request->get_param('term'));

    $filters['min_price'] = $prices['min'];
    $filters['max_price'] = $prices['max'];

    return $filters;
}

function get_filtered_price($term_slug = null)
{
    global $wpdb;

    $term_id = null;
    if ($term_slug) {
        $term = get_term_by('slug', $term_slug, 'product_cat');
        if ($term) {
            $term_id = $term->term_id;
        }
    }

    $sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM wp_posts ";
    $sql .= " LEFT JOIN wp_postmeta as price_meta ON wp_posts.ID = price_meta.post_id ";
    if ($term_id) {
        $sql .= " LEFT JOIN wp_term_relationships as terms_relation ON wp_posts.ID = terms_relation.object_id";
    }
    $sql .= " WHERE wp_posts.post_type IN ('product')
			AND wp_posts.post_status = 'publish'
			AND price_meta.meta_key IN ('_price')
			AND price_meta.meta_value > '' ";
    if ($term_id) {
        $sql .= "AND terms_relation.term_taxonomy_id = $term->term_id";
    }

    $prices = $wpdb->get_row($sql); // WPCS: unprepared SQL ok.

    return [
        'min' => floor($prices->min_price),
        'max' => ceil($prices->max_price),
    ];
}

// Ugly but not dumb... because it works
// By setting manually the global post ID we are fixing the breadcrumb generated by permalink manager
add_action('woocommerce_product_read', 'fix_breadcrumb', 10, 1);
function fix_breadcrumb($product_id)
{
    global $post, $wp;

    if (isset($wp->query_vars) && isset($wp->query_vars['rest_route']) && $wp->query_vars['rest_route'] == '/wc/v3/products' && isset($_GET['slug'])) {
        $post = new stdClass();
        $post->ID = $product_id;
    }
}
