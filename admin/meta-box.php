<?php
/**
 * Video Details Meta Box Handler.
 *
 * @package    Emmad_Video_Gallery
 * @subpackage Emmad_Video_Gallery/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Add Meta Box
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_add_video_meta_box' ) ) {
	/**
	 * Registers the video details meta box.
	 *
	 * @return void
	 */
	function emmad_add_video_meta_box() {

		add_meta_box(
			'emmad_video_details',
			__( 'Video Details', 'emmad-video-gallery' ),
			'emmad_video_meta_box_callback',
			'vg_video',
			'normal',
			'high'
		);

	}
}

add_action( 'add_meta_boxes', 'emmad_add_video_meta_box' );

/*
|--------------------------------------------------------------------------
| Meta Box Callback
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_video_meta_box_callback' ) ) {
	/**
	 * Renders the HTML content for the video details meta box.
	 *
	 * @param WP_Post $post Current post object.
	 * @return void
	 */
	function emmad_video_meta_box_callback( $post ) {

		// Security nonce.
		wp_nonce_field( 'emmad_video_nonce_action', 'emmad_video_nonce' );

		// Retrieve existing value from database.
		$video_url = get_post_meta( $post->ID, '_vg_video_url', true );

		?>
		<div class="emmad-meta-box-wrap" style="padding:10px 0;">
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row" style="width:160px;">
							<label for="emmad_video_url">
								<strong><?php esc_html_e( 'Video URL', 'emmad-video-gallery' ); ?></strong>
							</label>
						</th>
						<td>
							<input
								type="url"
								id="emmad_video_url"
								name="vg_video_url"
								value="<?php echo esc_url( $video_url ); ?>"
								class="large-text"
								placeholder="<?php echo esc_attr__( 'https://example.com/video.mp4 or YouTube / Vimeo URL', 'emmad-video-gallery' ); ?>"
								style="max-width:100%;"
							/>
							<p class="description" style="margin-top:6px;">
								<?php esc_html_e( 'Supports self-hosted MP4 / WebM videos, YouTube links (standard, youtu.be, shorts), and Vimeo URLs.', 'emmad-video-gallery' ); ?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php

	}
}

/*
|--------------------------------------------------------------------------
| Save Meta Box Data
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_save_video_meta' ) ) {
	/**
	 * Saves video meta box data.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	function emmad_save_video_meta( $post_id ) {

		$nonce_field = '';
		$action_name = '';

		if ( isset( $_POST['emmad_video_nonce'] ) ) {
			$nonce_field = sanitize_text_field( wp_unslash( $_POST['emmad_video_nonce'] ) );
			$action_name = 'emmad_video_nonce_action';
		} elseif ( isset( $_POST['ehvg_video_nonce'] ) ) {
			$nonce_field = sanitize_text_field( wp_unslash( $_POST['ehvg_video_nonce'] ) );
			$action_name = 'ehvg_video_nonce_action';
		}

		if ( empty( $nonce_field ) || ! wp_verify_nonce( $nonce_field, $action_name ) ) {
			return;
		}

		// Prevent autosave overwrites.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check user permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check post type.
		if ( 'vg_video' !== get_post_type( $post_id ) ) {
			return;
		}

		// Sanitize and save video URL.
		if ( isset( $_POST['vg_video_url'] ) ) {
			$raw_url   = sanitize_text_field( wp_unslash( $_POST['vg_video_url'] ) );
			$clean_url = esc_url_raw( trim( $raw_url ) );

			if ( ! empty( $clean_url ) ) {
				update_post_meta( $post_id, '_vg_video_url', $clean_url );
			} else {
				delete_post_meta( $post_id, '_vg_video_url' );
			}
		}

	}
}

add_action( 'save_post_vg_video', 'emmad_save_video_meta' );

