import { __ } from '@wordpress/i18n'
import { registerBlockCollection, registerBlockVariation } from '@wordpress/blocks'
import {
	Logo,
	Icon
} from './svg'

/**
 * Register the collection.
 * 
 * COLLECTIONS ARE NOT CATEGORIES!
 * @link https://make.wordpress.org/core/2020/02/27/block-collections/
 */
registerBlockCollection(
	'bigupweb',
	{
		title: __( 'Bigup Web' ),
		icon: Logo
	}
)

const VARIATION_NAME = 'service-query-loop'

/**
 * Service Query Loop Variation.
 * 
 * This query loop block variation extends the core block by adding the ability to sort by custom
 * metafields of the custom post type.
 */
registerBlockVariation( 'core/query', {
	name: VARIATION_NAME,
	title: __( 'Service Query Loop', 'bigup-services' ),
	description: __( 'Displays a list of services', 'bigup-services' ),
	icon: Icon,
	attributes: {
		namespace: VARIATION_NAME,
		// You can override default query props here (see source for available props).
		query: {
			postType: 'service',
			order: 'asc',
			sortByOrder: true
		},
	},
	isActive: [ 'namespace' ],
	scope: [ 'inserter', 'transform' ],
	allowedControls: [
		'inherit',
		'order',
		'taxQuery',
		'author',
		'search'
	],
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
