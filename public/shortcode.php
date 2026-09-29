<?php
/**
 * Gallery Shortcode and Output Renderer.
 *
 * @package    Emmad_Video_Gallery
 * @subpackage Emmad_Video_Gallery/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'emmad_video_gallery_shortcode' ) ) {
	/**
	 * Shortcode callback for [emmad_video_gallery].
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	function emmad_video_gallery_shortcode( $atts ) {

		// Ensure frontend assets are enqueued when shortcode is executed.
		wp_enqueue_style( 'emmad-gallery-style' );
		wp_enqueue_script( 'emmad-gallery-script' );

		$atts = shortcode_atts(
			array(
				'posts'        => -1,
				'category'     => '',
				'columns'      => 3,
				'show_filters' => 'yes',
			),
			$atts,
			'emmad_video_gallery'
		);

		// Sanitize attributes.
		$posts_count  = intval( $atts['posts'] );
		$columns      = absint( $atts['columns'] );
		$show_filters = strtolower( sanitize_key( $atts['show_filters'] ) );
		$category     = sanitize_text_field( $atts['category'] );

		if ( $columns < 1 || $columns > 6 ) {
			$columns = 3;
		}

		ob_start();

		/*
		|--------------------------------------------------------------------------
		| Category Filters
		|--------------------------------------------------------------------------
		*/

		if ( 'yes' === $show_filters ) {

			$terms_args = array(
				'taxonomy'   => 'video_category',
				'hide_empty' => true,
			);

			// If specific categories were requested, limit filters to those.
			if ( ! empty( $category ) ) {
				$terms_args['slug'] = array_map( 'trim', explode( ',', $category ) );
			}

			$terms = get_terms( $terms_args );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				echo '<div class="vg-filters" role="group" aria-label="' . esc_attr__( 'Video category filters', 'emmad-video-gallery' ) . '">';
				echo '<button type="button" class="active" data-filter="*" aria-pressed="true">' . esc_html__( 'VIEW ALL', 'emmad-video-gallery' ) . '</button>';

				foreach ( $terms as $term ) {
					printf(
						'<button type="button" data-filter=".%1$s" aria-pressed="false">%2$s</button>',
						esc_attr( $term->slug ),
						esc_html( strtoupper( $term->name ) )
					);
				}

				echo '</div>';
			}

		}

		/*
		|--------------------------------------------------------------------------
		| Query Videos
		|--------------------------------------------------------------------------
		*/

		$query_args = array(
			'post_type'              => 'vg_video',
			'post_status'            => 'publish',
			'posts_per_page'         => $posts_count,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);

		if ( ! empty( $category ) ) {
			$slugs = array_map( 'sanitize_title', explode( ',', $category ) );
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'video_category',
					'field'    => 'slug',
					'terms'    => $slugs,
				),
			);
		}

		$query = new WP_Query( $query_args );

		echo '<div class="vg-gallery columns-' . esc_attr( $columns ) . '">';

		if ( $query->have_posts() ) :

			while ( $query->have_posts() ) :
				$query->the_post();

				$video_id  = get_the_ID();
				$video_url = get_post_meta( $video_id, '_vg_video_url', true );
				$thumb     = get_the_post_thumbnail_url( $video_id, 'large' );

				if ( empty( $thumb ) ) {
					$thumb = EMMAD_URL . 'assets/images/placeholder.webp';
				}

				$terms   = get_the_terms( $video_id, 'video_category' );
				$classes = array();

				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$classes[] = sanitize_html_class( $term->slug );
					}
				}

				$item_classes = ! empty( $classes ) ? ' ' . implode( ' ', $classes ) : '';
				$post_title   = get_the_title();
				?>

				<div class="vg-item<?php echo esc_attr( $item_classes ); ?>">

					<a
						href="<?php echo esc_url( ! empty( $video_url ) ? $video_url : '#' ); ?>"
						class="vg-video"
						data-video="<?php echo esc_url( $video_url ); ?>"
						data-title="<?php echo esc_attr( $post_title ); ?>"
						role="button"
						tabindex="0"
						aria-haspopup="dialog"
						aria-label="<?php echo esc_attr( sprintf( /* translators: %s: Video title */ __( 'Play video: %s', 'emmad-video-gallery' ), $post_title ) ); ?>"
					>

						<img
							src="<?php echo esc_url( $thumb ); ?>"
							alt="<?php the_title_attribute(); ?>"
							loading="lazy"
						/>

						<div class="vg-overlay">
							<div class="vg-play" aria-hidden="true">
								<svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true">
									<polygon points="8,5 19,12 8,19" fill="#ffffff"></polygon>
								</svg>
							</div>
						</div>

					</a>

				</div>

				<?php
			endwhile;

			wp_reset_postdata();

		else :
			?>
			<div class="vg-no-videos" style="grid-column:1/-1;text-align:center;padding:40px 20px;color:#9ca3af;">
				<p><?php esc_html_e( 'No videos found.', 'emmad-video-gallery' ); ?></p>
			</div>
			<?php
		endif;

		echo '</div>'; // End .vg-gallery.

		/*
		|--------------------------------------------------------------------------
		| Fullscreen Modal Video Player (Rendered once per request)
		|--------------------------------------------------------------------------
		*/
		static $emmad_player_rendered = false;

		if ( ! $emmad_player_rendered ) {
			$emmad_player_rendered = true;

			// Fetch player logo with fallback.
			$logo_url = get_option( 'emmad_player_logo' );
			if ( empty( $logo_url ) ) {
				$logo_url = get_option( 'emmad_vg_player_logo', '' );
			}
			if ( empty( $logo_url ) ) {
				$logo_url = get_option( 'ehvg_player_logo', '' );
			}
			if ( empty( $logo_url ) ) {
				$logo_url = EMMAD_URL . 'assets/images/default-logo.svg';
			}
			?>

			<div
				id="vg-player"
				role="dialog"
				aria-modal="true"
				aria-label="<?php esc_attr_e( 'Video Player', 'emmad-video-gallery' ); ?>"
				tabindex="-1"
			>

				<div class="vg-player-header">

					<img
						class="vg-player-logo"
						src="<?php echo esc_url( $logo_url ); ?>"
						alt="<?php esc_attr_e( 'Player Logo', 'emmad-video-gallery' ); ?>"
					/>

					<button
						type="button"
						class="vg-close"
						aria-label="<?php esc_attr_e( 'Close video player', 'emmad-video-gallery' ); ?>"
					>
						<?php esc_html_e( 'CLOSE', 'emmad-video-gallery' ); ?>
					</button>

				</div>

				<video
					id="vg-video-player"
					preload="metadata"
					playsinline
				></video>

				<iframe
					id="vg-iframe-player"
					src=""
					title="<?php esc_attr_e( 'External Video Stream', 'emmad-video-gallery' ); ?>"
					frameborder="0"
					allow="autoplay; fullscreen; picture-in-picture"
					allowfullscreen
					style="display:none;"
				></iframe>

				<div class="vg-controls">

					<div class="vg-left">
						<span><?php esc_html_e( 'NOW PLAYING', 'emmad-video-gallery' ); ?></span>
					</div>

					<div class="vg-center">

						<button
							type="button"
							id="vg-play"
							aria-label="<?php esc_attr_e( 'Play / Pause', 'emmad-video-gallery' ); ?>"
							aria-pressed="false"
						>
							<svg
								id="vg-play-icon"
								viewBox="0 0 24 24"
								width="18"
								height="18"
								fill="white"
								aria-hidden="true"
							>
								<polygon points="7,5 20,12 7,19"></polygon>
							</svg>
						</button>

						<span id="vg-current" aria-live="off">00:00</span>

						<div
							id="vg-progress"
							role="slider"
							tabindex="0"
							aria-label="<?php esc_attr_e( 'Video progress seek bar', 'emmad-video-gallery' ); ?>"
							aria-valuemin="0"
							aria-valuemax="100"
							aria-valuenow="0"
						>
							<div id="vg-progress-track"></div>
							<div id="vg-progress-fill"></div>
							<div id="vg-progress-thumb"></div>
						</div>

						<span id="vg-duration" aria-live="off">00:00</span>

						<button
							type="button"
							id="vg-mute"
							aria-label="<?php esc_attr_e( 'Mute / Unmute', 'emmad-video-gallery' ); ?>"
							aria-pressed="false"
						>
							<svg
								viewBox="0 0 24 24"
								width="18"
								height="18"
								fill="none"
								stroke="white"
								stroke-width="2"
								stroke-linecap="round"
								stroke-linejoin="round"
								aria-hidden="true"
							>
								<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
								<path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
								<path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
							</svg>
						</button>

					</div>

					<div class="vg-right">
						<span id="vg-title"></span>
					</div>

				</div>

			</div>

			<?php
		}

		return ob_get_clean();

	}
}

// Register shortcodes.
add_shortcode( 'emmad_video_gallery', 'emmad_video_gallery_shortcode' );
add_shortcode( 'video_gallery', 'emmad_video_gallery_shortcode' );
