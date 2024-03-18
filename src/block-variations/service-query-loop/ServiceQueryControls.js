import { InspectorControls } from '@wordpress/block-editor'
import { ToggleControl } from '@wordpress/components'
import metadata from './block-variation.json'


const sortByOrderCheckbox = ( { attributes, setAttributes } ) => {
	const { query: { orderByMetafield } = {} } = attributes
	return (
		<ToggleControl
			label={ __( 'Manual sort order', 'advanced-query-loop' ) }
			checked={ orderByMetafield }
			onChange={ () => {
				setAttributes( {
					query: {
						...attributes.query,
						orderByMetafield: orderByMetafield || false,
					},
				} )
			} }
		/>
	)
}


/**
 * Custom Service Query Block Variation Controls.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/extending-the-query-loop-block/#adding-additional-controls
 */
const withServiceQueryControls = ( BlockEdit ) => ( props ) => {
	return (
		<>
			<BlockEdit { ...props } />

			// If this is the correct variation, add the controls.

			<p>{ 'TEST CONTROLS' }</p>

			{ props.namespace === metadata.name &&
				<InspectorControls>
					<p>{ 'TEST CONTROLS' }</p>
					<sortByOrderCheckbox />
				</InspectorControls>
			}
		</>
	)
}

export { withServiceQueryControls }
