<?php
/**
 * Zombify Public Hooks
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

add_action('wp', 'zombify_start_session', 1);
function zombify_start_session() {
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Load plugin textdomain
 */
add_action( 'init', 'zombify_load_textdomain', 9 );
function zombify_load_textdomain() {
    load_plugin_textdomain( 'zombify', false, dirname( plugin_basename( zombify()->file ) ) . '/languages' );
}

$zombify_zfps = '';

/**
 * Init function for zombify
 */
add_action( 'init', 'zombify_init', 9 );
function zombify_init() {
    global $zombify_zfps;

    if( isset($_COOKIE["zombify_zfps"]) ){
        $zombify_zfps = $_COOKIE["zombify_zfps"];
        setcookie("zombify_zfps", "", -1, "/");
    }

}

add_action( 'wp_enqueue_scripts', 'zombify_enqueue_styles' );
/**
 * Load CSS.
 */
function zombify_enqueue_styles()
{

   // wp_enqueue_style('zombify-iconfonts', zombify()->assets_url . 'fonts/icon-fonts/icomoon/style.css');
    //wp_enqueue_style('zombify-style', zombify()->assets_url . 'css/zombify.min.css');
    //wp_add_inline_style('zombify-style', zf_branding_color_css());
   // wp_enqueue_style('zombify-plugins-css', zombify()->assets_url . 'js/plugins/zombify-plugins.min.css');

   $enable_google_fonts = apply_filters( 'zombify_enable_google_fonts', false );
    if(isset($enable_google_fonts)) {
        wp_enqueue_style('zombify-font-cabin', 'https://fonts.googleapis.com/css?family=Cabin|Open+Sans');
    }

    if ( is_rtl() ) {
        wp_enqueue_style('zombify-rtl', zombify()->assets_url . 'css/zombify-rtl.css' );
    }

}

add_action( 'wp_enqueue_scripts', 'zombify_enqueue_scripts' );
/**
 * Load javascripts.
 */
function zombify_enqueue_scripts()
{


    /** Core ************************************** */

//    wp_enqueue_script( 'zombify-editor', zombify()->assets_url . 'js/plugins/tinymce/tinymce.min.js', array( 'jquery' ) );
   // wp_enqueue_script( 'zombify-js', zombify()->assets_url . 'js/zombify-scripts.min.js', array( 'jquery' ) );
	//	wp_localize_script( 'zombify-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

  //  wp_enqueue_script( 'zombify-comments-js', zombify()->assets_url . 'js/zombify-comments.min.js', array( 'comment-reply' ), false, true );

    $enable_twitter = apply_filters( 'zombify_enable_twitter_js', false );
    if( $enable_twitter ) {
        wp_enqueue_script('zombify-twitter-widget', 'https://platform.twitter.com/widgets.js');
    }

    $translatable = array(
        "invalid_file_extension" => __("Invalid file extension. Valid extensions are:", "zombify"),
        "invalid_file_size" => __("File is too large. Maximum allowed file size is:", "zombify"),
        "error_saving_post" => __("There was an error while saving the post. Please try again.", "zombify"),
        "processing_files" => __("Processing files ...", "zombify"),
        "uploading_files" => __("Uploading files", "zombify"),
        "preview_alert" => __( "Please save the post first!", "zombify" )
    );
if(isset($locale)){	
}else{
	$locale="";
}
    wp_localize_script( 'zombify-js', 'zf', array(
        'translatable' => $translatable,
        'locale' => $locale,
        'ajaxurl' => admin_url('admin-ajax.php')
    ) );

}

if( !function_exists("zombify_public_frontend_page_func") ) {

    // Global variable for zombify frontend content
    global $zombify_frontend_content;

    /**
     * Zombify public page controller
     */
    function zombify_public_frontend_page_func() {

        global $zombify_frontend_content, $post;

        $content = '';

        // Getting Zombify frontend page ID
        $frontend_page_id = get_option("zombify_frontend_page");

        // Getting Zombify post create page ID
        $post_create_page_id = get_option("zombify_post_create_page");

        if (is_page($frontend_page_id)) {
            $content = zombify_public_frontend_controller("index");
        }

        if (is_page($post_create_page_id)) {
            if( zombify()->hasCreateAccess() ) {
                $content = zombify_public_frontend_controller("", "create");
            } else {
                $content = zombify_public_frontend_controller("", "permissiondenied");
            }
        }

        if( $post && $post->post_type == 'list_item' ){

            $content = zombify_public_frontend_controller("", "subpost");

        }

        $zombify_frontend_content .= $content;

    }



}

add_filter( 'wp', 'zombify_public_frontend_page_func' );

function zombify_title_filter( $title, $id = null ) {

    global $post;

    if( $post && $post->ID == $id && $post->post_type == 'list_item' && $post->post_status == 'publish' ){

        $parent_post_data = json_decode( base64_decode( get_post_meta( $post->post_parent, 'zombify_data', true ) ), true );

        $num = 0;

        $post_ids = array();

        foreach( $parent_post_data["list"] as $pdata ) {

            $post_ids[] = $pdata["post_id"];

        }

        $args = array(
            'post__in' => $post_ids,
            'post_type' => 'list_item',
            'post_status' => 'publish',
            "posts_per_page" => -1
        );

        $posts = get_posts($args);

        foreach( $parent_post_data["list"] as $pdata ) {

            $st = '';

            foreach( $posts as $p )
                if( $p->ID == $pdata["post_id"] )
                    $st = $p->post_status;

            if( $st != 'publish' ) continue;

            $num++;

            if ($pdata["post_id"] == $post->ID) {
                break;
            }
        }

        $title .= ' ('.$num.'/'.count($posts).')';

    }

    return $title;
}
add_filter( 'the_title', 'zombify_title_filter', 10, 2 );

if( !function_exists("zombify_public_frontend_page_content") ) {

    /**
     * Zombify public page content
     */
    function zombify_public_frontend_page_content( $content ) {

        global $zombify_frontend_content;

        return $content.$zombify_frontend_content;
    }



}

add_filter( 'the_content', 'zombify_public_frontend_page_content' );

if( !function_exists("zombify_save_ajax") ) {

    /**
     * Zombify save ajax function
     */
    function zombify_save_ajax() {

        $content = zombify_public_frontend_controller();

        echo $content;

        exit;

    }



}

add_action( 'wp_ajax_zombify_save', 'zombify_save_ajax' );

if( !function_exists("zombify_poll_vote_ajax") ) {

    /**
     * Zombify save ajax function
     */
    function zombify_poll_vote_ajax() {

        $content = zombify_public_frontend_controller();

        echo $content;

        exit;

    }



}

add_action( 'wp_ajax_zombify_poll_vote', 'zombify_poll_vote_ajax' );
add_action( 'wp_ajax_nopriv_zombify_poll_vote', 'zombify_poll_vote_ajax' );

if( !function_exists("zombify_post_shortcode") ) {

    function zombify_post_shortcode(){

        $current_post_id = get_the_ID();

        $postsavetype = get_post_meta( $current_post_id, 'zombify_postsave_type', true );

        if( !$postsavetype ) $postsavetype = 'shortcode';

        if( $postsavetype == 'shortcode' ) {

            if ($post_type = get_post_meta($current_post_id, 'zombify_data_type', true)) {

                $zf_openlist_errors = array();

                zombify_frontend_save_post( $zf_openlist_errors );

                $data = json_decode(base64_decode(get_post_meta($current_post_id, 'zombify_data', true)), true);

                $template_file = zombify()->views_dir . 'quiz_view/' . strtolower($post_type) . '.php';

                ob_start();
                include $template_file;
                $output = ob_get_contents();
                ob_end_clean();

                return $output;
            } else
                return '';

        } else
            return '';

    }

    add_shortcode( 'zombify_post', 'zombify_post_shortcode' );

}

if( !function_exists("zombify_post_view") ){

    function zombify_post_view( $content ) {

        if( !has_shortcode($content, 'zombify_post') ) {

            $current_post_id = get_the_ID();

            $postsavetype = get_post_meta( $current_post_id, 'zombify_postsave_type', true );

            if( !$postsavetype ) $postsavetype = 'shortcode';

            $post_type = get_post_meta($current_post_id, 'zombify_data_type', true);

            if (is_single() && $post_type && $postsavetype == 'meta') {

                $zf_openlist_errors = array();

                zombify_frontend_save_post( $zf_openlist_errors );

                $data = json_decode(base64_decode(get_post_meta($current_post_id, 'zombify_data', true)), true);

                $template_file = zombify()->views_dir . 'quiz_view/' . strtolower($post_type) . '.php';

                ob_start();
                include $template_file;
                $output = ob_get_contents();
                ob_end_clean();

                $content .= $output;
            }

        }

        return $content;
    }


}

add_filter( 'the_content', 'zombify_post_view' );

if( !function_exists("zombify_custom_post_type") ){

    function zombify_custom_post_type()
    {
        register_post_type('list_item',
            [
                'labels'      => [
                    'name'          => __('List Items'),
                    'singular_name' => __('List Items'),
                ],
                'public'      => true,
								'rewrite'			=> array( 'slug' => 'review' ),
                'has_archive' => true,
                'supports'    => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
            ]
        );
    }

}

add_action( 'init', 'zombify_custom_post_type', 10 );

if( !function_exists("zombify_add_new_image_sizes") ){

    function zombify_add_new_image_sizes()
    {
        add_image_size( 'zombify_small', 350, 350, true );
    }


}
add_action( 'init', 'zombify_add_new_image_sizes', 10 );


if( !function_exists("zombify_toolbar_link") ) {

    function zombify_toolbar_link($wp_admin_bar)
    {
        global $pagenow, $post;

        if( ( (!is_admin() && is_single()) || ( is_admin() && $pagenow == 'post.php' ) ) && is_user_logged_in() && ( get_post_meta(get_the_ID(), 'zombify_data_type', true) || $post->post_type == 'list_item' ) != '' && ( get_the_author_meta( 'ID' ) == get_current_user_id() || wp_get_current_user()->roles[0] == 'administrator' ) ) {

            $args = array(

                'id' => 'zombify-edit-post',

                'title' => esc_html__( 'Edit With Zombify', 'zombify' ),

                'href' => add_query_arg( array( 'action' => 'update', 'post_id' => $post->post_type == 'list_item' ? $post->post_parent :get_the_ID() ), get_permalink( get_option( "zombify_post_create_page" ) ) ),

                'meta' => array(

                    'title' => esc_html__( 'Edit With Zombify', 'zombify' ),

                )

            );

            if($post->post_type == 'list_item'){
               $args['href'] = add_query_arg( array( 'action' => 'edit', 'post' => get_the_ID() ),admin_url('post.php') );

            }


            $wp_admin_bar->add_node($args);

        }

    }



}

add_action('admin_bar_menu', 'zombify_toolbar_link', 999);

if( !function_exists("zombify_post_update") ) {

    function zombify_post_update($post_id)
    {
        if( $data = get_post_meta($post_id, 'zombify_data', true) ){

            $post = get_post( $post_id );
            $post_thumbnail_id = get_post_thumbnail_id( $post_id );

            $post_data = json_decode(base64_decode($data), true);

            $post_data["title"] = $post->post_title;
            // $post_data["description"] = $post->post_excerpt;
            if( $post_thumbnail_id ){

                $zombify_small_image = wp_get_attachment_image_src($post_thumbnail_id, 'zombify_small');
                $attachment_post = get_post( $post_thumbnail_id );

                $file = get_attached_file($post_thumbnail_id);

                $image = array();
                $image[0] = array(
                    "name" => pathinfo($file, PATHINFO_BASENAME),
                    "type" => $attachment_post->post_mime_type,
                    "size" => "",
                    "attachment_id" => $post_thumbnail_id,
                    "uploaded" => array(
                        "file" => $file,
                        "url" => get_the_post_thumbnail_url($post_id),
                        "type" => $attachment_post->post_mime_type,
                    ),
                    "size_urls" => array(
                        "zombify_small" => isset($zombify_small_image[0]) ? $zombify_small_image[0] : ''
                    )
                );

            } else
                $image = array();

            $post_data["image"] = $image;

            update_post_meta($post_id, "zombify_data", base64_encode( json_encode( $post_data ) ));

        }
    }



}

add_action('save_post', 'zombify_post_update');

if( !function_exists("zombify_get_tags_ajax") ) {

    /**
     * Zombify get all tags ajax function
     */
    function zombify_get_tags_ajax() {

        $args = array();

        $args["name__like"] = isset($_GET["term"]) ? $_GET["term"] : '';

        $tags = get_tags($args);

        $json = array();

        foreach( $tags as $tag )
            $json[] = $tag->name;

        echo json_encode($json);

        exit;

    }



}

add_action( 'wp_ajax_zombify_get_tags', 'zombify_get_tags_ajax' );

if( ! function_exists( 'zombify_pending_popup' ) ) {

    function zombify_pending_popup() {
        ?>
        <div class="zombify-submit-popup zf-open">
            <div class="zombify-popup_body">
                <a class="zf-popup_close" href="#"><i class="zf-icon zf-icon-delete"></i></a>

                <div class="zf-content">
                    <div class="zf-head">
                        <i class="zf-icon zf-icon-check"></i>
                    </div>
                    <div class="zf-inner">
                        <div class="zf-inner_text">
                            <div class="h4"><?php esc_html_e("Thank You for Submission!", "zombify"); ?></div>
                            <div
                                class="zf-text"><?php esc_html_e("Your item is awaiting moderation. You'll be notified as soon as your submission will be approved.", "zombify"); ?></div>
                        </div>
                        <div class="zf-btn-group">
                            <a class="zf-btn zf-create"
                               href="<?php echo get_permalink(get_option("zombify_frontend_page")); ?>"><i
                                    class="zf-icon zf-icon-add"></i><?php esc_html_e("Create One More", "zombify") ?>
                            </a>
                        </div>
                        <div class="zf-footer">
                            <span class="zombify-logo"><img src="<?php echo get_option('zombify_logo', zombify()->options_defaults["zombify_logo"]); ?>" alt=""> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}


if( ! function_exists( 'zombify_publish_popup' ) ) {

    function zombify_publish_popup(){
        $popup_title = apply_filters( 'zf-publish-popup-title', esc_html__( "Congratulations!", "zombify" ) );
        $popup_content = apply_filters( 'zf-publish-popup-content', sprintf( '%1$s<br>%2$s', esc_html__("Your item is published now.", "zombify"), esc_html__("Don't forget to share it with your friends.", "zombify") ) )
        ?>
        <div class="zombify-submit-popup zf-open">
            <div class="zombify-popup_body">
                <a class="zf-popup_close" href="#"><i class="zf-icon zf-icon-delete"></i></a>
                <div class="zf-content">
                    <div class="zf-head">
                        <i class="zf-icon zf-icon-check"></i>
                    </div>
                    <div class="zf-inner">
                        <div class="zf-inner_text">
                            <?php if( $popup_title ) { ?>
                            <div class="h4"><?php echo $popup_title; ?></div>
                            <?php } ?>

                            <?php if( $popup_content ) { ?>
                            <div class="zf-text"><?php echo $popup_content; ?></div>
                            <?php } ?>
                        </div>
                        <div class="zf-btn-group">
                            <div class="zf-share_text"><?php esc_html_e("Share", "zombify"); ?></div>
                            <div class="zf-share_box">
                                <a class="zf-share zf_twitter" target='_blank'
                                   href="http://twitter.com/home?status=<?php print(urlencode(the_title())); ?>+<?php print(urlencode(get_permalink())); ?>">
                                    <i class="zf-icon zf-icon-twitter"></i><?php esc_html_e("Twitter", "zombify"); ?></a>
                                <a class="zf-share zf_facebook" target='_blank'
                                   href="http://www.facebook.com/share.php?u=<?php print(urlencode(get_permalink())); ?>&title=<?php print(urlencode(the_title())); ?>">
                                    <i class="zf-icon zf-icon-facebook"></i><?php esc_html_e("Facebook", "zombify"); ?>
                                </a>
                            </div>
                        </div>
                        <div class="zf-footer">
                            <span class="zombify-logo"><img src="<?php echo get_option('zombify_logo', zombify()->options_defaults["zombify_logo"]); ?>" alt=""></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}

if( ! function_exists( 'zombify_edit_post_button' ) ) {

    function zombify_edit_post_button() {
        if( is_user_logged_in() && ( get_the_author_meta( 'ID' ) == get_current_user_id() || current_user_can('administrator') || is_super_admin() ) ) {
            ?>
            <a class="zf-edit-button"
               href="<?php echo add_query_arg( array( 'action' => 'update', 'post_id' => get_the_ID() ), get_permalink( get_option( "zombify_post_create_page" ) ) ); ?>">
                <i class="zf-icon zf-icon-edit"></i>
                <?php esc_html_e( 'Edit the post', 'zombify' ); ?>
            </a>
            <?php
        }
    }

}
add_action( 'zombify_after_post_layout', 'zombify_edit_post_button' );




function zombify_post_pagination($posts){


    foreach( $posts as $post ){

        if( $data = get_post_meta($post->ID, "zombify_data", true) ) {

            $items_per_page = get_post_meta($post->ID, "zombify_items_per_page", true);

            if (!$items_per_page) $items_per_page = 1000;

            $post_type = get_post_meta($post->ID, "zombify_data_type", true);

            $data = json_decode(base64_decode($data), true);

            /**
             * Get addtional attached posts
             */

                $additional_items_str       =   get_post_meta( $post->ID, 'md_zombify_list_items', true );

        if( $additional_items_str != '' ){

            $additional_items       =   explode('|', $additional_items_str);

            foreach( $additional_items as $add_i ){
                if( $add_i > 0 ){
                    $temppp_post        =   get_post($add_i);
                    $db_data                =   json_decode(base64_decode(get_post_meta($temppp_post->post_parent, 'zombify_data', true)), true);
                    $daaaata                =   false;

                    foreach( $db_data['list'] as $db_d){
                        if( $db_d['post_id'] == $add_i ){
                            $daaaata    =   $db_d;
                            break;
                        }
                    }

                    if( count($daaaata) > 0 ){
                        $data["list"][] =   $daaaata;
                    }

                }
            }

        }
        /**
             * Get addtional attached files posts
             */

            $QuizClass = "Zombify_" . ucfirst(strtolower($post_type)) . "Quiz";

            if (class_exists($QuizClass)) {
                $quiz = new $QuizClass();

                $pagination_path = $quiz->pagination_path;

                $temp_data = $data;

                foreach( $pagination_path as $pag_path ){

                    $temp_data = $temp_data[ $pag_path ];

                }

                $items_count = count( $temp_data );

            } else {
                $items_count = 1;
            }
                // echo '<pre>';
                // print_r($data); die;
            $pages_count = ceil( $items_count / $items_per_page );

            if( empty( $post->post_content ) ){
                $post->post_content .= '<!--nextpage-->';
            }

            if( strpos($post->post_content, '[zombify_post]') !== false )
                $post->post_content .= str_repeat('<!--nextpage-->[zombify_post]', $pages_count-1);
            else
                $post->post_content .= str_repeat('<!--nextpage-->', $pages_count -1);

        }

    }

    return $posts;

}

add_filter( 'the_posts', 'zombify_post_pagination', 10, 2 );

function zombify_redirect_after_comment( $location, $comment ) {

    if ( isset( $_POST['redirectback'] ) && isset($_SERVER["HTTP_REFERER"]) ) {
        $location = $_SERVER["HTTP_REFERER"].'#comment-'.$comment->comment_ID;
    }

    return $location;
}

add_filter( 'comment_post_redirect', 'zombify_redirect_after_comment', 10, 2 );

if( !function_exists("zombify_post_vote_ajax") ) {

    /**
     * Zombify save ajax function
     */
    function zombify_post_vote_ajax() {

        if( isset($_POST["post_id"]) && isset($_POST["post_parent_id"]) && isset($_POST["vote_type"]) && get_post_meta((int)$_POST["post_id"], "openlist_close_voting", true) != 1 ){

            $votes_count = (int)get_post_meta((int)$_POST["post_id"], "zombify_post_rateing", true);

            $voted = 0;
            $voted_type = '';

            if( isset($_COOKIE["zombify_post_vote_".(int)$_POST["post_id"]]) ){

                $voted = 1;
                $voted_type = $_COOKIE["zombify_post_vote_".(int)$_POST["post_id"]];

            }


                if( $voted == 1 ){

                    if ($_POST["vote_type"] == 'up') {

                        if( $voted_type == 'up' ){
                            $votes_count--;

                            setcookie("zombify_post_vote_".(int)$_POST["post_id"], "up", time()-60*60*24, '/');
                        } else {
                            $votes_count+=2;

                            setcookie("zombify_post_vote_".(int)$_POST["post_id"], "up", time()+60*60*24, '/');
                        }

                    } else {

                        if( $voted_type == 'down' ){
                            $votes_count++;

                            setcookie("zombify_post_vote_".(int)$_POST["post_id"], "down", time()-60*60*24, '/');
                        } else {
                            $votes_count-=2;

                            setcookie("zombify_post_vote_".(int)$_POST["post_id"], "down", time()+60*60*24, '/');
                        }

                    }

                } else {

                    if ($_POST["vote_type"] == 'up') {
                        $votes_count++;
                        setcookie("zombify_post_vote_".(int)$_POST["post_id"], "up", time()+60*60*24, '/');
                    } else {
                        $votes_count--;
                        setcookie("zombify_post_vote_".(int)$_POST["post_id"], "down", time()+60*60*24, '/');
                    }

                }

                update_post_meta((int)$_POST["post_id"], "zombify_post_rateing", $votes_count);


            $post_parent_id = (int)$_POST["post_parent_id"];
            $post_data_type = get_post_meta($post_parent_id, "zombify_data_type", true);

            if( $post_data_type == "openlist" || $post_data_type == "rankedlist" ){

                $post_data = json_decode( base64_decode( get_post_meta($post_parent_id, "zombify_data", true) ), true );

                foreach( $post_data['list'] as $list_index=>$list_item ){

                    $post_data['list'][ $list_index ]["temp_item_rateing"] = (int)get_post_meta($list_item['post_id'], "zombify_post_rateing", true);

                }

                usort($post_data['list'],function($a, $b) {
                    return $b['temp_item_rateing']!=$a['temp_item_rateing'] ? $b['temp_item_rateing'] - $a['temp_item_rateing'] : $a['post_id'] - $b['post_id'];
                });

                foreach( $post_data['list'] as $list_index=>$list_item ){

                    unset( $post_data['list'][ $list_index ]["temp_item_rateing"] );

                }

                update_post_meta($post_parent_id, "zombify_data", base64_encode( json_encode( $post_data ) ));

            }

            echo json_encode(array("votes" => $votes_count, "post_id" => (int)$_POST["post_id"]));

            if( function_exists("w3tc_flush_post") ) {

                w3tc_flush_post($_POST["post_id"]);
                w3tc_flush_post($post_parent_id);

            }

            exit;

        }

    }



}

add_action( 'wp_ajax_zombify_post_vote', 'zombify_post_vote_ajax' );
add_action( 'wp_ajax_nopriv_zombify_post_vote', 'zombify_post_vote_ajax' );

add_filter('body_class', 'zombify_color_mode_class');

function zombify_color_mode_class($classes) {

    $color_mode = get_option("zombify_color_mode", "light");

    $classes[] = "zombify-".$color_mode;

    return $classes;
}


if( !function_exists("zombify_get_post_comments") ) {

    /**
     * Zombify save ajax function
     */
    function zombify_get_post_comments() {

        $json = array();

        if( isset($_POST["post_id"]) && isset($_POST["page"]) ){

            global $post;

            $post = get_post( (int)$_POST["post_id"] );

            $curr_user = wp_get_current_user();

            $comments = get_comments(
                array(
                    "post_id" => (int)$_POST["post_id"],
                    "status" => "approve",
                    "include_unapproved" => array($curr_user->user_email),
                    "orderby" => array("comment_parent" => "ASC", "comment_ID" => "DESC"),
                )
            );

            zf_remove_first_comment( $comments );


            $comments_html = wp_list_comments(
                array(
                    'walker'            => null,
                    'max_depth'         => '',
                    'style'             => 'div',
                    'callback'          => null,
                    'end-callback'      => null,
                    'type'              => 'all',
                    'reply_text'        => 'Reply',
                    'page'              => (int)$_POST["page"]-1,
                    'per_page'          => 5,
                    'avatar_size'       => 32,
                    'reverse_top_level' => null,
                    'reverse_children'  => '',
                    'format'            => 'html5', // or 'xhtml' if no 'HTML5' theme support
                    'short_ping'        => false,   // @since 3.6
                    'echo'              => false     // boolean, default is true
                ),
                $comments
            );

            $json['comments'] = $comments_html;

        }

        echo json_encode($json);

        exit;

    }



}

add_action( 'wp_ajax_zombify_get_post_comments', 'zombify_get_post_comments' );
add_action( 'wp_ajax_nopriv_zombify_get_post_comments', 'zombify_get_post_comments' );

function mv_zombify_mentioned_in(){

	global $wpdb;

	if( get_post_type() == 'list_item' ){

		echo '<h2>Mentioned In</h2>';

		$item_id										=	get_the_ID();
		$list_item									=	get_post( $item_id );
		$parent_id									=	$list_item->post_parent;
		$mentioned_ins							=	array();
		$mentioned_ins[$parent_id]	=	get_post( $parent_id );

		$rrr	=	$wpdb->get_col($wpdb->prepare( "SELECT `post_id` FROM $wpdb->postmeta WHERE meta_key = 'md_zombify_list_items' AND meta_value LIKE %s ", "%|$item_id|%" ));

		foreach( $rrr as $tt ){
			$mentioned_ins[]	=	get_post($tt);
		}

		foreach( $mentioned_ins as $mentioned_in ){
			echo '<div class="zombify_mentioned_in">';
				echo '<h4><a href="'.$mentioned_in->guid.'">'.$mentioned_in->post_title.'</a></h4>';
				echo '<a href="'.$mentioned_in->guid.'">'.get_the_post_thumbnail($mentioned_in->ID, array(230,200)).'</a>';
			echo '</div>';
		}

	}

}

add_action( 'comment_form_after', 'mv_zombify_mentioned_in' );

add_action( 'wp_ajax_openlist_ajax', 'openlist_ajax_func' );
add_action( 'wp_ajax_nopriv_openlist_ajax', 'openlist_ajax_func' );

add_action( 'wp_ajax_openlist_add_to_list', 'openlist_add_to_list_func' );
add_action( 'wp_ajax_nopriv_openlist_add_to_list', 'openlist_add_to_list_func' );

function openlist_add_to_list_func(){

	$item_id			=	$_POST['item_id'];
	$list_id			=	$_POST['list_id'];

	$list_items		=	get_post_meta( $list_id, 'md_zombify_list_items', true );
	// if( $list_items == '' )
	// 	$list_items	.=	'|';
	$list_items		.=	'|'.$item_id;

	update_post_meta($list_id, 'md_zombify_list_items', $list_items);

	die();

}

function openlist_ajax_func(){

	global $wpdb;
	$q					=	$_POST['q'];
	$list_id		=	$_POST['list_id'];

	$search_q		= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE `post_title` LIKE %s AND `post_type` = 'list_item' ", "%$q%" ) );

	if( count($search_q) > 0 ){
		foreach( $search_q as $res ){
			echo '<a href="javascript:;" onclick="md_zombify_add_to_list( \''.$res->ID.'\', \''.$list_id.'\' )"><div class="adder_search_res">';
				echo '<span class="add_icon"><i class="zf-icon zf-icon-add"></i></span>';
				echo '<h3>'.$res->post_title.'</h3>';
			echo '</div></a>';
		}
	}else{
		echo 'no';
	}


	die();

}