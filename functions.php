<?php

namespace DevHub;

/**
 * Custom template tags for this theme.
 */
require __DIR__ . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require __DIR__ . '/inc/extras.php';

/**
 * Customizer additions.
 */
require __DIR__ . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require __DIR__ . '/inc/jetpack.php';

if ( ! function_exists( 'loop_pagination' ) ) {
	require __DIR__ . '/inc/loop-pagination.php';
}

if ( ! function_exists( 'breadcrumb_trail' ) ) {
	require __DIR__ . '/inc/breadcrumb-trail.php';
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}


add_action( 'init', __NAMESPACE__ . '\\init' );


function init() {

	register_post_types();
	register_taxonomies();
	add_action( 'widgets_init', __NAMESPACE__ . '\\widgets_init' );
	add_action( 'pre_get_posts', __NAMESPACE__ . '\\pre_get_posts' );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\theme_scripts_styles' );
	add_filter( 'post_type_link', __NAMESPACE__ . '\\method_permalink', 10, 2 );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );

	// Temporarily disable comments
	add_filter( 'comments_open', '__return_false' );
}


/**
 * widgets_init function.
 *
 * @access public
 * @return void
 */
function widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'wporg' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="box gray widget %2$s">',
		'after_widget'  => '</div></aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1><div class="widget-content">',
	) );
}

/**
 * @param \WP_Query $query
 */
function pre_get_posts( $query ) {

	if ( $query->is_main_query() && $query->is_post_type_archive() ) {
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
	}
}

/**
 * Register the function and class post types
 */
function register_post_types() {
	$supports = array(
		'comments',
		'custom-fields',
		'editor',
		'excerpt',
		'revisions',
		'title',
	);

	// Functions
	register_post_type( 'wp-parser-function', array(
		'has_archive' => 'reference/functions',
		'label'       => __( 'Functions', 'wporg' ),
		'labels'      => array(
			'name'               => __( 'Functions', 'wporg' ),
			'singular_name'      => __( 'Function', 'wporg' ),
			'all_items'          => __( 'Functions', 'wporg' ),
			'new_item'           => __( 'New Function', 'wporg' ),
			'add_new'            => __( 'Add New', 'wporg' ),
			'add_new_item'       => __( 'Add New Function', 'wporg' ),
			'edit_item'          => __( 'Edit Function', 'wporg' ),
			'view_item'          => __( 'View Function', 'wporg' ),
			'search_items'       => __( 'Search Functions', 'wporg' ),
			'not_found'          => __( 'No Functions found', 'wporg' ),
			'not_found_in_trash' => __( 'No Functions found in trash', 'wporg' ),
			'parent_item_colon'  => __( 'Parent Function', 'wporg' ),
			'menu_name'          => __( 'Functions', 'wporg' ),
		),
		'public'      => true,
		'rewrite'     => array(
			'feeds'      => false,
			'slug'       => 'reference/function',
			'with_front' => false,
		),
		'supports'    => $supports,
	) );

	// Methods
	add_rewrite_rule( 'method/([^/]+)/([^/]+)/?$', 'index.php?post_type=wp-parser-function&name=$matches[1]-$matches[2]', 'top' );

	// Classes
	register_post_type( 'wp-parser-class', array(
		'has_archive' => 'reference/classes',
		'label'       => __( 'Classes', 'wporg' ),
		'labels'      => array(
			'name'               => __( 'Classes', 'wporg' ),
			'singular_name'      => __( 'Class', 'wporg' ),
			'all_items'          => __( 'Classes', 'wporg' ),
			'new_item'           => __( 'New Class', 'wporg' ),
			'add_new'            => __( 'Add New', 'wporg' ),
			'add_new_item'       => __( 'Add New Class', 'wporg' ),
			'edit_item'          => __( 'Edit Class', 'wporg' ),
			'view_item'          => __( 'View Class', 'wporg' ),
			'search_items'       => __( 'Search Classes', 'wporg' ),
			'not_found'          => __( 'No Classes found', 'wporg' ),
			'not_found_in_trash' => __( 'No Classes found in trash', 'wporg' ),
			'parent_item_colon'  => __( 'Parent Class', 'wporg' ),
			'menu_name'          => __( 'Classes', 'wporg' ),
		),
		'public'      => true,
		'rewrite'     => array(
			'feeds'      => false,
			'slug'       => 'reference/class',
			'with_front' => false,
		),
		'supports'    => $supports,
	) );

	// Hooks
	register_post_type( 'wp-parser-hook', array(
		'has_archive' => 'reference/hooks',
		'label'       => __( 'Hooks', 'wporg' ),
		'labels'      => array(
			'name'               => __( 'Hooks', 'wporg' ),
			'singular_name'      => __( 'Hook', 'wporg' ),
			'all_items'          => __( 'Hooks', 'wporg' ),
			'new_item'           => __( 'New Hook', 'wporg' ),
			'add_new'            => __( 'Add New', 'wporg' ),
			'add_new_item'       => __( 'Add New Hook', 'wporg' ),
			'edit_item'          => __( 'Edit Hook', 'wporg' ),
			'view_item'          => __( 'View Hook', 'wporg' ),
			'search_items'       => __( 'Search Hooks', 'wporg' ),
			'not_found'          => __( 'No Hooks found', 'wporg' ),
			'not_found_in_trash' => __( 'No Hooks found in trash', 'wporg' ),
			'parent_item_colon'  => __( 'Parent Hook', 'wporg' ),
			'menu_name'          => __( 'Hooks', 'wporg' ),
		),
		'public'      => true,
		'rewrite'     => array(
			'feeds'      => false,
			'slug'       => 'reference/hook',
			'with_front' => false,
		),
		'supports'    => $supports,
	) );

	// Methods
	register_post_type( 'wp-parser-method', array(
		'has_archive' => 'reference/methods',
		'label'       => __( 'Methods', 'wporg' ),
		'labels'      => array(
			'name'               => __( 'Methods', 'wporg' ),
			'singular_name'      => __( 'Method', 'wporg' ),
			'all_items'          => __( 'Methods', 'wporg' ),
			'new_item'           => __( 'New Method', 'wporg' ),
			'add_new'            => __( 'Add New', 'wporg' ),
			'add_new_item'       => __( 'Add New Method', 'wporg' ),
			'edit_item'          => __( 'Edit Method', 'wporg' ),
			'view_item'          => __( 'View Method', 'wporg' ),
			'search_items'       => __( 'Search Methods', 'wporg' ),
			'not_found'          => __( 'No Methods found', 'wporg' ),
			'not_found_in_trash' => __( 'No Methods found in trash', 'wporg' ),
			'parent_item_colon'  => __( 'Parent Method', 'wporg' ),
			'menu_name'          => __( 'Methods', 'wporg' ),
		),
		'public'      => true,
		'rewrite'     => array(
			'feeds'      => false,
			'slug'       => 'method',
			'with_front' => false,
		),
		'supports'    => $supports,
	) );
}

/**
 * Register the file and @since taxonomies
 */
function register_taxonomies() {
	// Files
	register_taxonomy( 'wp-parser-source-file', array( 'wp-parser-class', 'wp-parser-function', 'wp-parser-hook', 'wp-parser-method' ), array(
		'label'                 => __( 'Files', 'wporg' ),
		'labels'                => array(
			'name'                       => __( 'Files', 'wporg' ),
			'singular_name'              => _x( 'File', 'taxonomy general name', 'wporg' ),
			'search_items'               => __( 'Search Files', 'wporg' ),
			'popular_items'              => null,
			'all_items'                  => __( 'All Files', 'wporg' ),
			'parent_item'                => __( 'Parent File', 'wporg' ),
			'parent_item_colon'          => __( 'Parent File:', 'wporg' ),
			'edit_item'                  => __( 'Edit File', 'wporg' ),
			'update_item'                => __( 'Update File', 'wporg' ),
			'add_new_item'               => __( 'New File', 'wporg' ),
			'new_item_name'              => __( 'New File', 'wporg' ),
			'separate_items_with_commas' => __( 'Files separated by comma', 'wporg' ),
			'add_or_remove_items'        => __( 'Add or remove Files', 'wporg' ),
			'choose_from_most_used'      => __( 'Choose from the most used Files', 'wporg' ),
			'menu_name'                  => __( 'Files', 'wporg' ),
		),
		'public'                => true,
		'rewrite'               => array( 'slug' => 'reference/files' ),
		'sort'                  => false,
		'update_count_callback' => '_update_post_term_count',
	) );

	// Package
	register_taxonomy( 'wp-parser-package', array( 'wp-parser-class', 'wp-parser-function', 'wp-parser-hook', 'wp-parser-method' ), array(
		'hierarchical'          => true,
		'label'                 => '@package',
		'public'                => true,
		'rewrite'               => array( 'slug' => 'reference/package' ),
		'sort'                  => false,
		'update_count_callback' => '_update_post_term_count',
	) );

	// @since
	register_taxonomy( 'wp-parser-since', array( 'wp-parser-class', 'wp-parser-function', 'wp-parser-hook', 'wp-parser-method' ), array(
		'hierarchical'          => true,
		'label'                 => __( '@since', 'wporg' ),
		'public'                => true,
		'rewrite'               => array( 'slug' => 'reference/since' ),
		'sort'                  => false,
		'update_count_callback' => '_update_post_term_count',
	) );
}

function method_permalink( $link, $post ) {
	if ( $post->post_type !== 'wp-parser-function' || $post->post_parent == 0 )
		return $link;

	list( $class, $method ) = explode( '-', $post->post_name );
	$link = home_url( user_trailingslashit( "method/$class/$method" ) );
	return $link;
}

function theme_scripts_styles() {
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'open-sans', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,300,600' );
	wp_enqueue_style( 'wporg-developer-style', get_stylesheet_uri() );
	wp_enqueue_style( 'wp-dev-sass-compiled', get_template_directory_uri() . '/main.css', array( 'wporg-developer-style' ) );
	wp_enqueue_script( 'wporg-developer-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'wporg-developer-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}