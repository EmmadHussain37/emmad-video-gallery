<?php
/**
 * Asset Enqueuing, Registration, and Localization.
 *
 * @package    Emmad_Video_Gallery
 * @subpackage Emmad_Video_Gallery/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Register & Enqueue Frontend Assets
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_register_frontend_assets' ) ) {
	/**
	 * Registers and enqueues frontend stylesheets and scripts.
	 *
	 * @return void
	 */
	function emmad_register_frontend_assets() {

		$css_ver = file_exists( EMMAD_PATH . 'assets/css/gallery.css' ) ? (string) filemtime( EMMAD_PATH . 'assets/css/gallery.css' ) : EMMAD_VERSION;
		$js_ver  = file_exists( EMMAD_PATH . 'assets/js/gallery.js' ) ? (string) filemtime( EMMAD_PATH . 'assets/js/gallery.js' ) : EMMAD_VERSION;

		wp_enqueue_style(
			'emmad-gallery-style',
			EMMAD_URL . 'assets/css/gallery.css',
			array(),
			$css_ver
		);

		wp_enqueue_script(
			'emmad-gallery-script',
			EMMAD_URL . 'assets/js/gallery.js',
			array(),
			$js_ver,
			true
		);

		// Backward compatibility handle aliases.
		wp_register_style( 'ehvg-gallery', EMMAD_URL . 'assets/css/gallery.css', array(), $css_ver );
		wp_register_script( 'ehvg-gallery', EMMAD_URL . 'assets/js/gallery.js', array(), $js_ver, true );

		// Localize frontend script with translatable strings.
		wp_localize_script(
			'emmad-gallery-script',
			'emmadGalleryData',
			array(
				'play'           => __( 'Play', 'emmad-video-gallery' ),
				'pause'          => __( 'Pause', 'emmad-video-gallery' ),
				'mute'           => __( 'Mute', 'emmad-video-gallery' ),
				'unmute'         => __( 'Unmute', 'emmad-video-gallery' ),
				'close'          => __( 'Close video player', 'emmad-video-gallery' ),
				'nowPlaying'     => __( 'NOW PLAYING', 'emmad-video-gallery' ),
				'seekSlider'     => __( 'Video progress seek bar', 'emmad-video-gallery' ),
				'externalStream' => __( 'External Video Stream', 'emmad-video-gallery' ),
			)
		);

	}
}

add_action( 'wp_enqueue_scripts', 'emmad_register_frontend_assets' );

/*
|--------------------------------------------------------------------------
| Admin Assets & Localization
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_admin_assets' ) ) {
	/**
	 * Enqueues and localizes admin scripts and styles.
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	function emmad_admin_assets( $hook ) {

		global $post_type;

		$current_page     = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		$is_settings_page = (
			false !== strpos( $hook, 'emmad-video-gallery-settings' ) ||
			false !== strpos( $hook, 'emmad-settings' ) ||
			false !== strpos( $hook, 'ehvg-settings' ) ||
			'emmad-video-gallery-settings' === $current_page ||
			'emmad-vg-settings' === $current_page ||
			'ehvg-settings' === $current_page
		);
		$is_video_cpt     = ( 'vg_video' === $post_type );

		if ( ! $is_settings_page && ! $is_video_cpt ) {
			return;
		}

		$admin_css_ver = file_exists( EMMAD_PATH . 'assets/css/admin.css' ) ? (string) filemtime( EMMAD_PATH . 'assets/css/admin.css' ) : EMMAD_VERSION;
		$admin_js_ver  = file_exists( EMMAD_PATH . 'assets/js/admin.js' ) ? (string) filemtime( EMMAD_PATH . 'assets/js/admin.js' ) : EMMAD_VERSION;

		// Enqueue admin styles.
		wp_enqueue_style(
			'emmad-admin-style',
			EMMAD_URL . 'assets/css/admin.css',
			array(),
			$admin_css_ver
		);

		// Media frame and uploader script only on settings page.
		if ( $is_settings_page ) {
			wp_enqueue_media();

			wp_enqueue_script(
				'emmad-admin-script',
				EMMAD_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				$admin_js_ver,
				true
			);

			// Localize admin script for i18n and accessibility.
			wp_localize_script(
				'emmad-admin-script',
				'emmadAdminData',
				array(
					'mediaTitle'  => __( 'Select Player Logo', 'emmad-video-gallery' ),
					'mediaButton' => __( 'Use Selected Logo', 'emmad-video-gallery' ),
				)
			);
		}

	}
}

add_action( 'admin_enqueue_scripts', 'emmad_admin_assets' );
