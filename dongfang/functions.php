<?php
/**
 * Twenty Twelve functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 
add_action( 'init', 'init_components', 9999 );
add_action( 'admin_menu', 'custom_admin_menu');
add_action( 'wp_before_admin_bar_render', 'custom_admin_bar' );
add_action( 'login_head',  'custom_login_logo' );
add_action( 'wp_dashboard_setup', 'custom_admin_dashboard' );
add_action( 'admin_head-index.php', 'custom_dashboard' );
add_action( 'admin_print_styles-post-new.php', 'posttype_admin_css' );
add_action( 'admin_print_styles-post.php', 'posttype_admin_css' );
add_action( 'page_attributes_dropdown_pages_args','limit_post_hierarchical_level');

add_filter( 'cmb_meta_boxes', 'products_metaboxes' );
add_filter( 'enter_title_here', 'change_default_title' );
add_filter( 'admin_footer_text', 'remove_footer_admin' );
add_filter( 'update_footer', 'custom_footer_version', 9999 );
add_filter( 'gettext', 'rename_admin_menu_items' );
add_filter( 'ngettext', 'rename_admin_menu_items' );
add_filter( 'admin_title', 'custom_admin_title', 10, 2);
add_filter( 'contextual_help', 'remove_admin_help_tab', 999, 3 );

function include_libs() {
  if ( !class_exists( 'cmb_Meta_Box' ) ) {
    require_once( 'libs/Custom-Metaboxes-and-Fields-for-WordPress/init.php' );
  }
}

function init_components() {
  // init custom page post attributes
  include_libs();
  
  // create custom taxonomies
  create_custom_taxonomies();
  
  // create custom post type
  create_custom_post_type(); 
}

function create_custom_taxonomies() {
  // Add new "Locations" taxonomy to Posts
	register_taxonomy('user_categories', 'post', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Categories', 'taxonomy general name' ),
			'singular_name' => _x( 'Category', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Categories' ),
			'all_items' => __( 'All Categories' ),
			'parent_item' => __( 'Parent Category' ),
			'parent_item_colon' => __( 'Parent Category:' ),
			'menu_name' => __( 'Categories' ),
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'categories', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
}

function limit_post_hierarchical_level($a) {
  $a['depth'] = 8;
  return $a;
}

function create_custom_post_type() {
  
  register_post_type('custom_post_fixes',
		array(
		  'labels' => array(
				'name' => __( '报修信息' ),
				'singular_name' => __( '报修信息' ),
				'menu_name' => __( '报修信息' ),
				'all_items' => __( '所有报修信息' ),
				'add_new' => __( '新增维护指导' ),
				'add_new_item' => __( '新增维护指导' ),
				'edit_item' => __( '编辑维护指导' )
			),
		  'public' => true,
		  'taxonomies' => array(
		    'user_categories'
		  ),
		  'supports' => array(
		    'title',
		    'editor',
		  ),
		)
	);
	
  register_post_type('custom_post_guides',
		array(
		  'labels' => array(
				'name' => __( '维护指导' ),
				'singular_name' => __( '维护指导' ),
				'menu_name' => __( '维护指导' ),
				'all_items' => __( '所有维护指导' ),
				'add_new' => __( '新增维护指导' ),
				'add_new_item' => __( '新增维护指导' ),
				'edit_item' => __( '编辑维护指导' )
			),
		  'public' => true,
		  'has_archive' => true,
		  'hierarchical' => true,
		  'supports' => array(
		    'title',
		    'editor',
		    'page-attributes',
		  ),
		)
	);

	register_post_type('custom_post_sources',
		array(
		  'labels' => array(
				'name' => __( '资料下载' ),
				'menu_name' => __( '资料下载' ),
				'singular_name' => __( '资料下载' ),
				'all_items' => __( '所有资料' ),
				'add_new' => __( '新增资料' ),
				'add_new_item' => __( '新增资料' ),
				'edit_item' => __( '编辑资料' )
			),
		  'public' => true,
		  'supports' => array(
		    'title',
		    'editor',
		  ),
		)
	);
	
	register_post_type('custom_post_cases',
		array(
		  'labels' => array(
				'name' => __( '案例鉴赏' ),
				'menu_name' => __( '案例鉴赏' ),
				'singular_name' => __( '案例鉴赏' ),
				'all_items' => __( '所有案例' ),
				'add_new' => __( '新增案例' ),
				'add_new_item' => __( '新增案例' ),
				'edit_item' => __( '编辑案例' )
			),
		  'public' => true,
		  'supports' => array(
		    'title',
		    'editor',
		  ),
		)
	);
	
	register_post_type('custom_post_products',
		array(
		  'labels' => array(
				'name' => __( '东方产品' ),
				'menu_name' => __( '东方产品' ),
				'singular_name' => __( '东方产品' ),
				'all_items' => __( '所有产品' ),
				'add_new' => __( '新增产品' ),
				'add_new_item' => __( '新增产品' ),
				'edit_item' => __( '编辑产品' )
			),
		  'public' => true,
		  'supports' => array(
		    'title',
		    'editor',
		  ),
		)
	);
}

function custom_admin_menu() {
  
  remove_admin_menu_items();
  
  remove_update_notification();
  
}

function rename_admin_menu_items($menu) {
  $menu = str_ireplace( '仪表盘', '主页', $menu );
  return $menu;
}

function remove_admin_menu_items() {
  if ( function_exists('remove_menu_page') ) {
    remove_menu_page('link-manager.php'); // Links
    remove_menu_page('edit.php'); // Posts
    remove_menu_page('edit.php?post_type=page'); // Pages
    remove_menu_page('upload.php'); // Media
    remove_menu_page('edit-comments.php'); // Comments
    remove_menu_page('tools.php'); // Tools
    //remove_menu_page('plugins.php'); // Plugins
    remove_menu_page('themes.php'); // Appearances
    //remove_menu_page('options-general.php'); // Settings
  }
  
  global $submenu;
  unset($submenu['index.php'][10]); // Removes Updates
}

function products_metaboxes( $meta_boxes ) {
  $products_array = array();
  $products_array[] = array( 'name' => '请选择一个东方产品', 'value' => 'null' );
  
  $arg = array(
        'orderby' => 'menu_order',
        'post_type' => 'custom_post_products',
        'post_status' => 'publish',
        'parent' => 0
      );
      
  $posts_array = get_posts($arg);
  
  foreach($posts_array as $post) {
    $products_array[] = array( 'name' => get_the_title($post->ID), 'value' => $post->ID );
  }
  
  $prefix = 'products_';
  $meta_boxes[$prefix . 'metabox'] = array(
    'id' => $prefix . 'metabox',
    'title' => '选择产品',
    'pages' => array('custom_post_guides'),
    'context' => 'normal',
    'priority' => 'high',
    'show_names' => true,
    'fields' => array(
      array(
        'name' => '产品',
        'desc' => '为本条维护指导信息指定对应的产品；如果选项为空，请现在“东方产品”栏目中添加产品',
        'id' => $prefix . 'fields',
        'type' => 'select',
				'options' => $products_array,
      ),
    ),
  );
  return $meta_boxes;
}

function change_default_title( $title ){
  $screen = get_current_screen();
  if  ( 'custom_post_products' == $screen->post_type ) {
    $title = '在此键入产品名称';
  }
  return $title;
}

function custom_admin_bar() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');
  $wp_admin_bar->remove_menu('view');
  $wp_admin_bar->remove_menu('site-name');
  $wp_admin_bar->remove_menu('updates'); 
  $wp_admin_bar->remove_menu('comments');
  $wp_admin_bar->remove_menu('view-site');
}

function remove_footer_admin () 
{
    echo '<span id="footer-thankyou">由<a href="http://www.dfe-e5000.com" target="_blank">烟台东方科技环保节能有限公司</a>制作</span>';
}

function custom_login_logo()
{
    echo '<style type="text/css"> h1 a {  background-image:url(' . get_bloginfo('template_directory') . '/img/logo.png)  !important; } </style>';
}

function custom_footer_version() {
  return '';
}

function custom_admin_dashboard() {
  remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal');
  remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal');
  remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
  
  remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side');  // recent drafts
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
  
  global $wp_meta_boxes;
  wp_add_dashboard_widget('custom_dashboard_widget', '信息发布', 'render_custom_dashboard_widget');
}

function custom_dashboard()
{
    // Check if Welcome Panel is being displayed
    $option = get_user_meta( get_current_user_id(), 'show_welcome_panel', true );
    if( !$option )
        return;
    ?>
    <style type="text/css">
        /*
         * Hide the Welcome Panel and the "dismiss" message at the bottom
         */ 
        #welcome-panel {
          opacity:0.01;
        } 
        p.welcome-panel-dismiss {
          display:none
        }
    </style>
    <script type="text/javascript">
    jQuery(document).ready( function($) 
    {
      /*
       * Left side image and text
       * - changing CSS properties and raw Html content of the Div
       */
      $('div.wp-badge').hide();
      $('div.welcome-panel-content h3').css('margin-left', 0);
      $('div.welcome-panel-content .about-description').css('margin-left', 0);
      $('div.welcome-panel-content .about-description').css('height', 50);

      $('a.welcome-panel-close').hide();
      $('div.welcome-panel-content .welcome-panel-column-container').hide();

      $('div.welcome-panel-content h3').html('欢迎使用东方APP后台管理软件');
      $('p.about-description').html('东方APP后台管理软件可以方便地管理东方APP内容，可以点击左边管理菜单中的栏目对相应内容进行新增、删除、修改、查询等操作。');

      $('#welcome-panel').delay(300).fadeTo('slow',1);
    });     
    </script>
    <?php
}

function render_custom_dashboard_widget() {
  $arg = array(
        'orderby' => 'menu_order',
        'post_type' => 'custom_post_guides',
        'post_status' => 'publish',
        'parent' => 0
      );
      
  $posts_array = get_posts($arg);
  $guides_num = count($posts_array);
  
  $arg = array(
        'orderby' => 'menu_order',
        'post_type' => 'custom_post_products',
        'post_status' => 'publish',
        'parent' => 0
      );
      
  $posts_array = get_posts($arg);
  $products_num = count($posts_array);
  
  $arg = array(
        'orderby' => 'menu_order',
        'post_type' => 'custom_post_cases',
        'post_status' => 'publish',
        'parent' => 0
      );
      
  $posts_array = get_posts($arg);
  $cases_num = count($posts_array);
  
  $arg = array(
        'orderby' => 'menu_order',
        'post_type' => 'custom_post_sources',
        'post_status' => 'publish',
        'parent' => 0
      );
      
  $posts_array = get_posts($arg);
  $sources_num = count($posts_array);
  
  echo '
    <table width="100%">
      <tr>
        <td class>
          <a href=' . admin_url( 'edit.php?post_type=custom_post_guides', 'http' ) . '>维护指导 (' . $guides_num . ')</a>
        </td>
        <td>
          <a href=' . admin_url( 'edit.php?post_type=custom_post_sources', 'http' ) . '>资料下载 (' . $sources_num . ')</a>
        </td>
      </tr>
      <tr>
        <td>
          <a href=' . admin_url( 'edit.php?post_type=custom_post_cases', 'http' ) . '>案例鉴赏 (' . $cases_num . ')</a>
        </td>
        <td>
          <a href=' . admin_url( 'edit.php?post_type=custom_post_products', 'http' ) . '>东方产品 (' . $products_num . ')</a>
        </td>
      </tr>
    </table>
    ';
}

// 去掉编辑post的时候显示的固定连接、查看等
function posttype_admin_css() {
  global $post_type;
  if(($post_type == 'custom_post_guides')
  || ($post_type == 'custom_post_sources')
  || ($post_type == 'custom_post_cases')
  || ($post_type == 'custom_post_products')) {
    echo '<style type="text/css">#edit-slug-box,#view-post-btn,#post-preview,.updated p a{display: none;}</style>';
  }
}

// 去掉更新通知
function remove_update_notification() {
  remove_action( 'admin_notices', 'update_nag', 3 );
}

// 修网页标题，主要目的是去掉“ - Wordpress”
function custom_admin_title($admin_title, $title)
{
  return $title;
}

// 移除“帮助”按钮
function remove_admin_help_tab($old_help, $screen_id, $screen){
  $screen->remove_help_tabs();
  return $old_help;
}

// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * Twenty Twelve setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_setup() {
	/*
	 * Makes Twenty Twelve available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'twentytwelve' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentytwelve', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentytwelve' ) );

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'twentytwelve_setup' );

/**
 * Add support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @since Twenty Twelve 1.2
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function twentytwelve_get_font_url() {
	$font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'twentytwelve' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'twentytwelve' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		$font_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since Twenty Twelve 1.0
 *
 * @return void
 */
function twentytwelve_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds JavaScript for handling the navigation menu hide-and-show behavior.
	wp_enqueue_script( 'twentytwelve-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true );

	$font_url = twentytwelve_get_font_url();
	if ( ! empty( $font_url ) )
		wp_enqueue_style( 'twentytwelve-fonts', esc_url_raw( $font_url ), array(), null );

	// Loads our main stylesheet.
	wp_enqueue_style( 'twentytwelve-style', get_stylesheet_uri() );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentytwelve-style' ), '20121010' );
	$wp_styles->add_data( 'twentytwelve-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'twentytwelve_scripts_styles' );

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses twentytwelve_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since Twenty Twelve 1.2
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */
function twentytwelve_mce_css( $mce_css ) {
	$font_url = twentytwelve_get_font_url();

	if ( empty( $font_url ) )
		return $mce_css;

	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'twentytwelve_mce_css' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function twentytwelve_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentytwelve_wp_title', 10, 2 );

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentytwelve_page_menu_args' );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentytwelve_widgets_init' );

if ( ! function_exists( 'twentytwelve_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'twentytwelve_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentytwelve_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 *
 * @return void
 */
function twentytwelve_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'twentytwelve' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'twentytwelve' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'twentytwelve' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentytwelve' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'twentytwelve_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 *
 * @return void
 */
function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Twenty Twelve 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function twentytwelve_body_class( $classes ) {
	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_image ) ) {
		if ( empty( $background_color ) )
			$classes[] = 'custom-background-empty';
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
			$classes[] = 'custom-background-white';
	}

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'twentytwelve-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'twentytwelve_body_class' );

/**
 * Adjust content width in certain contexts.
 *
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Twenty Twelve 1.0
 *
 * @return void
 */
function twentytwelve_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'twentytwelve_content_width' );

/**
 * Register postMessage support.
 *
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Twenty Twelve 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function twentytwelve_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'twentytwelve_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Twenty Twelve 1.0
 *
 * @return void
 */
function twentytwelve_customize_preview_js() {
	wp_enqueue_script( 'twentytwelve-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130301', true );
}
add_action( 'customize_preview_init', 'twentytwelve_customize_preview_js' );
