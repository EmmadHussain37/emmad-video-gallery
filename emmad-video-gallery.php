<?php
/**
 * Plugin Name: Emmad Video Gallery
 * Plugin URI: https://github.com/EmmadHussain37/emmad-video-gallery
 * Description: A lightweight and powerful video gallery plugin for WordPress.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Emmad Hussain
 * Author URI: https://github.com/EmmadHussain37
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: emmad-video-gallery
 * Domain Path: /languages
 *
 * @package Emmad_Video_Gallery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

define( 'EMMAD_VERSION', '1.0.0' );
define( 'EMMAD_FILE', __FILE__ );
define( 'EMMAD_PATH', plugin_dir_path( __FILE__ ) );
define( 'EMMAD_URL', plugin_dir_url( __FILE__ ) );

/*
|--------------------------------------------------------------------------
| Includes
|--------------------------------------------------------------------------
*/

require_once EMMAD_PATH . 'includes/post-type.php';
require_once EMMAD_PATH . 'admin/meta-box.php';
require_once EMMAD_PATH . 'admin/admin-columns.php';
require_once EMMAD_PATH . 'admin/settings.php';
require_once EMMAD_PATH . 'public/assets.php';
require_once EMMAD_PATH . 'public/shortcode.php';

/*
|--------------------------------------------------------------------------
| Activation & Deactivation
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_activate' ) ) {
	/**
	 * Fired on plugin activation.
	 *
	 * @return void
	 */
	function emmad_activate() {
		emmad_register_post_type();
		emmad_register_taxonomy();
		flush_rewrite_rules();
	}
}

register_activation_hook( __FILE__, 'emmad_activate' );

if ( ! function_exists( 'emmad_deactivate' ) ) {
	/**
	 * Fired on plugin deactivation.
	 *
	 * @return void
	 */
	function emmad_deactivate() {
		flush_rewrite_rules();
	}
}

register_deactivation_hook( __FILE__, 'emmad_deactivate' );
