<?php
class Mv_Acf_Hooks 
{

	function __construct() 
	{
		add_action('post_edit_form_tag', 'post_edit_form_tag');
		add_action( 'init', array( $this, 'init' ) );
		//before save
		add_action( 'acf/save_post', array( $this, 'save_existing_attachment_data' ), 2 );
		//after save
		add_action( 'acf/save_post', array( $this, 'save_add_remove_list_attachment' ), 20 );
		add_action('acf/save_post',  array( $this, 'my_save_post'),20);
		add_filter('acf/fields/relationship/query/name=add_to_list', array( $this, 'my_relationship_query'), 10, 3);
		add_filter( 'acf/get_valid_field', array( $this,'change_input_labels'), 10, 3);
		// populate promoted items chekboxes in backend.
		add_filter('acf/load_field/name=promoted_list_items',array( $this, 'acf_load_promoted_item_choices'));
	}
	//ACf fields labels 
	function post_edit_form_tag() 
	{
		echo ' enctype="multipart/form-data"';
	}

	function change_input_labels($field) 
	{
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

	function save_existing_attachment_data( $post_id ) 
	{

		if ( isset( $_POST['acf']['field_59352650bde8a'] ) ) 
		{
			$posts = get_field( 'add_to_list', $post_id, false );
			update_post_meta( $post_id, 'previous_attached_lists', $posts );
		}

		if ( isset( $_POST['acf']['field_5d20360c9f178'] ) ) 
		{
			$posts = get_field( 'list_items', $post_id, false );
			update_post_meta( $post_id, 'previous_attached_lists', $posts );
		}
	}

	
	function my_relationship_query( $args, $field, $post_id ) 
	{
		file_put_contents("functions.txt",print_r($args,true));
		file_put_contents("pluginhooks.txt",print_r($args,true),FILE_APPEND);
		$value = get_field( "cat", $post_id );
		if(!empty($value) )
		{
			$args['post_type']='lists';
			$args['tax_query'][0]['taxonomy'] = 'list_categories';
			$args['tax_query'][0]['terms'] = $value;
			return $args;
		}
		else
		{
			return $args;
		}
	}

	function save_add_remove_list_attachment( $post_id ) 
	{
		$posts= 'false';
		if ( isset( $_POST['acf']['field_59352650bde8a'] ) ) {
			$posts = $_POST['acf']['field_59352650bde8a'];
			$meta_key = 'list_items';
		} 
		elseif ( isset( $_POST['acf']['field_5d20360c9f178'] ) ) {
			$posts = $_POST['acf']['field_5d20360c9f178'];
			$meta_key = 'add_to_list';
		}
		file_put_contents("pluginhooks.txt","posts\n".print_r($posts,true),FILE_APPEND);
		file_put_contents("pluginhooks.txt","posts value\n".$posts,FILE_APPEND);
		file_put_contents("pluginhooks.txt","posts boolean\n". ($posts != false) ,FILE_APPEND);

		if ( $posts != 'false') 
		{
			file_put_contents("pluginhooks.txt","crossed if posts\n",FILE_APPEND);

			if ( !empty( $posts ) ) 
			{
				file_put_contents("pluginhooks.txt","posts not empty\n",FILE_APPEND);
				$this->add_attached( $posts, $post_id, $meta_key );
				$previous_attached_lists = get_post_meta( $post_id, 'previous_attached_lists', true );
				if ( $previous_attached_lists ) 
				{
					$removed_lists = array_diff( $previous_attached_lists, $posts );
					file_put_contents("pluginhooks.txt","array diff\n".print_r($removed_lists,true).PHP_EOL,FILE_APPEND);
					if(!empty($removed_lists)){
						$this->remove_attached( $removed_lists, $post_id, $meta_key );
					}
				}
			}
			else {
				file_put_contents("pluginhooks.txt","posts is empty\n",FILE_APPEND);
				$previous_attached_lists = get_post_meta( $post_id, 'previous_attached_lists', true );
				$this->remove_attached( $previous_attached_lists, $post_id, $meta_key );
			}
		}
	}

	function kv_handle_attachment($file_handler,$post_id) 
	{ 
		//http://www.kvcodes.com/2013/12/create-front-end-multiple-file-upload-wordpress/
		// check to make sure its a successful upload
		if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
	
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	
		$attach_id = media_handle_upload( $file_handler, $post_id );
		// If you want to set a featured image frmo your uploads. 
		// if ($set_thu) set_post_thumbnail($post_id, $attach_id);
		return $attach_id;
	}

	function change_dir( $dir ) 
	{
		$update_post_id = $_SESSION['update_post_id'];
		return array(
			'path'   => $dir['basedir'] . '/listcreator/attachments//'.$update_post_id,
			'url'    => $dir['baseurl'] . '/listcreator/attachments//'.$update_post_id,
			'subdir' => '/listcreator/attachments//'.$update_post_id,
		) + $dir;
	}

	function my_save_post( $post_id ) 
	{
		file_put_contents("pluginhooks.txt","inside my_save_post ".print_r($_POST,true),FILE_APPEND);
		file_put_contents("pluginhooks.txt","files inside my_save_post ".print_r($_FILES,true),FILE_APPEND);
		$_SESSION['update_post_id']=$post_id;
		if ( $_FILES ) 
		{ 
			$files = $_FILES["kv_multiple_attachments"];  
			foreach ($files['name'] as $key => $value) 
			{ 			
				if ($files['name'][$key]) 
				{ 
					$file = array( 
						'name' => $files['name'][$key],
						'type' => $files['type'][$key], 
						'tmp_name' => $files['tmp_name'][$key], 
						'error' => $files['error'][$key],
						'size' => $files['size'][$key]
					); 
					$_FILES = array ("kv_multiple_attachments" => $file); 
					add_filter( 'upload_dir', array( $this, 'change_dir' ) );
					foreach ($_FILES as $file => $array) 
					{				
						$newupload = $this->kv_handle_attachment($file,$pid); 
					}
					remove_filter( 'upload_dir', array( $this, 'change_dir' ) );
				}
			} 
		}
	
		/*if($_FILES['file']['name'] != ''){
		$uploadedfile = $_FILES['file'];
		$upload_overrides = array( 'test_form' => false );
		// Register our path override.
		add_filter( 'upload_dir', array( $this, 'change_dir' ) );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		echo "movefile";
		print_r($movefile);
		file_put_contents("pluginhooks.txt","movefile ".print_r($movefile,true).PHP_EOL,FILE_APPEND);

		// Set everything back to normal.
		remove_filter( 'upload_dir', array( $this, 'change_dir' ) );
		$imageurl = "";
		if ( $movefile && ! isset( $movefile['error'] ) ) {
			$imageurl = $movefile['url'];
			echo "url : ".$imageurl;
		} else {
			echo $movefile['error'];
		}
		} */
		if(!has_post_thumbnail($post_id))
		{
			$url = get_field("affiliate_url", $post_id);
			//	file_put_contents("imagurl.txt", $url,FILE_APPEND);
			$width = 300;
			$height = 125;
			$title = "lkdjfklgdskgl";
			$image_set =  BrowserShots::get_shot( $url, 600, 450 );
			$image_url = $image_set;
			$image_tmp = download_url($image_url);
			
			if( is_wp_error( $image_tmp ) )
			{
				echo "<br> Image Download Fail:";
			}
			else 
			{
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
			
			//	file_put_contents("imageset.txt",$image_set);	
			//	file_put_contents("final_iamge.txt",$final);
		}
		if($_POST['acf']['register'] == 'true')
		{
			// means this form data is coming from /submit page form
			$current_user = wp_get_current_user();	
			$userid = $current_user->ID;		
			update_field("real_author",$userid,$post_id);
				
			//file_put_contents("mvcf.txt","inside send_real_user_db",FILE_APPEND);

			$user_id = $userid;
			//file_put_contents("mvcf.txt","user id :".$user_id,FILE_APPEND);
			if(trim($user_id) != '')
			{
				global $wpdb;
				$table_name = $wpdb->prefix . "user_wise_claim_list";
				file_put_contents("mvcf.txt","database name".$wpdb->dbname,FILE_APPEND);
				if($wpdb->get_var("SHOW TABLES LIKE $table_name") != $table_name) 
				{
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
				if ( null === $db_data ) 
				{
					$wpdb->insert($table_name,array('user_id' => $user_id, 'software_ids' => $post_id,), array('%d','%d', ));
				}
				else
				{
					$wpdb->update($table_name, array('user_id' => $user_id,	'software_ids' => $post_id), array( 'software_ids' => $post_id ), array('%d','%d'), array( '%d' ));
				}
			}

			//postcategory
			$post_categories = $_POST['acf'][field_5d47f0894d682];
			$cats = array_values($post_categories);
			wp_set_post_terms( $post_id, $cats, 'list_categories', false );
			//end of postcategory
			
			$update = "";
			$data =	get_post( $post_id );
			re_save_item_feature_meta_boxes($post_id, $data, $update);

			/* There is no re_save function for videos yet*/
			$abc = get_field("new_video_list[]", $post_id);
			//file_put_contents("mysavepost212.txt","videoArr is: ".print_r($abc,true),FILE_APPEND);
			//$newArr  = $_POST['new_features_list']; 
			//file_put_contents("mysavepost2.txt","newArr is: ".print_r($newArr,true),FILE_APPEND);
			
			foreach($newArr as $key=>$list_new_item)
			{
				$newArr[$key]= trim($list_new_item);
			
			}		
			$videoArr  = array_unique($_POST['new_video_list']); 
			file_put_contents("mysavepost2.txt","videoArr is before: ".print_r($videoArr,true),FILE_APPEND);
			foreach($videoArr as $key=> $video)
			{
				if(trim($video) == ''){
					unset($videoArr[$key]);
				}
			}
			file_put_contents("mysavepost2.txt","$post_id is post id, videoArr is after: ".print_r($videoArr,true),FILE_APPEND);
			update_post_meta($post_id, 'video_list', $videoArr);


			//coupons 
			$update = "";
			$data =	get_post( $post_id );
		    file_put_contents("hooks_file.txt","wp_object ".print_r($data,true),FILE_APPEND);
			re_save_coupon_meta_boxes($post_id,$data,$update);
			// end of coupons

			//qa 
			$update_qa = "";
			$data_qa =	get_post( $post_id );
			re_save_qa_meta_boxes($post_id,$data_qa,$update_qa);
			// end of qa
		}
	}

	function add_attached( $posts, $post_id, $metakey ) 
	{
		foreach ( $posts as $postid ) 
		{
			$existing_ids = get_post_meta( $postid, $metakey, true );
			if ( !in_array( $post_id, $existing_ids ) ) 
			{
				// add it to main list attachment.
				$existing_ids[]=$post_id;
				update_post_meta( $postid, $metakey, $existing_ids );
				if($metakey =='list_items'){
                    update_list_modified($postid);
                }
			}
		}
	}

	function remove_attached( $previous_attached_lists, $post_id, $metakey ) 
	{
		if ( $previous_attached_lists )
		foreach ( $previous_attached_lists as  $postid ) 
		{
			$existing_list_items = get_post_meta( $postid, $metakey, true );
			if ( ( $key = array_search( $post_id, $existing_list_items ) ) !== false ) {
				file_put_contents("pluginhooks.txt",print_r($existing_list_items,true).PHP_EOL."key ".$key.PHP_EOL,FILE_APPEND);
				print_r($existing_list_items);
				echo "key $key";
				unset( $existing_list_items[$key] );
				update_post_meta( $postid, $metakey, $existing_list_items );
			}
		}

	}

	function acf_load_promoted_item_choices( $field )
	{
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

	function init() 
	{
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