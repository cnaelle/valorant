<?php

header("Access-Control-Allow-Origin: *");

//? Add CORS to REST API endpoints
function add_cors_http_header()
{
    header("Access-Control-Allow-Origin: *");
}
add_action('rest_api_init', 'add_cors_http_header');

//? Force WP REST API to `/wp/wp-json` prefix
add_filter('rest_url_prefix', 'custom_api_url_prefix');
function custom_api_url_prefix($slug)
{
    return $slug;
}
