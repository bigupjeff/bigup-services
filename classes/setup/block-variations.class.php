<?php
namespace BigupWeb\Services;

/**
 * Register Gutenberg block variations.
 *
 * @package bigup-services
 */
class Block_Variations {

	/**
	 * Block variations root relative path.
	 *
	 * @var string
	 */
	const PATH = BIGUPSERVICE_PATH . 'build/block-variations/';

	/**
	 * Block variations root relative URL.
	 *
	 * @var string
	 */
	const URL = BIGUPSERVICE_URL . 'build/block-variations/';

	/**
	 * Block directory names.
	 * 
	 * @var array
	 */
	private array $names = array();

	/**
	 * Current variation name of `register_all` loop iteration.
	 */
	private string $current_name = '';

	/**
	 * Query props to implement sort by 'order' metafield.
	 */
	private array $custom_query = array(
		'meta_key' => '_bigup_service_order',
		'orderby'  => 'meta_value_num',
		'order'    => 'ASC',
	);


	/**
	 * Setup the class.
	 */
	public function __construct() {
		$dir_children = is_dir( self::PATH ) ? scandir( self::PATH ) : array();
		$this->names  = array_filter( preg_replace( '/\..*/', '', $dir_children ) );
	}


	/**
	 * Register all block variations.
	 */
	public function register_all() {
		if ( count( $this->names ) === 0 ) {
			error_log( 'Bigup Services ERROR: No child directories detected in block variations directory. Please check variations exist in {self::PATH}' );
			return;
		}
		foreach ( $this->names as $name ) {
			$this->current_name = $name;

			// Register block script.
			add_action( 'enqueue_block_editor_assets', array( $this, 'register_block_variation_script' ), 10, 0 );

			// Modify frontend query.
			add_filter( 'pre_render_block', array( $this, 'modify_query_before_frontend_block_render' ), 10, 2 );

			// Modify editor query - Note the dynamically generated hook which includes the CPT name.
			add_filter( 'rest_service_query', array( $this, 'modify_query_before_editor_block_render' ), 999, 2 );
		}
	}


	/**
	 * Register a block variation script.
	 * 
	 * Must be called before `admin_enqueue_scripts` hook.
	 */
	public function register_block_variation_script() {
		wp_enqueue_script(
			'bigup_' . preg_replace( '/-/', '_', $this->current_name ) . '_js',
			self::URL . $this->current_name . '/index.js',
			array( 'wp-blocks' ),
			filemtime( self::PATH . $this->current_name . '/index.js' ),
			true
		);
	}


	/**
	 * Modify the query before rendering the frontend block.
	 */
	public function modify_query_before_frontend_block_render( $pre_render, $parsed_block ) {
		// Identify the block that should be modified by matching the namespace.

		// TO FIX: This test is pointless as the 'query_loop_block_query_vars' seems to affect other queries anyway.
		if ( !empty( $parsed_block['attrs']['namespace'] ) && $this->current_name === $parsed_block['attrs']['namespace'] ) {

			add_filter(
				'query_loop_block_query_vars',
				function( $query, $block ) {

					// TEMP FIX: This is a hack to prevent non-service post queries being modified.
					// The problem is that hard-coding 'service' breaks the dynamic nature of this
					// code and also means service queries not being sorted by order will be broken. 
					if ( !empty( $query['post_type'] ) && $query['post_type'] === 'service' ) {
						$query = $this->merge_custom_query_args( $query );
					}

					// DEBUG
					error_log( serialize( $query ) );

					return $query;
				},
				10,
				2
			);
		}
		return $pre_render;
	}


	/**
	 * Modify the query before rendering the block in the editor.
	 */
	public function modify_query_before_editor_block_render( $args, $request ) {
		if ( $request['sortByOrder'] ) {
			$args = $this->merge_custom_query_args( $args );
		}
		return $args;
	}


	/**
	 * Merge the custom query with the default query args.
	 */
	private function merge_custom_query_args( $default_query ) {
		return array(
			...$default_query,
			...$this->custom_query
		);
	}
}
