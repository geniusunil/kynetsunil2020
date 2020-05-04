<?php

class Mv_Acf_Hooks {

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		//before save
		add_action( 'acf/save_post', array( $this, 'save_existing_attachment_data' ), 2 );
		//after save
		add_action( 'acf/save_post', array( $this, 'save_add_remove_list_attachment' ), 20 );
		add_action('acf/save_post',  array( $this, 'my_save_post'),20);
		add_filter('acf/fields/relationship/query', array( $this, 'my_relationship_query'), 10, 3);
		add_filter( 'acf/get_valid_field', array( $this,'change_input_labels'), 10, 3);
		// populate promoted items chekboxes in backend.
		add_filter('acf/load_field/name=promoted_list_items',array( $this, 'acf_load_promoted_item_choices'));
	}
	//ACf fields labels 


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




	function save_existing_attachment_data( $post_id ) {

		if ( isset( $_POST['acf']['field_59352650bde8a'] ) ) {
			$posts = get_field( 'add_to_list', $post_id, false );
			update_post_meta( $post_id, 'previous_attached_lists', $posts );
		}

		if ( isset( $_POST['acf']['field_5d20360c9f178'] ) ) {
			$posts = get_field( 'list_items', $post_id, false );
			update_post_meta( $post_id, 'previous_attached_lists', $posts );
		}


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
	// file_put_contents("update.txt","else not working3 ".$value);
	}
	
    
}

	function save_add_remove_list_attachment( $post_id ) {
		$posts= 'false';
		if ( isset( $_POST['acf']['field_59352650bde8a'] ) ) {
			$posts = $_POST['acf']['field_59352650bde8a'];
			$meta_key = 'list_items';
		} elseif ( isset( $_POST['acf']['field_5d20360c9f178'] ) ) {
			$posts = $_POST['acf']['field_5d20360c9f178'];
			$meta_key = 'add_to_list';
		}
		file_put_contents("pluginhooks.txt","posts\n".print_r($posts,true),FILE_APPEND);
		file_put_contents("pluginhooks.txt","posts value\n".$posts,FILE_APPEND);
		file_put_contents("pluginhooks.txt","posts boolean\n". ($posts != false) ,FILE_APPEND);
		/* if($posts == ''){
			$posts = array();
		} */
		if ( $posts != 'false') {
			file_put_contents("pluginhooks.txt","crossed if posts\n",FILE_APPEND);

			if ( !empty( $posts ) ) {
				file_put_contents("pluginhooks.txt","posts not empty\n",FILE_APPEND);

				$this->add_attached( $posts, $post_id, $meta_key );
				$previous_attached_lists = get_post_meta( $post_id, 'previous_attached_lists', true );
				if ( $previous_attached_lists ) {
					$removed_lists = array_diff( $previous_attached_lists, $posts );
					$this->remove_attached( $removed_lists, $post_id, $meta_key );
				}
			} else {
				file_put_contents("pluginhooks.txt","posts is empty\n",FILE_APPEND);

				$previous_attached_lists = get_post_meta( $post_id, 'previous_attached_lists', true );
				$this->remove_attached( $previous_attached_lists, $post_id, $meta_key );

			}
		}

	}

	function my_save_post( $post_id ) {
		// file_put_contents("pluginhooks.txt","inside my_save_post ".print_r($_POST,true));
		
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
		if($_POST['acf']['register'] == 'true'){ // means this form data is coming from /submit page form
	
			// if($_POST['acf'][field_5d47f0894d6f4][0] == 'Yes') {
				$current_user = wp_get_current_user();	
				$userid = $current_user->ID;		
				update_field("real_author",$userid,$post_id);
			// }
				
			// file_put_contents("mvcf.txt","inside send_real_user_db",FILE_APPEND);

			$user_id = $userid;
			// file_put_contents("mvcf.txt","user id :".$user_id,FILE_APPEND);
		
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
		  $videoArr  = array_unique($_POST['new_video_list']); 
	//		file_put_contents("mysavepost2.txt","videoArr is: ".print_r($_POST,true),FILE_APPEND);
			foreach($videoArr as $key=> $video){
				if(trim($video) == ''){
					unset($videoArr[$key]);
				}
			}
			update_post_meta($post_id, 'video_list', $videoArr);
			
		
	  }
	
	}

	function add_attached( $posts, $post_id, $metakey ) {
		foreach ( $posts as $postid ) {
			$existing_ids = get_post_meta( $postid, $metakey, true );
			if ( !in_array( $post_id, $existing_ids ) ) {
				// add it to main list attachment.
				$existing_ids[]=$post_id;
				update_post_meta( $postid, $metakey, $existing_ids );
				if($metakey =='list_items'){
                    update_list_modified($postid);
                }
			}
		}
	}

	function remove_attached( $previous_attached_lists, $post_id, $metakey ) {
		if ( $previous_attached_lists )
			foreach ( $previous_attached_lists as  $postid ) {
				$existing_list_items = get_post_meta( $postid, $metakey, true );
				if ( ( $key = array_search( $post_id, $existing_list_items ) ) !== false ) {
					unset( $existing_list_items[$key] );
					update_post_meta( $postid, $metakey, $existing_list_items );
				}
			}

	}

	function acf_load_promoted_item_choices( $field ){
			global $post;
			if(!empty($post)){
			$list_items = get_field( 'list_items',$post->ID , false );
			}
			if( !empty($list_items)){
				foreach ($list_items as $item_id) {
					$field['choices'][$item_id] = get_the_title($item_id);
				}
			}
		return $field;
	}

	function init() {

		// $existing_list_items = get_post_meta( 357, 'list_items', true );
		//    if ( ( $key = array_search( 375, $existing_list_items ) ) !== false ) {
		//     var_dump($existing_list_items);
		//     unset( $existing_list_items[$key] );
		//     var_dump($existing_list_items);
		//     update_post_meta( 357, 'list_items', $existing_ids );
		//    }


		// echo '<pre>';
		// $image = get_field( 'add_to_list', '375' );
		// update_post_meta( '375', 'add_to_list', array( '367' ) );
		// $image = get_post_meta( '367' );
		// print_r( $image );
	}

}

new Mv_Acf_Hooks();
