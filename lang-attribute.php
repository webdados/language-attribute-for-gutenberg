<?php 
/**
 * Plugin Name: Lang Attribute for the Block Editor
 * Plugin URI: https://www.yourwebsiteurl.com/
 * Description: Add lang attribute to the text formatting toolbar in the block editor.
 * Version: 1.0
 * Author: Guillaume Turpin
 * Author URI: https://github.com/gturpin-dev
 * text-domain: lang-attribute
**/

add_action( 'enqueue_block_editor_assets', function() {
	wp_enqueue_script( 'lang-attribute',
		plugins_url( 'build/index.js', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
		true
	);

	wp_enqueue_style( 'lang-attribute',
		plugins_url( 'build/index.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.css' )
	);
} );