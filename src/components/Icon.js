import PropTypes from 'prop-types'
// import 'external-svg-loader'


const Icon = ( { url, width, height } ) => {

	const isSVG = ( url ) => {
		return /\.svg$/.test( url )
	}

	// SVG image.
	if ( isSVG( url ) ) {
		// Get the SVG using external-svg-loader.
		return (
			<span>
				<svg
					data-src={ url }
					width={ width }
					height={ height }
					data-loading="lazy"

					data-cache="disabled"
				/>
			</span>
		)

	// Non-SVG image.
	} else {
		return(
			<figure>
				<img
					src={ url }
					width={ width }
					height={ height }
				/>
			</figure>
		)
	}
}

export { Icon }

Icon.propTypes = {
	url: PropTypes.string,
	width: PropTypes.number,
	height: PropTypes.number,
}
