<?php

//! Force REST API format to `standard`
add_filter( 'acf/settings/rest_api_format', function () {
  return 'standard';
});

//! Link
add_filter('acf/format_value/type=link', function ($value, $post_id, $field) {
  $value['path'] = parse_url($value['url'])['path'];
  return $value;
}, 10, 5);
