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
| Register & Localize Frontend Assets
|--------------------------------------------------------------------------
*/

/**
 * Registers and localizes frontend stylesheets and scripts.
 *
 * @return void
 */
function emmaviga_register_frontend_assets() {

	$gallery_css_ver = file_exists( EMMAVIGA_PATH . 'assets/css/gallery.css' ) ? (string) filemtime( EMMAVIGA_PATH . 'assets/css/gallery.css' ) : EMMAVIGA_VERSION;
	$gallery_js_ver  = file_exists( EMMAVIGA_PATH . 'assets/js/gallery.js' ) ? (string) filemtime( EMMAVIGA_PATH . 'assets/js/gallery.js' ) : EMMAVIGA_VERSION;

	wp_register_style(
		'emmaviga-gallery-style',
		EMMAVIGA_URL . 'assets/css/gallery.css',
		array(),
		$gallery_css_ver
	);

	wp_register_script(
		'emmaviga-gallery-script',
		EMMAVIGA_URL . 'assets/js/gallery.js',
		array(),
		$gallery_js_ver,
		true
	);

	// Localize frontend script with translatable strings.
	wp_localize_script(
		'emmaviga-gallery-script',
		'emmavigaGalleryData',
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

	// Pre-enqueue if the current post explicitly contains the shortcode.
	if ( is_singular() ) {
		$post = get_post();
		if (
			$post instanceof WP_Post &&
			(
				has_shortcode( $post->post_content, 'emmaviga_gallery' ) ||
				has_shortcode( $post->post_content, 'emmaviga_video_gallery' )
			)
		) {
			wp_enqueue_style( 'emmaviga-gallery-style' );
			wp_enqueue_script( 'emmaviga-gallery-script' );
		}
	}

}

add_action( 'wp_enqueue_scripts', 'emmaviga_register_frontend_assets' );

/*
|--------------------------------------------------------------------------
| Admin Assets & Localization
|--------------------------------------------------------------------------
*/

/**
 * Enqueues and localizes admin scripts and styles.
 *
 * @param string $hook Current admin page hook.
 * @return void
 */
function emmaviga_admin_assets( $hook ) {

	global $post_type;

	$screen           = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$screen_id        = ( $screen instanceof WP_Screen ) ? $screen->id : '';
	$screen_post_type = ( $screen instanceof WP_Screen ) ? $screen->post_type : '';

	$is_settings_page = (
		false !== strpos( $hook, 'emmaviga-video-gallery-settings' ) ||
		false !== strpos( $screen_id, 'emmaviga-video-gallery-settings' )
	);
	$is_video_cpt     = ( 'emmaviga_video' === $post_type || 'emmaviga_video' === $screen_post_type );

	if ( ! $is_settings_page && ! $is_video_cpt ) {
		return;
	}

	$admin_css_ver = file_exists( EMMAVIGA_PATH . 'assets/css/admin.css' ) ? (string) filemtime( EMMAVIGA_PATH . 'assets/css/admin.css' ) : EMMAVIGA_VERSION;
	$admin_js_ver  = file_exists( EMMAVIGA_PATH . 'assets/js/admin.js' ) ? (string) filemtime( EMMAVIGA_PATH . 'assets/js/admin.js' ) : EMMAVIGA_VERSION;

	// Enqueue admin styles.
	wp_enqueue_style(
		'emmaviga-admin-style',
		EMMAVIGA_URL . 'assets/css/admin.css',
		array(),
		$admin_css_ver
	);

	// Media frame and uploader script only on settings page.
	if ( $is_settings_page ) {
		wp_enqueue_media();

		wp_enqueue_script(
			'emmaviga-admin-script',
			EMMAVIGA_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			$admin_js_ver,
			true
		);

		// Localize admin script for i18n and accessibility.
		wp_localize_script(
			'emmaviga-admin-script',
			'emmavigaAdminData',
			array(
				'mediaTitle'  => __( 'Select Player Logo', 'emmad-video-gallery' ),
				'mediaButton' => __( 'Use Selected Logo', 'emmad-video-gallery' ),
			)
		);
	}

}

add_action( 'admin_enqueue_scripts', 'emmaviga_admin_assets' );
