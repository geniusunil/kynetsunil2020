<?php
// file_put_contents("mvcf.txt","");

// register register_list_filter_widget

function register_list_filter_widget() {

    register_widget( 'Mv_List_Filter_Widget' );

}

add_action( 'widgets_init', 'register_list_filter_widget' );

function mv_custom_post_type_template( $archive_template ) {

    global $post;

    global $wp_query;

    $taxonomy = get_query_var( 'taxonomy' );

    if($taxonomy == 'list_categories' ){

        $archive_template = MV_LIST_DIR_URL . '/inc/templates/list-items-category-archive.php';

    }elseif ( is_post_type_archive ( 'list_items' ) || $taxonomy == 'item_tags' ) {

        $archive_template = MV_LIST_DIR_URL . '/inc/templates/list-items-archive.php';

    }elseif ( is_post_type_archive ( 'lists' ) ) {

        $archive_template = MV_LIST_DIR_URL . '/inc/templates/lists-archive.php';

    }
    return $archive_template;
}
add_filter( 'archive_template', 'mv_custom_post_type_template' ) ;

add_filter( 'taxonomy_template', 'mv_custom_post_type_template' ) ;


/* 
function mv_custom_alternative_route_template( $template ) {

    global $wp_query;

    $item_name = get_query_var( 'item_name' );

    if (  !empty( $item_name ) ) {

        $template = MV_LIST_DIR_URL . '/inc/templates/list-items-alternative.php';

    }

    return $template;

} */



// add_filter( 'template_include', 'mv_custom_alternative_route_template' ) ;


/*coupon section start*/

function mv_custom_coupon_alternative_route_template( $template ) {

    global $wp_query;

    $item_name = get_query_var( 'item_name' );
	// echo $item_name;

    if (  !empty( $item_name ) ) {
        if ( is_page( 'coupon' )){
            $template = MV_LIST_DIR_URL . '/inc/templates/list-items-coupon.php';

        }
        else{
            $template = MV_LIST_DIR_URL . '/inc/templates/list-items-alternative.php';
   
        }

    }
    // echo "template is $template";
    return $template;

}
add_filter( 'template_include', 'mv_custom_coupon_alternative_route_template' );

/*coupon section start*/



function mv_custom_post_type_single_template( $single_template ) {

    global $post;

    $user = wp_get_current_user();

    if ( $post->post_type == 'lists' ) {



        $single_template = MV_LIST_DIR_URL . '/inc/templates/single-lists-new.php';



    } else if ( $post->post_type == 'list_items' ) {



        $single_template = MV_LIST_DIR_URL . '/inc/templates/single-list-items-new.php';



    }

    return $single_template;

}

add_filter( 'single_template', 'mv_custom_post_type_single_template' );
add_filter( 'page_template', 'wpa3396_page_template' );

function wpa3396_page_template( $page_template )

{

    if ( is_page( 'compare' ) ) {

        $page_template =  MV_LIST_DIR_URL . '/inc/templates/single-list-items-comparison.php';

    }

    return $page_template;

}

add_filter('wpseo_title', 'add_to_page_titles');\
	
	add_filter( 'page_template', 'deal_page' );
function deal_page( $page_template )
{
    if ( is_page( 'deals' ) ) {
        $page_template = MV_LIST_DIR_URL . '/inc/templates/list-items-deal.php';
    }
    return $page_template;
}

function add_to_page_titles($title) {

    if ( is_page( 'compare' ) ) {



    }



    return $title;

}
/*coupon section start*/
add_action( 'init', 'coupon_init' );

function coupon_init() {



    add_rewrite_tag( '%item_name%', '([^&]+)' );

    $page = get_page_by_path( 'coupon' );

    $pid  = $page->ID;

    add_rewrite_rule(

        'reviews/(.+)/coupon/?$',

        'index.php?page_id='.$pid.'&item_name=$matches[1]',

        'top' );



}


/*coupon section end*/


add_action( 'init', 'wpse26388_rewrites_init' );

function wpse26388_rewrites_init() {



    add_rewrite_tag( '%item_name%', '([^&]+)' );

    $page = get_page_by_path( 'alternative' );

    $pid  = $page->ID;

    add_rewrite_rule(

        'reviews/(.+)/alternative/?$',

        'index.php?page_id='.$pid.'&item_name=$matches[1]',

        'top' );



}


/**

 * Add meta tag & title to alternative page

 */


/*coupon start-[-----------------------------------------------------------*/
/* 
function alter_coupon_page_title( $title ) {

    global $wp_query;

    $item_name = get_query_var( 'item_name' );

    // if condition for you parameter

     if ( !empty( $item_name ) ) {

        $post = get_page_by_path( $item_name, OBJECT, 'list_items' );

        $settings = get_option( 'mv_list_items_settings' );
		$id = $post->ID;
        $page_title = $settings['coupon_page_title'];
        echo "page title is ".$page_title;
        $title = str_replace( '[item_name]', $post->post_title, $page_title );

        $title = str_replace( '[Year]', date('Y'), $title );
		
		$title = str_replace( '[total_items]', do_shortcode('[total_items id ="'.$id.'"]'), $title );
		

    }

      return $title;

}
 */
// add_filter( 'wp_title', 'alter_coupon_page_title', 200 );

// add_filter( 'wpseo_title', 'alter_coupon_page_title', 200 );

/*coupon start-------------------------------------------------------------------------------*/


function alter_coupon_alternative_page_title( $title ) {

    global $wp_query;

    $item_name = get_query_var( 'item_name' );

    // if condition for you parameter

     if ( !empty( $item_name ) ) {
        $post = get_page_by_path( $item_name, OBJECT, 'list_items' );

        $settings = get_option( 'mv_list_items_settings' );
        $id = $post->ID;

        if ( is_page( 'coupon' )){
           $page_title = $settings['coupon_page_title'];
        //    $title = $page_title;
        //    echo "page title is ".$page_title;

        }
        else{
            
            $page_title = $settings['alternative_page_title'];
    
    
        }

        $title = str_replace( '[item_name]', $post->post_title, $page_title );
    
        $title = str_replace( '[Year]', date('Y'), $title );
        $title = str_replace( '[month]', date('M'), $title );
        $title = str_replace( '[total_items]', do_shortcode('[total_items id ="'.$id.'"]'), $title );
        // echo "page title is ".$page_title;


    }

      return $title;

}

add_filter( 'wp_title', 'alter_coupon_alternative_page_title', 200 );

add_filter( 'wpseo_title', 'alter_coupon_alternative_page_title', 200 );



function alter_coupon_alternative_page_description( $description ) {

    global $wp_query;

    $item_name = get_query_var( 'item_name' );

    // if condition for you parameter

    if ( !empty( $item_name ) ) {
        $post = get_page_by_path( $item_name, OBJECT, 'list_items' );

        $settings = get_option( 'mv_list_items_settings' );
        if ( is_page( 'coupon' )){
            $page_title = $settings['coupon_page_description'];
            // $description = $page_title;
        }
        else{
            
    
            $page_title = $settings['alternative_page_description'];
    
           
        }
        $description = str_replace( '[item_name]', $post->post_title, $page_title );
        $description = str_replace( '[month]', date('M'), $description );

        $description = str_replace( '[Year]', date('Y'), $description );

      
    }

    return $description;

}

add_filter( 'wpseo_metadesc', 'alter_coupon_alternative_page_description', 200 );

/*coupon description---------------------------------*/
/* function alter_coupon_page_description( $description ) {

    global $wp_query;

    $item_name = get_query_var( 'item_name' );

    // if condition for you parameter

    if ( !empty( $item_name ) ) {

        $post = get_page_by_path( $item_name, OBJECT, 'list_items' );

        $settings = get_option( 'mv_list_items_settings' );

        $page_title = $settings['coupon_page_description'];

        $description = str_replace( '[item_name]', $post->post_title, $page_title );

        $description = str_replace( '[Year]', date('Y'), $description );

    }

    return $description;

}

add_filter( 'wpseo_metadesc', 'alter_coupon_page_description', 200 );

 */

/*coupon description---------------------------------*/


/**

 * Register Sidebars

 */



add_action( 'widgets_init', 'mv_slug_widgets_init' );

function mv_slug_widgets_init() {

    register_sidebar( array(

        'name' => __( 'List Archive Sidebar', 'theme-slug' ),

        'id' => 'lists-archive-sidebar',

        'description' => __( 'Widgets in this area will be shown on all List Archive Page', 'theme-slug' ),

        'before_widget' => '<li id="%1$s" class="widget %2$s">',

        'after_widget'  => '</li>',

        'before_title'  => '<h2 class="widgettitle">',

        'after_title'   => '</h2>',

    ) );



    register_sidebar( array(

        'name' => __( 'Review Archive Sidebar', 'theme-slug' ),

        'id' => 'review-archive-sidebar',

        'description' => __( 'Widgets in this area will be shown on all Review Archive Page', 'theme-slug' ),

        'before_widget' => '<li id="%1$s" class="widget %2$s">',

        'after_widget'  => '</li>',

        'before_title'  => '<h2 class="widgettitle">',

        'after_title'   => '</h2>',

    ) );



    // register_sidebar( array(

    // 		'name' => __( 'Category/Tags Sidebar', 'theme-slug' ),

    // 		'id' => 'cat-tag-archive-sidebar',

    // 		'description' => __( 'Widgets in this area will be shown on all Categories & Tags Page', 'theme-slug' ),

    // 		'before_widget' => '<li id="%1$s" class="widget %2$s">',

    // 		'after_widget'  => '</li>',

    // 		'before_title'  => '<h2 class="widgettitle">',

    // 		'after_title'   => '</h2>',

    // 	) );

    register_sidebar( array(

        'name' => __( 'Alternative Page Sidebar', 'theme-slug' ),

        'id' => 'alternative-sidebar',

        'description' => __( 'Widgets in this area will be shown on Alternative Page', 'theme-slug' ),

        'before_widget' => '<li id="%1$s" class="widget %2$s">',

        'after_widget'  => '</li>',

        'before_title'  => '<h2 class="widgettitle">',

        'after_title'   => '</h2>',

    ) ); 
	
	register_sidebar( array(

        'name' => __( 'Coupon Page Sidebar', 'theme-slug' ),

        'id' => 'coupon-sidebar',

        'description' => __( 'Widgets in this area will be shown on Coupon Page', 'theme-slug' ),

        'before_widget' => '<li id="%1$s" class="widget %2$s">',

        'after_widget'  => '</li>',

        'before_title'  => '<h2 class="widgettitle">',

        'after_title'   => '</h2>',

    ) );

}



/**

 * Save user rating average in post meta so that it can be used to filter posts

 *

 * @author Aman Saini

 * @since  1.0

 * @param [type]  $post_id

 * @return [type]

 */

function save_rating_on_rating_submit( $post_id ) {



    $likes = get_post_meta( $post_id, 'rwp_likes' );

    foreach ( $likes as $like ) {

        if ( is_array( $like ) ) {

            foreach ( $like as $review ) {

                $review_id = $review['review_id'];

            }



        }else {

            $review_id = $review['review_id'];

        }

    }



    $user_reviews = get_post_meta( $post_id, 'rwp_rating_'.$review_id, false );



    $rating = '';

    $i= 0;

    foreach ( $user_reviews as $key => $value ) {

        // echo RWP_Reviewer::get_avg( $value['rating_score'] ) ;

        // echo '<br/>';

        $rating+= RWP_Reviewer::get_avg( $value['rating_score'] ) ;

        $i++;

    }

    if ( $i!=0 ) {

        $total_score = ( $rating / $i );

    }else {

        $total_score = 0;

    }



    update_post_meta( $post_id, 'user_rating_custom', $total_score );



}

//$custom_tax_mb = new Taxonomy_Single_Term( 'list_comp_categories',array('list_items') );

function get_pew_related_data( $post_id,$args=array()) {

    global $post, $wpdb;

    $post_id = intval( $post_id );

    if( !$post_id && $post->ID ) {

        $post_id = $post->ID;

    }



    if( !$post_id ) {

        return false;

    }

    $campobj = get_the_terms( $post_id, 'list_comp_categories' );

    if ( $campobj && ! is_wp_error( $campobj ) ) {

        $comp_categories = $campobj;

        if(!empty($comp_categories)){

            $compareterm = $comp_categories[0]->term_id;

        }

    }



    $defaults = array(

        'taxonomy' => 'item_tags',

        'post_type' => array('list_items'),

        'max' => 5,

        'post__not_in'=>array()

    );

    $options = wp_parse_args( $args, $defaults );



//    $transient_name = 'pew-related-' . $options['taxonomy'] . '-' . $post_id;

//

//    if( isset($_GET['flush-related-links']) && is_user_logged_in() ) {

//        echo '<p>Related links flushed! (' . $transient_name . ')</p>';

//        delete_transient( $transient_name );

//    }



//    $output = get_transient( $transient_name );

//    if( $output !== false && !is_preview() ) {

//        //echo $transient_name . ' read!';

//        return $output;

//    }



    $args = array(

        'fields' => 'ids',

        'orderby' => 'count',

        'order' => 'ASC'

    );

    $orig_terms_set = wp_get_object_terms( $post_id, $options['taxonomy'] ,$args);



    //Make sure each returned term id to be an integer.

    $orig_terms_set = array_map('intval', $orig_terms_set);



    //Store a copy that we'll be reducing by one item for each iteration.

    $terms_to_iterate = $orig_terms_set;

    $postNot_in = array_merge(array($post_id),$options['post__not_in']);

    $post_args = array(

        'fields' => 'ids',

        'post_type' => $options['post_type'],

        'post__not_in' => $postNot_in,

        'posts_per_page' => 50

    );

    $output = array();

    while( count( $terms_to_iterate ) > 1 ) {



        $post_args['tax_query'] = array(

            array(

                'taxonomy' => $options['taxonomy'],

                'field' => 'id',

                'terms' => $terms_to_iterate,

//                'operator' => 'AND'

            )

        );

        if(!empty($compareterm)){



            $post_args['tax_query'][] = array(

                'taxonomy' => 'list_comp_categories',

                'field' => 'id',

                'terms' => $compareterm,



            );

        }

        $post_args['post__not_in'] = array_merge($post_args['post__not_in'],$output);

        $posts = get_posts( $post_args );



        foreach( $posts as $id ) {

            $id = intval( $id );

            if( !in_array( $id, $output) ) {

                $output[] = $id;

            }

        }

        array_pop( $terms_to_iterate );

    }



    $post_args['posts_per_page'] = 10;

    $post_args['tax_query'] = array(

        array(

            'taxonomy' => $options['taxonomy'],

            'field' => 'id',

            'terms' => $orig_terms_set

        )

    );

    if(!empty($compareterm)){

        $post_args['tax_query'][] = array(

            'taxonomy' => $options['taxonomy'],

            'field' => 'id',

            'terms' => $compareterm,



        );

    }



    $posts = get_posts( $post_args );



    foreach( $posts as $count => $id ) {

        $id = intval( $id );

        if( !in_array( $id, $output) ) {

            $output[] = $id;

        }

        if( count($output) > $options['max'] ) {

            //We have enough related post IDs now, stop the loop.

            break;

        }

    }



//    if( !is_preview() ) {

//        //echo $transient_name . ' set!';

//        set_transient( $transient_name, $output, 24 * HOUR_IN_SECONDS );

//    }



    return $output;

}



function genearate_post_slug($postsArr=array()){
    $postsArrOriginal = $postsArr;
    sort($postsArr);
    /* if(count($postsArr) ==2){
        file_put_contents("mvcf.txt","postarroriginal is: ".print_r($postsArrOriginal,true)."\n and postsArr is :".print_r($postsArr,true),FILE_APPEND);
    
        if($postsArrOriginal[0] != $postsArr[0]){
            wp_safe_redirect( 'https://next.softwarefindr.com/' );
            exit;
        }
    }
     */
    $slug = '';

    // $slugArr = '';

    foreach ($postsArr as $id){

        $slugArr[] = get_post_field( 'post_name', $id );

    }

    if(!empty($slugArr)){

        $slug = implode('-vs-',$slugArr);



    }

    return $slug;



}

add_action( 'wp_ajax_create_new_comparison' ,  'create_new_comparison' );

add_action( 'wp_ajax_nopriv_create_new_comparison' ,'create_new_comparison'  );

add_action('wp_ajax_add_comparison_item','callback_add_comparison_item');

add_action('wp_ajax_nopriv_add_comparison_item','callback_add_comparison_item');

add_action('wp_ajax_remove_comparison_item','callback_remove_comparison_item');

add_action('wp_ajax_nopriv_remove_comparison_item','callback_remove_comparison_item');

function create_new_comparison(){

    global $wpdb;



    $table = $wpdb->prefix.'comparison';

    $postId = $_REQUEST['id'];

    $item2 = $_REQUEST['secondary'];

    $item2Arr = explode(',',$item2);

    array_unshift($item2Arr,$postId);

    $userid = 0;

    $inserted = 0;

    $arrayreturn = array('status' => 0, 'msg' => 'error');

    if(!empty($item2Arr) && count($item2Arr) > 1){

        $comi = array_combinations($item2Arr,2);

        foreach ($comi as $its){

           $wpdb->insert($table, array( 'item1' => $its[0], 'item2' => $its[1],'time' => time()), array('%d', '%d', '%d'));

            $inserted ++;

        }



        if ($inserted > 0) {

            $url = generate_compare_link($item2Arr);

            $arrayreturn = array('status' => 1, 'msg' => 'done', 'url' => $url);

        }

    }

    echo json_encode($arrayreturn);

    die;



}

function array_combinations(array $myArray, $choose) {

    global $result, $combination;



    $n = count($myArray);



    function inner ($start, $choose_, $arr, $n) {

        global $result, $combination;



        if ($choose_ == 0) array_push($result,$combination);

        else for ($i = $start; $i <= $n - $choose_; ++$i) {

            array_push($combination, $arr[$i]);

            inner($i + 1, $choose_ - 1, $arr, $n);

            array_pop($combination);

        }

    }

    inner(0, $choose, $myArray, $n);

    return $result;

}

function generate_compare_link($posts=array()){

    $url = get_permalink(get_page_by_path('compare'));

    if(!empty($posts) && count($posts) > 1) {
		
//var_dump($posts);
		
        $url_slug = genearate_post_slug($posts);

        $url = rtrim($url, '/') . '/' . $url_slug;

    }

    return $url ;

}

function callback_add_comparison_item(){

    global $wpdb;



    $table = $wpdb->prefix.'comparison';

    $id = $_REQUEST['id'];

    $comId = $_REQUEST['comid'];

    $comItems = $_REQUEST['comitms'];

    $arrayreturn = array('status' => 0, 'msg' => 'error');

    if( !empty($id)) {

        foreach ($comItems as $it){

            $wpdb->insert($table,array('item1'=>$it,'item2'=>$id,'time'=>time()), array('%d', '%d', '%d'));



        }

        $comItems[] = $id;

        $url_slug = genearate_post_slug($comItems);

        $url = get_permalink(get_page_by_path('compare'));

        $url = rtrim($url,'/').'/'.$url_slug;

        $arrayreturn = array('status' => 1, 'msg' => 'done', 'url' => $url);

    }



    echo json_encode($arrayreturn);

    die;

}

function callback_remove_comparison_item(){

    $id = $_REQUEST['id'];

    $comId = $_REQUEST['comid'];

    $comItems = $_REQUEST['comitms'];

    $arrayreturn = array('status' => 0, 'msg' => 'error');

    if( !empty($id)) {





        if (($key = array_search($id, $comItems)) !== false) {

            unset($comItems[$key]);

        }

        $url_slug = genearate_post_slug($comItems);

        $url = get_permalink(get_page_by_path('compare'));

        $url = rtrim($url,'/').'/'.$url_slug;

        $arrayreturn = array('status' => 1, 'msg' => 'done', 'url' => $url);

    }

    echo json_encode($arrayreturn);

    die;

}

function get_acf_field_groups_by_cpt($compSlug = array()) {

    // need to create cache or transient for this data?



    $result = array();

    $acf_field_groups = acf_get_field_groups();

    foreach($acf_field_groups as $acf_field_group) {

        $count = 0;

        foreach($acf_field_group['location'] as $group_locations) {



            foreach($group_locations as $rule) {



                if($rule['param'] == 'post_type' && $rule['operator'] == '==' && $rule['value'] == 'list_items') {

                    $count++;



                }

                if($rule['param'] == 'post_taxonomy' && $rule['operator'] == '==' && in_array($rule['value'],$compSlug)) {

                    $count++;



                }

                if($count == count($group_locations) && $count >=2 ){



                    $result[] =  $acf_field_group ;

                }



            }



        }



    }



    return $result;

}

function acf_get_field_key( $field_name, $post_id ) {

    global $wpdb;

    $acf_fields = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_parent,post_name FROM $wpdb->posts WHERE post_excerpt=%s AND post_type=%s" , $field_name , 'acf-field' ) );

    // get all fields with that name.

    switch ( count( $acf_fields ) ) {

        case 0: // no such field

            return false;

        case 1: // just one result.

            return $acf_fields[0]->post_name;

    }

    // result is ambiguous

    // get IDs of all field groups for this post

    $field_groups_ids = array();

    $field_groups = acf_get_field_groups( array(

        'post_id' => $post_id,

    ) );

    foreach ( $field_groups as $field_group )

        $field_groups_ids[] = $field_group['ID'];



    // Check if field is part of one of the field groups

    // Return the first one.

    foreach ( $acf_fields as $acf_field ) {

        if ( in_array($acf_field->post_parent,$field_groups_ids) )

            return $acf_field->post_name;

    }

    return false;

}

function ordinal_suffix($num){

    $num = $num % 100; // protect against large numbers

    if($num < 11 || $num > 13){

        switch($num % 10){

            case 1: return 'st';

            case 2: return 'nd';

            case 3: return 'rd';

        }

    }

    return 'th';

}

function get_overall_combined_rating($post_id){

    $template_id 	= "rwp_template_590f86153bc54";

    $post_type 	= get_post_type( $post_id );

    $auto_id 	= -1;

    $review_id 	= md5( 'rwp-'. $template_id .'-'. $post_type . '-' . $post_id . '-' . $auto_id );

    $result = RWP_API::get_review( $post_id);

    $result['review_users_score'] =  RWP_Reviewer::get_ratings_single_scores( $post_id, $review_id, $template_id);

    $templates = get_option( 'rwp_templates', array() );



    if( is_array( $templates )  && isset( $templates[$template_id ] ) ) { // template exists

        $template = $templates[$template_id];



        $criteria = $template['template_criterias'];
        if(array_key_exists('review_scores',$result)){
        $scores = $result['review_scores'];
		}else{
			 $scores="";
		}

        $users_scores =  isset( $result['review_users_score']['scores'])?$result['review_users_score']['scores'] : array();



        $rs = array();

        $us = array();

        foreach ($criteria as $key => $label) { // Loop criteria

            if (isset($scores[$key])) { // Reviewer score

                $rs[$key] = array('label' => $label, 'score' => $scores[$key]);

            }

            if (isset($users_scores[$key])) { // Users score

                $us[$key] = array('label' => $label, 'score' => $users_scores[$key]);

            }

        }



        $result['review_scores'] = $rs;



        $result['review_users_score']['scores'] = $us;

    }

    $returnArr = array();

    $total  = 0;

    if(empty($result['review_scores']) && empty($result['review_users_score']['count'])){

        return array('count'=>0,'list'=>array());

    }

    $totalCount = count($result['review_users_score']['scores']);

    $scoresuser = $result['review_users_score'];

    $scoreseditor = $result['review_scores'];

    $dividefac = !empty($scoreseditor)?1+$scoresuser['count']:$scoresuser['count'];

    $returnArr['count']=$dividefac;

    foreach ($scoresuser['scores'] as $i => $revval) {



        $label =  $revval['label'];

        $index= strtolower(string_clean($label));



        $addfac = isset($scoreseditor[$i]['score'])?$scoreseditor[$i]['score']:0;

        $scr = ($addfac+($revval['score']*$scoresuser['count']))/$dividefac;

        $total += $scr;

        $scr = round($scr,1);

        $returnArr['list'][$index]=array('label'=>$label,'score'=>$scr);

    }

    $totalscr  = $total/$totalCount;

    $totalscr = round($totalscr,1);

    $returnArr['list']['overallrating']=array('label'=>'Overall Rating','score'=>$totalscr);

    return $returnArr;

}

function string_clean($string) {

    $string = htmlspecialchars_decode($string);

    $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.



    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

}

function custom_rewrite_rule() {

    add_rewrite_tag('%cid%',  '([^&]+)');

    $page = get_page_by_path( 'compare' );

    $pid  = $page->ID;

    add_rewrite_rule('^compare/(.+)/?$','index.php?page_id='.$pid.'&cid=$matches[1]','top');

}

add_action('init', 'custom_rewrite_rule', 10, 0);

function array_flatten($array) {

    if (!is_array($array)) {

        return FALSE;

    }

    $result = array();

    foreach ($array as $key => $value) {

        if (is_array($value)) {

            $result = array_merge($result, array_flatten($value));

        }

        else {

            $result[] = $value;

        }

    }

    return $result;

}

function get_thumbnail_small($postId,$size=array(100,100)){

    if(has_post_thumbnail($postId)):

        $imageId =   get_post_thumbnail_id($postId);

        return wp_get_attachment_image( $imageId, array($size[0], $size[1]), "", array( "class" => "img-responsive" ) );

    else:

        return "<img src='".plugins_url( '/image/100.png', __DIR__ )."' class='img-responsive'>";

    endif;

}

function get_item_batch($itemiid){

    $htm ='';

    $lists =  get_field( 'add_to_list', $itemiid, false );

    if ( !empty ( $lists ) && is_array( $lists ) ) {

        $listrankord = array();

        foreach ( $lists as $id ) {

            if(get_post_status ( $id ) =='publish'){

                $post_id = $id;

                $rank = get_item_rank($id,$itemiid);

                $listrankord[$id] = $rank;

            }

        }

        asort($listrankord);
        $uniq_ar_alice = array_slice($listrankord, 0, 1);
        $fisrt =  array_shift($uniq_ar_alice);

        if(!empty($fisrt)){

            $friends_count = array_count_values($listrankord);

            $batchCount = $friends_count[$fisrt];

            ob_start();

            ?>

            <div class="batch-id">

                <div class="batch-data"><span class="rank-val">#<?php echo $fisrt?></span><span class="rank-in">in</span><span class="rank-items"><?php echo $batchCount?></span></div>

                <div class="batch-image"></div>



            </div>

            <?php

            $htm =  ob_get_contents();

            ob_get_clean();

        }

    }

    return $htm;

}

function re_pippin_taxonomy_add_new_meta_field() {

    // this will add the custom meta field to the add new term page

    ?>

    <div class="form-field">

        <label ><?php _e( 'Features List', 'pippin' ); ?></label>

        <div class="features_list_container"><span class="add_new_cat_feature">Add</span> <ul>

                <li><input type="text" name="term_meta[features_list][]"  value=""> <span class="remove_cat_feature">X</span></li>

            </ul><span class="add_new_cat_feature">Add</span> </div>



    </div>

    <?php

}

add_action( 'list_comp_categories_add_form_fields', 're_pippin_taxonomy_add_new_meta_field', 10, 2 );

function re_pippin_taxonomy_edit_meta_field($term) {



    // put the term ID into a variable

    $t_id = $term->term_id;



    // retrieve the existing value(s) for this meta field. This returns an array

    $term_meta = get_option( "taxonomy_$t_id" );

    $items = $term_meta['features_list'];



    ?>

    <tr class="form-field">

        <th scope="row" valign="top"><label for="term_meta[custom_term_meta]"><?php _e( 'Example meta field', 'pippin' ); ?></label></th>

        <td>

            <div class="features_list_container"><span class="add_new_cat_feature">Add</span> <ul><?php

                    if(!empty($items) && is_array($items)){

                        foreach ($items as $it){

                            if(!empty(esc_attr($it))){

                                echo '<li><input type="text" name="term_meta[features_list][]"  value="' . esc_attr($it). '"> <span class="remove_cat_feature">X</span></li>';

                            }

                        }



                    }

                    ?>



                </ul><span class="add_new_cat_feature">Add</span> </div>





        </td>

    </tr>

    <?php

}

add_action( 'list_comp_categories_edit_form_fields', 're_pippin_taxonomy_edit_meta_field', 10, 2 );

function re_save_taxonomy_custom_meta( $term_id ) {

    if ( isset( $_POST['term_meta'] ) ) {

        $t_id = $term_id;

        $term_meta = get_option( "taxonomy_$t_id" );

        $cat_keys = array_keys( $_POST['term_meta'] );

        foreach ( $cat_keys as $key ) {

            if ( isset ( $_POST['term_meta'][$key] ) ) {

                $term_meta[$key] = $_POST['term_meta'][$key];

            }

        }

        // Save the option array.

        update_option( "taxonomy_$t_id", $term_meta );

    }

}

add_action( 'edited_list_comp_categories', 're_save_taxonomy_custom_meta', 10, 2 );

add_action( 'create_list_comp_categories', 're_save_taxonomy_custom_meta', 10, 2 );

add_action('admin_head','re_add_category_feature_js');

function re_add_category_feature_js(){

    ?>

    <script type="text/javascript">

        var wp_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

    </script>



    <?php

}

add_action('admin_init','re_setup_list_features');

function re_setup_list_features(){

    remove_meta_box( 'list_comp_categoriesdiv' , 'list_items' , 'side' );

    add_action("add_meta_boxes", "re_add_item_featue_meta_boxes");

    add_action("save_post","re_save_item_featue_meta_boxes", 10, 3);

}



function re_add_item_featue_meta_boxes()

{

    add_meta_box("list-item-options", "Item Features", "re_list_features_meta_box_markup", "list_items", "normal", "high", null);



}

function re_list_features_meta_box_markup($object)

{

    $post_id = $object->ID;

    wp_nonce_field(basename(__FILE__), "nd-list-item-mbnonce");

    echo '<div class="meta-box-list">';

    echo '<div class="metabox-field">';

    $terms = get_terms( array(

        'taxonomy' => 'list_comp_categories',

        'hide_empty' => false,

    ) );

//    var_dump($terms);

    $tax  = get_post_meta($post_id, 'features_category', true);

    $featureList  = get_post_meta($post_id, 'features_list', true);
    $featureListratings = get_post_meta($post_id, 'features_list_ratings', true);
    file_put_contents("mvcf.txt","feature list ratings ".print_r($featureListratings,true).PHP_EOL,FILE_APPEND);

    if(!is_array($featureList)){

        $featureList = array();
	

    }

 


    echo '</div>';

    echo '<div class="metabox-field">';

    echo '<label for="">Features</label><div class="meta-field-inp">

<div id="list_items_features_meta">';

    $term_meta = get_option( "taxonomy_$tax" );

    $items = $term_meta['features_list'];


// $featureList  = get_post_meta($post_id, 'features_list', true);
    if(!empty($featureList)){



        echo '<div class="features_list_container post_edit_page"><span class="add_new_item_feature">Add Extra Features</span> <ul>

              

            </ul> </div>';
		if(!empty($featureList) && is_array($featureList)){
		

        

            echo '<div id="features">

  <input class="search" type="text" placeholder="Search" />

<!-- class="sort" automagically makes an element a sort buttons. The date-sort value decides what to sort by. -->

  <button class="sort" data-sort="name">

    Sort

  </button>';

            echo "<ul class='post_features_list list'>";

    
            foreach ($featureList as $key => $it){

                if(!empty($it)){

                    $checked = "checked";

                    echo '<li data-mh="feat_li"> <input type="checkbox" name="features_list[]"  value="' . $it. '" id="feat_'.$key.'" '.$checked.'><label for="feat_'.$key.'" class="name">'.$it.'</label></li>';

                }

            }

            echo "</ul></div>";

        } else{

            echo "<div class='no_features'>No features added to category yet.</div>";

        }

        echo     '<div class="features_list_container post_edit_page"><span class="add_new_item_feature">Add Extra Features</span> <ul>

              

            </ul> </div>';



    } else{

        echo "<div class='no_features'>Invalid category selected.</div>";

    }



    echo '</div>';

    echo '</div>';

    echo '</div>';

    echo '</div>';

}

function re_save_item_featue_meta_boxes($post_id, $post, $update){

    if (!isset($_POST["nd-list-item-mbnonce"]) || !wp_verify_nonce($_POST["nd-list-item-mbnonce"], basename(__FILE__)))

        return $post_id;



    if(!current_user_can("edit_post", $post_id))

        return $post_id;



    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)

        return $post_id;



    $slug = "list_items";

    if($slug != $post->post_type)

        return $post_id;



    $catValue ='';

    if(isset($_POST['features_category']))

    {

        $catValue = $_POST['features_category'];

    }

    update_post_meta($post_id, 'features_category', $catValue);

    $meta_box_value =array();

    if(isset($_POST['features_list']))

    {

        $meta_box_value = $_POST['features_list'];

    }

    if(isset($_POST['new_features_list']) && is_array($_POST['new_features_list']) && !empty($_POST['new_features_list'])){

        $newArr = $_POST['new_features_list'];

        $newArr = array_filter($newArr);

        if(!empty($newArr) && !empty($catValue)){

            $term_meta = get_option( "taxonomy_$catValue" );

            $items = array();

            if(!empty($term_meta) && is_array($term_meta)){

                if(isset($term_meta['features_list'])) {

                    $items = $term_meta['features_list'];

                }

            } else{

                $term_meta = array();

            }

            $newFat = array_merge($items,$newArr);

            $term_meta['features_list']=$newFat;

            update_option( "taxonomy_$catValue", $term_meta );

            $meta_box_value = array_merge($meta_box_value,$newArr);

        }

    }
    $features_list_ratings = array();
    foreach($meta_box_value as $mbv){
        // update_post_meta($post_id, 'features_list_ratings', array());


        $total_score = 0;
        $votes = 0;
        $average = 0;
        // $unusedFeatureListRatings = $featureListratings;
        foreach($featureListratings as $fl => $flr){
            if($mbv == $fl){
                $total_score = $flr['total_score'];
                $votes = $flr['votes'];
                $average = $flr['average'];
                // unset($unusedFeatureListRatings[$fl]);
            }
        }
        $f_ = str_replace(" ","_",$mbv);    
        $features_list_ratings [$f_]= array('total_score' => $total_score,
        'votes' => $votes, 'average'=> $average);
    }
    // $features_list_ratings = NULL;
    update_post_meta($post_id, 'features_list', $meta_box_value);
    update_post_meta($post_id, 'features_list_ratings', $features_list_ratings);
}


//
//add_action('wp_ajax_get_feature_list','callback_re_get_feature_list');
//
//add_action('wp_ajax_nopriv_get_feature_list','callback_re_get_feature_list');

function callback_re_get_feature_list(){

    $tax = $_POST['taxonomy'];

    $post_id = $_POST['post'];

    $featureList  = get_post_meta($post_id, 'features_list', true);
//file_put_contents("mvcf.txt","featureList is: ".print_r($featureList,true));
	$featureList2  = get_post_meta($post_id, 'features_list2', true);
//file_put_contents("mvcf.txt","featureList2 is: ".print_r($featureList2,true),FILE_APPEND);
    if(!is_array($featureList)){

        $featureList = array();

    }

    $term_meta = get_option( "taxonomy_$tax" );

    $items = $term_meta['features_list'];

    $terms = get_terms( array(

        'taxonomy'	 => 'list_comp_categories',

        'hide_empty' => false,

    ) );

    $termsArr = array();

    foreach ($terms as $td){

        $termsArr[] = $td->term_id;

    }

    if(!empty($tax)  && in_array($tax,$termsArr) ){

        echo '<div class="features_list_container post_edit_page"><span class="add_new_item_feature">Add Extra Features</span> <ul></ul> </div>';

        if(!empty($items) && is_array($items)){

            echo '<div id="features">

			<input class="search" type="text" placeholder="Search" />
			<!-- class="sort" automagically makes an element a sort buttons. The date-sort value decides what to sort by. -->
			<button class="sort" data-sort="name">

				Sort

			</button>';

            echo "<ul class='post_features_list list'>";

            foreach ($items as $key => $it){

                if(!empty($it)){

                    $checked = in_array($it,$featureList)?'checked="checked"':'';

                    echo '<li data-mh="feat_li"><input type="checkbox" name="features_list[]"  value="' . $it. '" id="feat_'.$key.'" '.$checked.'> <label for="feat_'.$key.'" class="name">'.$it.'</label> </li>';

                }

            }

            echo "</ul>";

            echo "</div>";



        }else{

            echo "<div class='no_features'>No features added to category yet.</div>";

        }

        echo '<div class="features_list_container post_edit_page"><span class="add_new_item_feature">Add Extra Features</span> <ul>

              

            </ul> </div>';

    } else{

        echo "<div class='no_features'>Invalid category selected.</div>";

    }



    die();

}

function get_excerpt_custom($post,$limit=200){

    $content_post = get_post($post);

    $content = $content_post->post_content;

    $excerpt = preg_replace(" ([.*?])",'',$content);

    $excerpt = strip_shortcodes($excerpt);

    $excerpt = strip_tags($excerpt);

    $excerpt = substr($excerpt, 0, $limit);

    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));

//    $excerpt = trim(preg_replace( '/s+/', ' ', $excerpt));



    return $excerpt;

}

add_image_size( 'list_slider', 600, 600,true);

function get_all_image_sizes($attachment_id = 0) {

    $sizes = get_intermediate_image_sizes();

    if(!$attachment_id) $attachment_id = get_post_thumbnail_id();



    $images = array();

    foreach ( $sizes as $size ) {

        $imgData = wp_get_attachment_image_src( $attachment_id, $size );

        if(!empty($imgData)) {

            $images[$size] = $imgData[0];

            $images[$size . '-width'] = $imgData[1];

            $images[$size . '-height'] = $imgData[2];

        }

    }



    return $images;

}

function image_path_single($path=''){

    return plugins_url( 'assets/list/'.$path,dirname(__FILE__));

}

function list_url_anchor_target( $title,$collision_collector )



{

    $defaults = array(		// default options



        'fragment_prefix' => 'i',



        'position' => TOC_POSITION_BEFORE_FIRST_HEADING,



        'start' => 4,



        'show_heading_text' => true,



        'heading_text' => 'Contents',



        'auto_insert_post_types' => array('page'),



        'show_heirarchy' => true,



        'ordered_list' => true,



        'smooth_scroll' => false,



        'smooth_scroll_offset' => TOC_SMOOTH_SCROLL_OFFSET,



        'visibility' => true,



        'visibility_show' => 'show',



        'visibility_hide' => 'hide',



        'visibility_hide_by_default' => false,



        'width' => 'Auto',



        'width_custom' => '275',



        'width_custom_units' => 'px',



        'wrapping' => TOC_WRAPPING_NONE,



        'font_size' => '95',



        'font_size_units' => '%',



        'theme' => TOC_THEME_GREY,



        'custom_background_colour' => TOC_DEFAULT_BACKGROUND_COLOUR,



        'custom_border_colour' => TOC_DEFAULT_BORDER_COLOUR,



        'custom_title_colour' => TOC_DEFAULT_TITLE_COLOUR,



        'custom_links_colour' => TOC_DEFAULT_LINKS_COLOUR,



        'custom_links_hover_colour' => TOC_DEFAULT_LINKS_HOVER_COLOUR,



        'custom_links_visited_colour' => TOC_DEFAULT_LINKS_VISITED_COLOUR,



        'lowercase' => false,



        'hyphenate' => false,



        'bullet_spacing' => false,



        'include_homepage' => false,



        'exclude_css' => false,



        'exclude' => '',



        'heading_levels' => array('1', '2', '3', '4', '5', '6'),



        'restrict_path' => '',



        'css_container_class' => '',



        'sitemap_show_page_listing' => true,



        'sitemap_show_category_listing' => true,



        'sitemap_heading_type' => 3,



        'sitemap_pages' => 'Pages',



        'sitemap_categories' => 'Categories',



        'show_toc_in_widget_only' => false,



        'show_toc_in_widget_only_post_types' => array('page')



    );



    $options = get_option( 'toc-options', $defaults );



    $options = wp_parse_args( $options, $defaults );



    $return = false;







    if ( $title ) {



        $return = trim( strip_tags($title) );







        // convert accented characters to ASCII



        $return = remove_accents( $return );







        // replace newlines with spaces (eg when headings are split over multiple lines)



        $return = str_replace( array("\r", "\n", "\n\r", "\r\n"), ' ', $return );







        // remove &amp;



        $return = str_replace( '&amp;', '', $return );







        // remove non alphanumeric chars



        $return = preg_replace( '/[^a-zA-Z0-9 \-_]*/', '', $return );







        // convert spaces to _



        $return = str_replace(



            array('  ', ' '),



            '_',



            $return



        );







        // remove trailing - and _



        $return = rtrim( $return, '-_' );







        // lowercase everything?



        if ( $options['lowercase'] ) $return = strtolower($return);







        // if blank, then prepend with the fragment prefix



        // blank anchors normally appear on sites that don't use the latin charset



        if ( !$return ) {



            $return = ( $options['fragment_prefix'] ) ? $options['fragment_prefix'] : '_';



        }







        // hyphenate?



        if ( $options['hyphenate'] ) {



            $return = str_replace('_', '-', $return);



            $return = str_replace('--', '-', $return);



        }



    }







    if ( array_key_exists($return, $collision_collector) ) {



        $collision_collector[$return]++;



        $return .= '-' . $collision_collector[$return];



    }



    else



        $collision_collector[$return] = 1;



    $Val = apply_filters( 'toc_url_anchor_target', $return );



    return array('value'=>$Val,'titles'=>$collision_collector);



}

add_action('wp_ajax_report_list_item','callback_report_list_item');

add_action('wp_ajax_nopriv_report_list_item','callback_report_list_item');

function callback_report_list_item(){

    global $wpdb;

    $table = 'wpxx_reports';

    $data = $wpdb->insert($table,array(

            'list_id'=>$_POST['list_id'],

            'item_id'=>$_POST['list_item'],

            'reason'=>$_POST['reason'],

            'comment'=>$_POST['comment'],

            'time'=>time(),

            'user'=>is_user_logged_in()?get_current_user_id():0,

        )

        );

    if($data){

        $type = 'yes';

        $msg =  '<div class="alert alert-success" role="alert">

  Item has been reported - Thank You.


</div>';

    } else{

        $type = 'no';

        $msg =  '<div class="alert alert-danger" role="alert">

  Some error occurred, please try again.

</div>';



    }

    echo json_encode(array('type'=>$type,'msg'=>$msg));

    die;

}
///////////////////////////SEO filters//////////////////////////////////////////
function  alternative_canonical($url){
    if(is_page('alternative')){
       return $url = site_url().$_SERVER['REQUEST_URI'];
    }
    return $url;
   
}
add_filter("wpseo_canonical", "alternative_canonical");
add_filter("wpseo_opengraph_url", 'alternative_canonical');


/* coupon url ------------------------*/
function  coupon_canonical($url){
    if(is_page('coupon')){
       return $url = site_url().$_SERVER['REQUEST_URI'];
    }
    return $url;
   
}
add_filter("wpseo_canonical", "coupon_canonical");
add_filter("wpseo_opengraph_url", 'coupon_canonical');



/*coupon url ------------------------*/

function  alternative_opengraph_image(){
    if(is_page('alternative')){
       return $url = "https://www.softwarefindr.com/wp-content/uploads/alternatives.jpg";
    }
    if(is_page('compare')){
        return $url = "https://www.softwarefindr.com/wp-content/uploads/comparison.jpg";
     }
	
	
	if(is_page('coupon')){
        return $url = "https://www.softwarefindr.com/wp-content/uploads/comparison.jpg";
     }
	
}
add_filter("wpseo_opengraph_image", "alternative_opengraph_image");

///////////comparision page title/////////////////////////////////////////
function alter_comparision_page_title( $title ) {

    	global $post;
		if(is_page('compare')) {
		$settings = get_option( 'mv_list_items_settings' );
        $page_title = $settings['comparison_page_title'];
        $title = str_replace( '[item_name]', get_the_title($post->ID), $page_title );
        $title = str_replace( '[Year]', date('Y'), $title );
		
    	return do_shortcode($title);
		}
}

add_filter( 'wp_title', 'alter_comparision_page_title', 200 );

/// //////////////wp seo page titles filter
function title_shortcode_filter( $title ){
	global $post;
	
 		$title = str_replace( '[item_name]', get_the_title($post->ID), $title );
        $title = str_replace( '[Year]', date('Y'), $title );
		
		return do_shortcode($title);

}
add_filter('wpseo_title', 'title_shortcode_filter');

function description_shortcode_filter( $description ){
		global $post;
	
 		$description = str_replace( '[item_name]', get_the_title($post->ID), $description );
        $description = str_replace( '[Year]', date('Y'), $description );
		
		return do_shortcode($description);

}
add_filter('wpseo_metadesc', 'description_shortcode_filter');

// meta box for list items /////////////////////////
function available_add_meta_boxe( $post ){
	add_meta_box( 'available_meta_box', __( 'Available', 'available_meta_box' ), 'available_build_meta_box', 'list_items', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'available_add_meta_boxe' );

/**
 * Build custom field meta box
 *
 * @param post $post The post object
 */
function available_build_meta_box( $post ){
	// make sure the form request comes from WordPress
	wp_nonce_field( basename( __FILE__ ), 'available_meta_box_nonce' );

    $available = get_post_meta( $post->ID, '_item_availbility', true );
    if( empty($available) ) {
        $available = "yes";
    }
	?>
	<div class='inside'>
    <p class="post-attributes-label-wrapper">
        <label class="post-attributes-label" for="item_available"><?php _e( 'Item Available', 'available_meta_box' ); ?> </label>
    </p>
	<select style="width:150px" name="item_available">
                <option <?php echo ($available == 'yes') ? "selected" : ''; ?> value="yes">Yes</option>
                <option <?php echo ($available == 'no') ? "selected" : ''; ?> value="no">No</option>
    </select>	
	</div>
	<?php
}
function available_save_meta_box_data( $post_id ){
    // verify meta box nonce
	if ( !isset( $_POST['available_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['available_meta_box_nonce'], basename( __FILE__ ) ) ){
		return;
    }
    // return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ){

		return;
    }
    if ( isset( $_REQUEST['item_available'] ) ) {
		update_post_meta( $post_id, '_item_availbility',  $_POST['item_available'] );
	}
}
add_action( 'save_post', 'available_save_meta_box_data' );

 function my_item_rank($pId){
        $lists =  get_field( 'add_to_list', $pId, false );
        $itemiid = $pId;
        $listrankord = array();
        if ( !empty ( $lists ) && is_array( $lists ) ) {

            foreach ( $lists as $id ) {
                if(is_string( get_post_status( $id ) )){
                    $rank = get_item_rank($id,$itemiid);
                    $listrankord[$id] = $rank;
                }
            }
            asort($listrankord);
        }
        return   $listrankord;
    }

function wp_infinitepaginate(){ 
    
		$paged           = $_POST['page_no'];
		$list_id         = $_POST['list_id'];
		 global $wp;
        $index =1;
        $pageID = $list_id;
        $site_url 			 = get_site_url();
        $main_list_id        = $list_id ;
        $main_list_permalink = get_the_permalink( $list_id );
        $main_list_uri 	 	 = get_page_uri( $list_id );
        $attached_items      = get_field( 'list_items', $list_id, true );
        $promoted_list_items = get_field( 'promoted_list_items', $list_id, true );
        $items_per_page      = get_field( 'items_per_page', $list_id );
		//$items_per_page      = 10;
        $voting_closed       = get_field( 'voting_closed', $list_id );
        $current_page        = $paged;
        $total_pages         = ceil( count( $attached_items )/$items_per_page );

        //$items_by_votes = '';
		
        foreach ( $attached_items as $key =>$child_post ) {
            $total_votes = get_post_meta( $child_post->ID, 'votes_given', true );
            if ( ! isset( $total_votes[$main_list_id] ) ) {
                $total_votes[$main_list_id] = 0;
                $attached_items[$key]->votes = 0;
            }else {
                $attached_items[$key]->votes =$total_votes[$main_list_id];
            }

        }

        $index = ( ( $current_page - 1 ) * $items_per_page )+1;

        //usort( $attached_items, array( $this, "cmp" ) );
		
         Mes_Lc_Ext::sort($list_id, $attached_items);
		 //Mes_Lc_Ext::sortfree($list_id, $postarr);

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
		Mes_Lc_Ext::sortfree($list_id, $attached_items);
        //krsort($items_by_votes);
        // $posts =  array_slice( $attached_items, ( $current_page - 1 ) * $items_per_page , $items_per_page );
        $posts = $attached_items;
        /*echo '<pre>';
            print_r($posts);
            echo '</pre>';  */

        $totalVotes = 0;
        $totalActVOtes = 0;
        $postarr = array();
		$postarr1 = array();
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
		if(isset($_GET['sort'])){
	  $sorts = $_GET['sort'];
		}
	  $a = array();
        if(!empty($posts))
        {
            foreach($posts as $key=>$list_post)
            {
                //$totalVotes += $list_post->votes;
//            if ($list_post->votes > 0) {

                $score = Mes_Lc_Ext::calculate_score($list_post->votes, $list_post->age);
                $score = ($score / $totalVotes) * 100;
                $score = number_format($score, 2);
				$pricemodel12 = get_field( 'pricing_model', $list_post->ID );
			    $freetrial = get_field( 'free_trial', $list_post->ID);
				if(empty($pricemodel12)){
				$pricemodel12 = array();	
				}
				if(in_array('open_source', $pricemodel12) || in_array('freemium', $pricemodel12) || $freetrial == 1){
				$free_items_array[]="free";	
				}
////       
                    
     	if(isset($sorts) && $sorts =="free"){
			    $postarr12[$list_post->ID] = $pricemodel12;  
					if(in_array('freemium', $postarr12[$list_post->ID]) ||in_array('open_source', $postarr12[$list_post->ID]) || $freetrial == 1) {
				     $postarr[$list_post->ID] = $score;
				  }else{
					 $postarr1[$list_post->ID] = $score; 
				  }
				}elseif(isset($sorts) && $sorts =="affordable_price"){ 
				$aa = $score; 
			       $price_starting_from = get_field( 'price_starting_from', $list_post->ID);
			       $price_starting_from = str_replace("$","",$price_starting_from);
			       $postarr[$list_post->ID] = $price_starting_from;	
				   
				   $counts = array_count_values($postarr);
        $filtered = array_filter($postarr, function ($value) use ($counts) {
    return $counts[$value]>1 ;
});

if(count($filtered)>=1){
foreach($postarr as $key => $value) {
      foreach($filtered as $key1 => $value1) {
        if($key == $key1 && $value == $value1){
            unset($postarr[$key]);
			$postarr1[$key] = $aa;
			$postarr11[$key] = $value;
            break;
        }
      }
    }	
	
}
		}elseif(isset($sorts) && $sorts =="User_friendly"){
					$reviews1223 = get_overall_combined_rating($list_post->ID);
                    foreach($reviews1223 as $key=>$value){
	                $postarr[$list_post->ID] = $value['overallrating']['score'];
                       }
				}else{
				$postarr[$list_post->ID] = $score;	
				}
////
                $postarrOBJ[$list_post->ID] = $list_post;

//            }
            }
        }
////
		
		if(isset($sorts) && $sorts =="free"){
			 arsort($postarr);
			 arsort($postarr1);
        $postarr = $postarr+$postarr1;
		}elseif(isset($sorts) && $sorts =="affordable_price"){
			asort($postarr);
			
			if(isset($postarr1)){
			arsort($postarr1);
			foreach($postarr1 as $key => $value) {
      foreach($postarr11 as $key1 => $value1) {
        if($key == $key1){
			$postarr1[$key] = $value1;
            break;
        }
      }
			}
			$postarr = $postarr + $postarr1;
			asort($postarr);
			 }
			
//
		}else{
			 arsort($postarr);
		}
////		
        $maxsScore = array_keys($postarr, max($postarr));
        $maxsScorePost = $maxsScore[0];
////
        
$static_val = array_slice( $postarr, ( $current_page - 1 ) * $items_per_page , $items_per_page , true);		

               if ( ! empty( $posts ) ) {
            ob_start()
            ?>
        <div class="page-cont" data-page="<?php echo $current_page ?>" data-page-url="<?php echo $site_url."/best/".$main_list_uri ?>/">

            <?php $kk = 1;
                $promoteditems  = '';
                $alllistitems = '';
                $videoItems = '';
                $compareItems = array();
				
                foreach($static_val as $key=>$score): // variable must be called $post (IMPORTANT)
                    //setup_postdata( $post );
					/*if((isset($sorts) && $sorts =="free") || (isset($sorts) && $sorts =="affordable_price") || (isset($sorts) && $sorts =="User_friendly")){
					$main_list_id = $key;
					
					}*/
                    $list_post = $postarrOBJ[$key];
                    $list_id = $list_post->ID;
                    if($kk < 3){
                        $compareItems[] = $list_id;
                        $kk++;
                    }
					 
                    $affiliate_url = get_field( 'affiliate_url', $list_id );
                    $affiliate_button_text = get_field( 'affiliate_button_text', $list_id ) == '' ?'Download': get_field( 'affiliate_button_text', $list_id   );
                    $source_url = get_field( 'source_url', $list_id );
                    $credit = get_field( 'credit', $list_id );
					$pricemodel = get_field( 'pricing_model', $list_id );
					$freetrial = get_field( 'free_trial', $list_id );
                    $support       = get_field( 'support', $list_id );
                    $gallery = get_field('gallery',$list_id);
                    $thumbiD = get_post_thumbnail_id($list_id);
                    $price_starting_from = get_field( 'price_starting_from', $list_id);
					
                    if(!is_array($gallery)){
                        $gallery = array();
                    }
                    if(!empty($thumbiD)){
                        $img = wp_get_attachment_image_src( $thumbiD, 'list-thumb' );
                        $thumb = array('url'=>$img[0],'width'=>$img[1],'height'=>$img[2]);
                        $thumb['sizes']= get_all_image_sizes($thumbiD);
                        $gallery[] = $thumb;
                    }
                    $video = get_field('video',$list_id);
                    if(!empty($video)){
                        $videoId = 'video'.$list_id;
                        $ifarmeId = 'youtube'.$list_id;

                        ob_start();
						
                        ?>
                        <div id="<?php echo $videoId;?>" class="lightbox" onclick="hideVideo('<?php echo $videoId;?>','<?php echo $ifarmeId;?>')">
                            <div class="lightbox-container">
                                <div class="lightbox-content">

                                    <button onclick="hideVideo('<?php echo $videoId;?>','<?php echo $ifarmeId;?>')" class="lightbox-close">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <div class="video-container" id="<?php echo $ifarmeId;?>">
                                        <?php echo $video; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                        $videoItems.= ob_get_contents();
                        ob_end_clean();

                    }
                    ob_start();
                    ?>
                    <?php  $availability = get_post_meta( $list_id, '_item_availbility', true ); ?>
                    
                    
                    <div class="container-fluid section5 list-item-row pos-relative" id="<?php echo  $kk ==3?'show_comprison_footer':'';?>">
                   
                        <div class="container">
                            <div class="row" id="test"> 
                                <div class="col-md-6 center">
                                <div class="<?php echo ($availability == 'no') ? 'item-not-available' : ''; ?>"></div>
                                    <div id="componentWrapper<?php echo$index?>"  class="componentWrapper">
                                        <!--
                                        Note: slides are stacked in order from bottom, so first slide in '.componentPlaylist' is a the bottom!
                                        (Their z-position is manipulated with z-indexes in jquery)
                                         -->
                                        <div class="componentPlaylist">
                                            <?php
                                            $numItems = count($gallery);
                                            $i = 0;
                                            if(!empty($gallery) && is_array($gallery)){
//                                                echo '<pre>';
//                                                print_r($gallery);
//                                                echo '</pre>';
                                                foreach ($gallery as $img){
                                                    $sizeUrl  = isset($img['sizes']['list-thumb'])?$img['sizes']['list-thumb']:$img['url'];
                                                    $sizewidth  =$img['width'];
                                                    $sizeheight  =$img['height'];
                                                    $style ='';
                                                    if(++$i === $numItems) {
                                                        $style = " style='filter: blur(0px);'";
                                                    }
                                                    ?>
                                                    <div class="slide" <?php echo $style;?>>
                                                        <div class="scaler" style="width: <?php echo $sizewidth;?>px;height: <?php echo $sizeheight;?>px">
                                                        <?php /*$alt = basename($sizeUrl);
														$pos = strrpos($alt, ".");
														 $alt = substr($alt,0,$pos); */
														?>
                                                               <i class="helper"></i> <img class='stack_img' src='<?php  echo $sizeUrl;?>' width="<?php echo $sizewidth;?>" height="<?php echo $sizeheight;?>" alt='<?php echo get_the_title( $list_id ); ?>'/>
                                                            <div class="slide_detail">
                                                                <?php   if($list_id == $maxsScorePost){ echo '<div class="greenbadge">Voted The BEST</div>';}
                                                                elseif($list_post->promoted){ echo '<div class="purplebadge">Promoted</div>'; }
                                                                else{
                                                                    if(!empty($price_starting_from)){ echo '<div class="orangebadge">$'.$price_starting_from.'</div>';

                                                                    }

                                                                }
                                                                if(!empty($video)){
                                                                    ?>
                                                                    <div class="playbtn playme" onclick="revealVideo('<?php echo $videoId;?>','<?php echo $ifarmeId;?>')"></div>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </div>

                                        <!-- controls -->
                                        <?php if($numItems >1){?>
                                            <div class="componentControls test">
                                                <!-- next -->
                                                <div class="controls_next">
                                                    <img src="<?php echo image_path_single('data/icons/next.png');?>" alt="" width="30" height="30"/>
                                                </div>
                                                <!-- toggle -->
                                                <div class="controls_toggle">
                                                    <img src="<?php echo image_path_single('data/icons/pause.png');?>" alt="" width="30" height="30"/>
                                                </div>
                                                <!-- previous -->
                                                <div class="controls_previous">
                                                    <img src="<?php echo image_path_single('data/icons/prev.png');?>" alt="" width="30" height="30"/>
                                                </div>


                                            </div>
                                            <!-- nav controls -->
                                            <div class="navControls componentControls">
                                                <ul>
                                                    <li class="controls_previous">
                                                        <a href="#"></a>
                                                    </li>
                                                    <li class="controls_previous">
                                                        <a href="#"></a>
                                                    </li>
                                                    <li class="controls_next">
                                                        <a href="#"></a>
                                                    </li>
                                                    <li class="controls_next">
                                                        <a href="#"></a>
                                                    </li>
                                                </ul>

                                            </div>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-5 nopadding">
                              
                                    <span class="ranknumber"><?php echo $index;?></span>
                                   
                                    <div class="productbox zf-list_item"><?php if($availability == 'no') { echo "<div class='pos-relative'><div class='text-overlay'></div>"; } ?><p class="badge list-other-badge"> 
                                            <?php  // if($list_id == $maxsScorePost){
                                            $ranks = my_item_rank($list_id);
											//print_r($ranks);
                                            foreach ($ranks as $rid => $raval){
                                                if($rid != $main_list_id){
                                                    echo '
                                            <a class="ls_referal_link" data-parameter="listid" data-id="'.$main_list_id.'" href="'.get_permalink($rid).'"> <span class="rank-ts"><span class="rr-val">'.$raval.'</span> </span> <span class="badge-text"> in '.get_the_title($rid).'</span></a>
                                        ';
                                                    break;
                                                }
                                            }
                                            ?>
                                        </p>
                                        <?php //} ?>
                                        <div class="titlebox">

                                            <h3 class="zf-list_title producttitle" data-zf-post-parent-id="<?php echo $main_list_id; ?>">
                                                <a class="mes-lc-li-link update_list_modified_link" data-zf-post-id="<?php echo $list_id; ?>" data-zf-post-parent-id="<?php echo $main_list_id; ?>" href="<?php echo get_the_permalink( $list_id ) ?>"><?php echo get_the_title( $list_id ); ?></a>
                                            </h3>
                                            <?php $total_votes = get_post_meta( $list_id, 'votes_given', true );

                                            if ( ! isset( $total_votes[$main_list_id] ) ) {
                                                $total_votes[$main_list_id] = 0;
                                            }

                                            if ( $voting_closed ) {
                                                $class_prefix= 'vts_';
                                            }else {
                                                $class_prefix= 'zf_';
                                            }
                                            if ( ! $voting_closed ) {
                                                ?>


                                                <div class="commentbtn">
                                                    <ul  class="zf-item-vote thumbs" data-zf-post-id="<?php echo $list_id; ?>" data-zf-post-parent-id="<?php echo $main_list_id; ?>">
                                                        <li>
                                                            <a href="javascript:;" class="zf-vote_btn <?php echo $class_prefix.'vote_up'; ?>">
                                                                <i class="fa fa-thumbs-o-up"></i>
                                                            </a>
                                                        </li>
                                                        <li class="spinner-list"><span class="zf-vote_count" data-zf-post-id="<?php echo $list_id; ?>" data-zf-votes="<?php echo number_format((float)$total_votes[$main_list_id],1); ?>">
															<i class="zf-icon zf-spinner-pulse"></i>
															<span class="zf-vote_number">
																<?php echo number_format((float)$total_votes[$main_list_id],1); ?>														</span>
														</span></li>
                                                        <li >
                                                            <a href="javascript:;" class="zf-vote_btn <?php echo $class_prefix.'vote_down'; ?>">
                                                                <i class="fa fa-thumbs-o-down"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php  } ?>
                                        </div>
                                        <div class="reviewbox">
                                            <div class="review">
                                                <p class="nomargin">Editor</p>
                                                <?php echo do_shortcode( '[rwp_reviewer_rating_stars id=0 size=14 post='.$list_id.']' ); ?>
                                            </div>
                                            <div class="review">
                                                <p class="nomargin">User</p>
                                                <?php
                                                echo do_shortcode( '[rwp_users_rating_stars id="-1"  template="rwp_template_590f86153bc54" size=14 post='.$list_id.']' );
                                                ?>
                                            </div>
                                        </div>
                                        <div class="product-excerpt">
                                            <div class="hr">
                                                <p>
                                                    <?php echo get_excerpt_custom( $list_id,120); ?>
                                                    <a class="mes-lc-li-link update_list_modified_link" data-zf-post-id="<?php echo $list_id; ?>" data-zf-post-parent-id="<?php echo $main_list_id; ?>" href="<?php echo get_the_permalink( $list_id ); ?>" rel="nofollow"><span class="moretext">... Read full review</span></a>

                                                </p>
                                            </div>
                                            <div class="hr">
                                                <hr class="xshr">
                                            </div>
                                        </div>
                                       
                                        <div class="features">

                                            <?php $features   = get_field( 'features_list', $list_id );
                                            $count = 0;

                                            if(!empty($features) && is_array($features)){
                                                $count = count($features);
                                                $addClass = $count <= 6?' fullul':'';
                                                echo '  <p><strong>Key Features :</strong></p><ul class="featurespoints'.$addClass.'">';

                                                foreach ($features as $ind => $feat){echo "<li>• $feat</li>";
                                                    if($ind == 5){
                                                        break;
                                                    }
                                                }
                                                echo '</ul>';

                                                if($count > 6){
                                                    $rest = $count-6;
                                                    echo '<ul class="otherlist"> <li class="others"><a class="mes-lc-li-link update_list_modified_link" data-zf-post-id="'.$list_id.'" data-zf-post-parent-id="'.$main_list_id.'" href="'.get_the_permalink( $list_id ).'#features">+'.$rest.' others </a></li></ul>';
                                                }

                                            }

                                            ?>

                                        </div>
                                        <?php if($availability == 'no') { echo "</div>"; } ?>
                                        <hr>

                                        <div class="supportcontact">

                                            <?php if($availability == 'no') { ?>
                                              <div class="notavailable-notice pull-left">
                                                <center class="no-border red"><b>This item is no longer available ! </b></center>
                                               </div>
                                            <?php } ?>
                                            <?php 	if ( $support ) {
                                                $supportIcons = ' <ul class="pull-left">';
                                                $countSup = end($support);
                                                foreach ($support as  $ckey){
                                                    $supportIcons .=" <li><i class='fa fa-$ckey'></i></li>";
                                                    if($countSup != $ckey){
                                                        $supportIcons .=" <li class='grey'>|</li>";
                                                    }
                                                }
                                                $supportIcons .=" <li class='grey'>Support</li>";
                                                $supportIcons .= "</ul>";
                                                echo $supportIcons;

                                            }?>

                                            <ul class="pull-right">
                                                <li class="report greya">
                                                    <a data-fancybox data-src="#reportModal" data-animation-duration="500" data-modal="true" href="javascript:;" data-item="<?php echo $list_id?>" data-title="<?php echo get_the_title( $list_id ); ?>">

                                                        <i class="fa fa-flag"></i> Report
                                                    </a>
                                                </li>
                                                													<li>	<?php if ( ! empty( $source_url ) ) { ?>
												<a class="credit-btn" href="<?php echo $source_url ?>" rel="nofollow" target="_blank"><?php echo $credit ?></a>
													<?php } ?>    </li>  
                                                <?php 
                                                if($availability == 'no') { ?>
                                                        <li class="greya"><a href="<?php echo get_the_permalink( $list_id )?>alternative/" class="alter-btn getbtn" data-parameter="itemid" data-id="<?php echo $list_id ?>" >Alernative</a></li> 
												
												   
                                               <?php } else {

													if ( ! empty( $affiliate_url ) ) { 
														if(substr_count($affiliate_url, "?")>=1){
															$affiliate_url.="&utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=list".$pageID;
														}
														else{
															$affiliate_url.="?utm_source=softwarefindr.com&utm_medium=free%20traffic&utm_campaign=list".$pageID;
														}
														
												?>
                                                    <li class="greya"><a class="mes-lc-li-down btn-affiliate zf-buy-button getbtn" target="_blank" href="<?php echo $affiliate_url; ?>" rel="nofollow" ><?php echo $affiliate_button_text ?></a></li>
                                                <?php }
                                                } ?>



                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid hrcon">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $conval = ob_get_contents();
                    ob_end_clean();

                    if(isset( $list_post->promoted )){
                        $promoteditems .=  $conval;
                    } else{
                        $alllistitems .=  $conval;
                    }
                    $index++;
					
                endforeach;
                
                echo $promoteditems;
                echo $alllistitems;
				
            $html = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();

            //var_dump($current_page+1 ); die;

        }
        echo $html;
        echo "</div>";

 
  wp_die();
}
add_action('wp_ajax_infinite_scroll', 'wp_infinitepaginate');           // for logged in user
add_action('wp_ajax_nopriv_infinite_scroll', 'wp_infinitepaginate');    // if user not logged in

function list_404_event() {
    global $post;
    if ( is_singular( 'lists' ) ) {
          $att_items        = get_field( 'list_items', $post->ID, true );
          $curr_page        = get_query_var( "page" ) ? get_query_var( "page" ) : 1;
          $it_per_page      = get_field( 'items_per_page', $list_id );
         // $items_per_page      = get_field( 'items_per_page', $list_id );
          $tot_pages         = ceil( count( $att_items )/$it_per_page );
          
          
          if($curr_page > $tot_pages) {
              global $wp_query;
              $wp_query->set_404();
              status_header( 404 );
              nocache_headers();
              //include( get_query_template( '404' ) );
              //die();
          }
    }
  }
  add_action( 'wp', 'list_404_event' );
  
  /*function canonical_best(){
      if(is_singular( 'lists' )){
          $current_url  = site_url().$_SERVER['REQUEST_URI'];
          $url          = get_permalink( $id );
          $pattern      = '/page\\/[0-9]+\\//i';
          $nopaging_url = preg_replace($pattern, '', $current_url);
  
          echo '<link rel="canonical" href="'.$nopaging_url .'">';
      }
  
  }
  add_action('wp_head', 'canonical_best');
  
  
  $current_url =  $_SERVER[REQUEST_URI];
  
      $pattern = '/page\\/[0-9]+\\//i';
      $nopaging_url = preg_replace($pattern, '', $current_url);
  
      return  $nopaging_url;*/
  
  
  function canonical_best(){
      $the_url     = site_url().$_SERVER['REQUEST_URI'];
      $current_url = explode('/', $the_url);
      $integer     = '/1/2/3/4/5/6/7/8/9/0/';
      array_pop($current_url);
      $link2       =  implode('/', $current_url); 
      $trimmed     = rtrim($link2, $integer);
      echo '<link rel="canonical" href="'.$trimmed.'/">';
      
  
  }
  add_action('wp_head', 'canonical_best');
  
  
  
  
  
  
// [bartag foo="foo-value"]
function itempros_func( $atts ) {
    global $post;
    $post_id=$post->ID;
	$a = shortcode_atts( array(
		'id' => $post_id
		// 'bar' => 'something else',
	), $atts );
   
    $aoa = populate_both_r2b_arrays($a['id']);
    $r2b = $aoa[0];
	return count($r2b);
}
function itemcons_func( $atts ) {
    global $post;
    $post_id=$post->ID;
	$a = shortcode_atts( array(
		'id' => $post_id
		// 'bar' => 'something else',
	), $atts );
 
    $aoa = populate_both_r2b_arrays($a['id']);
    $rn2b = $aoa[1];
	return count($rn2b);
}
add_shortcode( 'item_pros', 'itempros_func' );
add_shortcode( 'item_cons', 'itemcons_func' );

function get_alternate_items_info($post_id) {
    $compObj = new Mv_List_Comparision();
    $lists = $compObj->most_compared($post_id,20,true);
    $alternate_info = array();
        foreach ( $lists as $pid ) {
            $alternate_info[] = array(
              'id'   =>  $pid,
             'price' =>  intval (get_field( 'price_starting_from', $pid ))
            );  
        }
        return $alternate_info;
   
}
function get_item_ranks($pId){

    $lists =  get_field( 'add_to_list', $pId, false );
    $itemiid = $pId;
    $listrankord = array();
    if ( !empty ( $lists ) && is_array( $lists ) ) {

        foreach ( $lists as $id ) {
            if(acme_post_exists($id)){
                $rank = get_item_rank($id,$itemiid);
                $listrankord[$id] = $rank;
            }
        }
        asort($listrankord);
    }
  

    return   $listrankord;
}
function acme_post_exists( $id ) {
    return is_string( get_post_status( $id ) );
}

function populate_both_r2b_arrays($post_id){
    $support = get_field( 'support', $post_id );
    $r2b = Array();
    $rn2b = Array();
   
    // print_r($support);
    foreach($support as $cit){
        if($cit == '24/7')
            $r2b []= "24/7 support options available";
    }
    if(sizeof($support)==1 && $support[0]=='envelope'){
        $rn2b []= "Support only includes emails";
    }
    $freeTrial = get_field('free_trial',$post_id);
    if($freeTrial)
        $r2b []= "Free Trial is offered to perform testing";

    $mbg = get_field('money_back_guarantee',$post_id);
    if($mbg){
        $r2b []= "Money back guarantee";
    }
    
    $reviews = get_overall_combined_rating($post_id);
    if($reviews['list']['customersupport']['score'] > 4)
        $r2b []= "Friendly customer service";

    if($reviews['list']['easeofuse']['score'] > 4)
        $r2b []= "Easy to use even for a beginner";
    elseif($reviews['list']['easeofuse']['score'] < 1)
        $rn2b []="According to our users ".get_the_title( $post_id )." can be a bit confusing";
    
        $compObj = new Mv_List_Comparision();
    $lists = $compObj->most_compared($post_id,1000,true);
    // print_r($lists);

    /* $alternateinfo = get_alternate_items_info($post_id);
    if(empty($alternateinfo)){
        $alternateinfo = array();
    }    */ 
    /* $maximum =  max(array_column($alternateinfo, 'price'));
    $minimum = min(array_column($alternateinfo, 'price'));
    print_r($alternateinfo);
    echo "max and min ".$maximum." and ".$minimum; */
    $max = 0;
    // echo "priceses are : ";
 

    foreach($lists as $alternate){
        
        $price = ltrim(get_field( 'price_starting_from', $alternate),"$");
        // echo $price."\n";
            if($price > $max){
                $max = $price;

            }
        }
    

    // echo "max is : ".$max;
        $price_starting_from = get_field( 'price_starting_from', $post_id );
        if($price_starting_from )
        $trimmedprice =trim(trim($price_starting_from," "),"$");
        if($trimmedprice=='')
            $trimmedprice = 0;
        $maxhalf = round($max/2);
        $ifvalue = ($trimmedprice <= $maxhalf);


    if($trimmedprice <= $maxhalf){

        $r2b []= "Compared to others the price is reasonable"; 

    }
    else{
        
        $rn2b []= "A bit on the Expensive side compared to other solution in this category.";

    }

        $tpi = get_field('third_party_integrations',$post_id);
    if($tpi){
        $r2b []= "Third-party integrations";
    }
        $pricing_model = get_field( 'pricing_model', $post_id);
    // print_r($pricing_model);
    if (in_array("freemium", $pricing_model))
        $r2b []= "Offers free plan with multiple advanced features";
    // if($pricing_model[])
    if($reviews['list']['overallrating']['score'] > 4)
        $r2b []= "The majority of our users are experiencing positive experience with ".get_the_title($post_id);
    elseif($reviews['list']['overallrating']['score'] < 2.5){
        $rn2b []= "A few of our users are experiencing dissatisfaction ";
    }


    $alternateinfo = get_alternate_items_info($post_id);
    if(empty($alternateinfo)){
        $alternateinfo = array();
    } 
    /* $maximum =  max(array_column($alternateinfo, 'price'));
    $minimum = min(array_column($alternateinfo, 'price')); */
    // print_r($alternateinfo);
    // echo "max and min ".$maximum." and ".$minimum;
    $sum=0;
    // echo "nof : ";
    for($i=0;$i<sizeof($alternateinfo);$i++){
        $nof = sizeof(get_field( 'features_list', $alternateinfo[$i]['id'] ));
        // echo $nof." ";
        $sum += $nof;
    }
    $avgnof = $sum/sizeof($alternateinfo);
    // echo "avgnof ".$avgnof." ";
    get_field( 'features_list', $post_id );
     if(sizeof(get_field( 'features_list', $post_id )) > $avgnof)
        $r2b []= "Great features list";
    else
        $rn2b []= "Lack of features compared to other solution in this category";
     

    // print_r($this->ranklist);
    $ranklist = get_item_ranks($post_id);
    foreach($ranklist as $listid=>$rank){
        if($rank < 4){
            $r2b []= "Category leader in ".get_the_title($listid );
        }
        elseif($rank < 11){
            $r2b []= "A contender in ".get_the_title($listid );

        }
    }
    if (in_array("open_source", $pricing_model))
        $r2b []= "The core product is 100% free";

    $arrayOfArrays = [$r2b,$rn2b];
    return $arrayOfArrays;
}

		

 //--------------------------video features------------------------------------

 add_action('admin_init','re_setup_video_list_features');

 function re_setup_video_list_features(){
    // echo "inside re setup video list";
    // file_put_contents("mvcf.txt","inside send_real_user_db",FILE_APPEND);

     //remove_meta_box( 'list_comp_categoriesdiv' , 'list_items' , 'side' );
 
     add_action("add_meta_boxes", "re_add_video_list_meta_boxes");
 
     //add_action("save_post","re_save_video_list_meta_boxes", 10, 3);
 
 }


 function re_add_video_list_meta_boxes()

 {
 
     add_meta_box("list-video-options", "Video Features", "re_video_list_meta_box_markup", "list_items", "normal", "high", null);
 
 
 
 }

 function re_video_list_meta_box_markup($object)
 {    $post_id = $object->ID;

    wp_nonce_field(basename(__FILE__), "nd-list-item-mbnonce");

    echo '<div class="meta-box-list">';

    echo '<div class="metabox-field">';

    $terms = get_terms( array(

        'taxonomy' => 'list_comp_categories',

        'hide_empty' => false,

    ) );

//    var_dump($terms);

    $tax  = get_post_meta($post_id, 'video_list', true);

    $videolist  = get_post_meta($post_id, 'video_list', true);

    if(!is_array($videolist)){

        $videolist = array();
	

    }

 


    echo '</div>';

    echo '<div class="metabox-field">';

    echo '<label for="">Features</label><div class="meta-field-inp">

<div id="list_items_features_meta">';

    $term_meta = get_option( "taxonomy_$tax" );

    $items = $term_meta['video_list'];


$videolist  = get_post_meta($post_id, 'video_list', true);
    if(!empty($videolist)){



        echo '<div class="features_list_container post_edit_page"><span class="add_new_item_feature">Add Extra Features</span> <ul>

              

            </ul> </div>';
		if(!empty($videolist) && is_array($videolist)){
		

        

            echo '<div id="features">

				  <input class="search" type="text" placeholder="Search" />

				  <!-- class="sort" automagically makes an element a sort buttons. The date-sort value decides what to sort by. -->

				  <button class="sort" data-sort="name">

					Sort

				  </button>';

            echo "<ul class='post_features_list list'>";

    
            foreach ($videolist as $key => $it){

                if(!empty($it)){

                    $checked = "checked";

                    echo '<li data-mh="feat_li"> <input type="checkbox" name="new_video_list[]"  value="' . $it. '" id="feat_'.$key.'" '.$checked.'><label for="feat_'.$key.'" class="name">'.$it.'</label></li>';

                }

            }

            echo "</ul></div>";

        } else{

            echo "<div class='no_features'>No features added to category yet.</div>";

        }

        echo     '<div class="features_list_container post_edit_page"><span class="add_new_item_feature">Add Extra Features</span> <ul>

              

            </ul> </div>';



    } else{

        echo "<div class='no_features'>Invalid category selected.</div>";

    }
    echo '</div>';

    echo '</div>';

    echo '</div>';

    echo '</div>';

 }
  
//--------------------------------end video feature-----------------------------


// coupon feature start //
    
 add_action('admin_init','re_setup_coupon_list');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
 function re_setup_coupon_list(){
 
     add_action("add_meta_boxes", "re_add_coupon_meta_boxes");
     add_action("save_post","re_save_coupon_meta_boxes", 10, 3);

 
 }


 function re_add_coupon_meta_boxes()

 {
 
     add_meta_box("list-coupon-options", "Coupons", "re_coupon_meta_box_markup", "list_items", "normal", "high", null);
 
 
 
 }

 function re_coupon_meta_box_markup($object)
 {    $post_id = $object->ID;

    wp_nonce_field(basename(__FILE__), "nd-list-item-mbnonce");

    echo '<div class="meta-box-list">';

    echo '<div class="metabox-field">';

    $terms = get_terms( array(

        'taxonomy' => 'list_comp_categories',

        'hide_empty' => false,

    ) );

//    var_dump($terms);

    // $tax  = get_post_meta($post_id, 'coupon_list', true);

    $couponlist  = get_post_meta($post_id, 'coupons_list', true);
    file_put_contents("mvcf.txt","couponlist is ".print_r($couponlist,true).PHP_EOL,FILE_APPEND);

    if(!is_array($couponlist)){

        $couponlist = array();
	

    }

 


    echo '</div>';

    echo '<div class="metabox-field">';

    echo '<label for="">Features</label><div class="meta-field-inp">

<div id="list_items_features_meta">';

    $term_meta = get_option( "taxonomy_$tax" );

    $items = $term_meta['video_list'];

    echo '<div class="coupons_container post_edit_page"><span class="add_new_coupon">Add Another Coupon</span> <ul>

              

    </ul> </div>';
// $couponlist  = get_post_meta($post_id, 'video_list', true);
    if(!empty($couponlist)){



      
		if(!empty($couponlist) && is_array($couponlist)){
		

        

            echo '<div id="features">

				  <input class="search" type="text" placeholder="Search" />

				  <!-- class="sort" automagically makes an element a sort buttons. The date-sort value decides what to sort by. -->

				  <button class="sort" data-sort="name">

					Sort

				  </button>';

            echo "<ul class='post_coupons_list list'>";

            // $couponlist = $couponlist[0];
			$checked = "checked";
			
            foreach ($couponlist as $coupon){

                if(!empty($coupon)){
                    // for($j=0;$j<count($coupon);$j++){
                            // print_r($coupon);
                            $coupontypecoupon = ($coupon[type]=="coupon")? "selected":"";
                            $coupontypedeal = ($coupon[type]=="deal")? "selected":"";
                            $ccclass = ($coupontypedeal=="selected")?"hidden":"";
                            $ccrequired = ($coupontypedeal=="selected")?"":"required";
                             $coupondateshow = ($coupon[showDate]=="show")? "selected":"";
                            $coupondatehide = ($coupon[showDate]=="hide")? "selected":"";
                           /* $cdclass = ($coupondatehide=="selected")?"hidden":""; */
                            echo '<li><label class="main_label"> Coupon : </label><span class="remove_coupon">X Remove Coupon</span></li>';
                            echo ' <li><label> Coupon Type : </label></label><select class="coupdeal" name="old_coupon_type[]" ><option value="coupon" '.$coupontypecoupon.'>Coupon</option><option value="deal"  '.$coupontypedeal.'>Deal</option></select></li>';
                            
                            echo '<li class="'.$ccclass.'" data-mh="feat_li"><label> Coupon code : </label> <input type="text" name="old_coupon_code[]"  value="' . $coupon[code]. '" id="feat_'.$key.'" '.$checked.' '.$ccrequired.'></li>';
                            echo '<li><label> Link : </label><input type="text" name="old_coupon_link[]" value="'.$coupon[link].'" required> </li>';
                            echo '<li data-mh="feat_li"><label>Discount Amount/Text :</label> <input type="text" name="old_coupon_title[]"  value="' . $coupon[title]. '" id="feat_'.$key.'" '.$checked.' required></li>';
                            echo '<li data-mh="feat_li"><label> Description : </label> <input type="text" name="old_coupon_description[]"  value="' . $coupon[description]. '" id="feat_'.$key.'" '.$checked.' required></li>';
                            echo '<li><label> Coupon/Deal Expiration : </label></label><select class="exptoggle" name="old_coupon_expdate_show[]"><option value="show" '.$coupondateshow.'>show</option><option '.$coupondatehide.' value="hide">Hide</option>
                            
                          </select></li>';
                            echo '<li data-mh="feat_li " class = "exp_date"> <label> Expiry date : </label><input type="text" name="old_coupon_expiry_date[]"  value="' . $coupon[expdate]. '" id="feat_'.$key.'" '.$checked.' required></li>';
					
                            echo '<li data-mh="feat_li"> <input type="hidden" class="immutable" name="old_coupon_id[]"  value="' . $coupon[id]. '" id="feat_'.$key.'" '.$checked.'></li>';
					
                            echo '<li data-mh="feat_li"> <input type="hidden" class="immutable" name="old_coupon_votes[]"  value="' . $coupon[votes]. '" id="feat_'.$key.'" '.$checked.'></li>';

                    // }
                 /*    $checked = "checked";

                    echo '<li data-mh="feat_li"> <input type="checkbox" name="new_video_list[]"  value="' . $it. '" id="feat_'.$key.'" '.$checked.'><label for="feat_'.$key.'" class="name">'.$it.'</label></li>';
 */
                }

            }

            echo "</ul></div>";

        } else{

            echo "<div class='no_features'>No features added to category yet.</div>";

        }

      



    } else{

        echo "<div class='no_features'>No coupons found!</div>";

    }
    echo '<div class="coupons_container post_edit_page"><span class="add_new_coupon">Add Another Coupon</span> <ul>

              

    </ul> </div>';
    echo '</div>';

    echo '</div>';

    echo '</div>';

    echo '</div>';

 }


 function re_save_coupon_meta_boxes($post_id, $post, $update){
    // echo "inside re_save_coupon_meta_boxes";
    file_put_contents("mvcf.txt","inside re_save_coupon_meta_boxes".PHP_EOL,FILE_APPEND);
	 
	     file_put_contents("mv_save.txt","post_id ".print_r($post_id,true),FILE_APPEND);
	 file_put_contents("mv_save.txt","post: ".print_r($post,true),FILE_APPEND);
	  file_put_contents("mv_save.txt","post: ".print_r($update,true),FILE_APPEND);
	 
	 
    if($_POST['acf']['register'] != 'true'){ // bypass if it's coming from frontend
        if (!isset($_POST["nd-list-item-mbnonce"]) || !wp_verify_nonce($_POST["nd-list-item-mbnonce"], basename(__FILE__)) )

        return $post_id;
    }
  



    if(!current_user_can("edit_post", $post_id))

        return $post_id;



    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)

        return $post_id;



    $slug = "list_items";

     if($slug != $post->post_type)

        return $post_id; 



    /* $catValue ='';

    if(isset($_POST['features_category']))

    {

        $catValue = $_POST['features_category'];

    } */

    // update_post_meta($post_id, 'features_category', $catValue);
    // echo "existing counpons_list is ".print_r(get_post_meta($post,'coupons_list'));
    file_put_contents("mvcf.txt","coupons_list is: ".print_r(get_post_meta($post_id,'coupons_list'),true),FILE_APPEND);
    $titles = $_POST['old_coupon_title'];
    $descriptions = $_POST['old_coupon_description'];
    $codes = $_POST['old_coupon_code'];
    $dates = $_POST['old_coupon_expiry_date'];
    $ids=  $_POST['old_coupon_id'];
    $votes = $_POST['old_coupon_votes'];
    $coupon_types =  $_POST['old_coupon_type'];
    $coupon_links = $_POST['old_coupon_link'];
    $dates_show = $_POST['old_coupon_expdate_show'];
    $oldArr = array();

    for($i=0;$i<count($titles);$i++){
        if($titles[$i] != '' ){
            $oldArr []= array('type'=>$coupon_types[$i],
            'link'=>$coupon_links[$i],
            'title'=>$titles[$i],
            'description'=>$descriptions[$i],
        'code'=>$codes[$i],
        'showDate'=>$dates_show[$i],
        'expdate'=>$dates[$i],
        'id'=>$ids[$i],
        'votes'=>$votes[$i]);
        }
        
    }
    $meta_box_value =$oldArr;

    /* if(isset($_POST['coupons_list_old']))

    {

        $meta_box_value = $_POST['coupons_list_old'];

    } */

    // print_r($_POST);
    file_put_contents("mvcf.txt","post inside save coupons function : ".print_r($_POST,true).PHP_EOL,FILE_APPEND);

    if(isset($_POST['new_coupon_title']) && is_array($_POST['new_coupon_title']) && !empty($_POST['new_coupon_title'])){
        $coupon_types =  $_POST['new_coupon_type'];
        $coupon_links = $_POST['new_coupon_link'];
        $titles = $_POST['new_coupon_title'];
        $descriptions = $_POST['new_coupon_description'];
        $codes = $_POST['new_coupon_code'];
        $dates_show = $_POST['new_coupon_expdate_show'];
        $dates = $_POST['new_coupon_expiry_date'];
        // $newArr = array_filter($newArr);
        $nextid=0;
        foreach($meta_box_value as $key=>$mbv){
            $toExp=$mbv[id];
            $id=explode("_",$toExp);
            $id= $id[1];
            if($mbv[title]==''){
                unset($meta_box_value[$key]);
            }elseif($mbv[id]>=$nextid){
                $nextid = $mbv[id]+1;
            }
        }
        
        $newArr = array();
        for($i=0;$i<count($titles);$i++){
            if($titles[$i] != '' ){
                $newArr []= array('type'=>$coupon_types[$i],
                'link'=>$coupon_links[$i],
                'title'=>$titles[$i],
                'description'=>$descriptions[$i],
            'code'=>$codes[$i],
            'showDate'=>$dates_show[$i],
            'expdate'=>$dates[$i],
            'id'=>$post_id."_".$nextid,
            'votes'=>0);
            }
            
        }

        file_put_contents("mvcf.txt","meta box value is: ".print_r($meta_box_value,true),FILE_APPEND);
        file_put_contents("mvcf.txt","newarr is: ".print_r($newArr,true),FILE_APPEND);


        if(!empty($newArr)){

            $meta_box_value = array_merge($meta_box_value,$newArr);

        }

    }

    file_put_contents("mvcf.txt","final metaboxvalue is: ".print_r($meta_box_value,true),FILE_APPEND);
    /* foreach($meta_box_value as $key=>$mbv){
        if($mbv[title]==''){
            unset($meta_box_value[$key]);
        }
    } */

    update_post_meta($post_id, 'coupons_list', $meta_box_value);

    // $post_12 = get_page_by_path('coupon',OBJECT,'page');

    $the_slug = 'deals';
$args = array(
  'name'        => $the_slug,
  'post_type'   => 'page',
  'post_status' => 'publish',
  'numberposts' => 1
);
$my_posts = get_posts($args);

$id = $my_posts[0]->ID;
    $allDealPosts = get_post_meta($id,'all_posts_with_coupon');
    
//    echo "all deal posts are ".print_r($allDealPosts);
	 if(empty($allDealPosts) ){
		
		 $allDealPosts = array();
	 }
	 else{
		 $allDealPosts = $allDealPosts[0];
	 }
    

    
    if(!empty($meta_box_value)){
        $allDealPosts []= $post_id;
        $allDealPosts = array_unique($allDealPosts);

    }
	 else{
		 if (($key = array_search($post_id, $allDealPosts)) !== false) {
    unset($allDealPosts[$key]);
}
		 
	 }
	         update_post_meta($id, 'all_posts_with_coupon',$allDealPosts);


}
// coupon feature end //


// QA feature start //
 
add_action('admin_init','re_setup_qa_list');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
 function re_setup_qa_list(){
 
     add_action("add_meta_boxes", "re_add_qa_meta_boxes");
     add_action("save_post","re_save_qa_meta_boxes", 10, 3);

 
 }


 function re_add_qa_meta_boxes()

 {
 
     add_meta_box("list-qa-options", "qas", "re_qa_meta_box_markup", "list_items", "normal", "high", null);
     add_meta_box("list-qa-options", "qas", "re_qa_meta_box_markup", "lists", "normal", "high", null);
 
 
 }

 function re_qa_meta_box_markup($object)
 {    $post_id = $object->ID;

    wp_nonce_field(basename(__FILE__), "nd-list-item-mbnonce");

    echo '<div class="meta-box-list">';

    echo '<div class="metabox-field">';

    $terms = get_terms( array(

        'taxonomy' => 'list_comp_categories',

        'hide_empty' => false,

    ) );

//    var_dump($terms);

    // $tax  = get_post_meta($post_id, 'qa_list', true);

    $qalist  = get_post_meta($post_id, 'qas_list', true);
    file_put_contents("mvcf.txt","qalist is ".print_r($qalist,true).PHP_EOL,FILE_APPEND);

    if(!is_array($qalist)){

        $qalist = array();
	

    }

 


    echo '</div>';

    echo '<div class="metabox-field">';

    echo '<label for="">Features</label><div class="meta-field-inp">

<div id="list_items_features_meta">';

    $term_meta = get_option( "taxonomy_$tax" );

    $items = $term_meta['video_list'];

    echo '<div class="qas_container post_edit_page"><span class="add_new_qa">Add Another qa</span> <ul>

              

    </ul> </div>';
// $qalist  = get_post_meta($post_id, 'video_list', true);
    if(!empty($qalist)){



      
		if(!empty($qalist) && is_array($qalist)){
		

        

            echo '<div id="features">

				  <input class="search" type="text" placeholder="Search" />

				  <!-- class="sort" automagically makes an element a sort buttons. The date-sort value decides what to sort by. -->

				  <button class="sort" data-sort="name">

					Sort

				  </button>';

            echo "<ul class='post_qas_list list'>";

            // $qalist = $qalist[0];
			$checked = "checked";
			
            foreach ($qalist as $qa){

                if(!empty($qa)){
                    // for($j=0;$j<count($qa);$j++){
                            // print_r($qa);
                            
                           /* $cdclass = ($qadatehide=="selected")?"hidden":""; */
                            echo '<li><label class="main_label"> qa : </label><span class="remove_qa">X Remove qa</span></li>';
                         
                            echo '<li class="" data-mh="feat_li"><label> question : </label> <input type="text" name="old_question[]"  value="' . $qa[question]. '" id="feat_'.$key.'" '.$checked.' '.$ccrequired.'></li>';
                            echo '<li><label> Link : </label><input type="text" name="old_answer[]" value="'.$qa[answer].'" required> </li>';
                           
                    // }
                 /*    $checked = "checked";

                    echo '<li data-mh="feat_li"> <input type="checkbox" name="new_video_list[]"  value="' . $it. '" id="feat_'.$key.'" '.$checked.'><label for="feat_'.$key.'" class="name">'.$it.'</label></li>';
 */
                }

            }

            echo "</ul></div>";

        } else{

            echo "<div class='no_features'>No features added to category yet.</div>";

        }

      



    } else{

        echo "<div class='no_features'>No qas found!</div>";

    }
    echo '<div class="qas_container post_edit_page"><span class="add_new_qa">Add Another qa</span> <ul>

              

    </ul> </div>';
    echo '</div>';

    echo '</div>';

    echo '</div>';

    echo '</div>';

 }


 function re_save_qa_meta_boxes($post_id, $post, $update){
    // echo "inside re_save_qa_meta_boxes";
    file_put_contents("mvcf.txt","inside re_save_qa_meta_boxes".PHP_EOL,FILE_APPEND);
	 
	     file_put_contents("mv_save.txt","post_id ".print_r($post_id,true),FILE_APPEND);
	 file_put_contents("mv_save.txt","post: ".print_r($post,true),FILE_APPEND);
	  file_put_contents("mv_save.txt","post: ".print_r($update,true),FILE_APPEND);
	 
	 
    if($_POST['acf']['register'] != 'true'){ // bypass if it's coming from frontend
        if (!isset($_POST["nd-list-item-mbnonce"]) || !wp_verify_nonce($_POST["nd-list-item-mbnonce"], basename(__FILE__)) )

        return $post_id;
    }
  



    if(!current_user_can("edit_post", $post_id))

        return $post_id;



    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)

        return $post_id;



    $slug = "list_items";
    $slug2 = "lists";

     if($slug != $post->post_type && $slug2 != $post->post_type)

        return $post_id; 



    /* $catValue ='';

    if(isset($_POST['features_category']))

    {

        $catValue = $_POST['features_category'];

    } */

    // update_post_meta($post_id, 'features_category', $catValue);
    // echo "existing counpons_list is ".print_r(get_post_meta($post,'qas_list'));
    file_put_contents("mvcf.txt","qas_list is: ".print_r(get_post_meta($post_id,'qas_list'),true),FILE_APPEND);
    $questions = $_POST['old_question'];
    $answers = $_POST['old_answer'];
   
    $oldArr = array();

    for($i=0;$i<count($questions);$i++){
        if($questions[$i] != '' ){
            $oldArr []= array(
            'question'=>$questions[$i],
            'answer'=>$answers[$i],
        );
        }
        
    }
    $meta_box_value =$oldArr;

    /* if(isset($_POST['qas_list_old']))

    {

        $meta_box_value = $_POST['qas_list_old'];

    } */

    // print_r($_POST);
    file_put_contents("mvcf.txt","post inside save qas function : ".print_r($_POST,true).PHP_EOL,FILE_APPEND);
    file_put_contents("mvcf.txt","check 1 : ".isset($_POST['new_question']).PHP_EOL,FILE_APPEND);
    file_put_contents("mvcf.txt","check 2 : ".is_array($_POST['new_question']).PHP_EOL,FILE_APPEND);
    file_put_contents("mvcf.txt","check 3 : ".!empty($_POST['new_question']).PHP_EOL,FILE_APPEND);
    if(isset($_POST['new_question']) && is_array($_POST['new_question']) && !empty($_POST['new_question'])){
     
        $questions = $_POST['new_question'];
        $answers = $_POST['new_answer'];
        
        $nextid=0;
      /*   foreach($meta_box_value as $key=>$mbv){
            $toExp=$mbv[id];
            $id=explode("_",$toExp);
            $id= $id[1];
            if($mbv[title]==''){
                unset($meta_box_value[$key]);
            }elseif($mbv[id]>=$nextid){
                $nextid = $mbv[id]+1;
            }
        } */
        
        $newArr = array();
        for($i=0;$i<count($questions);$i++){
            if($questions[$i] != '' ){
                $newArr []= array(
                'question'=>$questions[$i],
                'answer'=>$answers[$i],
          );
            }
            
        }

        file_put_contents("mvcf.txt","meta box value is: ".print_r($meta_box_value,true),FILE_APPEND);
        file_put_contents("mvcf.txt","newarr is: ".print_r($newArr,true),FILE_APPEND);


        if(!empty($newArr)){

            $meta_box_value = array_merge($meta_box_value,$newArr);

        }

    }

    file_put_contents("mvcf.txt","final metaboxvalue is: ".print_r($meta_box_value,true),FILE_APPEND);
    /* foreach($meta_box_value as $key=>$mbv){
        if($mbv[title]==''){
            unset($meta_box_value[$key]);
        }
    } */

    update_post_meta($post_id, 'qas_list', $meta_box_value);

    // $post_12 = get_page_by_path('qa',OBJECT,'page');


}


//QA feature end//
function send_real_user_db( $post_id ) {
    file_put_contents("mvcf.txt","inside send_real_user_db",FILE_APPEND);

    $user_id = get_field('real_author');
    file_put_contents("mvcf.txt","user id :".$user_id,FILE_APPEND);

    if(trim($user_id) != ''){
        
    global $wpdb;
    $table_name = $wpdb->prefix . "user_wise_claim_list";
    file_put_contents("mvcf.txt","database name".$wpdb->dbname,FILE_APPEND);
    if($wpdb->get_var("SHOW TABLES LIKE $table_name") != $table_name) {
        
        $charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $table_name (
  user_id mediumint(9) NOT NULL,
  software_ids mediumint(9) NOT NULL,
  PRIMARY KEY  (software_ids)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

    }
    $db_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE software_ids = {$post_id} " );	
    if ( null === $db_data ) {
    $wpdb->insert( 
        $table_name, 
    array( 
        'user_id' => $user_id, 
        'software_ids' => $post_id, 
    ), 
    array( 
        '%d', 
        '%d', 
    ) 
  );
}
else{
    $wpdb->update( 
        $table_name, 
array( 
    'user_id' => $user_id,	// string
    'software_ids' => $post_id	// integer (number) 
), 
array( 'software_ids' => $post_id ), 
array( 
    '%d',	// value1
    '%d'	// value2
), 
array( '%d' ) 
);

}
    }
}
add_action( 'save_post', 'send_real_user_db' );

function post_published_notification( $post ) {
    $email1 = get_field( "email" );
     $email2 = get_field( "email2" );
     $email3 = get_field( "email_3_optional" );
 	    file_put_contents("acf.txt",$email1.$email2.$email3);
     $group_emails = array( $email1, $email2, $email3 );
     $post_id = $post->ID;
     $post_name = get_the_title($post_id);
     $post_link = get_permalink($post_id);
     $subject = "Checkout my new software product on softwarefindr.com";
     $message ="$post_name\n $post_link";
     wp_mail( $group_emails, $subject, $message);
 }
 
 add_action(  'pending_to_publish',  'post_published_notification', 10, 1 );
 
 add_filter( 'mce_external_plugins', 'add_mce_placeholder_plugin' );

function add_mce_placeholder_plugin( $plugins ){

	// Optional, check for specific post type to add this
	// if( 'my_custom_post_type' !== get_post_type() ) return $plugins;

	// This assumes you placed mce.placeholder.js in root of child theme directory
	$plugins['placeholder'] = get_stylesheet_directory_uri() . '/assets/js/mce.placeholder.js';

	// You can also specify the exact path if you want:
	// $plugins[ 'placeholder' ] = '//domain.com/full/path/to/mce.placeholder.js';

	return $plugins;
}

/** Edit TinyMCE , it was adding a P below content field on submit page **/
function myformatTinyMCE($in) {
    $in['statusbar'] = false;

    return $in; 
}
add_filter('tiny_mce_before_init', 'myformatTinyMCE' );


add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() { //hides some unnecessary fields on edit list item backend
		  echo '<style>
			.acf-image-uploader.has-value {
				display: none;
			}
			.acf-field.acf-field-checkbox.acf-field-5d230e4e36286 {

				display: none;

			}.acf-field.acf-field-image {
				display: none;
			}div#backend_cat {
    display: none;
}
ul.post_coupons_list.list {
   border: #DFDFDF solid 1px;
    padding: 1em;
}
li.exp_date {
   border-bottom: 1px solid #ccc; 
    margin-bottom: 1em;
    padding-bottom: 1em;
   
}
label.main_label {
    font-size: 14px;
    font-weight: 700;
    color: #23282d;
}
		
		  </style>';
}			



//---------------------------------ajaxk sorted coupon list------------------------------------------

add_action('wp_ajax_populate_coupons', 'populate_coupons_func');           // for logged in user
add_action('wp_ajax_nopriv_populate_coupons', 'populate_coupons_func');    // if user not logged in

function populate_coupons_func(){
        $current_page_no = $_POST['current_page_no'];
        if($current_page_no == ''){
            $current_page_no=1;
        }
//		$category       = $_POST['category_name'];
		$search_term      = $_POST['search_item'];	
		$cat_id  = $_POST['term_id'];
//		$url = $_POST['link_url'];
file_put_contents ("mvcf.txt","populate coupons function : current page no : $current_page_no search term : $search_term cat_id : $cat_id",FILE_APPEND);
$post_per_page = 10;
$the_slug = 'deals';
$args = array(
  'name'        => $the_slug,
  'post_type'   => 'page',
  'post_status' => 'publish',
  'numberposts' => 1
);
$my_posts = get_posts($args);

$id = $my_posts[0]->ID;
	
			  $allDealPosts = get_post_meta($id,'all_posts_with_coupon');
	$allDealPost =$allDealPosts[0];
	
	$filtered_coupons =array();
	foreach($allDealPost as $post_id){
		
		    
	$couponlist  = get_post_meta($post_id, 'coupons_list', true);

		foreach($couponlist as $couponlist_new)
				{
						
  
		
//			file_put_contents("mv_ddd.txt",print_r($deal_form ,true),FILE_APPEND);		

			
			if($search_term == ''  || strpos(strtolower(get_the_title($post_id)),strtolower($search_term))!== false )
				{
				$category_list = get_the_terms($post_id,'list_categories');
				foreach($category_list as $category){
					
					if($category->term_id == $cat_id || trim($cat_id) == ''){
						$couponlist_new[post_id] = $post_id;
						
						$featured_img_url = get_the_post_thumbnail_url($post_id,array(150,150)); 
					 $alt = basename($featured_img_url);
					 $pos = strrpos($alt, ".");
					 $alt = substr($alt,0,$pos);
					$couponlist_new[image] = '<img src='.$featured_img_url.' class="img-responsive coupon_img"  alt='.$alt.'>';
					$couponlist_new[post_title] = get_the_title($post_id); 
					$couponlist_new[post_link] = get_post_permalink($post_id);
						$ratings = do_shortcode( '[rwp_reviewer_rating_stars id=0 size=20 post='.$post_id.']' );
					   $ratings = str_replace( "No review found! Insert a valid review ID.","",$ratings);
     $couponlist_new[rating]=  $ratings;
     	
						file_put_contents("mv.txt","rating".print_r( $couponlist_new[rating] ,true),FILE_APPEND);
//						file_put_contents("mv.txt","count_label".print_r($count_label ,true),FILE_APPEND);
//						file_put_contents("mv.txt","compCount".print_r($compCount ,true),FILE_APPEND);
//						
						
						if(empty($filtered_coupons)){
							$filtered_coupons []= $couponlist_new;
						}
						else{
							
							 find_swap_position( $filtered_coupons, $couponlist_new);
							
						}
					
						break;
					}
				}
				
						
				
			}
			
			

			
		}
//		$sorted_coupons = array();
//		foreach($filtered_coupons as $filtered_coupon){
//			
//			
//		}
		
	}
//	$current_page_no = 2;
			$start_index= ($current_page_no-1)*$post_per_page;
            $end_index = $start_index+$post_per_page;
            $total_posts = $current_page_no*$post_per_page;
            file_put_contents("mvvv.txt","total posts $total_posts "." count fc ".count($filtered_coupons),FILE_APPEND);

//			file_put_contents("mvvv.txt","filtered_coupons ".print_r($filtered_coupons ,true),FILE_APPEND);
            $no_more_posts = 0;
            if($total_posts > count($filtered_coupons)){
                $no_more_posts =1;
            }
			$filtered_coupons= array_slice($filtered_coupons,0 ,$total_posts);
//			file_put_contents("mvvv.txt","filtered_coupons ".print_r($filtered_coupons ,true),FILE_APPEND);
	
		 $resp = array("filtered_coupon"=> $filtered_coupons,"no_more_posts"=> $no_more_posts);
	
	
echo json_encode($resp); 
	
wp_die();
	
}


function find_swap_position( &$filtered_coupons, $couponlist_new){
	
	
	$position = count($filtered_coupons);
	
	$start = 0;
	$end = $position -1;
	$mid =floor(( $end + $start)/2);
	
	
	for(; $start <= $end; $mid =floor(( $end + $start)/2) ){
		 $sorted_coupon= $filtered_coupons[$mid];
		$votes = $sorted_coupon['votes'] ;
		
		if($couponlist_new['votes'] > $votes){
			
			$end = $mid-1;
			$position = $mid;
		}else{
			
			$start = $mid+1;
//			$position = $mid;
				
			
		}
		
		
//		file_put_contents("mvvv.txt","vote ".print_r($votes ,true),FILE_APPEND);
		
		
		
	}
	
	for($i= count($filtered_coupons)-1; $i>= $position; $i--){
		
		$filtered_coupons[$i+1] = $filtered_coupons[$i];
	}
	$filtered_coupons[$position]= $couponlist_new;

}

/************************************LIST ITEM TILTLE shortcode start **************************************** */

function list_item_title( $atts ) {
    // print_r($atts);
    if(!isset($atts[sop])){
        $atts[sop] = 'plural';
    }
    if(!isset($atts[id])){
        $atts[id] = get_the_ID();
    }
        
    $atts = shortcode_atts( array(
        'id' => $atts[id],
        'sop' => $atts[sop]
    ), $atts, 'list_number' );
    $list_id =  $atts['id'];

//    print_r($atts);
    if($atts[sop] == 'singular'){
        $list_item = get_field('list_content_title_singular', $list_id, true);
        if($list_item==''){
            $list_item = get_field('list_content_title_plural', $list_id, true);

        }
            
    }
        
    else{
        $list_item = get_field('list_content_title_plural', $list_id, true);
        if($list_item==''){
            $list_item = get_field('list_content_title_singular', $list_id, true);
            
        }
    }
        


    if(!empty($list_item)){
        return $list_item;
    }
    else{
        return get_the_title($atts['id']);
    }
    // var_dump($list_item);
}

function create_feature_ratings($post_id,$features){

    file_put_contents("mvcf.txt","inside create_feature_ratings $post_id,$features",FILE_APPEND);
    $features_list_ratings = array();
    foreach($features as $mbv){
        // update_post_meta($post_id, 'features_list_ratings', array());


        $total_score = 0;
        $votes = 0;
        $average = 0;
        // $unusedFeatureListRatings = $featureListratings;
        
        $f_ = str_replace(" ","_",$mbv);    
        $features_list_ratings [$f_]= array('total_score' => $total_score,
        'votes' => $votes, 'average'=> $average);
    }
    
    // update_post_meta($post_id, 'features_list', $meta_box_value);
    update_post_meta($post_id, 'features_list_ratings', $features_list_ratings);
    return $feature_list_ratings;
}
add_shortcode( 'list_title', 'list_item_title' );


/************************************LIST ITEM TILTLE shortcode start **************************************** */
/************************************   Finderscore **************************************** */

function get_or_calc_fs_individual($post_id){
    $fsArr = get_post_meta('findrScore',$post_id,true);
    file_put_contents("mvcf.txt","postid $post_id fsarr ".print_r($fsArr,true).PHP_EOL,FILE_APPEND);

    if(!empty($fsArr)){
       $lastupdated =  $fsArr['lastupdated'];
       $daysfromnow = (time() - $lastupdated)/1000;//60/60/24;
    }
    file_put_contents("mvcf.txt",'daysfromnow '.$daysfromnow.PHP_EOL,FILE_APPEND);
 

    file_put_contents("mvcf.txt",'emptyfsarr '. !empty($fsArr) .'days 7 '.($daysfromnow<7).PHP_EOL,FILE_APPEND);

    if(!empty($fsArr) && $daysfromnow<7){
        
    }
    else{
        $fs=$fsArr['score'];
        $fs = calculate_findrscore_individual($post_id);
    }

   
    return $fs;
}
function calculate_findrscore_individual($post_id){
        // file_put_contents("mvlistsvn.txt",$location,FILE_APPEND);
    $ranklist = get_item_ranks($post_id);

    $findrScore = 0; // for finderscore
    $reviews = get_overall_combined_rating($post_id);

    $findrScore = $reviews['list'][overallrating][score]*10;
    $listrankord = $ranklist;  

    $percentileSum = 0;
    foreach($listrankord as $listid => $rank){
        $noOflistitems = sizeof(get_field('list_items',$listid));
        $percentileSum += ($noOflistitems-$rank)/$noOflistitems;
    }
    $percentileAvg = $percentileSum/(sizeof($listrankord));
    $findrScore += $percentileAvg*50; 
    $findrScore = round($findrScore);
    $currenttimestamp = time();

    update_post_meta($post_id,'findrScore',array('fs'=>$findrScore,'lastupdated'=>$currenttimestamp));
    return $findrScore;
}

/************************************   Finderscore **************************************** */