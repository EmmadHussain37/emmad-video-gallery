<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package    Emmad_Video_Gallery
 * @subpackage Emmad_Video_Gallery/uninstall
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Capability check.
if ( ! current_user_can( 'activate_plugins' ) ) {
	return;
}

/*
|--------------------------------------------------------------------------
| Delete Plugin Options
|--------------------------------------------------------------------------
*/

delete_option( 'emmaviga_player_logo' );

/*
|--------------------------------------------------------------------------
| Delete All Videos
|--------------------------------------------------------------------------
*/

$emmaviga_videos = get_posts(
	array(
		'post_type'      => 'emmaviga_video',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	)
);

if ( ! empty( $emmaviga_videos ) && is_array( $emmaviga_videos ) ) {
	foreach ( $emmaviga_videos as $emmaviga_video_id ) {
		wp_delete_post( $emmaviga_video_id, true );
	}
}

/*
|--------------------------------------------------------------------------
| Delete Taxonomy Terms
|--------------------------------------------------------------------------
*/

if ( taxonomy_exists( 'emmaviga_video_category' ) ) {
	$emmaviga_terms = get_terms(
		array(
			'taxonomy'   => 'emmaviga_video_category',
			'hide_empty' => false,
			'fields'     => 'ids',
		)
	);

	if ( ! empty( $emmaviga_terms ) && ! is_wp_error( $emmaviga_terms ) && is_array( $emmaviga_terms ) ) {
		foreach ( $emmaviga_terms as $emmaviga_term_id ) {
			wp_delete_term( $emmaviga_term_id, 'emmaviga_video_category' );
		}
	}
}
