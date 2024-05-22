<?php

// Include extend-wordPress folder
if (file_exists(dirname(__DIR__, 3) . '/extend-wordpress/index.php')) {
	require_once(dirname(__DIR__, 3) . '/extend-wordpress/index.php');
}

add_action('acf/init', 'acf_init_block_types');
function acf_init_block_types()
{
	if (function_exists('acf_register_block_type')) {
		//* Media + text
		acf_register_block_type(array(
			'name'              => 'media-text',
			'title'             => __('Média & texte'),
			'description'       => __('Mettre un média et du texte côte-à-côte pour une mise en page plus riche'),
			'render_template'   => 'template-parts/blocks/media-text.php',
			'category'          => 'formatting',
			'icon'              => '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M3 18h8V6H3v12zM14 7.5V9h7V7.5h-7zm0 5.3h7v-1.5h-7v1.5zm0 3.7h7V15h-7v1.5z"></path></svg>',
			'keywords'          => array('media', 'text'),
		));

		//* Citation
		acf_register_block_type(array(
			'name'              => 'citation',
			'title'             => __('Citation'),
			'description'       => __('Donnez une emphase visuelle à vos citations'),
			'render_template'   => 'template-parts/blocks/testimonial.php',
			'category'          => 'formatting',
			'icon'              => 'admin-comments',
			'keywords'          => array('testimonial', 'quote'),
		));

		//* Button
		acf_register_block_type(array(
			'name'              => 'button',
			'title'             => __('Bouton'),
			'description'       => __('Inviter les utilisateurs à passer à l\'action avec des liens ayant la forme de boutons.'),
			'render_template'   => 'template-parts/blocks/button.php',
			'category'          => 'formatting',
			'icon'              => '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path d="M17 3H7c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm.5 6c0 .3-.2.5-.5.5H7c-.3 0-.5-.2-.5-.5V5c0-.3.2-.5.5-.5h10c.3 0 .5.2.5.5v4zm-8-1.2h5V6.2h-5v1.6zM17 13H7c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zm.5 6c0 .3-.2.5-.5.5H7c-.3 0-.5-.2-.5-.5v-4c0-.3.2-.5.5-.5h10c.3 0 .5.2.5.5v4zm-8-1.2h5v-1.5h-5v1.5z"></path></svg>',
			'keywords'          => array('buttons', 'link'),
		));

		//* FAQ
		acf_register_block_type(array(
			'name'              => 'faq',
			'title'             => __('FAQ'),
			'description'       => __('FAQ'),
			'render_template'   => 'template-parts/blocks/faq.php',
			'category'          => 'formatting',
			'icon'              => 'editor-ul',
			'keywords'          => array('faq', 'questions'),
		));

		//* Bannière (CTA)
		acf_register_block_type(array(
			'name'              => 'banner',
			'title'             => __('Bannière'),
			'description'       => __('Bannière avec CTA'),
			'render_template'   => 'template-parts/blocks/banner.php',
			'category'          => 'formatting',
			'icon'              => '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path d="M18 8H6c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zm.5 6c0 .3-.2.5-.5.5H6c-.3 0-.5-.2-.5-.5v-4c0-.3.2-.5.5-.5h12c.3 0 .5.2.5.5v4zM4 4v1.5h16V4H4zm0 16h16v-1.5H4V20z"></path></svg>',
			'keywords'          => array('banner', 'cta'),
		));

		//* Icônes de réseaux sociaux 
		acf_register_block_type(array(
			'name'              => 'social_icons',
			'title'             => __('Icônes de réseaux sociaux'),
			'description'       => __('Afficher des icônes pointant vers vos profils de réseaux sociaux ou vos sites'),
			'render_template'   => 'template-parts/blocks/social-icons.php',
			'category'          => 'formatting',
			'icon'              => 'share',
			'keywords'          => array('social', 'icons'),
		));

		//* Slider
		acf_register_block_type(array(
			'name'              => 'slider',
			'title'             => __('Slider d\'images'),
			'description'       => __('Affichez plusieur images dans un slider enrichi.'),
			'render_template'   => 'template-parts/blocks/slider.php',
			'category'          => 'media',
			'icon'              => 'slides',
			'keywords'          => array('slider', 'image'),
		));
	}
}

/**
 * Theme setup.
 */
function tailpress_setup()
{
	add_theme_support('title-tag');

	register_nav_menus(
		array(
			'primary' => __('Primary Menu', 'tailpress'),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	add_theme_support('custom-logo');
	add_theme_support('post-thumbnails');

	add_theme_support('align-wide');
	add_theme_support('wp-block-styles');

	add_filter('allowed_block_types', 'gutenberg_allowed_blocks');

	function gutenberg_allowed_blocks($allowed_blocks)
	{
		return array(
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/list-item',
			'core/table',
			'core/image',
			'core/gallery',
			'core/file',
			'core/gallery',
			'core/freeform',
			'core/video',
			'core/button',
			'core/columns',
			'core/separator',
			'core/spacer',
			'core/html',
			'core/embed',
			'core/shortcode',
			'acf/media-text',
			'acf/citation',
			'acf/button',
			'acf/faq',
			'acf/banner',
			'acf/social-icons',
			'acf/slider'
		);
	}

	add_theme_support('editor-styles');
	add_editor_style('css/editor-style.css');
}

add_action('after_setup_theme', 'tailpress_setup');

/**
 * Enqueue theme assets.
 */
function tailpress_enqueue_scripts()
{
	$theme = wp_get_theme();

	wp_enqueue_style('tailpress', tailpress_asset('css/app.css'), array(), $theme->get('Version'));
	wp_enqueue_script('tailpress', tailpress_asset('js/app.js'), array(), $theme->get('Version'));
}

add_action('wp_enqueue_scripts', 'tailpress_enqueue_scripts');

/**
 * Get asset path.
 *
 * @param string  $path Path to asset.
 *
 * @return string
 */
function tailpress_asset($path)
{
	if (wp_get_environment_type() === 'production') {
		return get_stylesheet_directory_uri() . '/' . $path;
	}

	return add_query_arg('time', time(),  get_stylesheet_directory_uri() . '/' . $path);
}

/**
 * Adds option 'li_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The curren item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_li_class($classes, $item, $args, $depth)
{
	if (isset($args->li_class)) {
		$classes[] = $args->li_class;
	}

	if (isset($args->{"li_class_$depth"})) {
		$classes[] = $args->{"li_class_$depth"};
	}

	return $classes;
}

add_filter('nav_menu_css_class', 'tailpress_nav_menu_add_li_class', 10, 4);

/**
 * Adds option 'submenu_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The curren item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_submenu_class($classes, $args, $depth)
{
	if (isset($args->submenu_class)) {
		$classes[] = $args->submenu_class;
	}

	if (isset($args->{"submenu_class_$depth"})) {
		$classes[] = $args->{"submenu_class_$depth"};
	}

	return $classes;
}

add_filter('nav_menu_submenu_css_class', 'tailpress_nav_menu_add_submenu_class', 10, 3);
