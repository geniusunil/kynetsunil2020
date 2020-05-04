<?php
 require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Set up theme support
function elementor_hello_theme_setup() {

	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'custom-logo', array(
		'height' => 70,
		'width' => 350,
		'flex-height' => true,
		'flex-width' => true,
	) );

	add_theme_support( 'woocommerce' );

	load_theme_textdomain( 'elementor-hello-theme', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'elementor_hello_theme_setup' );

add_image_size( 'item-thumb', 250 );
add_image_size( 'list-thumb', 450 );

// Theme Scripts & Styles
function elementor_hello_theme_scripts_styles() {
  wp_enqueue_style( 'elementor-hello-theme-style', get_stylesheet_uri() );
  wp_enqueue_script('listjs', '//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js');
  wp_register_script('adminjs', get_template_directory_uri().'/assets/js/admin.js', array(), date("h:i:s"));
  wp_localize_script('adminjs', 'WPURLS', array( 'siteurl' => get_option('siteurl') ));
  wp_enqueue_script('adminjs');	
  wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/admin-style.css', false, '1.0.0' );
  wp_enqueue_style( 'custom_wp_admin_css' );

}
function jssor_script() {
	
	wp_enqueue_script('diyslider', get_template_directory_uri().'/assets/js/jssor.slider-27.5.0.min.js', array(), date("h:i:s"));
	
}
add_action( 'wp_enqueue_scripts', 'jssor_script' );

add_action( 'wp_enqueue_scripts', 'elementor_hello_theme_scripts_styles' );

function elementor_hello_theme_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
};
add_action( 'elementor/theme/register_locations', 'elementor_hello_theme_register_elementor_locations' );

// Remove WP Embed
function elementor_hello_theme_deregister_scripts() {
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'elementor_hello_theme_deregister_scripts' );

// Remove WP Emoji
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer',
	'id'   => 'footer',    // ID should be LOWERCASE 
    'before_widget' => '<div id="%1$s" class="footer %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'SFheader',
	'id'   => 'sfheader',
    'before_widget' => '<div class = "SFheader">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

/**
 *  create shortcode to output the current year
 *  shortcode: [year] 
*/
function output_year() {
  $year = date("Y");
  return "$year";
}
add_shortcode('year', 'output_year');


/**
 *  create shortcode to output the current year
 *  shortcode: [month] 
*/
function output_month() {
  $month = date("F");
  return "$month";
}
add_shortcode('month', 'output_month');

add_filter( 'jpeg_quality', create_function('', 'return 50;' ) );

// Removing Font Awesome
add_filter( 'infophilic_fontawesome_essentials', 'infophilic_fontawesome_essentials' );
function infophilic_fontawesome_essentials()
{
 return true;
}
// Remove WP embed script
function infophilic_stop_loading_wp_embed() {
if (!is_admin()) {
wp_deregister_script('wp-embed');
}
}
add_action('init', 'infophilic_stop_loading_wp_embed');

/*CHRISTOPHER's CUSTOM MODS - 1.10.15 rev15A - (c) 2010-2015 Chris Simmons */ remove_action( 'wp_head', 'wp_generator' ) ; remove_action( 'wp_head', 'wlwmanifest_link' ) ; remove_action( 'wp_head', 'rsd_link' ) ; remove_action( 'wp_head', 'feed_links', 2 ); remove_action( 'wp_head', 'feed_links_extra', 3 ); add_filter( 'pre_comment_content', 'wp_specialchars' ); function no_errors_please(){ return 'You appear to be up to no good. Please stop now!'; } add_filter( 'login_errors', 'no_errors_please' ); /* Disable YOAST SEO Admin Bar. */ function mytheme_admin_bar_render() { global $wp_admin_bar; $wp_admin_bar->remove_menu('wpseo-menu'); } // and we hook our function via add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' ); function delete_enclosure(){ return ''; } add_filter( 'get_enclosed', 'delete_enclosure' ); add_filter( 'rss_enclosure', 'delete_enclosure' ); add_filter( 'atom_enclosure', 'delete_enclosure' ); function remove_cssjs_ver( $src ) { if( strpos( $src, '?ver=' ) ) $src = remove_query_arg( 'ver', $src ); return $src; } add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 ); add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 ); add_filter( 'jpeg_quality', create_function( '', 'return 50;' ) ); function remove_pingback_url( $output, $show ) { if ( $show == 'pingback_url' ) $output = ''; return $output; } add_filter( 'bloginfo_url', 'remove_pingback_url', 10, 2 ); add_action('init', 'myoverride', 100); function myoverride() { remove_action('wp_head', array(visual_composer(), 'addMetaData')); } function dequeue_visual_composer_css() { if (is_single()) { wp_dequeue_style('js_composer_front'); } } add_action('wp_enqueue_scripts', 'dequeue_visual_composer_css', 1003);

add_filter( 'the_title', 'do_shortcode' );

add_filter( 'acf/settings/show_admin', '__return_true', 50 );

add_filter( 'script_loader_tag', function( $tag, $handle ) {
 	if ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] ) {
 		return str_replace( ' src', ' data-cfasync="false" src', $tag ); 
	}
 	return $tag;
 }, 10, 2 );
 
 // Remove comment date
function wpb_remove_comment_date($date, $d, $comment) { 
    if ( !is_admin() ) {
        return;
    } else { 
        return $date;
    }
}
add_filter( 'get_comment_date', 'wpb_remove_comment_date', 10, 3);
 
// Remove comment time
function wpb_remove_comment_time($date, $d, $comment) { 
    if ( !is_admin() ) {
            return;
    } else { 
            return $date;
    }
}
add_filter( 'get_comment_time', 'wpb_remove_comment_time', 10, 3);

add_filter( 'script_loader_tag', function( $tag, $handle ) {
 	if ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] ) {
 		return str_replace( ' src', ' data-cfasync="false" src', $tag ); 
	}
 	return $tag;
 }, 10, 2 );
 

	


function new_attachment( $att_id ){
	
    // the post this was sideloaded into is the attachments parent!

    // fetch the attachment post
    $att = get_post( $att_id );

    // grab it's parent
    $post_id = $att->post_parent;
//	file_put_contents("imagurl.txt", "post id is $post_id",FILE_APPEND);

    // set the featured post
    set_post_thumbnail( $post_id, $att_id );
}







	


	

	function load_wp_media_files() {
	wp_enqueue_media();
	}
add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );







	
				







// post category

/* $custom_terms = get_terms('list_categories'); // I don't know what it does 20 sep 2019

foreach($custom_terms as $custom_term) {
    wp_reset_query();
    $args = array('post_type' => 'lists',
        'tax_query' => array(
            array(
                'taxonomy' => 'list_categories',
                'field' => 'slug',
                'terms' => $custom_term->slug,
            ),
        ),
     );

     $loop = new WP_Query($args);
     if($loop->have_posts()) {
        echo '<h2>'.$custom_term->name.'</h2>';

        while($loop->have_posts()) : $loop->the_post();
            echo '<a href="'.get_permalink().'">'.get_the_title().'</a><br>';
        endwhile;
     }
}
 */




