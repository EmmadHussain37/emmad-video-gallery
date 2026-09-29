<?php
/**
 * Admin Columns Management for vg_video Post Type.
 *
 * @package    Emmad_Video_Gallery
 * @subpackage Emmad_Video_Gallery/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Define Columns
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_video_columns' ) ) {
	/**
	 * Adds custom columns to the Videos admin list table.
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	function emmad_video_columns( $columns ) {

		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_columns['emmad_thumb'] = __( 'Thumbnail', 'emmad-video-gallery' );
			}

			$new_columns[ $key ] = $value;

			if ( 'title' === $key ) {
				$new_columns['video_url'] = __( 'Video Source', 'emmad-video-gallery' );
			}
		}

		return $new_columns;

	}
}

add_filter( 'manage_vg_video_posts_columns', 'emmad_video_columns' );

/*
|--------------------------------------------------------------------------
| Render Column Content
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_video_column_content' ) ) {
	/**
	 * Outputs content for custom columns in the Videos list table.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	function emmad_video_column_content( $column, $post_id ) {

		if ( 'emmad_thumb' === $column ) {
			$thumb_url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			if ( empty( $thumb_url ) ) {
				$thumb_url = EMMAD_URL . 'assets/images/placeholder.webp';
			}

			echo sprintf(
				'<img src="%1$s" alt="" style="width:64px;height:36px;object-fit:cover;border-radius:4px;display:block;background:#222;" />',
				esc_url( $thumb_url )
			);
			return;
		}

		if ( 'video_url' !== $column ) {
			return;
		}

		$url = get_post_meta( $post_id, '_vg_video_url', true );

		if ( empty( $url ) ) {
			echo '<em>' . esc_html__( 'Not Set', 'emmad-video-gallery' ) . '</em>';
			return;
		}

		// Determine video service badge.
		$badge_text  = __( 'MP4 / File', 'emmad-video-gallery' );
		$badge_color = '#64748b';

		if ( preg_match( '/(youtube\.com|youtu\.be)/i', $url ) ) {
			$badge_text  = 'YouTube';
			$badge_color = '#ef4444';
		} elseif ( preg_match( '/vimeo\.com/i', $url ) ) {
			$badge_text  = 'Vimeo';
			$badge_color = '#0ea5e9';
		}

		echo '<div style="display:flex;align-items:center;gap:8px;">';
		echo sprintf(
			'<span style="background:%1$s;color:#fff;font-size:10px;font-weight:600;padding:2px 6px;border-radius:3px;text-transform:uppercase;letter-spacing:0.5px;">%2$s</span>',
			esc_attr( $badge_color ),
			esc_html( $badge_text )
		);
		echo sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer" style="font-family:monospace;font-size:12px;">%2$s</a>',
			esc_url( $url ),
			esc_html( wp_trim_words( $url, 5, '...' ) )
		);
		echo '</div>';

	}
}

add_action( 'manage_vg_video_posts_custom_column', 'emmad_video_column_content', 10, 2 );
