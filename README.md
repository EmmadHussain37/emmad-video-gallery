# Emmad Video Gallery

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759b.svg)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4.svg)](https://www.php.net/)
[![Release](https://img.shields.io/badge/Release-1.0.0-emerald.svg)](https://github.com/EmmadHussain37/emmad-video-gallery/releases)

A lightweight and responsive WordPress video gallery plugin. Effortlessly publish video showcases, portfolios, and media galleries using a dedicated custom post type and accessible shortcode.

Features an immersive fullscreen custom video player with live timeline scrubbing, volume controls, custom branding logo, and instant animated category filtering.

---

## Features

- **Video Gallery Management**: Dedicated `vg_video` custom post type with featured images, video URL meta box, and category taxonomy.
- **YouTube Support**: Handles standard watch URLs, short `youtu.be` links, embeds, and YouTube Shorts.
- **Vimeo Support**: Seamless embed for standard Vimeo links and player URLs.
- **Self-Hosted Videos**: HTML5 playback for MP4 and WebM video files with native fullscreen player controls.
- **Responsive Player**: Distraction-free, responsive overlay player with custom branding logo and mobile touch scrubbing.
- **Instant Category Filtering**: Animated frontend category filter buttons without page reloads.
- **WordPress Shortcode Support**: Clean shortcode `[emmad_video_gallery]` with customizable columns, posts count, and category filters (plus backward-compatible alias `[video_gallery]`).
- **Accessibility (WCAG Compliant)**: Full keyboard navigation (Space/K for play/pause, M for mute, Left/Right arrow keys for seek, Escape to close) and ARIA attributes for screen readers.
- **Performance Optimized**: Assets are conditionally enqueued **only** on pages where the video gallery shortcode is executed.
- **Secure WordPress Coding Standards**: Strict nonces, capability checks (`edit_post`, `manage_options`), input sanitization, and output escaping.
- **Translation Ready**: Fully internationalized with included Gettext catalog (`languages/emmad-video-gallery.pot`).

---

## Requirements

- **WordPress**: 6.0 or higher
- **PHP**: 7.4 or higher (PHP 8.0, 8.1, 8.2, 8.3 compatible)

---

## Installation

### Method 1: Manual Upload
1. Download the latest release `emmad-video-gallery.zip`.
2. In your WordPress admin dashboard, navigate to **Plugins → Add New → Upload Plugin**.
3. Select the `.zip` file and click **Install Now**.
4. Click **Activate Plugin**.

### Method 2: Git Clone
Clone directly into your WordPress plugins directory:
```bash
cd wp-content/plugins/
git clone https://github.com/EmmadHussain37/emmad-video-gallery.git
```
Then activate **Emmad Video Gallery** from the **Plugins** screen in your WordPress admin.

---

## Getting Started

1. Go to **Videos → Add New** in your WordPress dashboard.
2. Enter the title of your video.
3. In the **Video Details** meta box, paste your video URL (YouTube, Vimeo, or self-hosted MP4/WebM).
4. Assign a **Featured Image** for the video cover thumbnail (optional; a fallback placeholder is provided if omitted).
5. Assign **Video Categories** to enable frontend category filtering.
6. Click **Publish**.
7. Embed the gallery anywhere using the shortcode:
   ```text
   [emmad_video_gallery]
   ```

---

## Shortcode Reference

### Basic Usage
```text
[emmad_video_gallery]
```

### Attributes

| Attribute | Default | Description |
| :--- | :--- | :--- |
| `posts` | `-1` | Number of videos to display (`-1` for all videos, or an integer like `6`, `9`, `12`). |
| `columns` | `3` | Number of gallery columns on desktop (`1`, `2`, `3`, `4`, `5`, `6`). |
| `category` | `""` | Comma-separated category slugs to filter (e.g., `trailers,interviews`). |
| `show_filters` | `"yes"` | Display category filter buttons above the grid (`yes` or `no`). |

### Examples

Display 6 videos in a 3-column layout:
```text
[emmad_video_gallery posts="6" columns="3"]
```

Display only tutorials without filter buttons:
```text
[emmad_video_gallery category="tutorials" show_filters="no"]
```

---

## Folder Structure

```text
emmad-video-gallery/
├── emmad-video-gallery.php            # Main plugin bootstrap file
├── readme.txt                         # WordPress.org plugin directory readme
├── README.md                          # GitHub repository documentation
├── license.txt                        # GNU General Public License v2
├── uninstall.php                      # Plugin uninstall and cleanup handler
├── .gitignore                         # Git exclusion rules
├── admin/
│   ├── admin-columns.php              # Custom admin list table columns & source badges
│   ├── meta-box.php                   # Video URL meta box handler
│   └── settings.php                   # Settings page with branding logo & documentation
├── public/
│   ├── assets.php                     # Script & style registration and conditional loader
│   └── shortcode.php                  # Gallery query and responsive player template
├── includes/
│   └── post-type.php                  # Post type (vg_video) & taxonomy registration
├── assets/
│   ├── css/
│   │   ├── admin.css                  # Admin interface styling
│   │   └── gallery.css                # Scoped, accessible, responsive player styles
│   ├── js/
│   │   ├── admin.js                   # Media uploader & logo preview handler
│   │   └── gallery.js                 # Accessible player, touch scrubbing, keyboard nav
│   └── images/
│       ├── default-logo.svg           # Clean SVG fallback player watermark
│       └── placeholder.webp           # Default video thumbnail placeholder
└── languages/
    └── emmad-video-gallery.pot        # Gettext translation template
```

---

## Security & WordPress Standards

- **XSS Prevention**: All database outputs and attributes are escaped with `esc_html()`, `esc_attr()`, `esc_url()`, and `the_title_attribute()`.
- **CSRF Defense**: Nonce verification (`wp_nonce_field` and `wp_verify_nonce`) on all form submissions and meta box updates.
- **Authorization**: Strict capability checks (`edit_post`, `manage_options`, `activate_plugins`).
- **Direct Access Guard**: Every PHP file is protected against direct web execution with `defined('ABSPATH') || exit;`.
- **Sanitization**: All user inputs sanitized with `sanitize_text_field()`, `esc_url_raw()`, and `absint()`.
- **Performance**: Queries optimized with `'no_found_rows' => true`, `'update_post_meta_cache' => false`, `'update_post_term_cache' => false`.

---

## Keyboard Shortcuts (Player Modal)

When the fullscreen player is open:
- <kbd>Space</kbd> / <kbd>K</kbd> : Toggle Play / Pause
- <kbd>M</kbd> : Toggle Mute / Unmute
- <kbd>←</kbd> / <kbd>→</kbd> : Seek backward / forward 5 seconds
- <kbd>Esc</kbd> : Close video player and return focus to thumbnail

---

## License

This plugin is free and open-source software licensed under the [GNU General Public License v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

```text
Copyright (C) 2026 Emmad Hussain

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

---

## Author

**Emmad Hussain**  
- GitHub: [@EmmadHussain37](https://github.com/EmmadHussain37)
