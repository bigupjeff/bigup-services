import { InspectorControls } from '@wordpress/block-editor'
import { CheckboxControl } from '@wordpress/components'
import metadata from './block-variation.json'


const sortByOrderCheckbox = ( { attributes, setAttributes } ) => {
	return (
		<CheckboxControl
			label={ __( 'Manual order', 'advanced-query-loop' ) }
			help={ __( 'Sort services by the configured order values', 'advanced-query-loop' ) }
			value={ attributes.orderByMetafield }
			onChange={ ( value ) => setAttributes( { orderByMetafield: value } ) }
		/>
	)
}


/**
 * Custom Service Query Block Variation Controls.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/extending-the-query-loop-block/#adding-additional-controls
 */
const serviceQueryControls = ( BlockEdit ) => ( props ) => {
	// If the is the correct variation, add the custom controls.
	if ( props.namespace === metadata.name ) {
		return (
			<>
				<BlockEdit { ...props } />
				<InspectorControls>
					<p>{ 'TEST CONTROLS' }</p>
					<sortByOrderCheckbox />
				</InspectorControls>
			</>
		)
	}
	return <BlockEdit { ...props } />
}

export { serviceQueryControls }
