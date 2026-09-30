=== Emmad Video Gallery ===
Contributors: emmad372080
Donate link: https://github.com/EmmadHussain37/emmad-video-gallery
Tags: video-gallery, video-player, youtube, vimeo, mp4
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight and powerful video gallery plugin for WordPress.

== Description ==

Emmad Video Gallery is a fast, secure, and fully responsive video gallery plugin for WordPress. Effortlessly publish video portfolios, video showcases, and multimedia galleries using a dedicated custom post type and a clean, accessible shortcode.

Features an immersive fullscreen custom video player with smooth controls, live scrubber, volume control, custom player branding logo, and instant category filtering.

= Key Features =

* Multi-Source Video Support: Seamlessly embed YouTube videos, Vimeo videos, or self-hosted MP4 files.
* Custom Fullscreen Player: Distraction-free, responsive overlay video player with custom branding.
* Instant Category Filtering: Animated, instant frontend filtering without page reloads.
* Fully Responsive & Touch-Ready: Optimized for all screen sizes with touch-scrubbing on mobile devices.
* Accessibility Focused: Full keyboard navigation (Space, Escape, Arrow keys, Mute) and ARIA attributes for screen readers.
* Custom Player Logo: Upload your own watermark or logo via WordPress Media Library in the settings page.
* Optimized Performance: CSS and JavaScript are strictly enqueued only on pages where the video gallery is rendered.
* Block Editor & Page Builder Compatible: Fully compatible with Gutenberg Shortcode blocks, Elementor, Divi, and classic themes.
* Secure & Standards Compliant: Strict nonces, capability validation, input sanitization, and output escaping across all operations.
* Translation Ready: Fully internationalized with an included .pot translation catalog.

== Shortcodes ==

Display your complete video gallery:

`[emmaviga_gallery]`

(Note: `[emmaviga_video_gallery]` is also supported).

= Shortcode Attributes =

* `posts` - Number of videos to display (-1 for all videos, or any integer like 6, 9, 12). Default: -1.
* `columns` - Number of columns on desktop (1, 2, 3, 4, 5, 6). Default: 3.
* `category` - Filter by one or more category slugs, separated by commas (e.g., trailers,interviews). Default: all categories.
* `show_filters` - Display category filter buttons (yes or no). Default: yes.

= Examples =

Display 6 videos in a 3-column grid:

`[emmaviga_gallery posts="6" columns="3"]`

Display videos from specific categories without filter buttons:

`[emmaviga_gallery category="tutorials" show_filters="no"]`

== Installation ==

= From your WordPress dashboard =
1. Visit Plugins > Add New.
2. Search for "Emmad Video Gallery".
3. Click "Install Now" and then "Activate".

= Manual upload =
1. Download `emmad-video-gallery.zip`.
2. Navigate to Plugins > Add New > Upload Plugin.
3. Select the zip file and click "Install Now".
4. Activate the plugin.

= Getting Started =
1. Navigate to Videos > Add New in your WordPress dashboard.
2. Enter the title of your video.
3. In the Video Details meta box, enter your video URL (YouTube, Vimeo, or self-hosted MP4).
4. Set a Featured Image as the video cover thumbnail (optional; a clean placeholder is provided if omitted).
5. Assign Video Categories to enable frontend category filters.
6. Click Publish.
7. Create or edit any page and add the shortcode `[emmaviga_gallery]`.

== Frequently Asked Questions ==

= Which video services and formats are supported? =
The plugin supports self-hosted MP4 and WebM videos, YouTube videos (standard watch links, youtu.be, embeds, shorts), and Vimeo videos.

= How do I upload a custom player logo? =
Go to Videos > Settings in your WordPress dashboard. Click Upload Logo to select an image from your WordPress Media Library, then click Save Changes. Your logo will display at the top of the fullscreen player.

= Does this plugin slow down my website? =
No. Emmad Video Gallery uses conditional asset loading. Scripts and stylesheets are loaded only on pages where the gallery shortcode is actually executed.

= Is the player keyboard accessible? =
Yes. When the video player modal is open, press Escape to close, Space to toggle play/pause, M to toggle mute, and Left/Right Arrow keys to seek backward or forward.

= Can I use this with page builders like Elementor or Divi? =
Yes. Simply insert a Shortcode widget or module and enter `[emmaviga_gallery]`.

= Will my videos be deleted if I deactivate the plugin? =
No. Deactivating the plugin preserves all your videos and categories. Content is only removed if you explicitly delete the plugin via the WordPress Plugins screen.

== Screenshots ==

1. Frontend responsive video gallery grid with animated category filters.
2. Immersive custom fullscreen video player with branded logo, seeker, and controls.
3. WordPress admin Video edit screen showing the clean Video Details meta box.
4. All Videos list view with preview thumbnails and direct video links.
5. Plugin Settings page for uploading a custom player logo with instant live preview.

== Changelog ==

= 1.0.0 =
* Initial official release for WordPress.org.
* Rebranded to Emmad Video Gallery (`emmad-video-gallery`).
* Restructured architecture into clean admin, public, includes, and assets modules.
* Added conditional frontend asset enqueuing to optimize page load speeds.
* Hardened security: comprehensive nonces, strict capability checks, input sanitization, and contextual output escaping.
* Fixed admin logo upload JavaScript selectors and added live preview and remove buttons.
* Enhanced fullscreen player with touch scrubbing on mobile devices and keyboard accessibility.
* Fully compliant with WordPress.org prefix requirements using unique prefix emmaviga.
* Full internationalization with included languages/emmad-video-gallery.pot.
* PHP 8.0, 8.1, 8.2, and 8.3 compatibility verified.

== Upgrade Notice ==

= 1.0.0 =
Initial official release. Production ready, secure, and optimized for WordPress 6.0 and higher.

