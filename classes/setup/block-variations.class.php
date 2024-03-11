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

			// Modify editor query.
			add_filter( 'rest_events_query', array( $this, 'modify_query_before_editor_block_render' ), 10, 2 );
		}
	}


	/**
	 * Register a block variation script.
	 * 
	 * Must be called before `admin_enqueue_scripts` hook.
	 */
	function register_block_variation_script() {
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
	function modify_query_before_frontend_block_render( $pre_render, $parsed_block ) {

		// Verify it's the block that should be modified using the namespace.
		if ( !empty( $parsed_block['attrs']['namespace'] ) && $this->current_name === $parsed_block['attrs']['namespace'] ) {
			add_filter(
				'query_loop_block_query_vars',
				function( $query, $block ) {


error_log( 'query_loop_block_query_vars before: ' . serialize( $query ) );



					// Modify the frontend query props here.
					$query['meta_key'] = '_bigup_service_order';
					$query['orderby']  = 'meta_value_num';
					$query['order']    = 'ASC'; // ASC || DESC.


error_log( 'query_loop_block_query_vars after: ' . serialize( $query ) );


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
	function modify_query_before_editor_block_render( $args, $request ) {


error_log( 'rest_events_query before: ' . serialize( $args ) );

		$sortByOrder = $request['sortByOrder'];
		if ( $sortByOrder ) {
		
			// Modify the editor query props here.
			$args['meta_key'] = '_bigup_service_order';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'ASC'; // ASC || DESC.
		}


error_log( 'rest_events_query after: ' . serialize( $args ) );

		return $args;
	}
}
