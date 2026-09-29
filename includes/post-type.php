<?php
/**
 * Register Custom Post Type and Taxonomy.
 *
 * @package    Emmad_Video_Gallery
 * @subpackage Emmad_Video_Gallery/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Register Custom Post Type: vg_video
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_register_post_type' ) ) {
	/**
	 * Registers the custom post type for videos.
	 *
	 * @return void
	 */
	function emmad_register_post_type() {

		$labels = array(
			'name'                  => _x( 'Videos', 'Post type general name', 'emmad-video-gallery' ),
			'singular_name'         => _x( 'Video', 'Post type singular name', 'emmad-video-gallery' ),
			'menu_name'             => _x( 'Videos', 'Admin Menu text', 'emmad-video-gallery' ),
			'name_admin_bar'        => _x( 'Video', 'Add New on Toolbar', 'emmad-video-gallery' ),
			'add_new'               => __( 'Add New', 'emmad-video-gallery' ),
			'add_new_item'          => __( 'Add New Video', 'emmad-video-gallery' ),
			'new_item'              => __( 'New Video', 'emmad-video-gallery' ),
			'edit_item'             => __( 'Edit Video', 'emmad-video-gallery' ),
			'view_item'             => __( 'View Video', 'emmad-video-gallery' ),
			'all_items'             => __( 'All Videos', 'emmad-video-gallery' ),
			'search_items'          => __( 'Search Videos', 'emmad-video-gallery' ),
			'parent_item_colon'     => __( 'Parent Videos:', 'emmad-video-gallery' ),
			'not_found'             => __( 'No videos found.', 'emmad-video-gallery' ),
			'not_found_in_trash'    => __( 'No videos found in Trash.', 'emmad-video-gallery' ),
			'featured_image'        => __( 'Video Thumbnail', 'emmad-video-gallery' ),
			'set_featured_image'    => __( 'Set video thumbnail', 'emmad-video-gallery' ),
			'remove_featured_image' => __( 'Remove video thumbnail', 'emmad-video-gallery' ),
			'use_featured_image'    => __( 'Use as video thumbnail', 'emmad-video-gallery' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-video-alt3',
			'show_in_rest'        => true,
			'supports'            => array(
				'title',
				'thumbnail',
			),
			'rewrite'             => array(
				'slug'       => 'videos',
				'with_front' => false,
			),
			'has_archive'         => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		);

		register_post_type( 'vg_video', $args );

	}
}

add_action( 'init', 'emmad_register_post_type' );

/*
|--------------------------------------------------------------------------
| Register Taxonomy: video_category
|--------------------------------------------------------------------------
*/

if ( ! function_exists( 'emmad_register_taxonomy' ) ) {
	/**
	 * Registers the taxonomy for video categories.
	 *
	 * @return void
	 */
	function emmad_register_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Video Categories', 'taxonomy general name', 'emmad-video-gallery' ),
			'singular_name'              => _x( 'Video Category', 'taxonomy singular name', 'emmad-video-gallery' ),
			'search_items'               => __( 'Search Categories', 'emmad-video-gallery' ),
			'popular_items'              => __( 'Popular Categories', 'emmad-video-gallery' ),
			'all_items'                  => __( 'All Categories', 'emmad-video-gallery' ),
			'parent_item'                => __( 'Parent Category', 'emmad-video-gallery' ),
			'parent_item_colon'          => __( 'Parent Category:', 'emmad-video-gallery' ),
			'edit_item'                  => __( 'Edit Category', 'emmad-video-gallery' ),
			'update_item'                => __( 'Update Category', 'emmad-video-gallery' ),
			'add_new_item'               => __( 'Add New Category', 'emmad-video-gallery' ),
			'new_item_name'              => __( 'New Category Name', 'emmad-video-gallery' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'emmad-video-gallery' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'emmad-video-gallery' ),
			'choose_from_most_used'      => __( 'Choose from the most used categories', 'emmad-video-gallery' ),
			'not_found'                  => __( 'No categories found.', 'emmad-video-gallery' ),
			'menu_name'                  => __( 'Categories', 'emmad-video-gallery' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug' => 'video-category',
			),
		);

		register_taxonomy( 'video_category', array( 'vg_video' ), $args );

	}
}

add_action( 'init', 'emmad_register_taxonomy' );

