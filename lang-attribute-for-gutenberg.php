<?php 
/**
 * Plugin Name: Lang Attribute for Gutenberg
 * Plugin URI: https://www.yourwebsiteurl.com/
 * Description: Add language attribute format tool to the block editor.
 * Version: 1.0
 * Author: Guillaume Turpin
 * Author URI: https://github.com/gturpin-dev
 * text-domain: lang-attribute-for-gutenberg
**/

add_action( 'enqueue_block_editor_assets', function() {
	wp_enqueue_script( 'language-attribute-for-gutenberg',
		plugins_url( 'build/index.js', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
		true
	);

	wp_enqueue_style( 'language-attribute-for-gutenberg',
		plugins_url( 'build/index.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.css' )
	);
} );