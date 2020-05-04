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
 

/*----------------------------------------------Software search functionality---------------------------------------------------------*/
function mv_search_shortcode() {
//file_put_contents("debug.txt","mvshortcode",FILE_APPEND);
?>
	<form autocomplete="off" action="/action_page.php" class="search_form">
	<div class="autocomplete" style="width:300px;">
	<input id="myInput" type="text" name="software" placeholder="Software">
	  <input type ="hidden" id = 'mv_search'> 
	  <span id="myOutput"></span>
	 </div>
	</form>	
<?php
	global $wpdb;
	$custom_post_type = 'list_items'; // define your custom post type slug here
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );
		foreach( $results as $index => $post ) {	
		  $current_title = $post['post_title'];
		  $current_id = $post['ID'];
		  $current_link = get_permalink($current_id);
		  $current_user = wp_get_current_user();
		  $user_id_mv = $current_user->ID;
		  $complex[] = $post['post_title']."|".$post['ID']."|".$current_link."|".$user_id_mv;
		  //print_r($current_link);
		}
	?>
  
<script>  
	
	 
	  var search_list = <?php echo json_encode($complex); ?>;  
	 
	  function autocomplete(inp,inpmv,arr) {
			var currentFocus;
			inp.addEventListener("input", function(e) {
			var a, b, i, val = this.value;
			closeAllLists();
            if (!val) { return false;}
			   currentFocus = -1;     
			   a = document.createElement("DIV");
				
			   a.setAttribute("id", this.id + "autocomplete-list");
			   a.setAttribute("class", "autocomplete-items");   
			   this.parentNode.appendChild(a);     
				 for (i = 0; i < arr.length; i++) {  
					  var ret = arr[i].split("|");		 
					  var post_title = ret[0];
					  var post_id = ret[1];
					  var post_link = ret[2];  
					  var user_id = ret[3];
console.log(search_list);
          if (post_title.substr(0, val.length).toUpperCase() == val.toUpperCase()) {        
				b = document.createElement("DIV"); 
			  b.setAttribute("class", "autocomplete_name");
			  
				b.innerHTML = "<strong>" + post_title.substr(0, val.length) + "</strong>";
				//b.innerHTML = "<a href=''" + post_link.substr(0, val.length) + "</a>";
				b.innerHTML += post_title.substr(val.length);	
				b.innerHTML += "<input type='hidden' value='" + post_title + "'>";
				b.innerHTML += "<input type='hidden' value='" + post_id + "'>"; 	
				b.innerHTML += "<input type='hidden' value='" + post_link + "'>";        
					b.addEventListener("click", function(e) {
						inp.value = this.getElementsByTagName("input")[0].value;
						inpmv.value = this.getElementsByTagName("input")[1].value;
						var link_mv = this.getElementsByTagName("input")[2].value;	
						window.open(link_mv);            
						var mv_postid = inp_id.value;         			   
  						closeAllLists();
					});
					a.appendChild(b);
					d = document.createElement("DIV"); 
					d.innerHTML += '<a class="claim-listing-button" onclick="claim_listing_func('+'1'+','+post_id+','+user_id+')" href="#">Claim Listing </a>';
					a.appendChild(d);
				  }
                    }
                     c = document.createElement("DIV");         
           			 c.innerHTML = '	<div class="items" ><span class="formheading-1">	DON\'T SEE WHAT YOU\'RE LOOKING FOR?	</span><p id="addnew"> Add a new product</p></div> ';
					 a.appendChild(c);
                      document.getElementById("addnew").addEventListener("click", function(e) {         
					  document.getElementById("acf-form").removeAttribute("hidden");
					  console.log("add a new product");
					  });
                    
			});
			  inp.addEventListener("keydown", function(e) {
				var x = document.getElementById(this.id + "autocomplete-list");
				if (x) x = x.getElementsByTagName("div");
				if (e.keyCode == 40) {        
				  currentFocus++;        
				addActive(x);
				} else if (e.keyCode == 38) {
				  currentFocus--;       
				  addActive(x);
					} else if (e.keyCode == 13) {
					  e.preventDefault();
				  if (currentFocus > -1) {         
				  if (x) x[currentFocus].click();
				  }
					}
				});
					  function addActive(x) {		
					  if (!x) return false;			
					  removeActive(x);
					  if (currentFocus >= x.length) currentFocus = 0;
					  if (currentFocus < 0) currentFocus = (x.length - 1);			
					  x[currentFocus].classList.add("autocomplete-active");
					  }
					  function removeActive(x) {			
					  for (var i = 0; i < x.length; i++) {
						x[i].classList.remove("autocomplete-active");
					  }
  					}
					  function closeAllLists(elmnt) {
					  var x = document.getElementsByClassName("autocomplete-items");

					  for (var i = 0; i < x.length; i++) {
						if (elmnt != x[i] && elmnt != inp) {
						x[i].parentNode.removeChild(x[i]);
					  }
					  }
					}
					document.addEventListener("click", function (e) {
					  closeAllLists(e.target);
					});
				}
	autocomplete(document.getElementById("myInput"),document.getElementById("mv_search"), search_list);  
	function submitAll(){
	  console.log("hello there");
	  $('.acf-button')[3].click();
	  console.log($('.acf-button')[3]);
	}
  </script>
<?php 
}
add_shortcode( 'mv_search', 'mv_search_shortcode' );		


add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

function my_action_javascript() { ?>
	<script type="text/javascript" >
  console.log("trying official");
	jQuery(document).ready(function($) {

		var data = {
			'action': 'my_action2',
			'whatever': 1234
		};
    var ajaxurl = "https://area51.softwarefindr.com/wp-admin/admin-ajax.php";
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			alert('Got this from the server: ' + response);
		});
	});
	</script> <?php
}
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
function my_relationship_query( $args, $field, $post_id ) {
	// file_put_contents("functions.txt",print_r($args,true));
	
		$value = get_field( "cat", $post_id );

	if(!empty($value) ){
		
	/* 	$args = array(
      'post_type' => 'lists',
      'tax_query' => array(
        array(
          'taxonomy' => 'list_categories',
//          'field' => 'slug',
          'terms' => $value,
        )
      )
	); */
	$args['post_type']='lists';
	$args['tax_query'][0]['taxonomy'] = 'list_categories';
	$args['tax_query'][0]['terms'] = $value;
	 	
	// file_put_contents("functions.txt",print_r($args,true),FILE_APPEND);

	
	return $args;

	}
	else{
		return $args;
	file_put_contents("update.txt","else not working3 ".$value);
	}
	
    
}

add_filter('acf/fields/relationship/query', 'my_relationship_query', 10, 3);


add_action('acf/save_post', 'my_save_post');
function my_save_post( $post_id ) {
	
	
	if(!has_post_thumbnail($post_id)){
		
		$url = get_field("affiliate_url", $post_id);
	
		//	file_put_contents("imagurl.txt", $url,FILE_APPEND);
			
		//$url = 'binarymoon.co.uk';
			$width = 300;
			$height = 125;
			$title = "lkdjfklgdskgl";
		//$image_set = BrowserShots::get_shot( "www.google.com", $width, $height );	
			$image_set =  BrowserShots::get_shot( $url, 600, 450 );
			$image_url = $image_set;
			$image_tmp = download_url($image_url);
		
			if( is_wp_error( $image_tmp ) ){
				echo "<br> Image Download Fail:";
			}else {
				$image_size = filesize($image_tmp);
				$image_name = basename($image_url) . ".jpg"; // .jpg optional
		
		
				//Download complete now upload in your project
				$file = array(
				   'name' => $image_name, // ex: wp-header-logo.png
				   'type' => 'image/jpg',
				   'tmp_name' => $image_tmp,
				   'error' => 0,
				   'size' => $image_size
				);
		
		
				//This image/file will show on media page...
				$thumb_id = media_handle_sideload( $file, $post_id, $desc);
				set_post_thumbnail($post_id, $thumb_id); //optional
		
				echo "<br> Image Save ";
			}
		
		//
		//	file_put_contents("imageset.txt",$image_set);	
		//
		//	file_put_contents("final_iamge.txt",$final);
		
		
	}
	if($_POST['acf']['register'] == 'true'){
		// if($_POST['acf'][field_5d47f0894d6f4][0] == 'Yes') {
			$current_user = wp_get_current_user();	
			$userid = $current_user->ID;		
			update_field("real_author",$userid,$post_id);
		// }
		// file_put_contents("functions.txt",print_r($_POST,true));
		//---------------------------------postcategory------------------------------------------------
		
		$post_categories = $_POST['acf'][field_5d47f0894d682];
		
		// wp_set_post_categories( $post_id, array(7686) );
//		file_put_contents("post_category.txt",print_r($post_categories,true),FILE_APPEND);

		$cats = array_values($post_categories);
//		file_put_contents("mysavepost2.txt",print_r($cats,true),FILE_APPEND);
		wp_set_post_terms( $post_id, $cats, 'list_categories', false );
		
		//---------------------------------postcategory------------------------------------------------	

		$meta_box_value =array();
			if(isset($_POST['features_list']))  
			{  
				$meta_box_value = $_POST['features_list'];
  
      		}
  
//		file_put_contents("upadted_value.txt",print_r($meta_box_value),FILE_APPEND);
      if(isset($_POST['new_features_list']) && is_array($_POST['new_features_list']) && !empty($_POST['new_features_list'])){
			  $newArr = $_POST['new_features_list'];  
			  $newArr = array_filter($newArr);  
		  			if(!empty($newArr) ){  
		//				  $term_meta = get_option( "taxonomy_$catValue" );
						  $items = array();
							if(!empty($term_meta) && is_array($term_meta)){  
									if(isset($term_meta['features_list'])) {  
										$items = $term_meta['features_list'];
//					  					file_put_contents("upadted_value.txt",print_r($items),FILE_APPEND);

  										}
  
              						} else{  
                  						$term_meta = array();
  
              						}
  
              $newFat = array_merge($items,$newArr);
  
              $term_meta['features_list']=$newFat;
 
              $meta_box_value = array_merge($meta_box_value,$newArr);
  
          }
  
		  
		  
      }
  	$abc = get_field("new_video_list[]", $post_id);
//  file_put_contents("mysavepost212.txt","videoArr is: ".print_r($abc,true),FILE_APPEND);
	$newArr  = $_POST['new_features_list']; 
//		file_put_contents("mysavepost2.txt","newArr is: ".print_r($newArr,true),FILE_APPEND);
		
		foreach($newArr as $key=>$list_new_item){
			
			
			$newArr[$key]= trim($list_new_item);
			
			
//			file_put_contents("new_list.txt","newArr is: ".print_r($result,true),FILE_APPEND);
		
		}
			$result = array_unique($newArr);
      update_post_meta($post_id, 'features_list', $result);
//	  update_post_meta($post_id, 'features_list2', $newArr);		
	  $videoArr  = $_POST['new_video_list']; 
//		file_put_contents("mysavepost2.txt","videoArr is: ".print_r($_POST,true),FILE_APPEND);
		foreach($videoArr as $key=> $video){
			if(trim($video) == ''){
				unset($videoArr[$key]);
			}
		}
		update_post_meta($post_id, 'video_list', $videoArr);
		
	
  }

}



add_action( 'wp_ajax_get_bubbles', 'get_bubbles' );
add_action( 'wp_ajax_nopriv_get_bubbles', 'get_bubbles' );
	function get_bubbles() {		
		$list_id = $_POST['list_id'];
//		file_put_contents("debugbubbles.txt","list_id is   ".print_r($list_id,true ) );
		
		$popularityArray = array();
		foreach($list_id as $li){
//			file_put_contents("debugbubbles.txt","list_id11 is   ".print_r($list_id,true ));
		 	$items_in_list = get_field("list_items",$li);
			$i=0;
				foreach($items_in_list as $iil){
					$thisFeatures = get_field( 'features_list', $iil->ID );
					// print_r($thisFeatures);
					foreach($thisFeatures as $tf){
//						file_put_contents("debugbubbles.txt","i is  $i ",FILE_APPEND);

						if(!array_key_exists($tf, $popularityArray))
							$popularityArray[$tf] = 1;
						else
							$popularityArray[$tf]++;

							}
						$i++;
					}
			}
		// echo "bubbles";
		arsort($popularityArray);
		$popularityArray = array_slice($popularityArray,0,20);
		 wp_send_json($popularityArray);
	}

add_action( 'wp_ajax_claim_action', 'claim_action' );
add_action( 'wp_ajax_nopriv_claim_action', 'claim_action' );
	function claim_action() {
			$allTheClaimsID = 146580;
			//$posttitle = $_POST['postiddata'];
			 $posttitle = $_POST['posttitle'];
//			file_put_contents("debug.txt","posttile is   ".$posttitle  ,FILE_APPEND);
			$existing_options = get_field("claim_list2",$_POST['post_id']);
			$existing_options_all = get_field("claim_list2",$allTheClaimsID);
			// array_push($existing_options,$_POST['current_user']);
			$value = array("red", "blue", "yellow");
			$value = $existing_options."checking\n";
			echo "post id ". $_POST['post_id'];
//			file_put_contents("debug.txt","postid is   ".$_POST['post_id']  ,FILE_APPEND);
			update_field("claim_list2",$existing_options_all."user Id of the claimant is : ".$_POST['current_user']." post id claimed is : ". $_POST['post_id']."\n",$allTheClaimsID );
			update_field("claim_list2",$existing_options."user Id of the claimant is : ".$_POST['current_user']."\n",$_POST['post_id']);
//			file_put_contents("claimaction.txt","1",FILE_APPEND);
			$resp = array("postid" => $posttitle);
//			file_put_contents("claimaction.txt","2",FILE_APPEND);
			echo "hi";
			echo json_encode($resp); 
//			file_put_contents("claimaction.txt","3",FILE_APPEND);
			wp_die();
//			file_put_contents("claimaction.txt","4",FILE_APPEND);
	}


	function load_wp_media_files() {
	wp_enqueue_media();
	}
add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );

function my_project_updated_send_email( $post_id ) {
			global $wpdb;
			$user_id = get_field('real_author');
			$db_data = $wpdb->get_row( "SELECT * FROM user_wise_claim_list WHERE software_ids = {$post_id} " );	
			if ( null === $db_data ) {
			$wpdb->insert( 
			'user_wise_claim_list', 
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
		'user_wise_claim_list', 
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
add_action( 'save_post', 'my_project_updated_send_email' );




function post_published_notification( $post ) {
   $email1 = get_field( "email" );
	$email2 = get_field( "email2" );
	$email3 = get_field( "email_3_optional" );
//	/file_put_contents("acf.txt",$email1.$email2.$email3);
	$group_emails = array( $email1, $email2, $email3 );
	$post_id = $post->ID;
	$post_name = get_the_title($post_id);
	$post_link = get_permalink($post_id);
    $subject = "Checkout my new software product on softwarefindr.com";
    $message ="$post_name\n $post_link";
    wp_mail( $group_emails, $subject, $message);
}



add_action(  'pending_to_publish',  'post_published_notification', 10, 1 );


	
				


add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
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
		
		  </style>';
}			



/**
 * Download an image from the specified URL and attach it to a post.
 * Modified version of core function media_sideload_image() in /wp-admin/includes/media.php  (which returns an html img tag instead of attachment ID)
 * Additional functionality: ability override actual filename, and to pass $post_data to override values in wp_insert_attachment (original only allowed $desc)
 *
 * @since 1.4 Somatic Framework
 *
 * @param string $url (required) The URL of the image to download
 * @param int $post_id (required) The post ID the media is to be associated with
 * @param bool $thumb (optional) Whether to make this attachment the Featured Image for the post (post_thumbnail)
 * @param string $filename (optional) Replacement filename for the URL filename (do not include extension)
 * @param array $post_data (optional) Array of key => values for wp_posts table (ex: 'post_title' => 'foobar', 'post_status' => 'draft')
 * @return int|object The ID of the attachment or a WP_Error on failure
 */
function somatic_attach_external_image( $url = null, $post_id = null, $thumb = null, $filename = null, $post_data = array() ) {
    if ( !$url || !$post_id ) return new WP_Error('missing', "Need a valid URL and post ID...");
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    // Download file to temp location, returns full server path to temp file, ex; /home/user/public_html/mysite/wp-content/26192277_640.tmp
    $tmp = download_url( $url );

    // If error storing temporarily, unlink
    if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']);   // clean up
        $file_array['tmp_name'] = '';
        return $tmp; // output wp_error
    }

    preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);    // fix file filename for query strings
    $url_filename = basename($matches[0]);                                                  // extract filename from url for title
    $url_type = wp_check_filetype($url_filename);                                           // determine file type (ext and mime/type)

    // override filename if given, reconstruct server path
    if ( !empty( $filename ) ) {
        $filename = sanitize_file_name($filename);
        $tmppath = pathinfo( $tmp );                                                        // extract path parts
        $new = $tmppath['dirname'] . "/". $filename . "." . $tmppath['extension'];          // build new path
        rename($tmp, $new);                                                                 // renames temp file on server
        $tmp = $new;                                                                        // push new filename (in path) to be used in file array later
    }

    // assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
    $file_array['tmp_name'] = $tmp;                                                         // full server path to temp file

    if ( !empty( $filename ) ) {
        $file_array['name'] = $filename . "." . $url_type['ext'];                           // user given filename for title, add original URL extension
    } else {
        $file_array['name'] = $url_filename;                                                // just use original URL filename
    }

    // set additional wp_posts columns
    if ( empty( $post_data['post_title'] ) ) {
        $post_data['post_title'] = basename($url_filename, "." . $url_type['ext']);         // just use the original filename (no extension)
    }

    // make sure gets tied to parent
    if ( empty( $post_data['post_parent'] ) ) {
        $post_data['post_parent'] = $post_id;
    }

    // required libraries for media_handle_sideload
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // do the validation and storage stuff
    $att_id = media_handle_sideload( $file_array, $post_id, null, $post_data );             // $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status

    // If error storing permanently, unlink
    if ( is_wp_error($att_id) ) {
        @unlink($file_array['tmp_name']);   // clean up
        return $att_id; // output wp_error
    }

    // set as post thumbnail if desired
    if ($thumb) {
        set_post_thumbnail($post_id, $att_id);
    }

    return $att_id;
}


// post category

$custom_terms = get_terms('list_categories');

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



//ACf fields labels 

add_filter( 'acf/get_valid_field', 'change_input_labels');
function change_input_labels($field) {
		
	if($field['name'] == '_post_title') {
		$field['label'] = 'Product name';		
		$field['placeholder'] = 'Name of your product?';
		
	}
		
	if($field['name'] == '_post_content') {
		$field['label'] = 'Product description';	
		
		 
		$field['required'] 	   = 1;
	}
		
	return $field;
		
}


/** Edit TinyMCE , it was adding a P below content field on submit page **/
function myformatTinyMCE($in) {
    $in['statusbar'] = false;

    return $in; 
}
add_filter('tiny_mce_before_init', 'myformatTinyMCE' );

add_filter( 'mce_external_plugins', 'add_mce_placeholder_plugin' );

function add_mce_placeholder_plugin( $plugins ){

	// Optional, check for specific post type to add this
	// if( 'my_custom_post_type' !== get_post_type() ) return $plugins;

	// This assumes you placed mce.placeholder.js in root of child theme directory
	$plugins['placeholder'] = get_stylesheet_directory_uri() . '/mce.placeholder.js';

	// You can also specify the exact path if you want:
	// $plugins[ 'placeholder' ] = '//domain.com/full/path/to/mce.placeholder.js';

	return $plugins;
}

