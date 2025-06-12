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

// See https://github.com/WordPress/gutenberg/pull/49985

function lang_attr_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'lang-attribute-script',
		plugins_url( 'build/index.js', __FILE__ ),
		array( 'wp-blocks', 'wp-dom', 'wp-dom-ready', 'wp-edit-post', 'wp-element', 'wp-i18n', 'wp-block-editor' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
		true
	);
	wp_set_script_translations( 'lang-attribute-script', 'lang-attribute' );

	wp_enqueue_style(
		'lang-attribute-style',
		plugins_url( 'build/index.css', __FILE__ ),
		array(),
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.css' )
	);
}
add_action( 'enqueue_block_editor_assets', 'lang_attr_enqueue_block_editor_assets' );

function lang_attribute_set_script_translations() {
	wp_set_script_translations( 'lang-attribute', 'lang-attribute' );
}
add_action( 'init', 'lang_attribute_set_script_translations' );



/**
 * Register block attributes for language settings
 */
function lang_attribute_register_block_attributes( $args, $block_type ) {
	$blocks_to_register = array( // Should be a global thing to use both on the front-end and the back-end.
		'core/group',
		'core/column',
	);
	if ( in_array( $block_type, $blocks_to_register, true ) ) {
		$args['attributes']['lang'] = array(
			'type'    => 'string',
			'default' => '',
		);
		$args['attributes']['dir']  = array(
			'type'    => 'string',
			'default' => 'ltr',
		);
	}
	return $args;
}
add_filter( 'register_block_type_args', 'lang_attribute_register_block_attributes', 10, 2 );


$blocks_to_register = array(
	'core/group',
	'core/column',
);
foreach ( $blocks_to_register as $block_name ) {
	add_filter(
		'render_block_' . $block_name,
		function ( $block_content, $block ) {
			if ( isset( $block['attrs']['lang'] ) && ! empty( $block['attrs']['lang'] ) ) {
				$lang          = esc_attr( $block['attrs']['lang'] );
				$dir           = isset( $block['attrs']['dir'] ) ? esc_attr( $block['attrs']['dir'] ) : 'ltr';
				$tag_processor = new WP_HTML_Tag_Processor( $block_content );
				$tag_processor->next_tag( 'div' );
				$tag_processor->set_attribute( 'lang', $lang );
				$tag_processor->set_attribute( 'dir', $dir );
				return $tag_processor->get_updated_html();
			}
			return $block_content;
		},
		10,
		2
	);
}
