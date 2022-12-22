<?php 
/**
 * Plugin Name: Lang Attribute for the Block Editor
 * Plugin URI: https://whodunit.fr/attribut-lang-pour-gutenberg-plugin
 * Description: Add lang attribute to the text formatting toolbar in the block editor.
 * Version: 0.3
 * Author: Guillaume Turpin, Jb Audras, Whodunit Agency
 * Author URI: https://whodunit.fr
 * text-domain: lang-attribute
 */

function lang_attr_enqueue_block_editor_assets() {
	wp_enqueue_script( 'lang-attribute-script',
		plugins_url( 'build/index.js', __FILE__ ),
		array( 'wp-blocks', 'wp-dom', 'wp-dom-ready', 'wp-edit-post', 'wp-element', 'wp-i18n', 'wp-block-editor' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
		true
	);
	wp_set_script_translations( 'lang-attribute-script', 'lang-attribute' );

	wp_enqueue_style( 'lang-attribute-style',
		plugins_url( 'build/index.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.css' )
	);
}
add_action( 'enqueue_block_editor_assets', 'lang_attr_enqueue_block_editor_assets' );

function lang_attribute_set_script_translations() {
	wp_set_script_translations( 'lang-attribute', 'lang-attribute' );
}
add_action( 'init', 'lang_attribute_set_script_translations' );
