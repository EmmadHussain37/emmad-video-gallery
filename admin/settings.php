<?php
/**
 * Settings Page Handler.
 *
 * @package    Emmad_Video_Gallery
 * @subpackage Emmad_Video_Gallery/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Register Settings Page
|--------------------------------------------------------------------------
*/

/**
 * Registers the plugin settings menu page.
 *
 * @return void
 */
function emmaviga_register_settings_page() {

	add_submenu_page(
		'edit.php?post_type=emmaviga_video',
		__( 'Emmad Video Gallery Settings', 'emmad-video-gallery' ),
		__( 'Settings', 'emmad-video-gallery' ),
		'manage_options',
		'emmaviga-video-gallery-settings',
		'emmaviga_settings_page'
	);

}

add_action( 'admin_menu', 'emmaviga_register_settings_page' );

/*
|--------------------------------------------------------------------------
| Register Setting
|--------------------------------------------------------------------------
*/

/**
 * Registers plugin settings with the WordPress Settings API.
 *
 * @return void
 */
function emmaviga_register_settings() {

	register_setting(
		'emmaviga_settings_group',
		'emmaviga_player_logo',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);

}

add_action( 'admin_init', 'emmaviga_register_settings' );

/*
|--------------------------------------------------------------------------
| Settings Page Callback
|--------------------------------------------------------------------------
*/

/**
 * Renders the Settings page in the WordPress admin.
 *
 * @return void
 */
function emmaviga_settings_page() {

	// Ensure proper capability.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'emmad-video-gallery' ) );
	}

	// Retrieve saved option.
	$player_logo = get_option( 'emmaviga_player_logo', '' );

	?>
	<div class="wrap emmaviga-settings-wrap">

		<h1><?php esc_html_e( 'Emmad Video Gallery Settings', 'emmad-video-gallery' ); ?></h1>

		<?php settings_errors(); ?>

		<div style="display:flex;gap:24px;flex-wrap:wrap;margin-top:20px;">

			<!-- Main Settings Column -->
			<div style="flex:1;min-width:320px;max-width:800px;">
				<div class="card" style="margin:0 0 20px 0;padding:20px;max-width:100%;">
					<h2 class="title" style="margin-top:0;"><?php esc_html_e( 'Player Branding', 'emmad-video-gallery' ); ?></h2>
					<p class="description">
						<?php esc_html_e( 'Configure the watermark or branding logo displayed in the top header of the fullscreen video player.', 'emmad-video-gallery' ); ?>
					</p>

					<form method="post" action="options.php">
						<?php settings_fields( 'emmaviga_settings_group' ); ?>

						<table class="form-table" role="presentation">
							<tr>
								<th scope="row">
									<label for="emmaviga_player_logo">
										<?php esc_html_e( 'Player Logo URL', 'emmad-video-gallery' ); ?>
									</label>
								</th>
								<td>
									<div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
										<input
											type="url"
											id="emmaviga_player_logo"
											name="emmaviga_player_logo"
											value="<?php echo esc_url( $player_logo ); ?>"
											class="regular-text"
											placeholder="https://example.com/logo.png"
										/>
										<button
											type="button"
											class="button button-secondary"
											id="emmaviga_upload_logo"
										>
											<span class="dashicons dashicons-upload" style="vertical-align:text-top;margin-right:3px;"></span>
											<?php esc_html_e( 'Upload Logo', 'emmad-video-gallery' ); ?>
										</button>
										<button
											type="button"
											class="button button-link-delete"
											id="emmaviga_remove_logo"
											style="<?php echo empty( $player_logo ) ? 'display:none;' : ''; ?>"
										>
											<?php esc_html_e( 'Remove', 'emmad-video-gallery' ); ?>
										</button>
									</div>

									<p class="description" style="margin-top:8px;">
										<?php esc_html_e( 'Recommended formats: transparent PNG, WebP, or SVG. Suggested height: 30px to 60px.', 'emmad-video-gallery' ); ?>
									</p>

									<!-- Logo Preview Box -->
									<div
										id="emmaviga_logo_preview_wrap"
										style="margin-top:16px;background:#18181b;padding:16px;border-radius:6px;display:inline-block;max-width:320px;text-align:center;<?php echo empty( $player_logo ) ? 'display:none;' : ''; ?>"
									>
										<span style="display:block;font-size:11px;color:#a1a1aa;margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;">
											<?php esc_html_e( 'Preview on Player Background', 'emmad-video-gallery' ); ?>
										</span>
										<img
											id="emmaviga_logo_preview"
											src="<?php echo esc_url( $player_logo ); ?>"
											alt="<?php esc_attr_e( 'Player Logo Preview', 'emmad-video-gallery' ); ?>"
											style="max-height:50px;max-width:240px;height:auto;width:auto;display:inline-block;"
										/>
									</div>
								</td>
							</tr>
						</table>

						<?php submit_button( __( 'Save Changes', 'emmad-video-gallery' ) ); ?>
					</form>
				</div>
			</div>

			<!-- Documentation Sidebar Column -->
			<div style="flex:0 0 320px;min-width:280px;">
				<div class="card" style="margin:0 0 20px 0;padding:20px;">
					<h2 class="title" style="margin-top:0;"><?php esc_html_e( 'Shortcode Reference', 'emmad-video-gallery' ); ?></h2>
					<p>
						<?php esc_html_e( 'Embed your video gallery on any page, post, or widget area using:', 'emmad-video-gallery' ); ?>
					</p>
					<code style="display:block;padding:8px;background:#f3f4f6;border-radius:4px;user-select:all;font-size:13px;">[emmaviga_gallery]</code>

					<h3 style="margin-top:20px;font-size:14px;"><?php esc_html_e( 'Custom Attributes', 'emmad-video-gallery' ); ?></h3>
					<ul style="list-style:disc;margin-left:18px;font-size:13px;line-height:1.6;">
						<li><strong>columns="3"</strong> &ndash; 1 to 6 columns.</li>
						<li><strong>posts="6"</strong> &ndash; Number of videos (-1 for all).</li>
						<li><strong>category="interviews"</strong> &ndash; Category slug(s).</li>
						<li><strong>show_filters="yes"</strong> &ndash; 'yes' or 'no'.</li>
					</ul>

					<h3 style="margin-top:16px;font-size:14px;"><?php esc_html_e( 'Example', 'emmad-video-gallery' ); ?></h3>
					<code style="display:block;padding:8px;background:#f3f4f6;border-radius:4px;user-select:all;font-size:12px;">[emmaviga_gallery posts="6" columns="3"]</code>

					<p class="description" style="margin-top:16px;">
						<em><?php esc_html_e( 'Use [emmaviga_gallery] to embed your videos anywhere on your site.', 'emmad-video-gallery' ); ?></em>
					</p>
				</div>
			</div>

		</div>

	</div>
	<?php

}
