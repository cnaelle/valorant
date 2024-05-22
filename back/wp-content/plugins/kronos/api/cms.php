<?php

// //? Remove HTML entities from Yoast Title & Meta Description
// Title
add_filter('wp_rest_yoast_meta/filter_yoast_title', 'rest_api_format_yoast_title', 10, 1);
function rest_api_format_yoast_title($yoast_title)
{
    return html_entity_decode($yoast_title, ENT_QUOTES);
}
// Meta desc
add_filter('wp_rest_yoast_meta/filter_yoast_meta', 'rest_api_format_yoast_meta', 10, 1);
function rest_api_format_yoast_meta($yoast_meta)
{
    $formatted_yoast_meta = [];
    foreach ($yoast_meta as &$meta) {
        if ($meta['property'] == 'og:title') {
            $meta['content'] = html_entity_decode($meta['content'], ENT_QUOTES);
        }
        array_push($formatted_yoast_meta, $meta);
    }
    return $yoast_meta;
}

//? Endpoint Any
add_action('rest_api_init', function () {
    $namespace = 'wp/v2';
    register_rest_route($namespace, '/any', array(
        'methods' => 'GET',
        'callback' => function ($params) {
            // On récupère tous les types de contenus
            $types = ['page', 'post'];
            foreach (get_custom_post_types() as $type) {
                array_push($types, $type['name']);
            }

            $slug = $params['slug'];

            if ($slug) {
                foreach ($types as $type) {
                    if (get_page_by_path($slug, OBJECT, $type)) {
                        if ($type == 'post' || $type == 'page') {
                            $type = $type . 's';
                        }

                        $request = new WP_REST_Request('GET', "/wp/v2/$type");
                        $request->set_query_params(['slug' => $slug]);
                        $response = rest_do_request($request);
                        $server = rest_get_server();
                        $data = $server->response_to_data($response, false);
                        return $data[0];
                    }
                }
            }
        },
    ));
});

//? Get all custom post types
function get_custom_post_types()
{
    return apply_filters('cptui_get_post_type_data', get_option('cptui_post_types', []), get_current_blog_id());
}

//? Get all custom taxonomies
function get_custom_taxonomies()
{
    return apply_filters('cptui_get_taxonomy_data', get_option('cptui_taxonomies', []), get_current_blog_id());
}

//? Improve all posts REST API responses
function improve_post_rest_api_response($response, $post, $request)
{
    global $post;

    clean_post_cache($post->ID);

    $params = $request->get_params();

    //* Get the previous & next post -- only for `post` type
    if ($post->post_type === 'post') {
        // Get the next post.
        $next = get_adjacent_post(false, '', false);

        //Get next post url and relative link
        $nextPostUrl = get_permalink(get_adjacent_post(false, '', false)->ID);
        $nextPostLink = wp_make_link_relative($nextPostUrl);

        // Get the previous post.
        $previous = get_adjacent_post(false, '', true);

        //Get previous post url and relative link
        $prevPostUrl = get_permalink(get_adjacent_post(false, '', true)->ID);
        $prevPostLink = wp_make_link_relative($prevPostUrl);

        // Only send id and slug (or null, if there is no next/previous post).
        $response->data['navigation']['next'] = (is_a($next, 'WP_Post')) ? array("id" => $next->ID, "slug" => $next->post_name, "path" => $nextPostLink) : null;
        $response->data['navigation']['previous'] = (is_a($previous, 'WP_Post')) ? array("id" => $previous->ID, "slug" => $previous->post_name, "path" => $prevPostLink) : null;
    }

    // Rework link & path
    $response->data['route']['link'] = $response->data['link'];
    $response->data['route']['path'] = parse_url($response->data['link'])['path'];

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
    $response->data['yoast_head_json']['title'] = html_entity_decode($response->data['yoast_head_json']['title'], ENT_QUOTES);
    $response->data['yoast_head_json']['og_title'] = html_entity_decode($response->data['yoast_head_json']['og_title'], ENT_QUOTES);
    $response->data['yoast_head_json']['og_site_name'] = html_entity_decode($response->data['yoast_head_json']['og_site_name'], ENT_QUOTES);

    // Return category object instead of id
    $taxonomies = [];
    foreach (get_taxonomies($post->id) as $key => $value) {
        $remove = ['nav_menu', 'link_category', 'post_format', 'wp_theme', 'wp_template_part_area', 'frm_tag', 'frm_application'];
        $taxonomy_terms = [];
        if (!in_array($key, $remove)) {
            $taxonomy = get_taxonomy($key);
            $taxonomy->rest_base ? $taxonomy->rest_base : $taxonomy->rest_base = $taxonomy->name;
            foreach ($response->data[$taxonomy->rest_base] as $term) {
                array_push($taxonomy_terms, get_term($term));
            }
            $taxonomies[$taxonomy->rest_base] = $taxonomy_terms;
        }
    }
    $response->data['taxonomies'] = $taxonomies;

    // Raw content
    $content = html_entity_decode($response->data['content']['rendered']);
    $raw_content = trim(preg_replace('/\s\s+/', ' ', strip_tags($content)));
    $response->data['raw_content'] = $raw_content;

    // Remove rendered for title, content & excerpt
    $response->data['title'] = html_entity_decode($response->data['title']['rendered']);
    $response->data['content']['rendered'] = html_entity_decode($response->data['content']['rendered']);

    // Excerpt length
    if ($params['excerpt_length']) {
        $excerpt_length = intval($params['excerpt_length'], 10);
    } else {
        $excerpt_length = 200;
    }
    $excerpt = strip_tags(html_entity_decode(get_the_excerpt($response->data['id'])));
    $response->data['excerpt'] = strlen($excerpt) >= $excerpt_length ? substr($excerpt, 0, $excerpt_length) . ' [...]' : $excerpt;

    // Add a thumbnail in root element
    if (has_post_thumbnail($post) && get_the_post_thumbnail_url($post)) {
        $response->data['image']['sizes']['thumbnail'] = has_post_thumbnail($post) && get_the_post_thumbnail_url($post) ? get_the_post_thumbnail_url($post) : wp_get_attachment_image_src(get_term_meta($response->data['id'], 'thumbnail_id', true), 'thumbnail')[0];
        $response->data['image']['sizes']['medium'] = has_post_thumbnail($post) && get_the_post_thumbnail_url($post, 'medium') ? get_the_post_thumbnail_url($post, 'medium') : wp_get_attachment_image_src(get_term_meta($response->data['id'], 'thumbnail_id', true), 'medium')[0];
        $response->data['image']['sizes']['large'] = has_post_thumbnail($post) && get_the_post_thumbnail_url($post, 'large') ? get_the_post_thumbnail_url($post, 'large') : wp_get_attachment_image_src(get_term_meta($response->data['id'], 'thumbnail_id', true), 'large')[0];
        $response->data['image']['sizes']['full_size'] = has_post_thumbnail($post) && get_the_post_thumbnail_url($post, 'full_size') ? get_the_post_thumbnail_url($post, 'full_size') : wp_get_attachment_image_src(get_term_meta($response->data['id'], 'thumbnail_id', true), 'full_size')[0];

        $response->data['image']['alt'] = get_post_meta(get_post_thumbnail_id($post), '_wp_attachment_image_alt', true);
    } else {
        $response->data['image'] = false;
    }

    if ($response->data['wpml_translations']) {
        foreach ($response->data['wpml_translations'] as &$translation) {
            $translation['lang'] = substr($translation['locale'], 0, 2);
            $translation['path'] = parse_url($translation['href'])['path'];
        }
    }

    $response->data['route']['link'] = $response->data['link'];
    $response->data['route']['path'] = parse_url($response->data['link'])['path'];

    return $response;
}
add_filter("rest_prepare_product_cat", 'improve_post_rest_api_response', 10, 3);
add_filter("rest_prepare_post", 'improve_post_rest_api_response', 10, 3);
add_filter("rest_prepare_page", 'improve_post_rest_api_response', 10, 3);
foreach (get_custom_post_types() as $postType) {
    add_filter("rest_prepare_" . $postType['name'], 'improve_post_rest_api_response', 10, 3);
}
foreach (get_custom_taxonomies() as $postType) {
    add_filter("rest_prepare_" . $postType['name'], 'improve_post_rest_api_response', 10, 3);
}

//? Improve category REST API responses
function improve_category_rest_api_response($response, $post, $request)
{
    //? Add route path in categories
    $response->data['path'] = parse_url($response->data['link'])['path'];

    $taxonomy = get_taxonomy($item->taxonomy)->object_type;
    $response->data['post_type'] = $taxonomy;

    //? Try to detect the parent page linked to this category
    $path = parse_url($response->data['link'])['path'];
    $paths = explode('/', $path);
    array_shift($paths);
    array_pop($paths);

    $linked_pages = get_posts(array(
        'post_name__in' => $paths,
        'post_type' => 'page',
        'post_status' => 'publish',
    ));

    foreach ($linked_pages as $page) {
        $page->path = parse_url(get_permalink($page->ID))['path'];
    }

    $response->data['linked_pages'] = $linked_pages;

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

    // If linked pages, append items to breadcrumb
    if (count($linked_pages) > 0) {
        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $items = [];
        foreach ($linked_pages as $key => $page) {
            array_push($items, [
                '@type' => 'ListItem',
                'position' => $key + 2,
                'item' =>  $base_url . $page->path,
                'name' => $page->post_title,
                'path' => $page->path,
            ]);
        }
        array_splice($listItems, 1, 0, $items);

        foreach ($listItems as $key => &$listItem) {
            $listItem['position'] = $key + 1;
        }
    }

    $response->data['breadcrumb'] = $listItems;
    $response->data['yoast_head_json']['schema']['@graph'][$breadcrumbsKey]['itemListElement'] = $listItems;

    return $response;
}
add_filter("rest_prepare_category", 'improve_category_rest_api_response', 10, 3);
foreach (get_custom_taxonomies() as $postType) {
    add_filter("rest_prepare_" . $postType['name'], 'improve_category_rest_api_response', 10, 3);
}

//? Get taxonomy by slug from REST API
function rest_filter_by_taxonomy_term_slug($args, $request)
{
    $params = $request->get_params();

    $filtered_params = array_filter($params, function ($key) {
        return strpos($key, '_term_slug') !== false;
    }, ARRAY_FILTER_USE_KEY);

    if ($filtered_params) {
        if (!isset($args['tax_query'])) {
            $args['tax_query'] = [];
        }
        // TODO: Faire les relations OR / AND pour chaque taxos
        foreach ($filtered_params as $key => $value) {
            $key = str_replace('_term_slug', '', $key);
            array_push($args['tax_query'], [
                // 'relation' =>  isset($params[$key . "_term_relation"]) ? strtoupper($params[$key . "_term_relation"]) : "AND",
                array(
                    'taxonomy' => $key,
                    'field'    => 'slug',
                    'terms'    => explode(",", $value),
                )
            ]);
        }
    }

    return $args;
}
add_filter('rest_post_query', 'rest_filter_by_taxonomy_term_slug', 10, 2);
add_filter('rest_page_query', 'rest_filter_by_taxonomy_term_slug', 10, 2);
foreach (get_custom_post_types() as $postType) {
    add_filter("rest_" . $postType['name'] . "_query", 'rest_filter_by_taxonomy_term_slug', 10, 2);
}
foreach (get_custom_taxonomies() as $postType) {
    add_filter("rest_" . $postType['name'] . "_query", 'rest_filter_by_taxonomy_term_slug', 10, 2);
}

//? Filter by ACF fields
function wp_rest_api_filter_by_acf_fields($args, $request)
{
    if (!isset($args['meta_query'])) {
        $args['meta_query'] = [
            'relation' => $request->get_param('relation') ? $request->get_param('relation') : 'AND',
        ];
    }
    if ($request->get_param('filters')) {
        if ($request->get_param('filters')[0] === '{') {
            $filters = (array)json_decode($request->get_param('filters'));
        } else {
            $filters = $request->get_param('filters');
        }
    }

    // $filters = $request->get_param('filters');
    $result = [];

    // Get all parameters from request
    foreach ($filters as $key => $value) {
        $exp_key = explode('_', $key);
        // Get all parameters starting with `acf_`
        if ($exp_key[0] == 'acf') {
            // Transforms boolean value to integer value
            if ($value->value === true) {
                $value->value = 1;
            } else if ($value->value === false) {
                $value->value = 0;
            }
            // Returns all params starting with `acf_`
            $result[str_replace('acf_', '', $key)] = [
                'value' => gettype($value) == 'object' ? $value->value : $value['value'],
                'operator' => gettype($value) == 'object' ? $value->operator : $value['operator'],
            ];
        }
    }
    $acf_fields = $result;

    // Filter ACF Fields
    if (!empty($acf_fields)) {
        foreach ($acf_fields as $key => $value) {
            if (gettype($value['value']) == 'array') {
                foreach ($value['value'] as &$val) {
                    $val = strval($val);
                }
                $value['value'] = serialize($value['value']);
            }
            array_push($args['meta_query'], array(
                'key'     => $key,
                'value'   => $value['value'],
                'compare' => $value['operator'],
            ));
        }
    }
    return $args;
}
add_filter('rest_post_query', 'wp_rest_api_filter_by_acf_fields', 10, 2);
add_filter('rest_page_query', 'wp_rest_api_filter_by_acf_fields', 10, 2);
foreach (get_custom_post_types() as $postType) {
    add_filter("rest_" . $postType['name'] . "_query", 'wp_rest_api_filter_by_acf_fields', 10, 2);
}
foreach (get_custom_taxonomies() as $postType) {
    add_filter("rest_" . $postType['name'] . "_query", 'wp_rest_api_filter_by_acf_fields', 10, 2);
}

//? Sort by ACF fields
function wp_rest_api_sort_by_acf_fields($args, $request)
{
    if ($request->get_param('orderby_acf')) {
        $acf_field = $request->get_param('orderby_acf');
        $order_acf = $request->get_param('order_acf') ? $request->get_param('order_acf') : 'DESC';
        array_push($args['meta_query'], array(
            'key'     => $acf_field,
            'orderby'   => 'meta_value',
            'order' => $order_acf,
        ));
    }
    return $args;
}
add_filter('rest_post_query', 'wp_rest_api_sort_by_acf_fields', 10, 2);
add_filter('rest_page_query', 'wp_rest_api_sort_by_acf_fields', 10, 2);
foreach (get_custom_post_types() as $postType) {
    add_filter("rest_" . $postType['name'] . "_query", 'wp_rest_api_sort_by_acf_fields', 10, 2);
}
foreach (get_custom_taxonomies() as $postType) {
    add_filter("rest_" . $postType['name'] . "_query", 'wp_rest_api_sort_by_acf_fields', 10, 2);
}

//? List all menus and items
add_action('rest_api_init', function () {

    register_rest_route(
        'wp/v2',
        '/menus/(?P<slug>[a-zA-Z0-9-]+)',
        array(
            'methods'  => 'GET',
            'callback' => 'get_menu',
            'permission_callback' => function () {
                return true;
            }
        )
    );
    register_rest_route(
        'wp/v2',
        '/menus',
        array(
            'methods'  => 'GET',
            'callback' => 'get_menus',
            'permission_callback' => function () {
                return true;
            }
        )
    );
});

function get_menu($data)
{
    $array_menu = wp_get_nav_menu_items($data['slug']);
    $menu = build_tree($array_menu);

    usort($menu, function ($first, $second) {
        return $first->order > $second->order;
    });

    foreach ($menu as &$item) {
        if ($item->children) {
            usort($item->children, function ($first, $second) {
                return $first->order > $second->order;
            });
        }
    }

    return $menu;
}

function get_menus($data) //TODO : handle params (ex: only get specific slugs)
{
    $menus = [];
    foreach (get_terms('nav_menu', array('hide_empty' => true)) as $menu) {
        $menus[$menu->slug] = get_menu(['slug' => $menu->slug]);
    }

    return $menus;
}

function build_tree(array $elements, $parentId = 0)
{
    $branch = array();

    foreach ($elements as &$element) {
        $element->title = html_entity_decode($element->title);
        $element->path = parse_url($element->url)['path'];
        $element->order = $element->menu_order;
        $element->acf = get_fields($element->ID);

        if ($element->menu_item_parent == $parentId) {
            $children = build_tree($elements, $element->ID);
            if ($children) {
                $element->children = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

//? Fetch user ACF fields
add_action('rest_api_init', function () {
    register_rest_route(
        'wp/v2',
        '/users/schema',
        array(
            'methods' => 'GET',
            'callback' => 'get_users_acf_schema',
            'permission_callback' => function () {
                return true;
            }
        )
    );
});
function get_users_acf_schema($request)
{
    $params = $request->get_params();
    $exclude = explode(',', $params['exclude']);

    $schema = [];
    foreach (acf_get_fields('group_user_acf_fields') as $field) {
        $schema[$field['name']] = $field;
    }

    // Remove keys based on exclude parameter
    foreach ($exclude as $key) {
        unset($schema[$key]);
    }

    return $schema;
}

//? Fetch homepage content
add_action('rest_api_init', function () {

    register_rest_route(
        'wp/v2',
        '/homepage',
        array(
            'methods'  => 'GET',
            'callback' => 'get_homepage',
            'permission_callback' => function () {
                return true;
            }
        )
    );
});
function get_homepage()
{
    $homepage_id = get_option('page_on_front');

    $homepage = get_post($homepage_id);
    $homepage->route = parse_url(get_permalink($homepage_id))['path'];
    $homepage->acf = get_fields($homepage_id);

    $meta_helper = YoastSEO()->classes->get(Yoast\WP\SEO\Surfaces\Meta_Surface::class);
    $meta = $meta_helper->for_post($homepage_id);
    $homepage->yoast_head_json = $meta->get_head()->json;

    return $homepage;
}

//? Add `AND` relation on rest category filter queries
add_action('pre_get_posts', 'improve_taxonomies_rest_api_relations');
function improve_taxonomies_rest_api_relations($query)
{
    if (!defined('REST_REQUEST') || !REST_REQUEST) {
        return;
    }

    if (!isset($_GET['and']) || !$_GET['and'] || 'false' === $_GET['and'] || !is_array($tax_query = $query->get('tax_query'))) {
        return;
    }

    foreach ($tax_query as $index => $tax) {
        $tax_query[$index]['operator'] = 'AND';
    }

    $query->set('tax_query', $tax_query);
}

// ? Returns 404 error instead of empty array when no slug found
add_action('rest_api_init', static function (): void {

    /**
     * Validate the REST request when querying by key: `slug`.
     * WordPress returns proper 404's when no ID is found, but not when querying by slug.
     * @param WP_REST_Response $response Result to send to the client. Usually a WP_REST_Response.
     * @param WP_REST_Server $server Server instance.
     * @param WP_REST_Request $request Request used to generate the response.
     * @return WP_REST_Response
     */
    add_filter('rest_post_dispatch', static function (
        WP_REST_Response $response,
        WP_REST_Server $server,
        WP_REST_Request $request
    ): WP_REST_Response {
        if (
            array_key_exists('slug', $request->get_query_params()) &&
            !empty($request->get_param('slug')) &&
            $request->get_method() === WP_REST_Server::READABLE &&
            empty($response->get_data())
        ) {
            $data = [
                'rest_post_invalid_slug',
                esc_html__('Invalid post slug.', 'thefrosty'),
                ['status' => WP_Http::NOT_FOUND],
            ];
            try {
                // Access the WP_REST_Server::error_to_response method.
                $error_to_response = (new ReflectionObject($server))->getMethod('error_to_response');
                $error_to_response->setAccessible(true);

                return $error_to_response->invoke($server, (new WP_Error(...$data)));
            } catch (ReflectionException $exception) {
                return new WP_REST_Response($data, WP_Http::NOT_FOUND);
            }
        }

        return $response;
    }, 10, 3);
});

//? Add route to make taxonomy trees, params are taxonomy (default:categories) and term (default:null)
add_action('rest_api_init', function () {

    register_rest_route(
        'wp/v2',
        '/get-taxonomy-tree',
        array(
            'methods'  => 'GET',
            'callback' => 'get_taxonomy_tree'
        )
    );
});

function get_taxonomy_tree(WP_REST_Request $request)
{
    $taxonomy = $request->get_param('taxonomy') ?: 'categories';
    $term_parameter = $request->get_param('term');
    $hide_empty = $request->get_param('hide_empty') ?: false;
    $term_id = null;
    if (!$term_parameter) {
        return new WP_Error('rest_no_term_param_error', 'The term parameter is required for this endpoint', array('status' => 400));
    }
    if ($term_parameter) {
        if (is_numeric($term_parameter)) {
            $term_id = $term_parameter;
        } else {
            $term = get_term_by('slug', $term_parameter, $taxonomy);
            if (!$term) {
                return new WP_Error('rest_wrong_term_param_error', "No term found with slug '$term_parameter'", array('status' => 400));
            }
            $term_id = $term->term_id;
        }
    }

    $categories = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => $hide_empty,
        'hierarchical' => true,
        'child_of' => $term_id,
        'fields' => 'all'
    ]);
    $categories[] = $term;

    $hierarchical_categories = [];
    sort_terms_hierarchically($categories, $hierarchical_categories);
    return $hierarchical_categories[0];
}

function sort_terms_hierarchically(array &$cats, array &$into, $parentId = 0)
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $media_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
            if ($media_id) {
                $cat->image = wp_get_attachment_url($media_id);
            }
            $into[] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        sort_terms_hierarchically($cats, $topCat->children, $topCat->term_id);
    }
}

//? Custom search endpoint: add thumbnail + return path instead of full URL
add_action('rest_api_init', function ($response) {
    register_rest_field('search-result', 'thumbnail', array(
        'get_callback' => function ($post_arr) {
            return parse_url(get_the_post_thumbnail_url($post_arr['id']))['path'];
        },
        'update_callback' => null,
        'schema' => null
    ));
    register_rest_field('search-result', 'url', array(
        'get_callback' => function ($post_arr) {
            return parse_url($post_arr['url'])['path'];
        },
        'update_callback' => null,
        'schema' => null
    ));
    register_rest_field('search-result', 'type_label', array(
        'get_callback' => function ($post_arr) {
            return get_post_type_object($post_arr['subtype'])->label;
        },
        'update_callback' => null,
        'schema' => null
    ));
});
