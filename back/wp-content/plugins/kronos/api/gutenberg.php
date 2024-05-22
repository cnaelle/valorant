<?php

//! Remove H1 from Gutenberg heading title block
function remove_h1_gutenberg_editor()
{
    echo '<style>
	#editor .block-library-heading-level-toolbar .components-toolbar-group button:first-child {
		width: 3px;
		min-width: auto;
		padding: 3px 0;
		pointer-events: none;
		visibility: hidden;
	}
	</style>';
}
add_action('admin_head', 'remove_h1_gutenberg_editor');

//! Disable Gutenberg welcome guide
function disable_gutenberg_tips()
{
    $script = "
	window.onload = function(){
		wp.data && wp.data.select( 'core/edit-post' ).isFeatureActive( 'welcomeGuide' ) && wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'welcomeGuide' );
};
	";
    wp_add_inline_script('wp-blocks', $script);
}
add_action('enqueue_block_editor_assets', 'disable_gutenberg_tips');
