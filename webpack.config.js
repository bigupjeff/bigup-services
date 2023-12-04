const path = require( 'path' )

// Import the @wordpress/scripts config.
const wordpressConfig = require( '@wordpress/scripts/config/webpack.config' )

// Import the utility to auto-generate the entry points in the src directory.
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' )

module.exports = {
	// Spread the existing WordPress config.
	...wordpressConfig,

	entry: {
		// Spread the auto-generated entrypoints.
		...getWebpackEntryPoints(),
		'css/bigup-cpt-service-classic-editor': path.resolve( process.cwd(), 'src', 'css/bigup-cpt-service-classic-editor.scss' ),
		'js/bigup-cpt-service-gutenberg': path.resolve( process.cwd(), 'src', 'js/bigup-cpt-service-gutenberg.js' ),
		'js/bigup-cpt-service-classic-editor': path.resolve( process.cwd(), 'src', 'js/bigup-cpt-service-classic-editor.js' ),
		// Import external-svg-loader to use enqueue with PHP.
		'js/bigup-external-svg-loader': path.resolve( process.cwd(), 'src', 'js/bigup-external-svg-loader.js' ),
	},
}
