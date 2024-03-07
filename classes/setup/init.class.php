<?php
namespace BigupWeb\Services;

/**
 * Initialise.
 *
 * @package bigup-services
 */
class Init {

	/**
	 * Relative path to the definition JSON file.
	 *
	 * @var string
	 */
	private $definition_path = 'data/service-definition.json';


	/**
	 * The definition array.
	 *
	 * @var array
	 */
	private $def;


	/**
	 * Populate the properties of this class.
	 */
	public function __construct() {
		$this->def = $this->get_definition();
	}


	/**
	 * Setup this plugin.
	 *
	 * Get and check definition, then call functions to register CPT and custom fields.
	 * All action hooks for this plugin should be registered here to manage sequence.
	 */
	public function setup() {

		if ( ! is_array( $this->def ) || ! array_key_exists( 'key', $this->def ) ) {
			error_log( 'BigupWeb\Services error: Could not retrieve post type definition' );
			return;
		}

		$Custom_Post_Type = new Custom_Post_Type( $this->def );
		add_action( 'init', array( &$Custom_Post_Type, 'register' ), 0, 1 );
		add_filter( 'allowed_block_types_all', array( &$Custom_Post_Type, 'allowed_block_types' ), 25, 2 );

		// Opt-in to allow WP to selectively load and inline front end block styles.
		// Commented while debugging - needs testing.
		// add_filter( 'should_load_separate_core_block_assets', '__return_true' );

		$Blocks = new Blocks( $this->def );
		add_action( 'init', array( &$Blocks, 'register_all' ), 10, 0 );

		if ( ! array_key_exists( 'customFields', $this->def ) ) {
			return;
		}

		// Setup classic editor metabox.
		$Metabox_Classic = new Metabox_Classic( $this->def );
		add_action( 'do_meta_boxes', array( &$Metabox_Classic, 'remove_default_meta_box' ), 10, 3 );
		add_action( 'add_meta_boxes', array( &$Metabox_Classic, 'add_custom_meta_box' ), 10, 0 );
		add_action( 'save_post', array( &$Metabox_Classic, 'save_custom_meta_box_data' ), 1, 2 );

		// Setup gutenberg metabox.
		$Metabox = new Metabox( $this->def );
		add_action( 'init', array( &$Metabox, 'setup_custom_fields' ), 11, 0 );

		// Setup post list custom columns. Note the hook names that include the target post type name.
		add_filter( 'manage_service_posts_columns', array( &$this, 'add_post_list_custom_columns' ), 10, 1 );
		add_action( 'manage_service_posts_custom_column', array( &$this, 'define_post_list_custom_columns_data' ), 10, 2 );
		add_filter( 'manage_edit-service_sortable_columns', array( &$this, 'make_post_list_custom_columns_sortable' ), 10, 1 );
		add_action( 'pre_get_posts', array( &$this, 'define_post_list_custom_columns_sorting' ), 10, 1 );

		// Register patterns.
		add_action( 'init', array( new Patterns(), 'register_all' ) );

		// Register scripts and styles.
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_frontend_scripts' ) );
		add_action( 'enqueue_block_editor_assets', array( &$this, 'enqueue_block_editor_scripts' ) );
		add_action( 'after_setup_theme', array( &$this, 'add_service_icon_image_size' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_init', array( &$this, 'add_classic_editor_styles' ) );

		// Enable WP custom fields even if ACF is installed.
		add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );
	}


	/**
	 * Add a custom image size for the service icons.
	 *
	 * WordPress will scale/crop images so they're ready for output in this size.
	 */
	public function add_service_icon_image_size() {
		add_image_size( 'bigup_service_icon', 100, 100, false );
	}


	/**
	 * Enqueue frontend scripts.
	 */
	public function enqueue_frontend_scripts() {
		// cannot get this to play ball.
		wp_enqueue_script( 'bigup_external_svg_loader_js', BIGUPSERVICE_URL . 'build/js/bigup-external-svg-loader.js', array(), filemtime( BIGUPSERVICE_PATH . 'build/js/bigup-external-svg-loader.js' ), true );
	}


	/**
	 * Enqueue media upload functionality.
	 *
	 * Initialise wp.media to handle the admin media upload/select modal.
	 */
	public function enqueue_admin_scripts() {
		if ( get_post_type() === $this->def['key'] ) {
			// Initialise wp.media to handle the admin media upload/select modal.
			wp_enqueue_media();
			wp_enqueue_script( 'bigup_Services_classic_js', BIGUPSERVICE_URL . 'build/js/bigup-services-classic-editor.js', array(), filemtime( BIGUPSERVICE_PATH . 'build/js/bigup-services-classic-editor.js' ), true );
		}
	}


	/**
	 * Enqueue gutenberg editor scripts.
	 */
	public function enqueue_block_editor_scripts() {
		wp_enqueue_script( 'bigup_services_gutenberg_js', BIGUPSERVICE_URL . 'build/js/bigup-services-gutenberg.js', array(), filemtime( BIGUPSERVICE_PATH . 'build/js/bigup-services-gutenberg.js' ), true );
		wp_enqueue_script( 'bigup_external_svg_loader_js', BIGUPSERVICE_URL . 'build/js/bigup-external-svg-loader.js', array(), filemtime( BIGUPSERVICE_PATH . 'build/js/bigup-external-svg-loader.js' ), true );
	}


	/**
	 * Add classic editor styles.
	 */
	public function add_classic_editor_styles() {
		// TinyMCE styles (asks for relative path to plugin root but full URL overrides this).
		add_editor_style( BIGUPSERVICE_URL . 'build/css/bigup-services-classic-editor.css' );
	}


	/**
	 * Get JSON definition, decode and return.
	 */
	private function get_definition() {
		$json       = Util::get_contents( BIGUPSERVICE_PATH . $this->definition_path );
		$definition = json_decode( $json, true );
		return $definition;
	}


	/**
	 * Hook custom columns for the post list.
	 */
	public function add_post_list_custom_columns( $columns ) {
		$new_columns = array_merge( $columns, array(
			'order' => __( 'Order', 'bigup-services' )
		) );

		// Move built-in columns to the end by removing and re-adding to the array.
		$categories = $new_columns[ 'categories' ];
		$tags = $new_columns[ 'tags' ];
		$date = $new_columns[ 'date' ];
		unset( $new_columns[ 'categories' ] );
		unset( $new_columns[ 'tags' ] );
		unset( $new_columns[ 'date' ] );
		$new_columns[ 'categories' ] = $categories;
		$new_columns[ 'tags' ] = $tags;
		$new_columns[ 'date' ] = $date;

		return $new_columns;
	}


	/**
	 * Configure data for the post list custom columns.
	 */
	public function define_post_list_custom_columns_data( $column_key, $post_id ) {
		if ( $column_key === 'order' ) {
			$order = get_post_meta( $post_id, '_bigup_service_order', true );
			if ( $order ) {
				echo $order;
			}
		}
	}


	/**
	 * Make custom post list columns sortable.
	 */
	public function make_post_list_custom_columns_sortable( $columns ) {
		$columns[ 'order' ] = 'order';
		return $columns;
	}


	/**
	 * Define custom columns SQL query post filter and sorting method.
	 * 
	 * We want to sort posts but not exclude any even if they have an empty value on the meta column
	 * being sorted. So we add conditions in the queries to match posts where the key 'EXISTS' or
	 * 'NOT EXISTS', then sort by either alphabetical ('meta_value') or numerical ('meta_value_num')
	 * order. This results in empty values being at the end of the sorted list, but not hidden.
	 */
	public function define_post_list_custom_columns_sorting( $query ) {
		$orderby = $query->get( 'orderby' );

		if ( $orderby == 'order' ) {

			$query->set( 'meta_query', array(
				'relation' => 'OR',
				array(
					'key' => '_bigup_review_rating',
					'compare' => 'EXISTS',
				),
				array(
					'key' => '_bigup_review_rating',
					'compare' => 'NOT EXISTS',
				),
			) );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}
}
