import { __ } from '@wordpress/i18n'
import { InspectorControls } from '@wordpress/block-editor'
import { ToggleControl, PanelBody, PanelRow } from '@wordpress/components'
import metadata from './block-variation.json'


const SortByOrderToggle = ( { attributes, setAttributes } ) => {
	const { query: { orderByMetafield } } = attributes
	return (
		<ToggleControl
			label={ __( 'Manual sort order', 'advanced-query-loop' ) }
			help={
				orderByMetafield === 'true'
					? __( 'Sort by the manually-configured order number', 'advanced-query-loop' )
					: __( 'Use default post sorting', 'advanced-query-loop' )
			}
			checked={ orderByMetafield === 'true' }
			onChange={ ( newValue ) => {
				setAttributes( {
					query: {
						...attributes.query,
						orderByMetafield: newValue ? 'true' : 'false',
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

			{ props.attributes.namespace === metadata.name &&
				<InspectorControls>
					<PanelBody 
						title={ __( 'Metafield Ordering', 'bigup-services' ) }
						initialOpen={true}
					>
						<PanelRow>
							<SortByOrderToggle { ...props }/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
			}
		</>
	)
}

export { withServiceQueryControls }
