/**
 * A Query Loop Variation for 'service' posts.
 * 
 * It will hide some controls such as post type selection, and implement new controls that allow for
 * sorting by metafields.
 * 
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/extending-the-query-loop-block
 */

import { __ } from '@wordpress/i18n'
import { registerBlockCollection, registerBlockVariation } from '@wordpress/blocks'
import {
	Logo,
	Icon
} from './svg'

/**
 * Register the collection.
 *
 * @see https://make.wordpress.org/core/2020/02/27/block-collections/
 */
registerBlockCollection(
	'bigupweb',
	{
		title: __( 'Bigup Web' ),
		icon: Logo
	}
)

const VARIATION_NAME = 'service-query-loop'

registerBlockVariation( 'core/query', {
	name: VARIATION_NAME,
	title: __( 'Service Query Loop', 'bigup-services' ),
	description: __( 'Display a list of services', 'bigup-services' ),
	icon: Icon,
	attributes: {
		namespace: VARIATION_NAME,
		// You can set query props here.
		query: {
			postType: 'service',
			order: 'asc',
			perPage: 12,
			orderByMetafield: true
		},
	},
	isActive: [ 'namespace' ],
	scope: [ 'inserter', 'transform' ], // 'transform' allows core query loop to be transformed to service query.
	allowedControls: [ 'order', 'taxQuery', 'search' ],
	innerBlocks: [
		[
			'core/post-template',
			{},
			[
				[ 'core/post-title' ]
			],
		]
	]
} )


/*
 * Add custom controls.
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/extending-the-query-loop-block/#adding-additional-controls
 * 
 * Good plugin example:
 * 
 * @see https://github.com/ryanwelcher/advanced-query-loop
 */

import { InspectorControls } from '@wordpress/block-editor'

export const withServiceQueryControls = ( BlockEdit ) => ( props ) => {

	const isServicesVariation = ( props ) => props.namespace === VARIATION_NAME
	
    return isServicesVariation( props ) ? (
        <>
            <BlockEdit key="edit" { ...props } />
            <InspectorControls>
				<p>{ 'TEST CONTROLS' }</p>
            </InspectorControls>
        </>
    ) : (
        <BlockEdit key="edit" { ...props } />
    )
}

addFilter( 'editor.BlockEdit', 'core/query', withServiceQueryControls )
