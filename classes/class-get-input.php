<?php
namespace BigupWeb\CPT_Service;

/**
 * Get Input.
 *
 * A library of form input field templates for the classic editor.
 *
 * @package bigup-cpt-service
 */

class Get_Input {

	/**
	 * Return HTML markup for the passed setting object and option value.
	 */
	public static function markup( $field, $value, $name_attr = null ) {

		$name = $name_attr ? $name_attr : $field['id'];

		switch ( $field['input_type'] ) {

			case 'hidden':
				return sprintf(
					'<input type="hidden" name="%s" id="%s" value="%s" %s>',
					$name,
					$field['id'],
					$value,
					$field['required']
				);

			case 'text':
				return sprintf(
					'<input type="text" class="regular-text" name="%s" id="%s" value="%s" placeholder="%s" %s %s %s>',
					$name,
					$field['id'],
					$value,
					$field['placeholder'],
					$r = ( isset( $field['regex'] ) ) ? 'pattern="' . $field['regex'] . '"' : '',
					$l = ( isset( $field['length_limit'] ) ) ? 'maxlength="' . $field['length_limit'] . '"' : '',
					$field['required']
				);

			case 'url':
				return sprintf(
					'<input type="url" class="regular-text" name="%s" id="%s" value="%s" placeholder="%s" %s %s>',
					$name,
					$field['id'],
					$value,
					$field['placeholder'],
					$l = ( isset( $field['length_limit'] ) ) ? 'maxlength="' . $field['length_limit'] . '"' : '',
					$field['required']
				);

			case 'textarea':
				return sprintf(
					'<textarea name="%s" class="regular-text" id="%s" rows="%s" cols="%s" %s>%s</textarea>',
					$name,
					$field['id'],
					$field['rows'],
					$field['cols'],
					$field['required'],
					$value
				);

			case 'password':
				return sprintf(
					'<input type="password" class="regular-text" name="%s" id="%s" value="%s" %s>',
					$name,
					$field['id'],
					$value,
					$field['required']
				);

			case 'email':
				return sprintf(
					'<input type="email" class="regular-text" name="%s" id="%s" value="%s" %s>',
					$name,
					$field['id'],
					$value,
					$field['required']
				);

			case 'number':
				return sprintf(
					'<input type="number" name="%s" id="%s" min="%s" max="%s" step="%s" value="%s" %s>',
					$name,
					$field['id'],
					$field['number_min'],
					$field['number_max'],
					$field['number_step'],
					$value,
					$field['required']
				);

			case 'checkbox':
				return sprintf(
					'<input type="checkbox" name="%s" id="%s" value="%s" %s>',
					$name,
					$field['id'],
					1,
					$s = ( $value ) ? 'checked' : ''
				);

			case 'select':
				return sprintf(
					'<select name="%s" id="%s" %s>%s</select>',
					$name,
					$field['id'],
					$field['select_multi'],
					self::get_select_data( $field['select_type'], $value )
				);

			case 'image-upload':
				if ( ! $value ) {
					$icon           = '<span>No image selected.</span>';
					$delete_display = 'none';
				} else {
					$icon_url = wp_get_attachment_image_url( 
						$value,               // Attachment ID.
						'bigup_service_icon', // Size.
						true                  // Treat as icon.
					);
					$icon_url       = ( strlen( $icon_url ) > 0 ) ? $icon_url : '';
					$icon           = "<img src='{$icon_url}' alt='icon preview' style='max-width:100px;min-height:0;'>";
					$delete_display = 'block';
				}
				$img_upload = <<<IMGUP
	<label for="{$field['id']}">
		<input type="number" name="{$field['id']}" id="{$field['id']}" class="meta-image" value="{$value}" min="0" max="999999999" step="1" style="display:none;">
		<input type="button" class="button image-upload" value="Browse">
		<div class="image-preview" style="display:block;margin-top:4px;">
			{$icon}
		</div>
		<a href="#" class="image-remove" style="display:{$delete_display};color:#b32d2e;margin-top:4px;">Remove icon image</a>
	</label>
IMGUP;
				return $img_upload;

			case 'dashicons-select':
				// Get a list of available dashicons from the SVG icon source file.
				$dashicons_svg = file_get_contents( ABSPATH . '/wp-includes/fonts/dashicons.svg' );
				$pattern       = '/id="(.*?)"/';
				preg_match_all( $pattern, $dashicons_svg, $dashicon_slugs );
				$class = $value ? 'dashicons dashicons-' . $value : '';

				$open = <<<OPEN
	<div class="dashiconsDropdown">
		<a href="#select">
			<span class="<?php echo htmlspecialchars( $class ); ?>">
				--select icon--
			</span>
		</a>
		<ul id="select">
		<li class="dashiconsDropdown_removeButton">
			<button>
				Remove Icon
			</button>
		</li>
OPEN;

				$options = '';
				foreach ( $dashicon_slugs[1] as $slug ) :
					$checked            = ( $slug === $value ) ? 'checked' : '';
					$screen_reader_text = str_replace( '-', ' ', $slug );
					$options           .= <<<OPTIONS
			<li>
				<input type="radio" name="{$field['id']}" value="{$slug}" id="{$slug}" {$checked}>
				<label for="{$slug}" title="{$slug}">
					<span class="dashicons dashicons-{$slug}"></span>
					<span class="screen-reader-text">{$screen_reader_text}</span>
				</label>
			</li>
OPTIONS;
				endforeach;

				$close = <<<CLOSE
		</ul>
	</div>
CLOSE;
				return $open . $options . $close;

			default:
				return sprintf(
					'<b>PLUGIN ERROR: No input type "%s" for setting ID "%s"!</b>',
					$field['input_type'],
					$field['id']
				);
		}
	}

	/**
	 * Return HTML select options markup for the passed select type.
	 */
	public static function get_select_data( $select_type, $selected_options ) {
		$markup = "\n";

		if ( 'taxonomies' === $select_type ) {
			$markup = $markup . '<option value="" disabled>--</option>' . "\n";

			$selected_options = is_array( $selected_options ) ? $selected_options : array();
			$all_post_types   = get_post_types();
			$post_taxonomies  = get_object_taxonomies( $all_post_types, 'names' );

			foreach ( $post_taxonomies as $taxonomy ) {
				if ( in_array( $taxonomy, $selected_options, true ) ) {
					$selected = ' selected';
				} else {
					$selected = '';
				}
				$markup = $markup . '<option value="' . $taxonomy . '"' . $selected . '>' . $taxonomy . '</option>' . "\n";
			}
		} else {
			return error_log( 'Bigup Web: passed input $select_type invalid' );
		}

		return $markup;
	}


}
