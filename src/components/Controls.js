import { __ } from '@wordpress/i18n'
import PropTypes from 'prop-types'
import { TextControl } from '@wordpress/components'
import { PanelRow, Button, ResponsiveWrapper } from '@wordpress/components'
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor'


const AnyNumberControl = ( { data: {
	label,
	description,
	value,
	updateValue,
	placeholder,
	required,
	max,
	min,
	step
} } ) => {
	return(
		<PanelRow>
			<TextControl
				label={ label }
				help={ description }
				value={ value }
				onChange={ updateValue }
				type={ 'number' }
				inputmode={ 'numeric' }
				placeholder={ placeholder }
				required={ required }
				max={ max }
				min={ min }
				step={ step }
			/>
		</PanelRow>
	)
}


const ImageControl = ( { data: { label, value, updateValue, media } } ) => {
	return(
		<>
			<PanelRow>
				<MediaUploadCheck>
					<MediaUpload
						title={ label }
						onSelect={ ( newMedia ) => updateValue( newMedia.id ) }
						value={ value }
						allowedTypes={ [ 'image' ] }
						render={ ( { open } ) => (
							<Button 
								className={ ! value ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
								onClick={ open }
							>
								{ ! value && __( 'Select', 'bigup-reviews' ) + ' ' + label }
								{ media !== undefined &&
								<ResponsiveWrapper
									naturalWidth={ media.media_details.width }
									naturalHeight={ media.media_details.height }
								>
									<img src={ media.source_url } />
								</ResponsiveWrapper>
							}
							</Button>
						) }
					/>
				</MediaUploadCheck>
			</PanelRow>

			{ value &&
				<PanelRow>
					<MediaUploadCheck>
						<MediaUpload
							title={ label }
							value={ value }
							onSelect={ ( newMedia ) => updateValue( newMedia.id ) }
							allowedTypes={ [ 'image' ] }
							render={ ( { open } ) => (
								<Button
									onClick={ open }
									variant="secondary" 
									isLarge
								>
									{ __( 'Replace', 'bigup-reviews' ) + ' ' + label }
								</Button>
							) }
						/>
					</MediaUploadCheck>
					<MediaUploadCheck>
						<Button
							onClick={ () => updateValue( 0 ) }
							variant="secondary" 
							isLarge
						>
							{ __( 'Remove', 'bigup-reviews' ) + ' ' + label }
						</Button>
					</MediaUploadCheck>
				</PanelRow>
			}
		</>
	)
}


AnyNumberControl.propTypes = {
	data: PropTypes.shape( {
		label: PropTypes.string.isRequired,
		description: PropTypes.string.isRequired,
		value: PropTypes.string.isRequired,
		updateValue: PropTypes.func.isRequired,
		placeholder: PropTypes.string.isRequired,
		required: PropTypes.bool.isRequired,
		max: PropTypes.number.isRequired,
		min: PropTypes.number.isRequired,
		step: PropTypes.number.isRequired
	} )
}

ImageControl.propTypes = {
	data: PropTypes.shape( {
		label: PropTypes.string.isRequired,
		value: PropTypes.number.isRequired,
		updateValue: PropTypes.func.isRequired,
		required: PropTypes.bool.isRequired
	} )
}

export {
	AnyNumberControl,
	ImageControl
}
