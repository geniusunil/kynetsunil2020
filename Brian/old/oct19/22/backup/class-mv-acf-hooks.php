<?php

class Mv_Acf_Hooks {

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		//before save
		add_action( 'acf/save_post', array( $this, 'save_existing_attachment_data' ), 2 );
		//after save
		add_action( 'acf/save_post', array( $this, 'save_add_remove_list_attachment' ), 20 );

		// populate promoted items chekboxes in backend.
		add_filter('acf/load_field/name=promoted_list_items',array( $this, 'acf_load_promoted_item_choices'));
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
