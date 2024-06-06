const path = require( 'path' )

// Import the @wordpress/scripts config.
const wordpressConfig = require( '@wordpress/scripts/config/webpack.config' )

module.exports = {
	// Spread the existing WordPress config.
	...wordpressConfig,

	entry: {
		// @wordpress/scripts helper which generates entry points from any '**/block.json' in 'src'.
		...wordpressConfig.entry(),
		'css/bigup-services-classic-editor': path.resolve( process.cwd(), 'src', 'css/bigup-services-classic-editor.scss' ),
		'js/bigup-services-gutenberg': path.resolve( process.cwd(), 'src', 'js/bigup-services-gutenberg.js' ),
		'js/bigup-services-classic-editor': path.resolve( process.cwd(), 'src', 'js/bigup-services-classic-editor.js' ),
		// Import external-svg-loader to use enqueue with PHP.
		'js/bigup-external-svg-loader': path.resolve( process.cwd(), 'src', 'js/bigup-external-svg-loader.js' ),
		// Block variations.
		'block-variations/service-query-loop/index': path.resolve( process.cwd(), 'src', 'block-variations/service-query-loop/index.js' ),
	},
}
