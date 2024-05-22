<?php

function force_user_option_admin_color($color_scheme)
{
    $color_scheme = 'modern';
    return $color_scheme;
}
add_filter('get_user_option_admin_color', 'force_user_option_admin_color', 5);

function remove_profile_color_scheme()
{
    global $_wp_admin_css_colors;
    $_wp_admin_css_colors = 0;
}
add_action('admin_head', 'remove_profile_color_scheme');

add_action('admin_head', 'remove_h1_title_from_gutenberg');
function remove_h1_title_from_gutenberg()
{
	echo '
	<style>
		button.components-button.components-dropdown-menu__menu-item.is-icon-only.has-icon:first-child {
			display: none;
		}
	</style>';
}

//? Fix special chars in admin label menu items
add_filter( 'cptui_pre_register_post_type', function( $args, $post_type_slug, $post_type_array ) {
	// First we convert "&amp;" back to "&"
	$args['labels'] = array_map( 'htmlspecialchars_decode', $args['labels'] );
	// Then we convert "&#39;" back to "'"
	$args['labels'] = array_map( function( $value ) {
		return html_entity_decode( $value, ENT_QUOTES, 'utf-8' );
	}, $args['labels'] );
 	return $args;
}, 10, 3 );
