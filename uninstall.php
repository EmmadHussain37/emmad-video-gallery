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

delete_option( 'emmad_vg_player_logo' );
delete_option( 'ehvg_player_logo' ); // Legacy option cleanup.

/*
|--------------------------------------------------------------------------
| Delete All Videos
|--------------------------------------------------------------------------
*/

$emmad_vg_videos = get_posts(
	array(
		'post_type'      => 'vg_video',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	)
);

if ( ! empty( $emmad_vg_videos ) && is_array( $emmad_vg_videos ) ) {
	foreach ( $emmad_vg_videos as $emmad_video_id ) {
		wp_delete_post( $emmad_video_id, true );
	}
}

/*
|--------------------------------------------------------------------------
| Delete Taxonomy Terms
|--------------------------------------------------------------------------
*/

$emmad_vg_terms = get_terms(
	array(
		'taxonomy'   => 'video_category',
		'hide_empty' => false,
		'fields'     => 'ids',
	)
);

if ( ! empty( $emmad_vg_terms ) && ! is_wp_error( $emmad_vg_terms ) && is_array( $emmad_vg_terms ) ) {
	foreach ( $emmad_vg_terms as $emmad_term_id ) {
		wp_delete_term( $emmad_term_id, 'video_category' );
	}
}
