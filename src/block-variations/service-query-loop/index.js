import { __ } from '@wordpress/i18n'
import { registerBlockCollection, registerBlockVariation } from '@wordpress/blocks'
import {
	Logo,
	Icon
} from './svg'
import { addFilter } from '@wordpress/hooks'
import { withServiceQueryControls } from '@wordpress/block-editor'
import metadata from './block-variation.json'


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


/**
 * Register the block variation.
 * 
 * Provides a Query Loop block variation for 'service' posts.
 * 
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/extending-the-query-loop-block
 */
registerBlockVariation( 'core/query', {
	name: metadata.name,
	title: __( 'Service Query Loop', 'bigup-services' ),
	description: __( 'Display a list of services', 'bigup-services' ),
	icon: Icon,
	attributes: {
		namespace: metadata.name,
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


/**
 * Hook into the block editor to add custom controls.
 */
addFilter( 'editor.BlockEdit', 'core/query', withServiceQueryControls )
