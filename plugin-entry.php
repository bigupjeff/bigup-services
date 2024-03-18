<?php
namespace BigupWeb\Services;

/**
 * Plugin Name:       Bigup Services
 * Description:       A custom 'Services' post type with custom meta fields.
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Version:           0.1.5
 * Author:            Jefferson Real
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bigup-services
 *
 * @package           bigup-services
 * @link              https://kinsta.com/blog/dynamic-blocks/
 * @link              https://kinsta.com/blog/wordpress-add-meta-box-to-post/
 * @link              https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/
 */

$enable_debug = false;

// Define constants.
define( 'BIGUPSERVICE_DEBUG', $enable_debug );
define( 'BIGUPSERVICE_PATH', trailingslashit( __DIR__ ) );
define( 'BIGUPSERVICE_URL', trailingslashit( get_site_url( null, strstr( __DIR__, '/wp-content/' ) ) ) );

// Register namespaced autoloader.
$namespace = 'BigupWeb\\Services\\';
$root      = BIGUPSERVICE_PATH . 'classes/';
require_once $root . 'autoload.php';

// Setup this plugin.
$Init = new Init();
$Init->setup();
