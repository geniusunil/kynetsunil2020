<?php
/*
Plugin Name: List Creator
Description: Create Lists & List Items
Version: 1.7
Plugin URI: https://www.softwarefindr.com
Author URI: https://www.softwarefindr.com
Author: SF Team
*/
// don't load directly

define( "MV_LIST_DIR_URL", WP_PLUGIN_DIR."/".basename( dirname( __FILE__ ) ) );
define( "DOWNLOAD_QUERY", 'utm_source=softwarefindr.com&utm_medium=free-traffic&utm_campaign=SoftwareFindr-Referral' );
require_once 'inc/class-mv-list-post-types.php';
//require_once 'inc/class-mv-list-single-view.php';
require_once 'inc/class-mv-list-single-view-new.php';
require_once 'inc/class-mv-list.php';
require_once 'inc/class-mv-list-comparision.php';
require_once 'inc/class.taxonomy-single-term.php';
require_once 'inc/class-mv-list-filter-widget.php';
require_once 'inc/class-mv-acf-hooks.php';
require_once 'inc/class-mv-list-shortcodes.php';


require_once 'inc/admin/class-mv-admin-settings.php';
require_once 'inc/mes/class-mes-lc-ext.php';

require_once 'inc/mv-custom-functions.php';

function createDateRange($startDate, $endDate, $format = "Y-m-d")
{
    $begin = new DateTime($startDate);
    $end = new DateTime($endDate);

    $interval = new DateInterval('P1D'); // 1 Day
    $dateRange = new DatePeriod($begin, $interval, $end);

    $range = [];
    foreach ($dateRange as $date) {
        $range[] = $date->format($format);
    }

    return $range;
}
function wpb_set_post_views($postID) {
    $today = date('Y-m-d');
    $count_key = 'wpb_views_count_'.$today;
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;

        $newDate = strtotime($today.' - 30 days');
        $newDate2 = strtotime($today.' + 1 day');
        $startDate = date('Y-m-d', $newDate);
        $endDate = date('Y-m-d', $newDate2);
        $dateRange =  createDateRange($startDate,$endDate);
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
        $totalvotes  = 0;
        foreach ($dateRange as $dt){
            $voteval  = get_post_meta($postID,'wpb_views_count_'.$dt,true);
            $totalvotes += is_numeric($voteval)?$voteval:0;
        }
        update_post_meta($postID, 'total_post_view', $totalvotes);

    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
        $total =  get_post_meta($postID, 'total_post_view', true);
        $total = is_numeric($total)?$total+1:1;
        update_post_meta($postID, 'total_post_view', $total);
    }
}

function wpb_track_post_views ($post_id=NULL) {
    if ( !is_singular('lists') ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;
    }
    wpb_set_post_views($post_id);
}
//add_action( 'wp_head', 'wpb_track_post_views');
add_action( 'wp_enqueue_scripts', 'include_accordion_script');
function include_accordion_script(){
    if ( is_tax( 'list_categories' ) || is_singular( 'list_items' )){
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_style('jquery-custom-accordion', plugins_url( 'css/jquery-ui.css',__FILE__));
    }
    if(is_singular( 'list_items' )){
        wp_enqueue_script('jquery-readmore', plugins_url( 'js/readmore.min.js',__FILE__));
    }
}

//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
add_action('transition_post_status', 'product_created_function', 10, 3);
function product_created_function($newstatus, $oldstatus, $post) {
    if($oldstatus != 'publish' && $newstatus == 'publish' && !empty($post->ID) && in_array( $post->post_type, array( 'lists') ) ) {
        $postva = get_post_meta($post->ID, 'total_post_view',true);
        if($postva == '') {
            add_post_meta($post->ID, 'total_post_view', '0');
        }
    }
}
function get_PostViews($post_ID){
    $count_key = 'total_post_view';
    //Returns values of the custom field with the specified key from the specified post.
    $count = get_post_meta($post_ID, $count_key, true);

    return $count;
}

//Function that Adds a 'Views' Column to your Posts tab in WordPress Dashboard.
function post_column_views($newcolumn){
    //Retrieves the translated string, if translation exists, and assign it to the 'default' array.
    $newcolumn['total_post_view'] = __('Views');
    return $newcolumn;
}

//Function that Populates the 'Views' Column with the number of views count.
function post_custom_column_views($column_name, $id){

    if($column_name === 'total_post_view'){
        // Display the Post View Count of the current post.
        // get_the_ID() - Returns the numeric ID of the current post.
        echo get_PostViews(get_the_ID());
    }
}
//Hooks a function to a specific filter action.
//applied to the list of columns to print on the manage posts screen.
add_filter('manage_lists_posts_columns', 'post_column_views');

//Hooks a function to a specific action.
//allows you to add custom columns to the list post/custom post type pages.
//'10' default: specify the function's priority.
//and '2' is the number of the functions' arguments.
add_action('manage_lists_posts_custom_column', 'post_custom_column_views',10,2);
add_filter( 'manage_lists_sortable_columns', 'my_movie_sortable_columns' );

function my_movie_sortable_columns( $columns ) {

    $columns['total_post_view'] = 'total_post_view';

    return $columns;
}

add_action( 'pre_get_posts', 'mycpt_custom_orderby' );

function mycpt_custom_orderby( $query ) {
    if ( ! is_admin() )
        return;

    $orderby = $query->get( 'orderby');

    if ( 'total_post_view' == $orderby ) {
        $query->set( 'meta_key', 'total_post_view' );
        $query->set( 'orderby', 'meta_value_num' );
    }
}
function filter_wpseo_title( $wpseo_replace_vars ) {
    // make filter magic happen here...

    return do_shortcode($wpseo_replace_vars);
};

// add the filter
add_filter( 'wpseo_title', 'filter_wpseo_title', 10, 1 );

add_filter( 'wpseo_metadesc', 'namespace_yoast_metadesc', 10, 1 );
function namespace_yoast_metadesc( $str ) {
    return do_shortcode( $str );
}



/**
 * Enqueue scripts
 *
 * @param string  $handle    Script name
 * @param string  $src       Script url
 * @param array   $deps      (optional) Array of script names on which this script depends
 * @param string|bool $ver       (optional) Script version (used for cache busting), set to null to disable
 * @param bool    $in_footer (optional) Whether to enqueue the script before </head> or before </body>
 */
function multiverse_name_scripts() {
    wp_enqueue_style( 'tags-input', plugins_url()."/".basename( dirname( __FILE__ ) ).'/css/jquery.tagsinput.css' );
    wp_enqueue_style( 'flagstrapcss', plugins_url()."/".basename( dirname( __FILE__ ) ).'/css/flags.css' );
    wp_enqueue_style( 'ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css' );
    wp_enqueue_style( 'flexslider-css', plugins_url()."/".basename( dirname( __FILE__ ) ).'/css/flexslider.css' );
    wp_enqueue_style( 'fancybox-css', plugins_url()."/".basename( dirname( __FILE__ ) ).'/css/jquery.fancybox.min.css' );
    wp_enqueue_style( 'list-creator', plugins_url()."/".basename( dirname( __FILE__ ) ).'/css/list-creator.css',array(),time() );
	//wp_enqueue_style( 'list-fontawsm', plugins_url()."/".basename( dirname( __FILE__ ) ).'/css/fontawsm.css' );
	//wp_enqueue_script( 'list-fontawsm' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-autocomplete' );

    wp_enqueue_script( 'tags-input', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/jquery.tagsinput.js', array( 'jquery' ), false, false );
    wp_enqueue_script( 'flexslider-new', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/jquery.flexslider-min.js', array( 'jquery' ), false, false );

    wp_enqueue_script( 'match-height', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/jquery.matchHeight.js', array( 'jquery' ), false, false );
    wp_enqueue_script( 'sticky_kit', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/sticky_kit.js', array( 'jquery' ), false, false );

    wp_enqueue_script( 'fancybox-js', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/jquery.fancybox.min.js', array( 'jquery' ), false );
    wp_register_script( 'list-creator', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/list-creator-scripts.js', array( 'jquery' ), time(), false );
	
    wp_localize_script('list-creator', 'gMesLCData', array(
        'ajaxUrl' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_script( 'list-creator');

    //sunil

    //end sunil
	
	wp_enqueue_script( 'morris-min-js', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/morris.min.js', array( 'jquery' ), false );
	
	
	
	//wp_register_script( 'chart', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/linechart.js', array( 'jquery' ), false, true );
	 //wp_enqueue_script( 'chart');
	
	//wp_register_script( 'chartline', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/linechartmin.js', array( 'jquery' ), false, true );
	 //wp_enqueue_script( 'chartline');
	
	 wp_register_script( 'chart-bundel', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/Chart.bundle.js', array( 'jquery' ), false, true );
	 wp_enqueue_script( 'chart-bundel');
	 /*wp_register_script( 'pricing-graph', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/pricegraph.js', array( 'jquery' ), false, true );
	   wp_enqueue_script( 'pricing-graph'); */
	  wp_register_script( 'util', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/utils.js', array( 'jquery' ), false, true );
	  wp_enqueue_script( 'util');
	  wp_register_script( 'doughnut', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/ChartDoughnut.js', array( 'jquery' ), false, true );
	  wp_enqueue_script( 'doughnut');
	  wp_register_script( 'inview', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/jquery.inview.min.js', array( 'jquery' ), false, true );
	  wp_enqueue_script( 'inview');
      wp_register_script( 'flagstrap', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/jquery.flagstrap.min.js', array( 'jquery' ), false, true );
      wp_enqueue_script( 'flagstrap');
      wp_register_script( 'popper', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/popper.min.js', array( 'jquery' ), false, true );
	  wp_enqueue_script( 'popper');
	  
}

add_action( 'wp_enqueue_scripts', 'multiverse_name_scripts' );


function multiverse_admin_scripts() {
    wp_enqueue_style( 'list-admin', plugins_url()."/".basename( dirname( __FILE__ ) ).'/css/list-admin.css' );
	
}

add_action( 'admin_enqueue_scripts', 'multiverse_admin_scripts' );

//add_action('init','multiverse_get_all_tags');

// function multiverse_get_all_tags(){
//  $terms = get_terms( array(
//      'taxonomy' => 'item_tags',
//      'hide_empty' => true,
//  ) );
//  $tags =array();

//  if($terms){
//   foreach ($terms as  $term) {
//    $tags[]=array('id'=>$term->term_id,'label'=>$term->name, 'value'=>$term->term_id);
//   }

//  }
//  set_transient( 'all_item_tags', $tags );

// }


// add_action('wp_ajax_get_filter_tags','multiverse_get_filter_tags' );
// add_action('wp_ajax_nopriv_get_filter_tags','multiverse_get_filter_tags' );
// function multiverse_get_filter_tags(){

//  $tags = get_transient( 'all_item_tags', $tags );
//  echo json_encode($tags); die;
// }

add_shortcode( 'total_votes','func_total_votes' );


function func_total_votes( $atts ) {

    // print_r( $atts['id'] ); die;
    $atts = shortcode_atts( array(
        'id' => get_the_ID()
    ), $atts, 'total_votes' );
    $list_id =  $atts['id'];

    $maxLimit = 10;

    /// Prepare the posts //
    global $wp;
    $index =1;
    $main_list_id        = $list_id ;

    $attached_items      = get_field( 'list_items', $list_id, true );
    $promoted_list_items =  get_field( 'promoted_list_items', $list_id, true );
    $items_per_page      = get_field( 'items_per_page', $list_id );


    //$items_by_votes = '';
    $Total = 0;
    foreach ( $attached_items as $key =>$child_post ) {
        $total_votes = get_post_meta( $child_post->ID, 'votes_given', true );
        $date = get_the_date('Y-m-d',$child_post->ID);
        if ( ! isset( $total_votes[$main_list_id] ) ) {
            $total_votes[$main_list_id] = 0;
            $attached_items[$key]->votes = 0;
        }else {
            $attached_items[$key]->votes =$total_votes[$main_list_id];
            $Total += $total_votes[$main_list_id];
        }

    }



    //usort( $attached_items, array( $this, "cmp" ) );
    Mes_Lc_Ext::sort($list_id, $attached_items);

    $itmes_with_promoted = $attached_items;
    $temp_array=array();

    // make promoted items at top
    if ( ! empty( $promoted_list_items ) ) {
        foreach ( $attached_items as $key => $item ) {
            if ( in_array( $item->ID, $promoted_list_items ) ) {
                unset( $itmes_with_promoted[$key] );
                $item->promoted =1;
                $temp_array[]=$item;
            }

        }
    }
    $attached_items = array_merge( $temp_array, $itmes_with_promoted );
    $posts =  array_slice( $attached_items, 0 , $maxLimit );
    $totalVotes = 0;
    $totalActVOtes = 0;

    if(!empty($posts))
    {
        foreach($posts as $key=>$list_post)
        {
            //$totalVotes += $list_post->votes;
            if ($list_post->votes > 0) {
                // $list_post->votes = $list_post->votes*2.27;
                $totalActVOtes  += $list_post->votes;
                $totalVotes += Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);


            }
        }
    }
    $finalVotes = round($totalActVOtes*2.7);

    return $finalVotes;
}
function suppress_if_blurb( $atts ) {
    $atts = shortcode_atts( array(
        'id' => get_the_ID()
    ), $atts, 'list_number' );
    $list_id =  $atts['id'];
    $count = '' ;
    $attached_items      = get_field( 'list_items',$list_id, true );
	
    if ( $attached_items ) {
        $count = count( $attached_items );
		if(isset($_GET['sort'])){
		 $sorts = $_GET['sort'];
		}
		if(isset($sorts) && $sorts =="free"){
		foreach($attached_items as $key=>$list_post){
			if ($list_post->votes > 0) {
				    $totalActVOtes = "";
					$totalVotes = "";
                    $totalActVOtes  += $list_post->votes;
                    $totalVotes += Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
					$score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
                    $score = ($score / $totalVotes) * 100;
                    $score = number_format($score, 2);
                }else{
					$score = 0;
				}
		$pricemodel12 = get_field( 'pricing_model', $list_post->ID );
		$freetrial = get_field( 'free_trial', $list_post->ID);
		$postarr12[$list_post->ID] = $pricemodel12;
		if(empty($postarr12[$list_post->ID])){
			$postarr12[$list_post->ID] = array();
		}
	if(in_array('freemium', $postarr12[$list_post->ID]) || in_array('open_source', $postarr12[$list_post->ID]) || $freetrial == 1) {
				     $postarr[$list_post->ID] = $score;
				  }	
	 }
	 $count = count( $postarr )." Free";
		}elseif(isset($sorts) && $sorts =="affordable_price"){
		$count = "The Cheapest";	
		}elseif(isset($sorts) && $sorts =="user_friendly"){
			$count = "The Most User friendly";
		}
    }

    return $count;
}
add_shortcode( 'list_number', 'suppress_if_blurb' );

/* add_shortcode( 'ip_to_location', 'ip_to_location' ); replaced by geoip plugin wordpress
function ip_to_location(){
    // file_put_contents("list-creator.txt","ip IN IP_to_location is :".$_SERVER['REMOTE_ADDR'],FILE_APPEND);

    $locationObj = ip_info($_SERVER['REMOTE_ADDR'], "Country");
    return $locationObj->name;
} */
/* add_filter( 'post_type_link', 'append_query_string', 10, 2 );
function append_query_string( $url, $post ) 
{
    $locationObj = ip_info($_SERVER['REMOTE_ADDR'], "Country");
    return $url.'?lang='.($locationObj->gec);
} */
/* function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    file_put_contents("list-creator.txt","ip is :".$ip,FILE_APPEND);
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("https://api.ipgeolocationapi.com/geolocate/" . $ip));
        // file_put_contents("list-creator.txt","ipdat is :".print_r($ipdat,true),FILE_APPEND);

       /*  if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        } */
    /*}
    $output = $ipdat;
    return $output;
} */

function mv_enable_shortcode_in_title( $title ) {
    //if ( !is_admin() ) {

    return do_shortcode( $title );

    //}
    return $title;
}

add_filter( 'wp_title', 'mv_enable_shortcode_in_title',100 );
add_filter( 'the_title', 'mv_enable_shortcode_in_title' );
function get_item_rank($list_id,$list_itemid){
    // file_put_contents("debug.txt","list id : $list_id, list item id : $list_itemid".PHP_EOL,FILE_APPEND);
    $maxLimit = 10;

    /// Prepare the posts //
    global $wp;
    $main_list_id        = $list_id ;

    $promoted_list_items =  get_field( 'promoted_list_items', $list_id, true );
    // file_put_contents("debug.txt","promoted items : ".$promoted_list_items.PHP_EOL,FILE_APPEND);

    // file_put_contents("debug.txt","promoted items : ".print_r($promoted_list_items,true).PHP_EOL,FILE_APPEND);

    $attached_items      = get_field( 'list_items', $list_id, true );
    // file_put_contents("debug.txt","attached items : ".print_r($attached_items,true).PHP_EOL,FILE_APPEND);

    
    $items_per_page      = get_field( 'items_per_page', $list_id );
    // file_put_contents("debug.txt","items per page : ".print_r($items_per_page,true).PHP_EOL,FILE_APPEND);

    //$items_by_votes = '';
    $Total = 0;
    foreach ( $attached_items as $key =>$child_post ) {
        $total_votes = get_post_meta( $child_post->ID, 'votes_given', true );
        $date = get_the_date('Y-m-d',$child_post->ID);
        if ( ! isset( $total_votes[$main_list_id] ) ) {
            $total_votes[$main_list_id] = 0;
            $attached_items[$key]->votes = 0;
        }else {
            $attached_items[$key]->votes =$total_votes[$main_list_id];
            $Total += $total_votes[$main_list_id];
        }

    }

    //usort( $attached_items, array( $this, "cmp" ) );
    Mes_Lc_Ext::sort($list_id, $attached_items);

    $itmes_with_promoted = $attached_items;
    $temp_array=array();

    // make promoted items at top
    if ( ! empty( $promoted_list_items ) ) {
        foreach ( $attached_items as $key => $item ) {
            if ( in_array( $item->ID, $promoted_list_items ) ) {
                unset( $itmes_with_promoted[$key] );
                $item->promoted =1;
                $temp_array[]=$item;
            }

        }
    }
    $attached_items = array_merge( $temp_array, $itmes_with_promoted );
    $posts =  $attached_items;

    $graphData = array();
    $totalVotes = 0;
    $totalActVOtes = 0;
    $postarr = array();
    $postarrOBJ = array();
    if(!empty($posts))
    {
        foreach($posts as $key=>$list_post)
        {
            //$totalVotes += $list_post->votes;
            if ($list_post->votes > 0) {
                // $list_post->votes = $list_post->votes*2.27;
                $totalActVOtes  += $list_post->votes;
                $totalVotes += Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);


            }
        }
    }
    if(!empty($posts))
    {
        foreach($posts as $key=>$list_post)
        {
            //$totalVotes += $list_post->votes;
//            if ($list_post->votes > 0) {

            $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
            $score = ($score / $totalVotes) * 100;
            $score = number_format($score, 2);
            $postarr[$list_post->ID] = $score;

//            }
        }
    }
    arsort($postarr);
    $i =  array_search($list_itemid, array_keys($postarr));

    return $i+1;

}
add_action( 'pre_get_posts', 'my_change_sort_order');
function my_change_sort_order($query){
    if(!is_admin() && $query->is_main_query()):
        if(is_post_type_archive('lists')) {
            //If you wanted it for the archive of a custom post type use: is_post_type_archive( $post_type )
            //Set the order ASC or DESC
            $meta_query = array(
                'relation' => 'OR',
                array(
                    'key' => 'total_post_view',
                    'value' => 0,
                    'compare' => '>='
                ),
                array(
                    'key' => 'total_post_view',
                    'compare' => 'NOT EXISTS'
                )
            );
            $query->set('order', 'DESC');
            //Set the orderby
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_query', $meta_query);
            $query->set('meta_key', 'total_post_view');

            // $query->set('meta_query', $meta_query);
        }
    endif;

};
/*Include ajax functionality */
add_action( 'wp_enqueue_scripts', 'graph_js_include' );
function graph_js_include()
{
    wp_enqueue_script( 'theme.js', plugins_url( '/list-creator/js/ajax-scripts.js', __DIR__ ), array(), '3.7.3',false );
    wp_localize_script( 'theme.js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_ajax_nopriv_get_graph_data', 'graph_get_graph_data' );
add_action( 'wp_ajax_get_graph_data', 'graph_get_graph_data' );
function graph_get_graph_data()
{
    $list_id = $_POST['listID'];
    $duration = $_POST['duration'];
    $maxLimit = 8;

    /// Prepare the posts //
    global $wp;
    $index =1;
    $main_list_id        = $list_id ;
    $main_list_permalink = get_the_permalink( $list_id );
    $attached_items      = get_field( 'list_items', $list_id, true );
    $promoted_list_items =  get_field( 'promoted_list_items', $list_id, true );
    $items_per_page      = get_field( 'items_per_page', $list_id );
    $voting_closed       = get_field( 'voting_closed', $list_id );

    //$items_by_votes = '';
    $Total = 0;
    foreach ( $attached_items as $key =>$child_post ) {
        $total_votes = get_post_meta( $child_post->ID, 'votes_given', true );
        $date = get_the_date('Y-m-d',$child_post->ID);
        if ( ! isset( $total_votes[$main_list_id] ) ) {
            $total_votes[$main_list_id] = 0;
            $attached_items[$key]->votes = 0;
        }else {
            $attached_items[$key]->votes =$total_votes[$main_list_id];
            $Total += $total_votes[$main_list_id];
        }

    }

    $index = ( ( $current_page - 1 ) * $items_per_page )+1;

    //usort( $attached_items, array( $this, "cmp" ) );
    Mes_Lc_Ext::sort($list_id, $attached_items);

    $itmes_with_promoted = $attached_items;
    $temp_array=array();

    // make promoted items at top
    if ( ! empty( $promoted_list_items ) ) {
        foreach ( $attached_items as $key => $item ) {
            if ( in_array( $item->ID, $promoted_list_items ) ) {
                unset( $itmes_with_promoted[$key] );
                $item->promoted =1;
                $temp_array[]=$item;
            }

        }
    }
    $attached_items = array_merge( $temp_array, $itmes_with_promoted );
    $posts =  array_slice( $attached_items, 0 , $maxLimit );

    $graphData = array();
    $totalVotes = 0;
    $totalActVOtes = 0;
    $postarr = array();
    $postarrOBJ = array();
    if(!empty($posts))
    {
        foreach($posts as $key=>$list_post)
        {
            //$totalVotes += $list_post->votes;
            if ($list_post->votes > 0) {
                // $list_post->votes = $list_post->votes*2.27;
                $totalActVOtes  += $list_post->votes;
                $totalVotes += Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);


            }
        }
    }
    if(!empty($posts))
    {
        foreach($posts as $key=>$list_post)
        {
            //$totalVotes += $list_post->votes;
            if ($list_post->votes > 0) {

                $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
                $score = ($score / $totalVotes) * 100;
                $score = number_format($score, 2);
                $postarr[$list_post->ID] = $score;
                $postarrOBJ[$list_post->ID] = $list_post;

            }
        }
    }
    arsort($postarr);
    $footer = array();$labels = array();
    $html = '<div class="starter">';
    $liIndex = 0;
    if(!empty($posts))
    {
        $totalList = 0;
$arrTitles = array();
        foreach($postarr as $key=>$score) {
            $list_post = $postarrOBJ[$key];
            $list_id = $list_post->ID;
            if ($list_post->votes > 0) {
                //$list_post->votes = $list_post->votes*2.27;
                $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
                //$score = ($list_post->votes / $totalVotes) * 100;
                //$vote = $list_post->votes;
                //$age = $list_post->age;
                $score = ($score / $totalVotes) * 100;
                $score = number_format($score, 2);

                $featured_img_url = get_the_post_thumbnail_url($list_id, array(80, 60));

                $affiliate_url = get_field('affiliate_url', $list_id);
                $affiliate_button_text = get_field('affiliate_button_text', $list_id) == '' ? 'Download' : get_field('affiliate_button_text', $list_id);
                $title = get_the_title($list_id);
                $anchors  = list_url_anchor_target($title,$arrTitles);
                $defId = str_replace(' ','_',$title);
                $anchorId = isset($anchors['value'])?$anchors['value']:$defId;
                $arrTitles = isset($anchors['titles'])?$anchors['titles']:$arrTitles;
                $graphData[] = array('y' => get_the_title($list_id), 'a' => $score . '%');
                $aff = '<p><a class="mes-lc-li-down graph-btn update_list_modified" href="' . $affiliate_url . '" class="mes-lc-li-link" data-zf-post-parent-id="' . $main_list_id . '"  data-zf-post-id="'. $list_id.'"  rel="nofollow" target="_blank"><i class="zf-icon zf-icon-buy_now"></i><span class="affilate_button">' . $affiliate_button_text . '</span></a></p>';

                if ($liIndex == 0) {
                    $img = '<a href="' . get_permalink($list_id) . '" class="mes-lc-li-link" style="position:relative; display:inline-block;" data-zf-post-parent-id="' . $main_list_id . '" rel="nofollow"><img width="80" height="60" src="' . $featured_img_url . '" /><img width="80" height="60" src="../../wp-content/plugins/list-creator/image/crown1.png" style="position: absolute; width: 75%; left: -36%; transform: rotate(-45deg); z-index: 9999; top: -45%;" /></a>';
                } else {
                    $img = '<a href="' . get_permalink($list_id) . '" class="mes-lc-li-link" data-zf-post-parent-id="' . $main_list_id . '" rel="nofollow"><img width="80" height="60" src="' . $featured_img_url . '" /></a>';
                }
                $liIndex++;
                $labelData = '<div class="single_data" id="label_con_'.$totalList.'"><span class="l_title"><a class="barlink" href="#' .$anchorId . '"><span class="numtext"><img  src="' . $featured_img_url . '" /></span><span class="acttext">' . get_the_title($list_id) . '</span></a></span></div>';
                $html .= $labelData;
                $labels[] = $labelData;
                $totalList++;
                //$footer[] = array('title'=>get_the_title( $list_id ));
            }

        }
    }
    $html .= '</div>';
    $finalVotes = round($totalActVOtes*2.7);
    if($finalVotes >=200){
        $votesText = "Based on ".$finalVotes." votes cast";
    } else{
        $votesText = ' ';
    }
    echo json_encode(array('gdata'=>$graphData,'fdata'=>$html,'labels' =>$labels,'votes'=>$votesText));
    wp_die();
}
function filter_wpseo_opengraph_show_publish_date( $false, $get_post_type ) {

    if($get_post_type == 'lists'){
        return true;
    }
    // make filter magic happen here...
    return $false;
};

// add the filter
add_filter( 'wpseo_opengraph_show_publish_date', 'filter_wpseo_opengraph_show_publish_date', 10, 2 );

function update_list_modified($postId){
    $my_post = array();
    $my_post['ID'] = $postId;
    $date = date("Y:m:d H:i:s");
    $gmtdate =  get_gmt_from_date($date);
    $my_post['post_modified'] = $date;
    $my_post['post_modified_gmt'] = $gmtdate;
// Update the post into the database
    wp_update_post( $my_post );
}
function add_admin_scripts( $hook ) {

    global $post,$pagenow, $typenow, $taxnow ;;
    if ( $hook == 'post-new.php' || $hook == 'post.php' || $hook == 'edit-tags.php' || $hook == 'term.php'  ) {
        if ( 'list_items' === $post->post_type || $taxnow === 'list_comp_categories' ) {
            wp_enqueue_style( 'list_admin', plugins_url()."/".basename( dirname( __FILE__ ) ).'/css/admin.css',array(),time() );
            wp_enqueue_script(  'list.min.js', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/list.min.js', array( 'jquery' ), false, false  );
            wp_enqueue_script(  'jquery.matchHeight.js', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/jquery.matchHeight.js', array( 'jquery' ), false, false  );
            wp_enqueue_script(  'list_admin.js', plugins_url()."/".basename( dirname( __FILE__ ) ).'/js/admin.js', array( 'jquery' ), time(), false  );

        }
    }
}
add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );

function alternative_list_count($atts) {
$atts = shortcode_atts( array(
        'id' => get_the_ID()
    ), $atts, 'list_number' );
    $list_id =  $atts['id'];
    $count = '' ;
	$compareClass = new Mv_List_Comparision();
	$alterItems = $compareClass->most_compared($list_id,100,true);
	$count = count($alterItems);
	 if(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'free'){
	foreach ( $alterItems as $pid ) {
		 $pricing_model = get_field('pricing_model', $pid);
		 if(count($pricing_model) > 0) {
                            if(!empty($pricing_model[0]))
                             $p_model = ucwords(str_replace("_", " ", $pricing_model[0]));
                            else
                             $p_model = "Not available";
                        } else {
                            $p_model = "Not available";
                        }
		 if($p_model == "Freemium"){
			$alterItems1[$pid] = $pid; 
		 }
	}
	$count = count($alterItems1);
	 }elseif(isset($_REQUEST['alternative']) && $_REQUEST['alternative'] == 'price'){
	$alterItems = $compareClass->price_filter($alterItems);
	$count = count($alterItems);
}
  
return $count;
   
}
add_shortcode( 'total_items', 'alternative_list_count' );


function alternative_name($atts) {
$atts = shortcode_atts( array(
        'id' => get_the_ID()
    ), $atts, 'list_number' );
    $list_id =  $atts['id'];
    $count = '' ;
	$name = get_the_title( $list_id );
  
return $name;
   
}
add_shortcode( 'item_name', 'alternative_name' );


add_action( 'wp_ajax_nopriv_my_action', 'my_action' );

function my_action() {
	global $wpdb;

 
 $theme_ids = $_POST['post_ids'];
 $feature_name = str_replace(' ', '_', strtolower($_POST['feature_name']));
 $act = $_POST['act'];
 $btn_id = $_POST['btn_id'];
 $add_vote = $_POST['votes'];
 $xx = $_SESSION['user_ip'];
 $_SESSION['post_ids']= $theme_ids;
 
  foreach($theme_ids as $theme_id){
 $db_data1 = $wpdb->get_results( "SELECT * FROM wpxx_feature_rating WHERE post_id = '".$theme_id."' AND feature_name = '".$feature_name."' " );
  foreach($db_data1 as $datas){
	  $po_id1 =  $datas->post_id;
	  $feature_namess1 =  $datas->feature_name;
	  $vote1 = $datas->votes;
  }
  if($act == 'relevent'){
	  $v1 =  $vote1 + $add_vote;
  }	else{
	$v1 = $vote1 - $add_vote; 
  }
  if($wpdb->num_rows > 0){
	   if($po_id1 == $theme_id && $feature_name == $feature_namess1){
		  echo "u1";
		$wpdb->query($wpdb->prepare("UPDATE wpxx_feature_rating SET votes='$v1' WHERE post_id = '".$theme_id."' AND feature_name = '".$feature_name."'"));
	   }
	    }else{
	   $wpdb->insert( 'wpxx_feature_rating', array(
            'post_id' => $theme_id, 
            'feature_name' => $feature_name,
            'votes' => $_POST['votes']
             ),
            array( '%s', '%s', '%s', '%s')
        );
     }
  }

  
   if(!isset($_SESSION['feature_btn_actions'])){
    //If it doesn't, create an empty array.
    	$_SESSION['feature_btn_actions'] = array();
		//$_SESSION['feature_btn_act'][$feature_namess] = $btn_id;
    }
	if($_SESSION['feature_btn_actions'][$feature_name] != $btn_id){
	$_SESSION['feature_btn_actions'][$feature_name] = $btn_id;
	}
   
	wp_die(); // this is required to terminate immediately and return a proper response
}
function get_the_user_ip() {
if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
//check ip from share internet
$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//to check ip is pass from proxy
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}
return apply_filters( 'wpb_get_ip', $ip );
}
 
add_shortcode('show_ip', 'get_the_user_ip');