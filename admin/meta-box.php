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

/**
 * Registers the video details meta box.
 *
 * @return void
 */
function emmaviga_add_video_meta_box() {

	add_meta_box(
		'emmaviga_video_details',
		__( 'Video Details', 'emmad-video-gallery' ),
		'emmaviga_video_meta_box_callback',
		'emmaviga_video',
		'normal',
		'high'
	);

}

add_action( 'add_meta_boxes', 'emmaviga_add_video_meta_box' );

/*
|--------------------------------------------------------------------------
| Meta Box Callback
|--------------------------------------------------------------------------
*/

/**
 * Renders the HTML content for the video details meta box.
 *
 * @param WP_Post $post Current post object.
 * @return void
 */
function emmaviga_video_meta_box_callback( $post ) {

	// Security nonce.
	wp_nonce_field( 'emmaviga_video_nonce_action', 'emmaviga_video_nonce' );

	// Retrieve existing value from database.
	$video_url = get_post_meta( $post->ID, '_emmaviga_video_url', true );

	?>
	<div class="emmaviga-meta-box-wrap" style="padding:10px 0;">
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row" style="width:160px;">
						<label for="emmaviga_video_url">
							<strong><?php esc_html_e( 'Video URL', 'emmad-video-gallery' ); ?></strong>
						</label>
					</th>
					<td>
						<input
							type="url"
							id="emmaviga_video_url"
							name="emmaviga_video_url"
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

/*
|--------------------------------------------------------------------------
| Save Meta Box Data
|--------------------------------------------------------------------------
*/

/**
 * Saves video meta box data.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function emmaviga_save_video_meta( $post_id ) {

	if ( ! isset( $_POST['emmaviga_video_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['emmaviga_video_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'emmaviga_video_nonce_action' ) ) {
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
	if ( 'emmaviga_video' !== get_post_type( $post_id ) ) {
		return;
	}

	// Sanitize and save video URL.
	if ( isset( $_POST['emmaviga_video_url'] ) ) {
		$raw_url   = sanitize_text_field( wp_unslash( $_POST['emmaviga_video_url'] ) );
		$clean_url = esc_url_raw( trim( $raw_url ) );

		if ( ! empty( $clean_url ) ) {
			update_post_meta( $post_id, '_emmaviga_video_url', $clean_url );
		} else {
			delete_post_meta( $post_id, '_emmaviga_video_url' );
		}
	}

}

add_action( 'save_post_emmaviga_video', 'emmaviga_save_video_meta' );
