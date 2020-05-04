<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entry_model extends CI_Model {
	/**
	 * Entry_model class.
	 *
	 * @extends CI_Model
	 */

    function get_all_entries($start, $limit){
    	/**
		 * load all entries
		 *
	    */
	    $this->db->limit($limit, $start);
        $this->db->where('entry_list.entry_action', '')->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
        									THEN group_concat(DISTINCT headings.button_text ORDER BY find_in_set( headings.heading_id, entry_list.heading_assignment ) SEPARATOR ', ')
        									ELSE heading_assignment
        								END AS button_text, action_record.date");
        $this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
        $this->db->join('action_record', 'action_record.entry_id = entry_list.entry_id');
        $this->db->group_by('entry_list.entry_id');
        $this->db->order_by('action_record.date','DESC');
        //entry_list.entry_id

        $query 		= $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->result_array();
		    return $result;

        }else{
            return false;
        }
    }

	function get_all_entries12(){
    	/**
		 * load all entries
		 *
	    */
	   // $this->db->limit($limit, $start);
        $this->db->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
        									THEN group_concat(DISTINCT headings.button_text ORDER BY find_in_set( headings.heading_id, entry_list.heading_assignment ) SEPARATOR ', ')
        									ELSE heading_assignment
        								END AS button_text, action_record.date");
        $this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
        $this->db->join('action_record', 'action_record.entry_id = entry_list.entry_id');

        $this->db->group_by('entry_list.entry_id');
        $this->db->order_by('action_record.date','DESC');
        //entry_list.entry_id

        $query 		= $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->result_array();
		    return $result;

        }else{
            return false;
        }
    }

	public function count_entries(){
		/**
		* Count all entries
		*/
		//$query = $this->db->where('entry_action', NULL);
		$query = $this->db->where('entry_action', "")->get('entry_list');
		return $query->num_rows();
	}
    public function get_ent_of_staff($userIds){
	$this->db->select('entry_id')->from('entry_list');
	$query 	= $this->db->or_where("FIND_IN_SET('".$userIds."',staff_assignment)!=", 0)->get();
	$entries 	= $query->result_array();
	return $entries;
	}
	public function get_entries_by_id($userIds){
		/**
		 * load entries
		 * by user id
		 */
		$i 			= 0;
		$entries 	= array();

		foreach($userIds as $userId){
			$this->db->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
												THEN group_concat(headings.button_text ORDER BY find_in_set( headings.heading_id, entry_list.heading_assignment ) SEPARATOR ', ')
					ELSE heading_assignment
											END AS button_text");
			$this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
			 $this->db->join('action_record', 'action_record.entry_id = entry_list.entry_id');
			$query 			= $this->db->or_where(array("staff_assignment", $userId, "user_assignment" => $userId))->group_by('entry_list.entry_id')->get();
			$entries[$i] 	= $query->result_array();
			$i++;
		}
		if(count($entries) != 0){
			return $entries;
		}else{
			return false;
		}
	}


	public function get_entries_by_date($startDate){
		/**
		 * load entries
		 * filtered by date
		 */

		/*$get_entry_ids 	= $this->db->query("SELECT DISTINCT(entry_id) AS entry_id FROM action_record WHERE date = '".$startDate."'");*/
		$get_entry_ids 	= $this->db->select('entry_id')->from('action_record')->like(array('date'=> $startDate))->get();
		$entries 		= array(); $i = 0;
		$entry_ids 		= $get_entry_ids->result_array();
		$entry_ids 		= array_unique($entry_ids, SORT_REGULAR);

		foreach($entry_ids as $entry_id){
			$this->db->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
												THEN group_concat(headings.button_text ORDER BY find_in_set( headings.heading_id, entry_list.heading_assignment ) SEPARATOR ', ')
												ELSE heading_assignment
											END AS button_text");
			$this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
			$get_entry_ids 		= $this->db->where("entry_id", $entry_id['entry_id'])->group_by("entry_list.entry_id")->get();
			foreach($get_entry_ids->result_array() as $entry){
				$entries[$i] 	= $entry;
			}
			$i++;
		}
		if(count($entries) != 0)
			return $entries;
		else
			return false;
	}


	function get_today_entries(){
		/**
		* get all the entries made today or scheduled for today
		*/
		$current_date 	= date('Y-m-d');

		$get_entry_ids 	= $this->db->select('entry_id')->from('action_record')->like(array('action_schedule'=> $current_date))->get();
		$entries 		= array(); $i = 0;
		$entry_ids 		= $get_entry_ids->result_array();
		$entry_ids 		= array_unique($entry_ids, SORT_REGULAR);

		foreach($entry_ids as $entry_id){
			$this->db->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
												THEN group_concat(headings.button_text ORDER BY find_in_set( headings.heading_id, entry_list.heading_assignment ) SEPARATOR ', ')
												ELSE heading_assignment
											END AS button_text ");
			$this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
			$query 		= $this->db->where("entry_id", $entry_id['entry_id'])->group_by("entry_list.entry_id")->get();
			foreach($query->result_array() as $entry){
				$entries[$i] = $entry;
			}
			$i++;
		}
		if(count($entries) != 0)
			return $entries;
		else
			return false;
	}


	function get_this_week_entries(){
		/**
		* get the entries made in this week and are scheduled for this week
		*/
		$start_weekdate = date('Y-m-d h:i:s', strtotime("monday this week"));
		$end_weekdate   = date('Y-m-d h:i:s', strtotime("sunday this week"));

		$get_entry_ids 	= $this->db->select('entry_id')->from('action_record')->where("action_schedule BETWEEN '".$start_weekdate."' AND '".$end_weekdate."'")->get();
		$entries 		= array(); $i = 0;
		$entry_ids 		= $get_entry_ids->result_array();
		$entry_ids 		= array_unique($entry_ids, SORT_REGULAR);

		foreach($entry_ids as $entry_id){
			$this->db->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
												THEN group_concat(headings.button_text ORDER BY find_in_set( headings.heading_id, entry_list.heading_assignment ) SEPARATOR ', ')
												ELSE heading_assignment
											END AS button_text ");
			$this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
			$query = $this->db->where("entry_id", $entry_id['entry_id'])->group_by("entry_list.entry_id")->get();

			foreach($query->result_array() as $entry){
				$entries[$i] = $entry;
			}
			$i++;
		}
		if(count($entries) > 0)
			return $entries;
		else
			return false;
	}


	function in_array_r($array, $key, $val) {
		/**
		 * check in array
		 */
	    foreach ($array as $item){

	        foreach($item as $index=>$value){

	        	if ($index == $key && $value == $val)
	            	return true;
	       }
	    }
	    return false;
	}


	public function get_assigned_headings(){
		/**
		 * get assigned headings
		 */
		$query 			= $this->db->select('*')->from('headings')->like('heading_script', '"own"')->get();

		if($query->num_rows() > 0){
			$result 	= $query->result_array();
			$headings 	= array();

			foreach($result as $index => $val){
				$check_in_array = $this->in_array_r($headings, 'heading_id', $val['heading_id']);

				if($check_in_array == true){
					unset($result[$index]);
					continue;
				} else{
					$headings[] = $val;
				}
			}
			return $result;
		} else{
			return false;
		}
	}


	function action_types(){
		/**
		 * load all actions
		 *
	    */
        $query 		= $this->db->get('action_types');

        if($query->num_rows() > 0){
            $result = $query->result_array();
            return $result;
        }else{
            return false;
        }
    }


	public function entry_details($id){
		/**
		 *fetch a specific entry
		 *using entry_id
	    */
		$this->db->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
																				 THEN group_concat(headings.button_text SEPARATOR ', ')
																				 ELSE heading_assignment
																			 END AS button_text ");
		$this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
		$this->db->where("entry_id", $id)->group_by('entry_list.entry_id')->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
			return $query->result_array();
		else
			return false;
    }


	public function update_entry_status($id) {
		/**
		* get the status of entry
		*/
		$this->db->select('action_status')->from('action_record')->where('entry_id', $id);
		$query 		= $this->db->get();

		if($query->num_rows() > 0){
			$flag 	= true;

			foreach($query->result_array() as $res){
					if($res['action_status'] === 'Scheduled')
							$flag = false;
			}
			$headings 	= $this->db->select('heading_assignment')->from('entry_list')->where('entry_id', $id)->get();
			$h_ids 		= ','.$headings->result_array()[0]['heading_assignment'].',';

			if( ($flag === false) && (strpos($h_ids, ',7,') != -1) ){
				$h_ids = str_replace(',7,', ',4,', $h_ids);
				$h_ids = substr($h_ids, 1, -1);

				if( $this->db->where('entry_id', $id)->update('entry_list',array('heading_assignment'=>$h_ids)) )
					return 'Scheduled';

			} elseif( ($flag === true) && (strpos($h_ids, ',4,') != -1) ) {
				$h_ids = str_replace(',4,', ',7,', $h_ids);
				$h_ids = substr($h_ids, 1, -1);

				if( $this->db->where('entry_id', $id)->update('entry_list',array('heading_assignment'=>$h_ids)) )
					return 'Completed';
			}
		} else{
			return false;
		}
	}


	public function entry_types(){
		/**
		 *get all entry Ttypes
		 *
		 */
		$query 		= $this->db->get('entry_type');

		if($query->num_rows() > 0){
			$result = $query->result_array();
			return $result;

		}else{
			return false;
        }
	}


	public function save_entry($data){
		/**
		* save entry data
		*/
		if($this->db->insert('entry_list', $data)){
			return $this->db->insert_id();

		} else{
			return false;
		}
	}

	public function update_entry_quote_content($id, $quotes){
		$get_earlier_contents = $this->db->select('action_content')->from('action_record')->where('entry_id', $id)->get();

		if($get_earlier_contents->num_rows() > 0){
			$old_content 		= $get_earlier_contents->row()->action_content;
			$existing_quotes 	= ((strpos($old_content, 'quotes:') !== false)?str_replace('quotes:','', $old_content):$old_content);
		}
		$action_content 		= 'quotes: '.((isset($existing_quotes))?$existing_quotes.','.$quotes:$quotes);

		$this->db->query('UPDATE action_record SET action_content = "'.$action_content.'" WHERE entry_id = '.$id.' AND action_content LIKE "%quotes:%"');

		if($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}

	public function update_inventory_and_quotes($inv_id, $ent_id, $inv_title, $quotes, $currency, $url, $sup_assign, /*$product_spaci, $product_sku, $product_quantity,*/ $discount){
		/*
		** update the inventory title and quotes
		*/
		$this->db->where('inventory_id',$inv_id);
		$this->db->update('inventory',array('product_title'=> $inv_title, 'product_url'=> $url, 'supplier_id '=> $sup_assign, /*'product_code'=> $product_sku, 'stock_volume'=>$product_quantity,*/ 'product_disconunt'=>$discount /*'product_spacification'=>$product_spaci*/));

		if($this->db->affected_rows() < 0){
			$flag = false;
		}
		else{
			$flag = true;
		}
		$this->db->flush_cache();
$cont =0;
		foreach($quotes as $quote){
			$this->db->where('quote_id', $quote['qid']);
			$price 		= $quote['price'].'['.$currency[$cont].']';
			$this->db->update('quote_record', array('price' => $price));

			if($this->db->affected_rows() < 0){
				$flag = false;
			}
		}
		return $flag;
	}

	public function getQuoteDetails($quote_ids){
		/**
		 * get the quote details
		 */
		$query = $this->db->select('quote_id, inventory_id, price, price_type')->from('quote_record')->where_in('quote_id', $quote_ids)->get();

		if($query->num_rows() > 0){
			return $query->result_array();
		} else{
			return false;
		}
	}

	public function delete_product_from_entrylist($act_id, $ret_qid, $who_qid, $cos_qid, $del_from){
		// delete from `quote_record` table
		$quotes = array($who_qid, $ret_qid, $cos_qid);
		$this->db->where_in('quote_id', $quotes);
		$this->db->delete('quote_record');

		if($this->db->affected_rows() > 0){
			$statusFlag = true;
			$this->db->flush_cache();
			/**
			 * get the action type of the action_id
			 */
			$get_action_type = $this->db->select('action_type')->from('action_record')->where('action_id', $act_id)->get();
			$action_type = $get_action_type->row()->action_type;
			$this->db->flush_cache();

			if($action_type == 20){
				/**
				 * administration action; delete the action
				 */
				$this->db->where('action_id', $act_id);
				$this->db->delete('action_record');
				$statusFlag = (($this->db->affected_rows() > 0)?true:false);

			} else{
				/**
				 * other action so, delete the action_content > quotes: content
				 */
				$this->db->where('action_id', $act_id);
				$get_act_content 	= $this->db->select('action_content')->from('action_record')->where('action_id', $act_id)->get();
				$act_content 		= $get_act_content->row()->action_content;
				$remove_content 	= 'quotes:'.$ret_qid .','. $who_qid .','. $cos_qid;
				$new_content 		= str_replace($remove_content, '', trim($act_content, ' '));
				$this->db->flush_cache();
				$this->db->where('action_id', $act_id);
				$this->db->update('action_record', array('action_content' => $new_content));
				$statusFlag = (($this->db->affected_rows() > 0)?true:false);
			}

		} else{
			$statusFlag = false;

		}
		return $statusFlag;
	}

	public function search($value, $type){
		/**
		 * search user information
		 * get the userId
		 */
		$query = $this->db->query("SELECT * FROM user_information WHERE information_type ='".$type."' AND information_text ='".$value."'");

		if($query->num_rows() != 0){
			return $query->result_array();

		}else{
			return false;
		}
	}
///////////////////////////new///////////////

public function search_user_entries($value, $type){
		/**
		 * search user information
		 * get the userId
		 */
		$query = $this->db->query("SELECT * FROM user_information WHERE information_type ='".$type."' AND information_text ='".$value."'");

		if($query->num_rows() != 0){
			//return $query->result_array();
			$data = $query->row_array();

 $value = $data['user_id'];

 return $value;

		}else{
			return false;
		}
	}


	public function unset_session_var($var){
		/**
		 * unset session variable
		 */
		if($this->session->flashdata($var)){
			unset($_SESSION[$var]);
		}
	}


	public function search_username($key){
		/**
		* search username in user_login table
		*/
		$this->db->select('user_login.user_id')->from('user_information')->join('user_login', 'user_information.user_id = user_login.user_id');

	$this->db->where("user_login.status = '' AND (information_text LIKE '%$key%' OR user_login.user_name LIKE '%$key%')");

		 $searchUserId =  $this->db->get();

		$searchedIds = array();

		foreach($searchUserId->result_array() as $id){
				$searchedIds[] = $id['user_id'];
		}
		$searchedIds 	= array_unique($searchedIds);
		$searchedIds 	= implode(',',$searchedIds);
		$getUserDetails = $this->db->select("user_login.user_id, user_login.user_type, information_type, information_text")->from('user_information')->join('user_login', 'user_information.user_id = user_login.user_id')->where("FIND_IN_SET(user_login.user_id, '$searchedIds')  ")->get();

		if($getUserDetails->num_rows() > 0)
			return $getUserDetails->result_array();
		else
			return false;
	}


	////////////////////////////////////////////////////
	//////////functions for entrylist view//////////////
	////////////////////////////////////////////////////


	public function display_email_contents($filees, $email_name, $entry_id, $action_id){
		/**
		* display email text and attachments
		*/
		$action_direction = $this->db->select('action_direction')->from('action_record')->where('action_id', $action_id)->get();
			 $action_direct = $action_direction->row()->action_direction;

		if(file_exists($filees.'/'.$email_name)){

			$email_contents = fetch_email_contents($email_name); // actioninfo helper
			if($action_direct !=="Incoming"){
			echo "<li>Destination address: ".str_replace('To:', '', $email_contents['to_email'])."</li>";
			}
			else{
				echo "<li>".str_replace('To:', '', $email_contents['to_email'])."</li>";
			}
			echo "<li>Subject: <a target='_blank' href='".base_url('Email/thread/'.$entry_id.'/'.$action_id)."'>". strip_tags(str_replace('Subject: ', '', $email_contents['subject'])) . '  <i class="fa fa-external-link" aria-hidden="true"></i>
</a></li>'; // email text //
if($action_direct =="Incoming"){
	echo '<li>Attachments:  N/A';
	echo "</li>";
}else{
			echo '<li>Attachments: '; // email attachments //

			$attachments = explode(',', $email_contents['email_attachments']);
			$attachments = array_filter($attachments, function($val){ return (($val !== '')&&($val !== "\n")&&($val !== null)); });
			$empty 			= true;
			$totalAttach 	= count($attachments);

			for($i = 0; $i < $totalAttach; $i++){

				if(($attachments[$i] != null) && ($attachments[$i] != "") && ($attachments[$i] != "\n")){
					$attachment 		= new SimpleXMLElement($attachments[$i]);
					$att_name 			= $attachment[0];
					$att_url			= $attachment['data-url'];
					echo '<span class="attachment-name text-primary" data-url="'.$att_url.'" data-toggle="modal" data-target="#attachment_modal">'.$att_name.'</span>';
					//echo '<span class="attachment-download" data-value="'.$attachment.'"><i class="fa fa-download" aria-hidden="true"></i></span>';
					echo (($i < ($totalAttach-1))?',&nbsp;':'');
					$att_array 	= array();
					$empty 		= false;
				}
			}
			if($empty === true){
				echo 'n/a';
			}
			$attachments = array();

}
			echo "</li>";
			echo "<li>Notes: ".strip_tags($email_contents['notes'])."</li>";
		}
	}

		public function update_staff_assignment($entry, $data){
			$this->db->where('entry_id', $entry);
 		    $this->db->update('entry_list', $data);

		}
		public function update_customer_assignment($entry, $data){
			$this->db->where('entry_id', $entry,'action_type');
 		   $query=  $this->db->update('entry_list', $data);
		   if($query){
 		  return true;
		   }else{
			   return false;
		   }
		}
		public function update_heading_assignment($entry, $data){
			$this->db->where('entry_id', $entry);
 		    $this->db->update('entry_list', $data);

		}
		public function update_entry_notes($entry, $data){
			$array = array('entry_id' => $entry, 'action_type' => '23');
            $this->db->where($array);
			//$this->db->where('entry_id', $entry);
 		    $this->db->update('action_record', $data);

		}
		public function get_heading_data($entry)
		{
			 $query = 'select heading_assignment from entry_list where entry_id = '.$this->db->escape($entry);
   			$this->db->query($query);
		}
		function save_action($action_data){

			if($this->db->insert('action_record', $action_data)){
				return $this->db->insert_id();
			}
			else{
				return false;
			}
		}


public function get_entry_by_userId($userIds){
		/**
		 * load entries
		 * by user id
		 */
		//$i 			= 0;
		$entries 	= array();


			$this->db->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
												THEN group_concat(headings.button_text ORDER BY find_in_set( headings.heading_id, entry_list.heading_assignment ) SEPARATOR ', ')
												ELSE heading_assignment
											END AS button_text ");
			$this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
			$query 			= $this->db->or_where("FIND_IN_SET('".$userIds."',staff_assignment) or FIND_IN_SET('".$userIds."',user_assignment) !=", 0)->group_by('entry_list.entry_id')->get();
			$entries 	= $query->result_array();

		if(count($entries) != 0){
			return $entries;
		}else{
			return false;
		}
	}
	  ///function for delete row////
 public function row_delete($entry_id, $data)
  { $this->db->where('entry_id', $entry_id);
      $this->db->delete($data);
  }
  ///function for select customer////
  public function get_customer_data($entry)
		{
			 $query = 'select user_assignment from entry_list where entry_id = '.$this->db->escape($entry);
   			$this->db->query($query);
		}
  ///function for update customer////
  public function update_c_user($entry, $data){
			$this->db->where('entry_id', $entry);
 		    $this->db->update('entry_list', $data);

		}

		public function display_note_contents($note_name, $entry_id, $action_id, $filees){
		/**
		* display email text and attachments
		*/$action_direction = $this->db->select('action_direction')->from('action_record')->where('action_id', $action_id)->get();
			$action_direct = $action_direction->row()->action_direction;
		if(file_exists($filees.'/'.$note_name)){
			$email_contents = fetch_note_contents($note_name); // actioninfo helper
			echo "<li>Action Notes: ".html_entity_decode($email_contents['notes'])."</li>";
		}
	}

	function hide_entry($ent_id){
		$this->db->query("UPDATE `entry_list` SET entry_action = 'Inactive' WHERE entry_id = " .$ent_id);
	}

	function permanent_del_entry($ent_id){
$tables = array('action_record', 'entry_list');
$this->db->where('entry_id', $ent_id);
$this->db->delete($tables);
	}
	function restore_entries($ent_id){
$this->db->query("UPDATE `entry_list` SET entry_action = NULL WHERE entry_id = " .$ent_id);
	}

	function get_userassign(){
		 $this->db->select('user_assignment, staff_assignment');
		   $this->db->from('entry_list');
		   $query = $this->db->get();
		  return $query->result();

	}

	function get_scheduled_entries(){
		$current_date 	= date('Y-m-d H:i:s');
		$this->db->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
        									THEN group_concat(DISTINCT headings.button_text ORDER BY find_in_set( headings.heading_id, entry_list.heading_assignment ) SEPARATOR ', ')
        									ELSE heading_assignment
        								END AS button_text, action_record.date");
        $this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
        $this->db->join('action_record', 'action_record.entry_id = entry_list.entry_id');
		    $this->db->where('action_status','Scheduled');
		    $this->db->where('action_schedule <',$current_date);
		    $this->db->where('schedule_status','');
        $this->db->group_by('entry_list.entry_id');
        $this->db->order_by('action_record.date','DESC');

        $query  = $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->result_array();
		    return $result;

        }else{
            return false;
        }

	}

	function get_customers_in_entry($entry_id){
			$get_customerIds = $this->db->select('user_assignment')->from('entry_list')->where('entry_id', $entry_id)->get();

			if($get_customerIds->num_rows() > 0){
				$customerIds = $get_customerIds->row()->user_assignment;
				$customers   = explode(',', $customerIds);
				return $customers;
			} else{
				return FALSE;
			}
		}

	function remove_user($data, $entryid){
	$this->db->where('entry_id', $entryid);
 		   $query=  $this->db->update('entry_list', $data);
		   if($query){
 		  return true;
		   }else{
			   return false;
		   }

	}

	/////////for supplier////////////////////
	function get_supplier_in_entry($inventory_id){
			$get_customerIds = $this->db->select('supplier_id')->from('inventory')->where('inventory_id', $inventory_id)->get();

			if($get_customerIds->num_rows() > 0){
				$supplierIds = $get_customerIds->row()->supplier_id;
				$supplier   = explode(',', $supplierIds);
				return $supplier;
			} else{
				return FALSE;
			}
		}

function update_supplier($inventory_id, $data){
	$this->db->where('inventory_id', $inventory_id);
 		   $query=  $this->db->update('inventory', $data);
		   if($query){
 		  return true;
		   }else{
			   return false;
		   }

	}
	  function get__entries_by_entryid($entryid){
    	$entries 	= array();
		$i=0;
		foreach($entryid as $entr_yid){

			$this->db->select("entry_list.*, CASE WHEN entry_list.heading_assignment IS NOT NULL
												THEN group_concat(headings.button_text ORDER BY find_in_set( headings.heading_id, entry_list.heading_assignment ) SEPARATOR ', ')
												ELSE heading_assignment
											END AS button_text ");
			$this->db->from('entry_list')->join('headings', 'find_in_set( headings.heading_id, entry_list.heading_assignment )', 'left');
			$query 			= $this->db->where("entry_id = ".$entr_yid."")->group_by('entry_list.entry_id')->get();
			$entries[$i] 	= $query->result_array();
			$i++;
		}
		if(count($entries) != 0){
			return $entries;
		}else{
			return false;
		}
    }
}
