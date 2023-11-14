<?php
namespace BigupWeb\CPT_Service;

/**
 * Initialise.
 *
 * @package cpt-service
 */
class Initialise {

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
	 * Setup this plugin.
	 *
	 * Get and check definition, then call functions to register CPT and custom fields.
	 * All action hooks for this plugin should be registered here to manage sequence.
	 */
	public function __construct() {
		$json       = Util::get_contents( CPTSERV_DIR . $this->definition_path );
		$definition = json_decode( $json, true );

		if ( ! is_array( $definition ) || ! array_key_exists( 'key', $definition ) ) {
			$this->def = false;
		} else {
			$this->def = $definition;
		}

	}


	/**
	 * Setup this plugin.
	 *
	 * Get and check definition, then call functions to register CPT and custom fields.
	 * All action hooks for this plugin should be registered here to manage sequence.
	 */
	public function setup_plugin() {
		$def = $this->def;

		if ( ! is_array( $def ) || ! array_key_exists( 'key', $def ) ) {
			error_log( 'BigupWeb\CPT_Service error: Could not retrieve post type definition' );
			return;
		}

		$cpt = new Custom_Post_Type( $def );
		add_action( 'init', array( $cpt, 'register' ), 0, 1 );

		if ( ! array_key_exists( 'customFields', $def ) ) {
			return;
		}

		$Editor_Classic = new Editor_Classic( $def );
		add_action( 'do_meta_boxes', array( &$Editor_Classic, 'remove_default_meta_box' ), 10, 3 );
		add_action( 'add_meta_boxes', array( &$Editor_Classic, 'add_custom_meta_box' ), 10, 0 );
		add_action( 'save_post', array( &$Editor_Classic, 'save_custom_meta_box_data' ), 1, 2 );

		$Editor_Gutenberg = new Editor_Gutenberg( $def );
		add_action( 'init', array( &$Editor_Gutenberg, 'setup_custom_fields' ), 11, 0 );
		add_filter( 'allowed_block_types_all', array( &$Editor_Gutenberg, 'allowed_block_types' ), 25, 2 );

		$Patterns = new Patterns();
		add_action( 'init', array( &$Patterns, 'register_all' ) );

		// Enable WP custom fields even if ACF is installed.
		add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );

		add_action( 'enqueue_block_editor_assets', array( &$this, 'enqueue_block_editor_scripts' ) );
		add_action( 'after_setup_theme', array( &$this, 'add_service_icon_image_size' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_init', array( $this, 'add_classic_editor_styles' ) );
	}


	/**
	 * Add a custom image size for the service icons.
	 * 
	 * Wordpress will scale/crop images so they're ready for output in this size.
	 */
	public function add_service_icon_image_size() {
		add_image_size( 'bigup_service_icon', 100, 100, false );
	}


	/**
	 * Enqueue media upload functionality.
	 * 
	 * Initialise wp.media to handle the admin media upload/select modal.
	 */
	public function enqueue_admin_scripts() {
		if ( get_post_type() === $this->def[ 'key' ] ) {
			// Initialise wp.media to handle the admin media upload/select modal.
			wp_enqueue_media();
			wp_enqueue_script( 'bigup_cpt_service_classic_js', CPTSERV_URL . 'build/js/bigup-cpt-service-classic-editor.js', array(), filemtime( CPTSERV_DIR . 'build/js/bigup-cpt-service-classic-editor.js' ), true );
		}
	}


	/**
	 * Enqueue scripts for this plugin.
	 */
	public function enqueue_block_editor_scripts() {
		wp_enqueue_script( 'bigup_cpt_service_gutenberg_js', CPTSERV_URL . 'build/js/bigup-cpt-service-gutenberg.js', array(), filemtime( CPTSERV_DIR . 'build/js/bigup-cpt-service-gutenberg.js' ), true );
	}


	/**
	 * Add classic editor styles.
	 */
	public function add_classic_editor_styles() {
		// TinyMCE styles (asks for relative path to plugin root but full URL overrides this).
		add_editor_style( CPTSERV_URL . 'build/css/bigup-cpt-service-classic-editor.css' );
	}
}
