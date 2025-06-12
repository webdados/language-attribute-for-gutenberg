import { __ } from '@wordpress/i18n';

import './index.scss';

import { BlockControls, RichTextToolbarButton, InspectorControls } from '@wordpress/block-editor';
import { TextControl, SelectControl, Button, Popover, PanelBody } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { registerFormatType, applyFormat, removeFormat, useAnchorRef } from '@wordpress/rich-text';
import { ENTER } from '@wordpress/keycodes';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';

const LangAttributeButton = ( props ) => {
	const { contentRef, isActive, onChange, value } = props;
	const anchorRef = useAnchorRef( { ref: contentRef, value } );
	
	const [ lang, setLang ] = useState( '' );
    const [ dir, setDir ] = useState( 'ltr' );

	const [ isPopoverVisible, setIsPopoverVisible ] = useState( false );
	const togglePopover = () => {
		setIsPopoverVisible( ( state ) => ! state );
		setLang( '' )
		setDir( 'ltr' )
	};

	return (
		<>
			<RichTextToolbarButton
				icon="translation"
				label={ __( 'Lang attribute', 'lang-attribute' ) }
				title={ __( 'Lang attribute', 'lang-attribute' ) }
				onClick={ () => {
					if ( isActive ) {
						onChange( removeFormat( value, 'lang-attribute/format-lang-attribute' ) );
					} else {
						togglePopover()
					}
				} }
				isActive={ isActive }
			/>

			{ isPopoverVisible && (
				<Popover
					className="components-lang-attribute-popover"
					anchor={ anchorRef }
					position="bottom center"
				>
					<TextControl
						label={ __( 'Lang attribute', 'lang-attribute' ) }
						value={ lang }
						onChange={ lang => setLang( lang ) }
					/>
					<p class="lang-attribute-info">
						<em>{ __( 'Should be a valid lang attribute, like "en" or "fr".', 'lang-attribute' ) }</em>
					</p>

					<SelectControl
						label={ __( 'Text direction', 'lang-attribute' ) }
						value={ dir }
						options={[
							{ label: __( 'Left to right', 'lang-attribute' ), value: 'ltr' },
							{ label: __( 'Right to left', 'lang-attribute' ), value: 'rtl' },
						]}
						onChange={ dir => setDir( dir ) }
					/>
					<Button
						isPrimary
						text={ __( 'Apply', 'lang-attribute' ) }
						onClick={ () => {
							onChange(
								applyFormat( value, {
									type: 'lang-attribute/format-lang-attribute',
									attributes: {
										lang: lang,
										dir: dir
									}
								} )
							)
							togglePopover()
						} }
					/>
				</Popover>
			) }
		</>
	)
};
		
// Register the Format.
registerFormatType( 'lang-attribute/format-lang-attribute', {
	className: 'lang-attribute',
	edit     : LangAttributeButton,
	tagName  : 'span',
	icon     : 'translation',
	title    : __( 'Lang attribute', 'lang-attribute' ),
} );


/* GROUP BLOCK THINGY */

// Add language attributes to Group block
const addLangAttributesToGroupBlock = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		// Only target the Group block (and its variations) as well as the Column block
		if ( props.name !== 'core/group' && props.name !== 'core/column' ) {
		  return <BlockEdit { ...props } />;
		}
	  
		const { attributes, setAttributes } = props;
		
		// Get existing lang and dir attributes or set default values
		const lang = attributes.lang || '';
		const dir = attributes.dir || 'ltr';
	  
		return (
			<>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody
						title={ __( 'Language Settings', 'lang-attribute' ) }
						initialOpen={ true }
					>
						<TextControl
							label={ __( 'Language Code', 'lang-attribute' ) }
							value={ lang }
							onChange={ ( value ) => setAttributes( { lang: value } ) }
							help={ __( 'Valid language code, like "en" or "fr".', 'lang-attribute' ) }
						/>
						<SelectControl
							label={ __( 'Text Direction', 'lang-attribute' ) }
							value={ dir }
							options={[
								{ label: __( 'Left to right', 'lang-attribute' ), value: 'ltr' },
								{ label: __( 'Right to left', 'lang-attribute' ), value: 'rtl' },
							]}
							onChange={ ( value ) => setAttributes( { dir: value } ) }
						/>
					</PanelBody>
				</InspectorControls>
			</>
		);
	};
}, 'addLangAttributesToGroupBlock' );

// Register the filters
addFilter(
	'editor.BlockEdit',
	'lang-attribute/add-lang-attributes-to-group-block',
	addLangAttributesToGroupBlock
);

function addListBlockClassName( settings, name ) {
	if ( name === 'core/group' || name === 'core/column' ) {
		// Add custom attributes for lang and dir
		settings.attributes = {
			...settings.attributes,
			lang: {
				type: 'string',
				default: '',
			},
			dir: {
				type: 'string',
				default: 'ltr',
			},
		};
	}
	return settings;

}

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'lang-attribute/add-list-block-class-name',
	addListBlockClassName
);