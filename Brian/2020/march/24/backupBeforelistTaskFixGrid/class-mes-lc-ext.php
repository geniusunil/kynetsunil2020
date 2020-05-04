<?php
if (!class_exists('Mes_Lc_Ext')){
	class Mes_Lc_Ext{
		const LIST_POST_TYPE = 'lists';
		const LIST_ITEM_POST_TYPE = 'list_items';

		const READ_VOTED_COOKIE_NAME = 'mes_lc_read_voted_';
		const ONE_DAY_IN_SECS =  86400;

		const LIST_ITEM_VOTES_KEY = 'votes_given';
		const READ_REVIEW_VOTE = 0.6;
		
		const VOTES_META_KEY = 'votes_given';
		const COUPONS_META_KEY = 'coupons_list';
		const DOWNLOADS_LOCATION_META_KEY = 'downloads_in_';
		const VOTE_AJAX_ACTION = 'mes-lc-li-vote';
		const RATE_FEATURE_AJAX_ACTION = 'mes-lc-feature-rate';
		const VOTE_COUPON_AJAX_ACTION = 'mes-lc-cp-vote';
		const VOTE_TYPE_DOWNLOAD = 'download';

		const MES_NEG_SCALE = -100;
		const MES_ONE_WEEK_IN_SECS = 604800;
		const MES_ROUND_PRE = 6;

		const LIST_ITEMS_FIELD = 'field_58f9f66153d75';
		const MEMBERSHIP_META_KEY = 'mes-lc-ms-time';

		const READ_VOTE_TYPE = 'read';
		const DOWNLOAD_VOTE_TYPE = 'download';
		const UP_VOTE_TYPE = 'up';
		const DOWN_VOTE_TYPE = 'down';
		const VOTE_TYPES = array(self::READ_VOTE_TYPE => 0.6, self::DOWNLOAD_VOTE_TYPE => 0.5, self::UP_VOTE_TYPE=> 1, self::DOWN_VOTE_TYPE=>-1);
		const UNDO_VOTE_TYPE = 'undo';
		const REDO_VOTE_TYPE = 'redo';
		const LIST_VOTES_COOKIE_NAME = 'mes-lc-list-votes_';
		const ITEM_FEATURE_RATING_COOKIE_NAME = 'mes-lc-feature-rate';

		const LIST_COUPON_VOTES_COOKIE_NAME = 'mes-lc-coupon-votes_';
		private static $totalDownloads;
		private static $location;
		function __construct(){
			add_action( 'wp', array($this,'on_list_item_view'), 10);
			add_action( 'wp_ajax_' . self::VOTE_AJAX_ACTION, array( $this, 'on_list_item_vote' ) );
			add_action( 'wp_ajax_nopriv_' . self::VOTE_AJAX_ACTION, array( $this, 'on_list_item_vote' ) );
			
			add_action( 'wp_ajax_' . self::RATE_FEATURE_AJAX_ACTION, array( $this, 'on_feature_rate' ) );
			add_action( 'wp_ajax_nopriv_' . self::RATE_FEATURE_AJAX_ACTION, array( $this, 'on_feature_rate' ) );
			add_action( 'wp_ajax_' . self::VOTE_COUPON_AJAX_ACTION, array( $this, 'on_coupon_vote' ) );
			add_action( 'wp_ajax_nopriv_' . self::VOTE_COUPON_AJAX_ACTION, array( $this, 'on_coupon_vote' ) );
			add_action( 'save_post_' . self::LIST_POST_TYPE, array($this,'save_membership_time'), 20, 3);
		}
		function save_membership_time( $post_id, $post, $update){
			if (isset($_POST['acf']) && 
				isset($_POST['acf'][self::LIST_ITEMS_FIELD])){
				$items = $_POST['acf'][self::LIST_ITEMS_FIELD];
				$memberships = get_post_meta($post_id, self::MEMBERSHIP_META_KEY, true);
				
				if (!is_array($items)) 
					$items = array();
				else 
					$items = array_values($items);
				
				if (!is_array($memberships)) 
					$memberships = array();

				foreach($memberships as $k=>$v){
					if (!in_array($k, $items))
						unset($memberships[$k]);
				}
				foreach($items as $v){
					if (!in_array($v, $memberships))
						$memberships[$v] = time();
				}
				update_post_meta($post_id, self::MEMBERSHIP_META_KEY, $memberships);
			}
		}
		function get_cookie_value($cookie_name){
			$cookie_value = isset($_COOKIE[$cookie_name])? $_COOKIE[$cookie_name]: false;
			file_put_contents("get_cook.txt","cookie vlue : ".print_r($_COOKIE[$cookie_name],true)." \n ",FILE_APPEND);

		    $cookie_value = $cookie_value? json_decode(stripslashes($cookie_value),true): false;
		    return $cookie_value;
		}


		function get_cookie_value_feature($cookie_name){
			$cookie_value = isset($_COOKIE[$cookie_name])? $_COOKIE[$cookie_name]: false;
			file_put_contents("get_feature.txt","cookie name : ".print_r($cookie_value,true)." \n ",FILE_APPEND);
			// file_put_contents("get_feature.txt","cookie vlue : ".print_r($cookie_value,true)." \n ",FILE_APPEND);
		    $cookie_value = $cookie_value? json_decode(stripslashes($cookie_value),true): false;
		    return $cookie_value;
		}
		function on_feature_rate(){
			file_put_contents("meslcext.txt","on feature rate",FILE_APPEND);
			$item_id = $_POST['post_id'];
			$feature_name = $_POST['feature_name'];
			$vote_size = $_POST['vote_size'];
			file_put_contents("meslcext.txt","item id : $item_id \n feature_name : $feature_name \n vote_size : $vote_size\n",FILE_APPEND);
			// if (array_key_exists($vote_type,self::VOTE_TYPES)){
				$cookie_name = self::ITEM_FEATURE_RATING_COOKIE_NAME;
				// file_put_contents("on_feature_ratename.txt","ITEM_FEATURE_RATING_COOKIE_NAME ".print_r($cookie_name,true),FILE_APPEND);
				$cookie_value = $this->get_cookie_value_feature($cookie_name);
				$cookie_update_needed = true;
				file_put_contents("meslcext.txt","cookie_value ".print_r($cookie_value,true),FILE_APPEND);

		
				$old_vote_Size = $cookie_value[$item_id][$feature_name];
				if(isset($old_vote_Size)){
					$old_vote_Size = -1 * abs($old_vote_Size);
				
					$cur_feature_score = $this->update_feature_rating($item_id, $feature_name,  $old_vote_Size);
					file_put_contents("meslcext.txt",": old_vote_Size : $vote_size\n",FILE_APPEND);

				}else{
					file_put_contents("meslcext.txt",": else condition : $vote_size\n",FILE_APPEND);
				}

				
				
				// if (isset($vote_value)){
					// update_list_modified($list_id);
					
					// unset($_COOKIE[$cookie_value]);

				$cur_feature_score = $this->update_feature_rating($item_id, $feature_name, $vote_size);
					// $this->register_votes_with_location($item_id,$list_id,$location,$vote_value);
					setcookie($cookie_name, 
		    			json_encode($this->set_voted_feature($cookie_value, $item_id, $vote_size,$feature_name)), 
		    			time() + self::ONE_DAY_IN_SECS, '/');
				     	$voted = 1;
				// }

			// }
			echo wp_json_encode(
				array('voted'=>isset($voted)?1:0, 'post_id'=>$item_id,  
					 'rating'=> $cur_feature_score['average'])
			);
			

			die;
		}
		function on_list_item_vote(){
			$item_id = $_POST['post_id'];
			$list_id = $_POST['post_parent_id'];
			$vote_type = $_POST['vote_type'];
			$location = $_POST['location'];
			file_put_contents("meslcext.txt","LOCATION FROM post".print_r($location,true),FILE_APPEND);

			if (array_key_exists($vote_type,self::VOTE_TYPES)){
				$cookie_name = self::LIST_VOTES_COOKIE_NAME . $list_id;
				$cookie_value = $this->get_cookie_value($cookie_name);
				$cookie_update_needed = true;
				if (self::DOWNLOAD_VOTE_TYPE == $vote_type){
					if(!$this->has_voted($cookie_value, $item_id, $vote_type)){
						$vote_value = self::VOTE_TYPES[$vote_type];
						
					}
				}	
				else if (self::UP_VOTE_TYPE == $vote_type || self::DOWN_VOTE_TYPE == $vote_type){
					if(!$this->has_voted($cookie_value, $item_id, self::UP_VOTE_TYPE) && 
					   !$this->has_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE)){
						$vote_value = self::VOTE_TYPES[$vote_type];
					}
					else if (self::UP_VOTE_TYPE == $vote_type){
						if ($this->has_voted($cookie_value, $item_id, self::UP_VOTE_TYPE)){
							$vote_value = self::VOTE_TYPES[$vote_type] * -1;
							$cookie_value = $this->remove_voted($cookie_value, $item_id, self::UP_VOTE_TYPE);
							$vote_type = false;
							$vote_type_action = self::UNDO_VOTE_TYPE;
						}
						else if ($this->has_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE)){
						  	$vote_value = self::VOTE_TYPES[$vote_type] +  (self::VOTE_TYPES[self::DOWN_VOTE_TYPE] * -1); 
							$cookie_value = $this->remove_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE);
							$vote_type_action = self::REDO_VOTE_TYPE;
						}	
					}
					else{
						if ($this->has_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE)){ 
							$vote_value = self::VOTE_TYPES[$vote_type] * -1;
							$cookie_value = $this->remove_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE);
							$vote_type = false;
							$vote_type_action = self::UNDO_VOTE_TYPE;
						}
						else if ($this->has_voted($cookie_value, $item_id, self::UP_VOTE_TYPE)){
						  	$vote_value = self::VOTE_TYPES[$vote_type] +  (self::VOTE_TYPES[self::UP_VOTE_TYPE] * -1); 
							$cookie_value = $this->remove_voted($cookie_value, $item_id, self::UP_VOTE_TYPE);
							$vote_type_action = self::REDO_VOTE_TYPE;
						}		
					}	  	   	
				}
                if (self::READ_VOTE_TYPE == $vote_type){
					file_put_contents("meslcext.txt","INside read vote type",FILE_APPEND);

					update_list_modified($list_id);
					$bool = "false";
					if($this->has_voted($cookie_value, $item_id, self::READ_VOTE_TYPE)){
						$bool = "true";
					}

					file_put_contents("meslcext.txt","bool is $bool",FILE_APPEND);


					if(!$this->has_voted($cookie_value, $item_id, self::READ_VOTE_TYPE)){
						file_put_contents("meslcext.txt","INside hasvoted read vote type",FILE_APPEND);

						$this->register_votes_with_location($item_id,$list_id,$location,self::READ_REVIEW_VOTE);
					}
                }
				if (isset($vote_value)){
                    update_list_modified($list_id);
					$votes_count = $this->update_vote($item_id, $list_id, $vote_value);
					$this->register_votes_with_location($item_id,$list_id,$location,$vote_value);
					setcookie( $cookie_name, 
		    			json_encode($this->set_voted($cookie_value, $item_id, $vote_type)), 
		    			time() + self::ONE_DAY_IN_SECS, '/');
					$voted = 1;
				}
				
			}
			echo wp_json_encode(
				array('voted'=>isset($voted)?1:0, 'post_id'=>$item_id, 'list_id'=>$list_id, 'vote_type'=>$vote_type, 
					'votes'=>isset($votes_count)?$votes_count:'na', 'vote_type_action'=>isset($vote_type_action)?$vote_type_action:false)
			);
			die;
		}

		function on_coupon_vote(){
			file_put_contents("meslcext.txt","INSIDE on_coupon_vote\n",FILE_APPEND);

			$coupon_id = $_POST['coup_id'];
			$post_id = $_POST['post_id'];
			// $item_id = $post_id."_".$coupon_id;
			$vote_type = $_POST['vote_type'];
			file_put_contents("meslcext.txt","coupon id $coupon_id\npost id $post_id\nitem_id $item_id\nvote type $vote_type",FILE_APPEND);

			if (array_key_exists($vote_type,self::VOTE_TYPES)){
				$cookie_name = self::LIST_COUPON_VOTES_COOKIE_NAME .$item_id;
				$cookie_value = $this->get_cookie_value($cookie_name);
				$cookie_update_needed = true;
				file_put_contents("meslcext.txt","inside arra exists\n",FILE_APPEND);

			
				if (self::UP_VOTE_TYPE == $vote_type || self::DOWN_VOTE_TYPE == $vote_type){
					if(!$this->has_voted($cookie_value, $item_id, self::UP_VOTE_TYPE) && 
					   !$this->has_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE)){
						$vote_value = self::VOTE_TYPES[$vote_type];
					}
					else if (self::UP_VOTE_TYPE == $vote_type){
						if ($this->has_voted($cookie_value, $item_id, self::UP_VOTE_TYPE)){
							$vote_value = self::VOTE_TYPES[$vote_type] * -1;
							$cookie_value = $this->remove_voted($cookie_value, $item_id, self::UP_VOTE_TYPE);
							$vote_type = false;
							$vote_type_action = self::UNDO_VOTE_TYPE;
						}
						else if ($this->has_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE)){
						  	$vote_value = self::VOTE_TYPES[$vote_type] +  (self::VOTE_TYPES[self::DOWN_VOTE_TYPE] * -1); 
							$cookie_value = $this->remove_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE);
							$vote_type_action = self::REDO_VOTE_TYPE;
						}	
					}
					else{
						if ($this->has_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE)){ 
							$vote_value = self::VOTE_TYPES[$vote_type] * -1;
							$cookie_value = $this->remove_voted($cookie_value, $item_id, self::DOWN_VOTE_TYPE);
							$vote_type = false;
							$vote_type_action = self::UNDO_VOTE_TYPE;
						}
						else if ($this->has_voted($cookie_value, $item_id, self::UP_VOTE_TYPE)){
						  	$vote_value = self::VOTE_TYPES[$vote_type] +  (self::VOTE_TYPES[self::UP_VOTE_TYPE] * -1); 
							$cookie_value = $this->remove_voted($cookie_value, $item_id, self::UP_VOTE_TYPE);
							$vote_type_action = self::REDO_VOTE_TYPE;
						}		
					}	  	   	
				}
				file_put_contents("meslcext.txt","before setting value\n",FILE_APPEND);

				if (isset($vote_value)){
                    update_list_modified($list_id);
					$votes_count = $this->update_cp_vote($coupon_id, $post_id, $vote_value);
				
					setcookie($cookie_name, 
		    			json_encode($this->set_voted($cookie_value, $item_id, $vote_type)), 
		    			time() + self::ONE_DAY_IN_SECS, '/');
					$voted = 1;
				}
				
			}
			echo wp_json_encode(
				array('voted'=>isset($voted)?1:0, 'coup_id'=>$coupon_id, 'list_id'=>$list_id, 'vote_type'=>$vote_type, 
					'votes'=>isset($votes_count)?$votes_count:'na', 'vote_type_action'=>isset($vote_type_action)?$vote_type_action:false)
			);
			die;
		}

		function register_votes_with_location($item_id,$list_id,$location,$vote_value){
			// file_put_contents("meslcext.txt","register download running",FILE_APPEND);
			// file_put_contents("meslcext.txt","ITEM id $item_id and location $location",FILE_APPEND);

			//for list
			$total_downloads_this_location = get_post_meta( $list_id, self::DOWNLOADS_LOCATION_META_KEY.$location, true );
			
			$total_downloads_this_location+=$vote_value;
			file_put_contents("meslcext.txt","meta key ".self::DOWNLOADS_LOCATION_META_KEY.$location." and total downloads this location $total_downloads_this_location",FILE_APPEND);

			update_post_meta( $list_id, self::DOWNLOADS_LOCATION_META_KEY.$location, $total_downloads_this_location );

			// for list_item

			$total_downloads_this_location = get_post_meta( $item_id, self::DOWNLOADS_LOCATION_META_KEY.$location, true );
			
			$total_downloads_this_location+=$vote_value;
			file_put_contents("meslcext.txt","meta key ".self::DOWNLOADS_LOCATION_META_KEY.$location." and total downloads this location $total_downloads_this_location",FILE_APPEND);

			update_post_meta( $item_id, self::DOWNLOADS_LOCATION_META_KEY.$location, $total_downloads_this_location );
			return $total_downloads_this_location;

		}
		
		function has_voted($data, $item_id, $vote_type){
		 	if ($data && is_array($data) && isset($data[$vote_type])){
				$arr = $data[$vote_type];
		 		if (is_array($arr) && in_array($item_id,$arr)) return true;
		 	}	
			return false;
		}

		function set_voted($data, $item_id, $vote_type){

			if (!is_array($data)) $data = array();	

			if (!isset($data[$vote_type])) $data[$vote_type] = array();
		
			if (!is_array($data[$vote_type])) $data[$vote_type] = array();

			if ($vote_type && array_key_exists($vote_type,self::VOTE_TYPES))
				$data[$vote_type][] = $item_id;
				file_put_contents("set_voteitem.txt","after code".print_r($data,true),FILE_APPEND);
				
			return $data;
		}
		function set_voted_feature($data, $item_id, $vote_size,$feature_name){			
			if (!is_array($data)) $data = array();	
			if (!isset($data[$item_id])) {
				$data[$item_id] = array();
			}
			if (!is_array($data[$item_id])){
				$data[$item_id] = array();
			} 
	
				 $data[$item_id][$feature_name] = $vote_size;
				 file_put_contents("set_vote.txt","array voted ".print_r($data,true),FILE_APPEND);
			return $data;
			
		}
	
		function remove_voted_feature($data, $item_id,$feature_value, $vote_size){
			
			if (isset($vote_size) && isset($data[$vote_size]) 
				&& is_array($data[$vote_size]) && in_array($item_id, $data[$vote_size])
				){
				$index = array_search($item_id, $data[$vote_size]);
				if (FALSE!==$index) array_splice($data[$vote_size], $index, 1);
			}
			return $data;	
			file_put_contents("set.txt","array voted ".print_r($data,true),FILE_APPEND);
			
		}

		function remove_voted($data, $item_id, $o_vote_type){
			if (isset($o_vote_type) && isset($data[$o_vote_type]) 
				&& is_array($data[$o_vote_type]) && in_array($item_id, $data[$o_vote_type])
				){
				$index = array_search($item_id, $data[$o_vote_type]);
				if (FALSE!==$index) array_splice($data[$o_vote_type], $index, 1);
			}
			return $data;	
		}

		function on_list_item_view(){
			file_put_contents("meslcext.txt","location is on list item view ".$_GET['lang'],FILE_APPEND);

			if (is_singular(self::LIST_ITEM_POST_TYPE)){
				if(isset($_GET['listid'])) { 
			    	$list_id = $_GET['listid'];
			    	$item_id = get_the_ID();
			    	$cookie_name = self::LIST_VOTES_COOKIE_NAME . $list_id;
					$cookie_value = $this->get_cookie_value($cookie_name);
			    	if(!$this->has_voted($cookie_value, $item_id, self::READ_VOTE_TYPE)){

						$this->update_vote($item_id, $list_id, self::READ_REVIEW_VOTE);
						$this->register_votes_with_location($item_id,$list_id,$_GET['lang'],self::READ_REVIEW_VOTE);

		    			setcookie($cookie_name, 
		    				json_encode($this->set_voted($cookie_value, $item_id, self::READ_VOTE_TYPE)), 
		    				time() + self::ONE_DAY_IN_SECS, '/');
			  		}
		  		}
		  	}		
		}
		function update_vote($post_id, $post_parent_id, $vote){
			$total_votes = get_post_meta( $post_id, self::VOTES_META_KEY, true );
			if (!is_array($total_votes)){
				$total_votes = array();
			}
			if (!isset( $total_votes[$post_parent_id])){
				$total_votes[$post_parent_id] = 0;
			}
			$total_votes[$post_parent_id] = round((float)$total_votes[$post_parent_id] + (float)$vote, 2);
			update_post_meta( $post_id, self::VOTES_META_KEY, $total_votes );
			return $total_votes[$post_parent_id];
		}
		
		function update_feature_rating($post_id, $feature_name, $vote){
			$featureListratings = get_post_meta($post_id, 'features_list_ratings', true);
			file_put_contents("meslcext.txt","cur_feature_score : ".print_r($featureListratings,true),FILE_APPEND);
			file_put_contents("meslcext.txt","vote type is : ".gettype($vote),FILE_APPEND);


	$increment = 1;
	if($vote <0){
		$increment = -1;
	}
		
			$featureListratings[$feature_name]['votes'] += $increment;
			
			$featureListratings[$feature_name]['total_score'] += $vote;
			$featureListratings[$feature_name]['average']=$featureListratings[$feature_name]['total_score']/$featureListratings[$feature_name]['votes'];
			file_put_contents("meslcext.txt","cur_feature_score : ".print_r($featureListratings,true),FILE_APPEND);

			update_post_meta( $post_id, 'features_list_ratings', $featureListratings );
			return $featureListratings[$feature_name];
		}
		function update_cp_vote($coupon_id, $post_id, $vote){
			file_put_contents("meslcext.txt","1 inside update cp vote\n",FILE_APPEND);

			$all_coupons = get_post_meta( $post_id, self::COUPONS_META_KEY, true );
			file_put_contents("meslcext.txt","3 inside update cp vote $vote\n",FILE_APPEND);
			file_put_contents("meslcext.txt","6 inside update cp vote\n".print_r($all_coupons,true),FILE_APPEND);

			foreach($all_coupons as $key=>$coupon){
				file_put_contents("meslcext.txt","5 inside update cp vote\n",FILE_APPEND);

				if($coupon[id]==$coupon_id){
					file_put_contents("meslcext.txt","votes are". $all_coupons[$key][votes].PHP_EOL,FILE_APPEND);

					$all_coupons[$key][votes]= $all_coupons[$key][votes]+$vote;
					break;
				}
			}
			file_put_contents("meslcext.txt","4 inside update cp vote\n",FILE_APPEND);

			/* if (!is_array($total_votes)){
				$total_votes = array();
			} */
			/* if (!isset( $total_votes[$post_parent_id])){
				$total_votes[$post_parent_id] = 0;
			}
			 */
			// $total_votes[$post_parent_id] = round((float)$total_votes[$post_parent_id] + (float)$vote, 2);
			update_post_meta( $post_id, self::COUPONS_META_KEY, $all_coupons );
			file_put_contents("meslcext.txt","2 inside update cp vote\n",FILE_APPEND);

			return 0;//$total_votes[$post_parent_id];
		}

		static function calculate_score($votes, $age){
			if($age>0){
			$a = round($votes / $age, self::MES_ROUND_PRE);
			}else{
			$a = $votes;	
			}
			$b = ($votes < 0? 1: 0)  * self::MES_NEG_SCALE * ($age - 1);
			return $a + $b;
		}
		static function sort_cmp($a, $b){
			// file_put_contents("meslcext.txt","\ntotal downloads : ".self::$totalDownloads,FILE_APPEND);
			$fixFor0 = 0.01;
			$downloads_a_percent = get_post_meta($a->ID,'downloads_in_'.self::$location,true)/self::$totalDownloads+$fixFor0;
			
			$downloads_b_percent = get_post_meta($b->ID,'downloads_in_'.self::$location,true)/self::$totalDownloads+$fixFor0;

			// file_put_contents("meslcext.txt","\n".$a->post_title. " percent: ".$downloads_a_percent,FILE_APPEND);
			// file_put_contents("meslcext.txt","\n".$b->post_title. " percent: ".$downloads_b_percent,FILE_APPEND);
			$score_a = self::calculate_score($a->votes, $a->age);
			$score_b = self::calculate_score($b->votes, $b->age);
			// file_put_contents("meslcext.txt","\n".$a->post_title. " score before percentage : ".$score_a,FILE_APPEND);
			// file_put_contents("meslcext.txt","\n".$b->post_title. " score before percentage : ".$score_b,FILE_APPEND);

			$score_a *= $downloads_a_percent;
			$score_b *= $downloads_b_percent;

			// file_put_contents("meslcext.txt","\n".$a->post_title. " final score: ".$score_a,FILE_APPEND);
			// file_put_contents("meslcext.txt","\n".$b->post_title. " final score: ".$score_b,FILE_APPEND);

			

			if ($score_a < $score_b) return 1;
			else if ($score_a > $score_b) return -1;
			else return $a->ID - $b->ID;
		}
		static function sort($post_id, &$items,$location){
			// $location = $_GET['lang'];
			// file_put_contents("meslcext.txt","location : ".$location,FILE_APPEND);

			$memberships = get_post_meta($post_id, self::MEMBERSHIP_META_KEY, true);
			if (!is_array($memberships)) $memberships = array();
			self::$totalDownloads = 0;
			foreach($items as $k => $v){
				self::$totalDownloads += get_post_meta($v->ID,'downloads_in_'.$location,true); 
				self::$location = $location;
				$id = $v->ID;
				if (isset($memberships[$id]))
					$items[$k]->age = ceil((time() - $memberships[$id]) / self::MES_ONE_WEEK_IN_SECS);
				else
					$items[$k]->age = ceil((time() - get_post_time('U', true, $id)) / self::MES_ONE_WEEK_IN_SECS);
			}
			usort($items, array('Mes_Lc_Ext', 'sort_cmp')) ;
		}
		static function sort_free($a, $b){
			$item_b = $b->ID;
			$item_a = $a->ID;
			$pricemodel_a = get_field( 'pricing_model', $item_a );
			$pricemodel_b = get_field( 'pricing_model', $item_b );
			if($pricemodel_a == $pricemodel_b) {
				return 0;
			}
			if($pricemodel_b == "freemium") {
				return 1;
			}
			
		}
		static function sortfree($post_id, &$items){
			usort($items, array('Mes_Lc_Ext', 'sort_free'));
		}
	}
	$mes_lc_ext = new Mes_Lc_Ext();
}