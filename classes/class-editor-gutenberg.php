<?php
namespace BigupWeb\CPT_Service;

/**
 * Register custom meta box for the Gutenberg editor.
 *
 * @package cpt-service
 */
class Editor_Gutenberg {

	/**
	 * Custom post type key.
	 *
	 * @var string
	 */
	private $key = '';

	/**
	 * Prefix for storing custom fields in the postmeta table.
	 *
	 * @var string
	 */
	private $prefix = '';

	/**
	 * Metabox ID.
	 *
	 * @var string
	 */
	private $metabox_id = '';

	/**
	 * Custom field definitions.
	 *
	 * @var array
	 */
	private $custom_fields = '';


	/**
	 * Register the post meta for block support.
	 *
	 * The passed definition data is verbosely stored in the class properties before being used
	 * to setup the post meta with WP hooks.
	 */
	public function __construct( $definition ) {
		$this->key           = $definition['key'];
		$this->prefix        = $definition['prefix'];
		$this->metabox_id    = $definition['metaboxID'];
		$this->custom_fields = $definition['customFields'];
	}


	/**
	 * Register block and metafield on the post type.
	 */
	public function setup_custom_fields() {
		foreach ( $this->custom_fields as $metafield ) {

			$block_dir = CPTSERV_DIR . 'build/blocks/' . str_replace( '_', '-', $this->key . $metafield['suffix'] );
			$result    = register_block_type_from_metadata(
				$block_dir,
				array(
					'render_callback' => array( $this, 'dynamic_render_callback' ),
				)
			);
			if ( false === $result ) {
				error_log( "ERROR: Block registration failed for path '{$block_dir}'" );
			}
			$user_capabilities = $metafield['user_capabilities'];
			$sanitize_callback = Sanitize::get_callback( $metafield['input_type'] );
			register_post_meta(
				$this->key,                                                 // Post type.
				$this->prefix . $this->key . $metafield['suffix'],          // Metafield key.
				array(
					'type'              => $metafield['type'],              // The type of data.
					'description'       => $metafield['description'],       // A description of the data.
					'sanitize_callback' => $sanitize_callback,              // The sanitize callback.
					'show_in_rest'      => $metafield['show_in_rest'],      // Show in REST API. Must be true for Gut.
					'single'            => $metafield['single'],            // Single value or array of values?
					'auth_callback'     => function() use ( $user_capabilities ) {
						return current_user_can( $user_capabilities );
					},
				)
			);
		}
	}


	/**
	 * Dynamic front-end render callback.
	 *
	 * Builds markup for the dynamic content when called by the render_callback of register_block_type().
	 *
	 * @param array $attributes Attributes that relate to the block.
	 * @param array $content Content to be inserted into the markup.
	 * @param array $block Registered block definition and settings.
	 *
	 * @link https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/
	 */
	public function dynamic_render_callback( $attributes, $content, $block ) {

		// Get the custom field definition for the calling block.
		$field = array();
		foreach ( $this->custom_fields as $custom_field ) {
			if ( $block->name === $custom_field['block_name'] ) {
				$field = $custom_field;
			}
		}

		// Get the custom field value.
		$context_post_id = $block->context['postId'];
		$meta_key        = $this->prefix . $this->key . $field['suffix'];
		$value           = get_post_meta( $context_post_id, $meta_key, true );

		// Display the output as configured by the custom field definition.
		$output = '';
		if ( ! empty( $value ) > 0 ) {
			switch ( $field['suffix'] ) {

				case '_price':
					$output .= '<p>' . esc_html( $value ) . '</p>';
					break;

				case '_icon':
					$icon_markup = wp_get_attachment_image( 
						$attachment_id = $value,                 // Attachment id.
						$size = 'bigup_service_icon',            // Size.
						$icon = true,                            // Treat image as an icon.
						$attr = array(
							'alt' => $field['label'] .  ' icon', // alt text.
						),
					);
					if ( strlen( $icon_markup ) > 0 ) {
						$output .= '<figure>' . $icon_markup . '</figure>';
					}
					break;
			}
		}
		if ( strlen( $output ) > 0 ) {
			return '<div ' . get_block_wrapper_attributes() . '>' . $output . '</div>';
		} else {
			return '';
		}
	}


	/**
	 * Filter the allowed blocks for this post type.
	 *
	 * @param array $allowed_blocks The allowed blocks
	 * @param array $editor_context The editor context
	 */
	public function allowed_block_types( $allowed_blocks, $editor_context ) {
		$post_type = ( !! $editor_context->post ) ? $editor_context->post->post_type : false;
		if ( $post_type && $this->key === $post_type ) {
			$allowed_blocks = array(
				'core/list',
				'core/list-item',
				'core/paragraph',
				'core/image',
				'core/heading',
				'core/gallery',
				'core/list',
				'core/quote',
				'core/archives',
				'core/audio',
				'core/button',
				'core/buttons',
				'core/calendar',
				'core/categories',
				'core/code',
				'core/columns',
				'core/column',
				'core/cover',
				'core/embed',
				'core/file',
				'core/group',
				'core/freeform',
				'core/html',
				'core/media-text',
				'core/latest-comments',
				'core/latest-posts',
				'core/preformatted',
				'core/pullquote',
				'core/search',
				'core/separator',
				'core/block',
				'core/social-links',
				'core/social-link',
				'core/spacer',
				'core/subhead',
				'core/table',
				'core/tag-cloud',
				'core/text-columns',
				'core/verse',
				'core/video',
			);
			return $allowed_blocks;
		} else {
			return;
		}
	}
}
