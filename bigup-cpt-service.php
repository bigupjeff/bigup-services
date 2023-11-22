<?php
namespace BigupWeb\CPT_Service;

/**
 * Plugin Name:       Bigup Web: Custom Post Type - Service
 * Description:       A custom 'Services' post type with custom meta fields.
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Version:           0.1.
 * Author:            Jefferson Real
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bigup-cpt-service
 *
 * @package           bigup-cpt-service
 * @link              https://kinsta.com/blog/dynamic-blocks/
 * @link              https://kinsta.com/blog/wordpress-add-meta-box-to-post/
 * @link              https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/
 */

// Define constants.
define( 'CPTSERV_DEBUG', defined( 'WP_DEBUG' ) && WP_DEBUG === true );
define( 'CPTSERV_DIR', trailingslashit( __DIR__ ) );
define( 'CPTSERV_URL', trailingslashit( get_site_url( null, strstr( __DIR__, '/wp-content/' ) ) ) );

// Setup PHP namespace.
require_once CPTSERV_DIR . 'classes/autoload.php';

// Setup this plugin.
$Init = new Init();
$Init->setup();
