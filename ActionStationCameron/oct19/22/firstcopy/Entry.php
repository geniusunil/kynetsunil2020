<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Entry extends CI_Controller {
	/**
	 * Entry controller for all the entry related functions
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/entry
	 *	- or -
	 * 		http://example.com/index.php/entry/index
	 */

	public function __construct(){

		parent::__construct();

		// Load session library
		$this->load->library('session');
		$this->load->library('currency_converter');
        $this->load->library('timezones');
		// load models //
		$this->load->model('entry_model');
		$this->user_data 	= $this->session->userdata('logged_in');
		 $time = $this->session->userdata('time');
		
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		if($dbname !="")
		{
			 $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->entry_model->db = $this->load->database($config_app,TRUE);
			}

		// Set default entry acion type for adding new entry "Manual Entry"
		$this->action_id = 23;// later will be selected by the admin setting

		// load helpers
		$this->load->helper('settings');
		$this->load->helper('commonfunctions');
		$this->load->helper('userinfo');
		$this->load->helper('accesscontrol');
		$this->load->helper('heading');
        $this->load->helper('email');
		$this->load->model('crmmails_model');
		$this->load->helper('download');
		$this->load->helper('products');
		if(is_logged_in() === false){
			/**
			* if user not logged in
			*/
			redirect('login', 'refresh');
		}

		$session_data 		      = $this->session->userdata('logged_in');
	  	$this->user_id    		  = $session_data['id'];
	  	$this->closeAlert 		  = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
		
		$this->load->model('Entry_model');
		$this->load->model('Heading_model');
		$this->load->model('accounts_model');
		$this->load->model('product_model');
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->Entry_model->db = $this->load->database($config_app,TRUE);
			$this->Heading_model->db = $this->load->database($config_app,TRUE);
			$this->accounts_model->db = $this->load->database($config_app,TRUE);
			$this->product_model->db = $this->load->database($config_app,TRUE);
			$this->entry_model->db = $this->load->database($config_app,TRUE);
		}
	}
	public function index()
	{
		/**
		*  create a new entry
		*/

		$this->load->model('heading_model');
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		if($dbname !="")
		{
			 $config_app = switch_database($dbname, $db_usr, $db_pass);
					$this->heading_model->db = $this->load->database($config_app,TRUE);
				}
		$assigned_headings  = $this->entry_model->get_assigned_headings();

		$data['entry_type']	= $this->entry_model->entry_types();
		$data['headings']	= $assigned_headings;//$this->heading_model->get_headings();
		$data['title'] 		= "Add Entry";
        $data['session_currency']	= $this->user_data['currency'];
		$this->load->view('templates/header', $data);
		$this->load->view('add_entry', $data);

	}

	public function add()
	{
		/**
		*   add the new entry data to the database
		*/
		ob_start();
		$this->load->model('action_model');
		$this->load->model('product_model');
		$dbname = $this->session->userdata('database_name');
		 $session_data = $this->session->userdata('logged_in'); 
		 $time = $this->session->userdata('time');
		
		 if(empty($time)){
			 $time = $this->accounts_model->system_data_timezone();
		 }
	    $id = $session_data['id'];
		 $user_type = $session_data['usertype'];
		 $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype); 
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->action_model->db = $this->load->database($config_app,TRUE);
			$this->product_model->db = $this->load->database($config_app,TRUE);
		}
		
		
		// Get Form Data //
		
		$data['entry_type']          = $this->input->post('entryType');
		$s_assign			         = $this->input->post('sAssign');
		$sup_assign			         = $this->input->post('supAssignid');
		
		$c_assign			         = $this->input->post('cAssign');
		$h_assign			         = $this->input->post('eHeading');
		
		$submit				         = $this->input->post('addEntry');
		$saveSchedule                = $this->input->post('addSchedule');
		$saveAndClose                = $this->input->post('addClose');
		$notes 				         = $this->input->post('eNote'); // product and quote notes are needed to be reviewed later //
		$u_entry 			         = $this->input->post('add_entry');
		// product data //
		$p_sources			         = $this->input->post('eSource');
		$p_names 			         = $this->input->post('eProduct');
		$p_urls				         = $this->input->post('eUrl');
		$p_retails 			         = $this->input->post('eRetail');
		$p_wholesales 		         = $this->input->post('eWholeSale');
		$p_costs 			         = $this->input->post('eCost');
		$p_notes			         = $this->input->post('ePnotes');
		/*-------------------------------------------*/
		$p_spacific			         = $this->input->post('eProduct_specification');
		$p_sku			             = $this->input->post('eProduct_sku');
		$p_qunt			             = $this->input->post('eProduct_qunt');
		$p_discont		             = $this->input->post('eProduct_discont');
		$p_retaile		             = $this->input->post('retaileCurrency');
		$p_whole			         = $this->input->post('wholeCurrency');
		$p_cost			             = $this->input->post('costCurrency');
		$sAssign 		             =  implode(', ', array_values($s_assign));
		$s_Assign 		             =  implode(', ', array_values(array_unique($sup_assign)));
		$cAssign		             =  implode(', ', array_values($c_assign));
		$hAssign		             =  implode(',', array_values($h_assign));
		
		$data['staff_assignment'] 	 = 	rtrim($sAssign, ',');
		$data['user_assignment'] 	 = 	rtrim($cAssign, ',');
		$s_Assign                  = 	rtrim($s_Assign, ',');
	    $sup_assign =  explode(', ', $s_Assign);
		$i=1;
		foreach($sup_assign as $s_Assignd => $value){
		${'sup_assignd'.$i} = rtrim($value, ',');
		$i++;	
		}
        $my_action 			         = $this->input->post('action');
		
		if($my_action == "email"){
			$this->action_id = 27;
		}
		$h_asgn  = ',' . $hAssign . ',';
	     if($ustype == 'businessaccountmanager'){
		/** check if headings contain dead leads [7] or completed sales [6] heading filters **/
		

		if( (strpos($h_asgn, ',7,') != -1) || (strpos($h_asgn, ',6,') != -1) ){
			/**
			* don't add action in entries
			*/
			$data['heading_assignment']  = '2'.$h_asgn;

		} else{
			$data['heading_assignment']  = '1'.$h_asgn;
			$default_heading = '1'.$h_asgn;
		}
		 }else{
			if(empty($hAssign)){
				$data['heading_assignment']  = '1'.$h_asgn;
				$default_heading = '1'.$h_asgn;
			}else{
				$data['heading_assignment']  = '1'.$h_asgn;
				$default_heading = '2'.$h_asgn;
			}
		 }
		
		if($submit == 'newentry' || $saveSchedule == 'nextSchedule' || $saveAndClose == 'actionClose'){
	
			$save = $this->entry_model->save_entry($data);

			if($save != 0){
				$data_1['entry_id'] 		 = $save;
				$data_1['action_source']  	 = $this->input->post('entrySource');
				$data_1['action_type']  	 = $this->action_id;
				$data_1['action_direction']  = "internal";
				$data_1['action_status']     = "Completed";
				$data_1['action_notes']      = $notes;
				$data_1['date'] 	    	 = $time;
				$data_1['action_schedule'] 	 = $time;
				$data_1['action_author'] 	 = $this->user_id;
				$data_1['action_entry']    =  rtrim($cAssign, ',').'|'.rtrim($sAssign, ',').'|'.rtrim($s_Assign, ',').'_'.rtrim($default_heading, ',');
				// add product quote //
				if($p_sources && $p_names && $p_urls && $p_costs){
					$count_products = count(array_filter($p_names));

					for($i = 1; $i <= $count_products; $i++){
						// check if product exists in inventory //
						$if_exists = $this->product_model->product_exists($p_urls[$i]);

						if($if_exists === FALSE){
							/**
							* product doesn't exists
							*/
							
							
						   $s_Assign                              = rtrim(${'sup_assignd'.$i}, ',');
							
						   $product['supplier_id']                = $s_Assign;
                           $product['product_code']               = $p_sku[$i];
                           $product['product_title']              = $p_names[$i];
                           $product['product_url']                = $p_urls[$i]; 
                           $product['stock_volume']               = $p_qunt[$i]; 	
                           $product['store_location']             = '';
                           $product['mo_quantity']                = 1;
                           $product['notes']                      = $p_notes[$i];
                           $product['creation_date']              = $time;
                           $product['update_date']                = $time;
                           $product['inventory_author']           = $this->user_id;
                           $product['update_author']              = $this->user_id;
						   $product['product_spacification']      = $p_spacific[$i]; 
						    $product['product_disconunt']         = $p_discont[$i]; 
                           $inv_id = $this->product_model->addInventory($product);

						} else{
							/**
							* product exist and inventory id is returned
							*/
							$inv_id = $if_exists;
						}
						// check if all quotes exists //
						// add quotes //
						$prices = array('retail'=>$p_retails[$i], 'wholesale'=>$p_wholesales[$i], 'cost'=>$p_costs[$i]);
						$p_currency = array($p_retaile, $p_whole, $p_cost);
						
						$inv_quote[$i] = $this->product_model->add_quotes($inv_id, $prices, $p_currency, date('Y-m-d H:i:s') );
						//$inv_quote = $this->product_model->get_inventory_quotes($inv_id);
	                    $data_1['action_content'] ='';
	           		}

	           		if( isset($inv_quote) ){

	           			if( $inv_quote !== FALSE ){
	                        $data_1['action_content'] .= 'quotes: '.implode_md(',', $inv_quote);
	                    } else{
	                        $data_1['action_content'] .= 'no quotes';
	                    }
	           		}

				}
				// Save Action Record //
				$actionId = $this->action_model->save_action($data_1);
				$this->entry_model->update_entry_status($save);

				if($actionId != false){
					$this->session->set_flashdata('add_entry', '<div class="alert alert-success">Entry added successfully!'.$this->closeAlert.'</div>');
					if(isset($u_entry)){
function getContents($str, $startDelimiter, $endDelimiter) {
  $contents = array();
  $startDelimiterLength = strlen($startDelimiter);
  $endDelimiterLength = strlen($endDelimiter);
  $startFrom = $contentStart = $contentEnd = 0;
  while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
    $contentStart += $startDelimiterLength;
    $contentEnd = strpos($str, $endDelimiter, $contentStart);
    if (false === $contentEnd) {
      break;
    }
    $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
    $startFrom = $contentEnd + $endDelimiterLength;
  }
  
  return $contents;
}
$file = $_REQUEST['f_file'];
		  $id = $session_data['id'];
$dbname = $this->session->userdata('database_name');
$incoming_email = $this->accounts_model->systems_datas($id);
if ($dbname) {
    foreach ($incoming_email as $incoming_emails) {
        $incoming_emailss = $incoming_emails->incoming_email;
    }
}
$incoming_sys_email = $this->accounts_model->systems_data();
foreach ($incoming_sys_email as $incoming_emails) {
    $incoming_sys_emails = $incoming_emails->setting_value;
}
if (!empty($incoming_emailss)) {
    $username = $incoming_emailss;
} else {
    $username = $incoming_sys_emails;
}
				 $file = $this->input->post('f_file');
	   
		   if($ustype == 'businessaccountmanager'){
			$f_file   =  'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/'.$file;	
		}else{
			if($dbname){
			if(empty($incoming_emailss)){
			 $f_file   =  'email_files/'.$dbname."/".$username.'/'.$file;
		}else{
			$id = $session_data['id'];	
	        $f_file   =  'email_files/'.$dbname."/".$username.'/'.$file;	
		}	
			}else{
				$id = $session_data['id'];	
				$f_file   =  'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/'.$file;
			}

		}
		 $sample = file_get_contents($f_file);
		 $message1 = getContents($sample, '<message>', '<endmessage>');
		foreach( $message1 as  $mess){
					$template['content']= $mess;
		} 
				//$template['content']  = $this->input->post('message');
		        $to_email             = $this->input->post('semail');
		        $subject              = $this->input->post('subject');
		        $entry_id             = $save;
		        $head                 = get_headings_id($entry_id);
				    $heading              = $head[0]['heading_assignment'];
				    $heading              = rtrim($heading,", ");
				    $heading              = explode(',',$heading);
				    //$heading              = array_diff($heading, ["1"]);
				if (($key = array_search('3', $heading)) == false) {
					 array_push($heading,3);
					$data['heading_assignment'] = implode(',',$heading);
					$this->entry_model->update_heading_assignment($entry_id, $data);
				}

				$attachments          = "N/A";
				$nte                  = "N/a";
				$source               = $this->input->post('entrySource');
				$body                 = $template['content'];
				$filename             = $this->input->post('f_file');
				//$folder               =  $_SERVER['DOCUMENT_ROOT'].'/action_station/email_files/';
				 $session_data = $this->session->userdata('logged_in'); ?>
					<?php $user_type = $session_data['usertype']; ?>
                    <?php $user_details = get_user_details($session_data['id']); ?>
                    <?php $utype = get_user_type($user_type);
                     $ustype =$utype->type_name ;
                     $ustype=preg_replace('/\s+/', '', $ustype);
                    $ustype = strtolower($ustype);
                   $uid =  $session_data['id'];
                    $db = $this->session->userdata('database_name');
			
					$incoming_email = $this->accounts_model->systems_datas($id);
					foreach($incoming_email as $incoming_emails){
			  $incoming_emailss = $incoming_emails->incoming_email;
			  }
			   $incoming_sys_email = $this->accounts_model->systems_data();
			 foreach($incoming_sys_email as $incoming_emails){
			  $incoming_sys_emails = $incoming_emails->setting_value;
			  }
			   if(!empty($incoming_emailss)){
				   $username = $incoming_emailss;
			   }else{
				   $username = $incoming_sys_emails;
			   }
			if($ustype == "businessaccountmanager"){
		$filees = "e-mails/ceocamer_cam_crm";
		$pathss = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com";
	 }
	 elseif($ustype == "businessaccount"){
	 $filees = "e-mails/".$db;
	$pathss = "email_files/".$db."/".$username;
	 }else{
		 if($db != ""){
	 $filees = "e-mails/".$db."/".$uid;
	 $pathss = "email_files/".$db."/".$username;
		 }else{
			 $filees = "e-mails/ceocamer_cam_crm/".$uid;
			 $pathss = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com"; 
		 }
	 }		 
				
				
				$folder               =  $pathss.'/';
				
				 $file                 = $folder.$filename;
				
				 $file1                = basename($file);
				$date = "process_";
				if (file_exists($file)) {
					$file_name = $folder.$date.$file1;
					rename($file, $file_name);
				}else {
					//echo "no file";
				}

			$data = array();

			$data['entry_id'] 		    = 	$entry_id;
			$data['action_source'] 	    = 	$source;
			$data['action_direction'] 	= 	"Incoming";
			$data['action_status'] 	    = 	"Completed";
			$data['action_type'] 		= 	'2';
			$data['action_content'] 	= 	$template['content'];
			$data['action_notes'] 	    = 	$nte;
			$data['date'] 	            = 	$time;
			$data['action_schedule'] 	= 	$time;
			$data['action_author'] 	    = 	$this->user_id;

			 $action = $this->action_model->save_action_data($data);
			if($action !== false){
				// save mail on server //
				$date       =  'Date: '.$time;
				$mailname   =  $entry_id."_".$action."_".$time;
				$mailto 	=  "From: ".$to_email;
				$notes  	=  "\nNotes: ".$notes;
				$saved_mail =  $mailto."\n".$date."\nSubject: ".$subject."\n".$body.$attachments.$notes;
				$dir 		=  $filees."/"; // Full Path
				$myfile 	=  file_put_contents($dir.$mailname.".txt", $saved_mail.PHP_EOL , FILE_APPEND);
				$update     = $this->entry_model->update_entry_status($entry_id);


				}
				header('location: '.base_url('crmmails/viewmails?page=1'));
					}
					elseif($submit == 'newentry'){
						header('Location: '.base_url());

					} elseif( $saveSchedule == 'nextSchedule' && isset($data_1['entry_id']) ){
						$this->session->set_flashdata('scheduleNext', $data_1['entry_id']);

					} elseif($saveAndClose == 'actionClose'){
						$this->session->set_flashdata('closeAction', true);
					}
					redirect('entry', 'refresh');

				} else{
					$this->session->set_flashdata('add_entry', '<div class="alert alert-success">Error occured while saving action'.$this->closeAlert.'</div>');
					header("Location: ".base_url('entry'));
				}
			}
			else{
				$this->session->set_flashdata('add_entry', '<div class="alert alert-danger">Error in saving entry! Please Try Again'.$this->closeAlert.'</div>');
				header("Location: ".base_url('entry'));
			}

		
		}else{
		    $this->session->set_flashdata('add_entry', '<div class="alert alert-danger">Something went wrong! Please try again.'.$this->closeAlert.'</div>');
			header("Location: ".base_url('entry'));
		}
	}
	public function search(){
		/**
		* search for the key in database
		* for customer or user details
		*/
		$key = $this->input->post('key');
		//$user_type = $this->input->post('user_type');
		$search = $this->entry_model->search_username($key);
		echo json_encode($search);
	}
			//////////////////////////////////////////////
///////////function for save staffid/////////
////////////////////////////////////////////
	public function update_staff_assign()
	{
		$this->load->helper('settings');
		$this->load->model('action_model');
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		$time = $this->session->userdata('time');
		if($dbname !="")
		{
			 $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->action_model->db = $this->load->database($config_app,TRUE);
			
		}
		
		$entry= $this->input->post('entryId');
		$sid=get_staff_id($entry);
		$data['staff_assignment'] = $this->input->post('staffid');
		$curnt_staff_assignd = $this->action_model->get_staff_assign_in_entry($entry);
		$update = $this->entry_model->update_staff_assignment($entry, $data);
		$updated_staff_assignd = $this->action_model->get_staff_assign_in_entry($entry);
		if(count($curnt_staff_assignd) > count($updated_staff_assignd)){
			$xxc = array_diff($curnt_staff_assignd, $updated_staff_assignd);
			$stf_id = implode(",",$xxc);
		}else{
		$array_diff2 = array_diff($updated_staff_assignd, $curnt_staff_assignd);
		$stuff = implode(",",$array_diff2);
		}
		$staffid                       =   $sid[0]['staff_assignment'];
		if(isset($xxc) && !empty($xxc)){
		$act_textss = 'deletedStaff:'.$entry.','.$stf_id.'|deletedst';
		$act_notee ='Staff Deleted From The Entry';	
		}else{
		$act_textss = 'editedStaff:'.$entry.','.$stuff.'|editedst';
		$act_notee ='Staff Added To The Entry';	
			}
			/*
			if(!empty($xxc)){
				$stf_id = implode("@",$xxc);
		$act_textss = 'delediteddStaff:'.$entry.','.$staffid.'@'.$stf_id.'|deleditedst';
		$act_notee ='Staff Added And Deleted From The Entry';	
			
		}*/
				if($update !== false)
		{
							
							$action 		= array();
							$action['entry_id'] 		    = 	$entry;
							$action['action_type']			=   20;
							$action['action_source'] 		= 	base_url('dashboard');
							$action['action_direction'] 	= 	"Internal"; 	// to be confirmed
							$action['action_status'] 		= 	"Completed";
							$action['action_notes'] 	    = 	$act_notee;
							$action['action_content']		=   $act_textss;
							$action['date'] 	    		= 	$time;
							$action['action_schedule'] 		= 	$time;
							$action['action_author'] 		= 	$this->user_id;
							//$add_admin_action  = $this->Action_model->save_action($action);
							$addedActionId  		= $this->action_model->save_action($action);
							$historyData = array(
									"entry_id"  => $entry,
									"action_id" => (($addedActionId !== false)?$addedActionId:0),
									"edit_date" => $time
								);

							$saveEditHistory = $this->action_model->insert_edit_history($historyData);

		}
}
	//////////////////////////////////////////////
///////////function for save heading/////////
////////////////////////////////////////////

	public function update_heading_assignment()
	{
		 $this->load->model('action_model');
		 $dbname = $this->session->userdata('database_name');
		 $db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		$time = $this->session->userdata('time');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->action_model->db = $this->load->database($config_app,TRUE);
			
		}
		 $entry= $this->input->post('entryId');
		 //$data['heading_assignment'] = $this->input->post('headingid');
		 $hed_id_array   = explode(",",$this->input->post('headingid'));
		 $headid = $this->entry_model->get_heading_data($entry);
		 $head = get_headings_id($entry);
		 $temp_hed_array = explode(",",$head[0]['heading_assignment']); 
		  if(in_array("1", $temp_hed_array) && in_array("2", $hed_id_array)){
			$hed_id_array = array_diff($hed_id_array, array('1')); 
		 }
		 if(in_array("2", $temp_hed_array) && in_array("1", $hed_id_array)){
			$hed_id_array = array_diff($hed_id_array, array('2')); 
		 }
		 $data['heading_assignment'] = implode(",",$hed_id_array);
		
		 
         $updated = $this->entry_model->update_heading_assignment($entry, $data);
		if($updated !== false)
		{
							$headings                       =   $head[0]['heading_assignment'];
							$action 		                =   array();
							$action['entry_id'] 		    = 	$entry;
							$action['action_type']			=   20;
							$action['action_source'] 		= 	base_url('dashboard');
							$action['action_direction'] 	= 	"Internal";
							$action['action_status'] 		= 	"Completed";
							$action['action_notes'] 	    = 	'Heading Added To The Entry';
							$action['action_content']		=   'editedHeading:'.$entry.','.$headings.'|edited';
							$action['date'] 	    		= 	$time;
							$action['action_schedule'] 		= 	$time;
							$action['action_author'] 		= 	$this->user_id;
							//$add_admin_action  = $this->Action_model->save_action($action);
							$addedActionId  		= $this->action_model->save_action($action);
							$historyData = array(
									"entry_id"  => $entry,
									"action_id" => (($addedActionId !== false)?$addedActionId:0),
									"edit_date" => $time
								);

							$saveEditHistory = $this->action_model->insert_edit_history($historyData);

		}

	}
	public function update_customer()
	{

		  $abc                      = $this->input->post('c_id');
	      $entry                    = $this->input->post('entrys');
		  $current_uid              = get_customer_id($entry);
		  $current_uid              =  $current_uid['0']['user_assignment'];
		  $current_uid              = explode(", ", $current_uid);
		  array_push($current_uid, $abc);

		  $data['user_assignment'] = implode(',', $current_uid);

		  $up                       = $this->entry_model->update_c_user($entry, $data);

	}
	
	
	public function update_customer_assign()
	{
		$this->load->helper('settings');
		$this->load->model('action_model');
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		$time = $this->session->userdata('time');
		if($dbname !="")
		{
			 $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->action_model->db = $this->load->database($config_app,TRUE);
			
		}
		$entry= $this->input->post('entryId');
		$entry_notesss= $this->input->post('cust_notes');
		$sid = get_customer_id($entry);
		$current_sid = $this->action_model->get_customers_in_entry($entry);
		$data['user_assignment'] = $this->input->post('custid');
		$update = $this->entry_model->update_customer_assignment($entry, $data);
		$updated_sid = $this->action_model->get_customers_in_entry($entry);
		if(count($current_sid) < count($updated_sid)){
			$xxc = array_diff($updated_sid, $current_sid);
			$custf_id = implode(",",$xxc);
		}
		 if(!empty($custf_id)){
			$custid = $custf_id;
		 }else{
			 $custid   =   $sid[0]['user_assignment'];
		 }
				if($update !== flase)
		{
							
							$action 		= array();
							$action['entry_id'] 		    = 	$entry;
							$action['action_type']			=   20;
							$action['action_source'] 		= 	base_url('dashboard');
							$action['action_direction'] 	= 	"Internal"; 	// to be confirmed
							$action['action_status'] 		= 	"Completed";
							$action['action_notes'] 	    = 	$entry_notesss;
							$action['action_content']		=   'editedCustomer:'.$entry.','.$custid.'|editeds';
							$action['date'] 	    		= 	$time;
							$action['action_schedule'] 		= 	$time;
							$action['action_author'] 		= 	$this->user_id;
							//$add_admin_action  = $this->Action_model->save_action($action);
							$addedActionId  		= $this->action_model->save_action($action);
							$historyData = array(
									"entry_id"  => $entry,
									"action_id" => (($addedActionId !== false)?$addedActionId:0),
									"edit_date" => $time
								);

							$saveEditHistory = $this->action_model->insert_edit_history($historyData);

		}
}

public function trash_entries()
    {
		$data['title'] 		= "Trashed Entries";
	$this->load->view('templates/header', $data);
	
	  
	//echo $id;
	
	$this->load->model('entry_model');
	$this->load->model('heading_model');
	$this->load->model('accounts_model');
	$dbname = $this->session->userdata('database_name');
	$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->entry_model->db = $this->load->database($config_app,TRUE);
			$this->heading_model->db = $this->load->database($config_app,TRUE);
			$this->accounts_model->db = $this->load->database($config_app,TRUE);
		}
   //$user_ids_string = $file = $this->input->post('us_id');
	/*$id = $this->uri->segment(4);
	if(isset($id)){
	$user_ids_string = $id;	
	}*/
	
	if(null !== $this->input->post('us_id')){
		 $user_ids_string = $this->input->post('us_id');
	}else{
		 $user_ids_string = $this->input->get('us_id');
	}
	
	//$_SESSION['ids']= $user_ids_string;
	$entriesArray    = $this->entry_model->get_all_entries12();
	 //$data['entries'] = $this->crmmails_model->getentries($id);
	 $data['entry'] = $entriesArray;
	   $data['headings'] 		= $this->heading_model->get_headings();
		$data['countries'] 		= $this->currency_converter->getCountries();
		$data['action'] 		= $this->entry_model->action_types();
		$data['timezones']		= $this->timezones->getTimezones();
		$data['utcTime'] 		= $this->timezones->getUtcTime();
		
		$userInfo = $this->accounts_model->get_user_information_trash();
		if(is_array($userInfo) && array_key_exists('preferred_currency', $userInfo))
			$user_currency = $userInfo['preferred_currency'];

		// getting the currency values //
		if(isset($user_currency))
			$data['user_currency'] = $user_currency;
		 
		// getting user timezone //
		if(is_array($userInfo) && array_key_exists('timezone', $userInfo))
			$data['timezone'] = $userInfo['timezone'];
		
		$data['action_page'] 		= FALSE;
		$data['entry_model'] 		= $this->entry_model;
		$data['session_currency']	= $this->user_data['currency'];
		$data['sender']             = $this->input->post('uid');
		$data['subject']            = $this->input->post('subject');
		$message                    = $this->input->post('message');
		$data['message']            = html_entity_decode($message, ENT_COMPAT,'ISO-8859-1');
		$data['attachment']         = $this->input->post('attach');
		$data['attachment_pdf']         = $this->input->post('attach_1');
		$data['attachment_doc']         = $this->input->post('attach_2');
		$data['file']               = $this->input->post('file_name');
		$data['fname']              = $this->input->post('f_name');
		$data['edate']              = $this->input->post('edate');
		$data['ids']                = $user_ids_string ;
		$data['hids']               = $this->input->post('he_id');
	
	$this->load->view('trash_entry_view', $data);
		
	}
	
	public function permanent_delete_entry(){
		 $ent_id = $this->input->post('ent_id');
	$this->Entry_model->permanent_del_entry($ent_id);

	}
	
	public function restore_entry(){
		 $ent_id = $this->input->post('ent_id');
	$this->Entry_model->restore_entries($ent_id);

	}
	
	
	
public function match_producturl_title(){
	$this->load->model('action_model');
		$this->load->model('product_model');
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		if($dbname !="")
		{
			 $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->action_model->db = $this->load->database($config_app,TRUE);
			$this->product_model->db = $this->load->database($config_app,TRUE);
		}
		
		$prot			= $this->input->post('prot');
		$prou			= $this->input->post('prou');
		$pro 			= $this->product_model->geteproducttitle($prot);
		$prour           = $this->product_model->geteproducturl($prou);
		if($pro == TRUE ){
			$resp = array('result' => 0, 'Product_title' => true);
			echo json_encode($resp);
			exit();
		}
		/*if($prour == TRUE){
			$resp = array('result' => 1, 'Product_url' => true);
			echo json_encode($resp);
			exit();
		}*/
		
	}
	
	public function user_remove(){
		$this->load->helper('settings');
		$this->load->model('action_model');
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		$time = $this->session->userdata('time');
		if($dbname !="")
		{
			 $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->action_model->db = $this->load->database($config_app,TRUE);
			
		}
		 $time = $this->session->userdata('time');
		
		 $remove_user_id  = trim($this->input->post('u_id'));
		 
		  $entry_id  = $this->input->post('entry_id');
		  
		  $note_text = $this->input->post('note_text');
		
		$user_assm = $this->entry_model->get_customers_in_entry($entry_id);
		
		 $usercount = count($user_assm);
		 if($usercount!=1){
			//$findid = array_search($remove_user_id, $user_assm);
			
		if (($key = array_search($remove_user_id, $user_assm)) !== false)  {
			 //array_diff($user_assm, $remove_user_id);
			 
		 unset($user_assm[$key]);
		 $data['user_assignment'] = implode(',', $user_assm);
		
		$userInfo = $this->entry_model->remove_user($data, $entry_id);
		
		if($userInfo != false){
			
							//$custid                       =   implode(',',$remove_user_id);
							
			 				$action 		= array();
							$action['entry_id'] 		    = 	$entry_id;
							$action['action_type']			=   20;
							$action['action_source'] 		= 	base_url('dashboard');
							$action['action_direction'] 	= 	"Internal"; 	// to be confirmed
							$action['action_status'] 		= 	"Completed";
							$action['action_notes'] 	    = 	$note_text;
							$action['action_content']		=   'deletedCustomer:'.$entry_id.','.$remove_user_id.'|deletedc';
							$action['date'] 	    		= 	$time;
							$action['action_schedule'] 		= 	$time;
							
							$action['action_author'] 		= 	$this->user_id;
							
							//$add_admin_action  = $this->Action_model->save_action($action);
								
							$addedActionId  		= $this->action_model->save_action($action);
							
							$historyData = array(
									"entry_id"  => $entry_id,
									"action_id" => (($addedActionId !== false)?$addedActionId:0),
									"edit_date" => $time
								);

							$saveEditHistory = $this->action_model->insert_edit_history($historyData);
			
			$resp = array('result' => 1, 'removeuser' => true);
			echo json_encode($resp);
			exit();
			
		}else{
			 $resp = array('result' => 2, 'error' => true);
			echo json_encode($resp);
			exit();
		}
		}
		 }else{
			 $resp = array('result' => 0, 'oneuser' => true);
			echo json_encode($resp);
			exit();
			
		 }
		
	}
	/*****************remove supplier************************/
	public function supplier_remove(){
		$this->load->helper('settings');
		$this->load->model('action_model');
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		$time = $this->session->userdata('time');
		if($dbname !="")
		{
			 $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->action_model->db = $this->load->database($config_app,TRUE);
			
		}
		 $time = $this->session->userdata('time');
		 $remove_user_id  = trim($this->input->post('u_id'));
		  $inventory_id  = $this->input->post('inventory_id');
		  $entry_id  = $this->input->post('entryid');
		  $note_text  = $this->input->post('note_text');
		$user_assm = $this->entry_model->get_supplier_in_entry($inventory_id);
		
		//$user_assm = array_filter($user_assm);
		
		$usercount = count($user_assm);
		
		 if($usercount!=1){
			//$findid = array_search($remove_user_id, $user_assm);
		if (($key = array_search($remove_user_id, $user_assm)) !== false)  {
			 //array_diff($user_assm, $remove_user_id);
			
		 unset($user_assm[$key]);
		 $data['supplier_id'] = implode(',', $user_assm);
		
		$userInfo = $this->entry_model->update_supplier($inventory_id, $data);
		if($userInfo != false){
			//$custid                       =   implode(',', $user_assm);
							
							$action 		= array();
							$action['entry_id'] 		    = 	$entry_id;
							$action['action_type']			=   20;
							$action['action_source'] 		= 	base_url('dashboard');
							$action['action_direction'] 	= 	"Internal"; 	// to be confirmed
							$action['action_status'] 		= 	"Completed";
							$action['action_notes'] 	    = 	$note_text;
							$action['action_content']		=   'deletedSupplier:'.$entry_id.','.$remove_user_id.'|deleteds';
							$action['date'] 	    		= 	$time;
							$action['action_schedule'] 		= 	$time;
							$action['action_author'] 		= 	$this->user_id;
							//$add_admin_action  = $this->Action_model->save_action($action);
							$addedActionId  		= $this->action_model->save_action($action);
							$historyData = array(
									"entry_id"  => $entry_id,
									"action_id" => (($addedActionId !== false)?$addedActionId:0),
									"edit_date" => $time
								);

							$saveEditHistory = $this->action_model->insert_edit_history($historyData);
			///////////////////////
			$resp = array('result' => 1, 'removeuser' => true);
			echo json_encode($resp);
			exit();
			
		}else{
			 $resp = array('result' => 2, 'error' => true);
			echo json_encode($resp);
			exit();
		}
		}
		 }else{
			 $resp = array('result' => 0, 'oneuser' => true);
			echo json_encode($resp);
			exit();
			
		 }
		
	}


////update supplier from inventory///
public function update_supplier_assign()
	{
		$this->load->helper('settings');
		$this->load->model('action_model');
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		$time = $this->session->userdata('time');
		if($dbname !="")
		{
			 $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->action_model->db = $this->load->database($config_app,TRUE);
			
		}
		 $time = $this->session->userdata('time');
		$inventoryId= $this->input->post('inventoryId');
		$curnt_supidd= $this->entry_model->get_supplier_in_entry($inventoryId);
		$sid = $this->input->post('supid');
		//$data['supplier_id'] = implode(',', $user_assm);
		$sids = explode(",", $sid);
		//print_r($sid);
		 $data['supplier_id'] = implode(',', $sids);
		 //print_r($data); 
		$entry = $this->input->post('entryid');
		$entry_noetss = $this->input->post('sup_notes');
		$aa = get_supplier_id($inventoryId);
		foreach($aa as $av){
			 $sup_idss = $av['supplier_id'];
		}
		$sup_ids= explode(',' , $sup_idss);
		$i=0;
		foreach($sids as $sup_idsss){
				if(in_array($sup_idsss,$sup_ids)){
					unset($sids[$i]);
				}
			$i++;
		}
		
		$update = $this->entry_model->update_supplier($inventoryId, $data);
		$updated_supidd = $this->entry_model->get_supplier_in_entry($inventoryId);
		if(count($curnt_supidd) < count($updated_supidd)){
			$xxc = array_diff($updated_supidd, $curnt_supidd);
			$stf_id = implode(",",$xxc);
		}
		 if(!empty($stf_id)){
			$supid = $stf_id;
		 }else{
			 $supid   =   $sid; 
		 }
		
				if($update !== false)
		{
					       
							$action 		                =   array();
							$action['entry_id'] 		    = 	$entry;
							$action['action_type']			=   20;
							$action['action_source'] 		= 	base_url('dashboard');
							$action['action_direction'] 	= 	"Internal"; 	// to be confirmed
							$action['action_status'] 		= 	"Completed";
							$action['action_notes'] 	    = 	$entry_noetss;
							$action['action_content']		=   'editedsupplier:'.$entry.','.$supid.'|editedsup';
							$action['date'] 	    		= 	$time;
							$action['action_schedule'] 		= 	$time;
							$action['action_author'] 		= 	$this->user_id;
							//$add_admin_action  = $this->Action_model->save_action($action);
							$addedActionId  		= $this->action_model->save_action($action);
							$historyData = array(
									"entry_id"  => $entry,
									"action_id" => (($addedActionId !== false)?$addedActionId:0),
									"edit_date" => $time
								);

							$saveEditHistory = $this->action_model->insert_edit_history($historyData);

		}
}
}