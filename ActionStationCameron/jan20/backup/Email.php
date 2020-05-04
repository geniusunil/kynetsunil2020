<?php defined('BASEPATH') OR exit('No direct script access allowed');
Class Email extends CI_Controller 
{
	/**
	 * Email controller
	 *
	 * @extends CI_Controller
	 */
	private $user_id = null;
 	public function __construct()
	{

		parent::__construct();
 
		// Load helpers //
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('directory');
		$this->load->helper('products');
		$this->load->helper('accesscontrol');
		$this->load->helper('userinfo');
		$this->load->helper('heading');
		$this->load->helper('filecontet');
		$this->load->helper('settings');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('timezones');

		// load models //
		$this->load->model('entry_model');
		$this->load->model('cons_model');
		$this->load->model('action_model');
		$dbname = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
	    $time = $this->session->userdata('time');
		
		if(empty($time)){
	    $time = get_systems_timezone();	
		}
		if($dbname !="")
		{ 
			$config_app             = switch_database($dbname, $db_usr, $db_pass);
			$this->entry_model->db  = $this->load->database($config_app,TRUE);
			$this->cons_model->db   = $this->load->database($config_app,TRUE);
			$this->action_model->db = $this->load->database($config_app,TRUE);
		}
		    $this->session_data 	= $this->session->userdata('logged_in');
	  	    $this->user_id    		= $this->session_data['id'];

	  	if(is_logged_in() === false)
		{
			/**
			* if user not logged in
			*/
			redirect('login', 'refresh');
		}
		$this->closeAlert = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
		
		
		$this->load->model('Entry_model');
		$this->load->model('Heading_model');
		$this->load->model('accounts_model');
		$this->load->model('product_model');
		
		$dbname  = $this->session->userdata('database_name');
		$db_usr  = $this->session->userdata('db_name');
		$db_pass = $this->session->userdata('db_pass');
		if($dbname !="")
		{
			$config_app               = switch_database($dbname, $db_usr, $db_pass);
			$this->Entry_model->db    = $this->load->database($config_app,TRUE);
			$this->Heading_model->db  = $this->load->database($config_app,TRUE);
			$this->accounts_model->db = $this->load->database($config_app,TRUE);
			$this->product_model->db  = $this->load->database($config_app,TRUE);
		
		}
	}
	
	public function index($entry, $action, $ent_id=NULL)
	{
		/**
		* email action for entry
		*/
		error_reporting(E_ERROR | E_PARSE);
		$this->load->helper('directory');
		$header_data['title'] =  "Email";
		$this->load->view('templates/header', $header_data);
		$session_data = $this->session->userdata('logged_in');
        $user_type    = $session_data['usertype'];
        $user_details = get_user_details($session_data['id']);
        $utype        = get_user_type($user_type);
	    $ustype       = $utype->type_name ;
	    $ustype       = preg_replace('/\s+/', '', $ustype);
        $ustype       = strtolower($ustype);
        $uid          = $session_data['id'];
        $db           = $this->session->userdata('database_name');
		$a_data       = get_action_data($entry);
		
		if($ustype == "businessaccountmanager")
		{
			$filees = "e-mails/ceocamer_cam_crm";
		}
		elseif($ustype == "businessaccount")
		{
		   $filees = "e-mails/".$db;
		}
		else
		{
			if($db != "")
			{
				$filees = "e-mails/".$db."/".$uid;
			}
			else
			{
				$filees = "e-mails/ceocamer_cam_crm/".$uid; 
			}
		}		 
		$filees_business_log_stf = "e-mails/".$db;
		$filees_stf_log_business = "e-mails/".$db."/".$a_data[0]['action_author'];

		$previous_mail = $this->action_model->get_previous_mail($entry, $filees,$filees_business_log_stf,$filees_stf_log_business);

		if($previous_mail != false)
			$data['previous_mail'] = $this->action_model->get_mail_contents($previous_mail, $filees);

		$data['entry_id'] 	      =  $entry;
		$data['action_id']        =  $action;
		$data['entry'] 		      =  $this->entry_model->entry_details($entry);
		$data['showAllEntries']   =  TRUE;
		$data['timezones']        =  $this->timezones->getTimezones();
		$data['entry_model']      =  $this->entry_model;
		$data['action']		      =  $this->entry_model->action_types();
		$data['parts_cate']       =  $this->cons_model->email_parts_category();
		$data['parts_content']	  =  $this->cons_model->getAllEmailPartsContent();	
		$data['action_page']      =  TRUE;
		$data['attachments']      =  $this->load->view('email_attachments', NULL, TRUE);
		$data['countries'] 		  =  $this->currency_converter->getCountries();
		$data['session_currency'] =  $this->session_data['currency'];
		$data['out_username']     =  $this->accounts_model->ftch_outsystem_data();
		$data['out_email']        =  $this->accounts_model->systems_outdata();
        $data['out_email_info']   =  $this->accounts_model->ftch_system_datas($uid);

		$data['entry_count']      =  $cont;
		$data['actiion_id']       =  $ent_id;
		$data['ent_id']           =  $ent_id;
		$data['em_file'] 	      =  $previous_mail;
		//Load view of for adding new emails
		//$data['add_email'] = $this->load->view('accounts/add_email', NULL, TRUE);
		// load email views
	
		if(isset($_REQUEST['schedule']))
		{
			$schedual = $_REQUEST['schedule'];
			$data['Action_complete']  = $schedual;	
		}
		$this->load->view('email/email_view', $data);
	}

	public function emailparts()
	{
		/**
	     *
	     *  Load Email Parts(construction aids)
	     *
		*/
		$cate_id = $this->input->post('cate_id');
		$data    =  $this->cons_model->email_parts_by_cate($cate_id);
		$jsonstring = json_encode($data);
		echo $jsonstring;
	}

	public function emailpart_content()
	{
		/**
		 *  get Email Part Conatent (construction aids)
		*/
		$content_id = $this->input->post('content_id');
		$data  	  =  $this->cons_model->email_parts_by_id($content_id);
		$jsonstring = json_encode($data);
		echo $jsonstring;
	}

	public function add_email_aids()
	{
		/**
		 *  Add new conctruction Aid
		*/
		$data['content_title']	    = $this->input->post('template_name');
		$data['content']            = $this->input->post('template_cont');
		$data['category']	        = $this->input->post('template_cate');
		$session_data 		        = $this->session->userdata('logged_in');
		$data['content_author']     = $session_data['id'];
		$data['content_status']     = "Active";
		$data['creation_date']      = date('Y-m-d H:i:s');

		$data  =  $this->cons_model->save_aid($data);

		if($data)
		  	$msg = $this->cons_model->email_parts_content();
		else
			$msg = array("msg" => 'Error in saving construction aid! Please Try again.');

		$jsonstring = json_encode($msg);
		echo $jsonstring;
	}
	
	public function pro_data()
	{
		/**
		 *  Add new conctruction Aid
		*/
		$i_id	          = $this->input->post('selectbx_id');
		$entids	      = $this->input->post('entids');
		
		$product_details = get_quote_details_in_entry($entids);
		$quotes          = $product_details['quotes'];
		foreach($quotes as $key => $qot)
		{
			if ( $qot['inventory_id'] === $i_id )
			{
        
			}
			else
			{
				unset($quotes[$key]); 
			}
		}
		 
		$productName     = getProductInfo($i_id); 
		$supplier_id     = $productName[0]->supplier_id;
		$suplierid       = rtrim($supplier_id, ',')	;
		$supplier_ar     = explode(',', $supplier_id);
		$supplier_ari    = array_filter($supplier_ar);
		$sup_name        = array();
		$supplier_info1   = array();
		foreach($supplier_ari as $sp_id)
		{
			$supplier_info = get_user_details($sp_id);
			array_push($supplier_info1,$supplier_info);
		}
		 
		$aar             = array_merge($quotes, $productName);
		 
		$jsonstring      = json_encode($aar);
		echo $jsonstring;
	}
	
	public function add_email_categories()
	{
		/**
		 *  Add new conctruction Aid
		*/
		//$category_name              = $this->input->post('Cat_name');
		$data['category_name']	    = $this->input->post('Cat_name');
		$data['category_status']	= 'Active';
		$session_data 		        = $this->session->userdata('logged_in');
		$data  =  $this->cons_model->save_cat($data);
		$data1['cat'] = $this->cons_model->email_parts_category();
		$data1['con'] = $this->cons_model->email_parts_content();
		$jsonstring = json_encode($data1);
		echo $jsonstring;
	}
	

	public function send_email()
	{
		/**
		* send Email to user
		*/
		$time = $this->session->userdata('time');
		if(empty($time))
		{
			$time = get_systems_timezone();	
		}
			
		$session_data = $this->session->userdata('logged_in');
		$user_type    = $session_data['usertype'];
		$user_details = get_user_details($session_data['id']);
		$utype        = get_user_type($user_type);
		$ustype       = $utype->type_name ;
		$ustype       = preg_replace('/\s+/', '', $ustype);
		$ustype       = strtolower($ustype);
		$uid          = $session_data['id'];
		$db           = $this->session->userdata('database_name');
	  
		if($ustype == "businessaccountmanager")
		{
			$filees = "e-mails/ceocamer_cam_crm";
		}
		elseif($ustype == "businessaccount")
		{
			$filees = "e-mails/".$db;
		}
		else
		{
			if($db != "")
			{
				$filees = "e-mails/".$db."/".$uid;
			}
			else
			{
				$filees = "e-mails/ceocamer_cam_crm/".$uid; 
			}
		}	
		$chekCommand 			= $this->input->post('replyOnly');
		if($chekCommand == 'replyOnly')
		{
			die('reply comnig');
		}
		$template['content']    = $this->input->post('content');
		$to_email      			= $this->input->post('email');
		$subject       		  	= $this->input->post('subject');
		$mail_cc				= $this->input->post('cc_arr');
		$mail_bcc				= $this->input->post('bcc_arr');
		$entry_id       		= $this->input->post('entry_id');
		$action_id      		= $this->input->post('action_id');
		$notes		    		= $this->input->post('notes');
		$actiion_id     		= $this->input->post('actiion_id');
		$send_again_file		= $this->input->post('send_again_file');
		$email_sender 			= $this->input->post('email_sender');
		
		if(empty($notes) || $notes == "<p><br></p>")
		{
			$notes = "";
		}
		$attachments     = $this->input->post('attachment');
		$source          = $this->input->post('source');
		$action_status_email   = $this->input->post('action_status');
		$data            = array();

		//load email liberary
		$this->load->library('email');
		//configure mail setting
		$config = Array(
			'protocol'  => 'sendmail',
			'mailpath'  => '/usr/sbin/sendmail',
			'charset'   => 'iso-8859-1',
			'mailtype'  => 'html',
			'Content-Transfer-Encoding'=> 'base64',
			'_encoding' =>'base64',
			'_bit_depths' => '7bit', '8bit', 'base64'
		);
		   
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");
		$outemail = $this->accounts_model->systems_outdata();
		if(!empty($outemail))
		{
			foreach($outemail as $outemails)
			{
				$outemailss = $outemails->setting_value;	
			}
		}
		else
		{
			$outemailss = "crm@camsaurus.com";	
		}
		$this->email->from($outemailss);
		$this->email->to($to_email);
		$this->email->cc($mail_cc);
		$this->email->bcc($mail_bcc);
		
		$this->email->subject($subject);
		
		$body =   $this->load->view('mail_templates/default',$template,TRUE);
		//die($body);
		$this->email->message($body);
		$attached = "\nE-mail Attachments:\n";

		if(is_array($attachments))
		{
			foreach($attachments as $attachment)
			{
				$this->email->attach('./uploads/'.$attachment);
				$att_url   	 = base_url().'uploads/'.$attachment;
				$attached 	.= "<span class='attachment-name text-primary' data-url='".$att_url."' data-target='#attachment_modal' data-toggle='modal'>".$attachment."</span>\n";
			}
		}
		
		if($this->email->send()) 
		{
			//mail sent now save the action record
			$data['entry_id'] 		    = 	$entry_id;
			$data['action_source'] 	    = 	$source;
			$data['action_direction'] 	= 	"Outgoing";
			$data['action_status'] 	    = 	"Completed";
			$data['action_type'] 		= 	$action_id;
			$data['action_content'] 	= 	$template['content'].'@_%'.str_replace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $subject); //preg_replace('/(:\/\/|/\{1})/', '_', $subject);
			$data['action_notes'] 	    = 	$notes;
			$data['date'] 	            = 	$time;
			$data['action_schedule'] 	= 	$time;
			$data['action_author'] 	    = 	$this->user_id;
			$conclude                   =   $this->input->post('concludes');

			$head               		= get_headings_id($entry_id);
			$heading              		= $head[0]['heading_assignment'];
			$heading              		= rtrim($heading,", ");
			$heading              		= explode(',',$heading);
			if(!empty($conclude))
			{
				if (($key = array_search('2', $heading)) == false) 
				{
					array_push($heading,2);
					if (($key = array_search('1', $heading)) !== false) 
					{
						unset($heading[$key]);
					}
					$data1['heading_assignment'] = implode(',',$heading);
					$this->entry_model->update_heading_assignment($entry_id, $data1);
				}
			}
			if($action_status_email == 'ActionComplete')
			{
				$action = $this->action_model->update_action_data($action_status_email,$actiion_id);
			}
			if (($key = array_search('3', $heading)) !== false) 
			{
				unset($heading[$key]);
			}
			$data1['heading_assignment'] = implode(',',$heading);
			$this->entry_model->update_heading_assignment($entry_id, $data1);

			$action = $this->action_model->save_action($data);

			if($action !== false)
			{
				// save mail on server //
				$date       =  'Date: '.$time;
				$mailname   =  $entry_id."_".$action."_".$time;
				//mail logs 
				if (is_array($to_email))
				{
					$mailto     =  implode(", ",$to_email)."<br>";
				}
				else
				{
					$mailto = $to_email;
				}
				$Cc_mails = "";
				if(is_array($mail_cc))
				{
					$Cc_mails = implode(", ",$mail_cc)."<br>";
				}
				$Bcc_mails = "";
				if(is_array($mail_bcc))
				{
					$Bcc_mails = implode(", ",$mail_bcc)."<br>";
				}
				
				$notes  	=  "Notes: ".$notes;  //ExclusiveLineBreak is a custom line break to use instead of \n to save in the file
				// It saves from ambiguity
				/* testcase using "\n" instead of ExclusiveLineBreak spoils the email in email_threads page
					  (for debugging purposes) SID : aoFnmXQkp8 UID : 40477 EmailID : scalesplus.cameron@gmail.com
					   */
				$saved_mail =  $mailto."ExclusiveLineBreak".$date."ExclusiveLineBreakSubject: ".$subject."ExclusiveLineBreak".$body."ExclusiveLineBreak".$notes."ExclusiveLineBreak".$attached."ExclusiveLineBreakCc: ".$Cc_mails."ExclusiveLineBreakBcc: ".$Bcc_mails."ExclusiveLineBreakFrom: ".$email_sender;
				
				$dir 		=  $filees."/"; // Full Path
				$myfile 	=  file_put_contents($dir.$mailname.".txt", $saved_mail.PHP_EOL , FILE_APPEND);
			
				$this->entry_model->update_entry_status($entry_id);
				echo "Mail Sent & Action Record Saved";
			}
			else
			{
				echo "Mail Sent But Error in Saving Action Record";
			}
		}
		else 
		{
			echo "Error in sending email please try again";
		}

	 
	}
	//END OF SEND MAIL FUNCTION
	public function schedule($entryId = NULL, $actionId = NULL)
	{
		/**
		 * schedule next action
		 */
		$header['title']     =  'Schedule Next Action';
		$this->load->view('templates/header', $header);

		$data['action']		 = 	$this->entry_model->action_types();
		$data['entry_id']	 =  $entryId;
		$data['action_id']	 =  $actionId;
		
		$this->load->view('next_action', $data);
	}

	public function save_next_action()
	{
		/**
		*  save the next scheduled action
		*/
		$time = $this->session->userdata('time');
		$notess = $this->input->post('notes');
		if(empty($notess))
		{
			$notess = "";
		}
		$data['entry_id'] 		    = 	$this->input->post('entryId');
		$data['action_type']		=   $this->input->post('actionId');
		$data['action_source'] 		= 	$this->input->post('source');
		$data['action_direction'] 	= 	"Outgoing"; 	// to be confirmed
		$data['action_status'] 		= 	"Scheduled";
		$data['action_notes'] 	    = 	$notess;
		$data['action_content'] 	= 	'';
		$data['date'] 	    		= 	$time;
		$data['action_schedule'] 	= 	$this->input->post('date');
		$data['action_author'] 		= 	$this->user_id;

		$action = $this->action_model->save_action($data);
		$act    = $this->entry_model->update_entry_status($data['entry_id']);

		if($act !== false)
			echo "true";
		else
			echo "false";
	}
   
    public function getThreadArray()
	{
		
		//$entry_id = '29'; 
		//$action_id = '587'; 
		$entry_id = $this->input->post('entryId');
		$action_id = $this->input->post('actionId');
		
		$action_details = NULL;
		
		$session_data = $this->session->userdata('logged_in'); 
		$user_type    = $session_data['usertype']; 
		$user_details = get_user_details($session_data['id']); 
		$utype        = get_user_type($user_type);
		$ustype       = $utype->type_name ;
		$ustype       = preg_replace('/\s+/', '', $ustype);
		$ustype       = strtolower($ustype);
		$uid          =  $session_data['id'];
		$db           = $this->session->userdata('database_name');
		$a_data       = $this->action_model->get_action_author($entry_id); 
		if($ustype == "businessaccountmanager")
		{
			$filees = "e-mails/ceocamer_cam_crm";
		}
		elseif($ustype == "businessaccount"){
			$filees = "e-mails/".$db;
		}
		else
		{
			if($db != "")
			{
				$filees = "e-mails/".$db."/".$uid;
			}
			else
			{
				$filees = "e-mails/ceocamer_cam_crm/".$uid; 
			}
		}	
	  
		$filees_business_log_stf = "e-mails/".$db;
		$filees_stf_log_business = "e-mails/".$db."/".$action_details;
		$data       = array();
		$email_data = array();
		
		$emails = $this->action_model->get_email_by_entry($entry_id, $filees,$filees_business_log_stf,$filees_stf_log_business);
		
		if(!empty($emails))
		{
			sort($emails);
		}
	
		if($emails != false)
		{
			$data['folderpath1'] = $filees;
		
			foreach($emails as $email)
			{	
				foreach($a_data as $ad)
				{
					if(file_exists ($filees_business_log_stf."/".$ad['action_author'].'/'.$email))
					{
						$fileName = $filees_business_log_stf."/".$ad['action_author'].'/'.$email;
						$filees   = $filees_business_log_stf."/".$ad['action_author'];
						$data['folderpath1'] = $filees;
					}
				}
				if(file_exists ($filees.'/'.$email))
				{	
					$fileName = $filees.'/'.$email;
				}
				elseif(file_exists ($filees_business_log_stf.'/'.$email))
				{
					$fileName = $filees_business_log_stf.'/'.$email;
					$filees   = $filees_business_log_stf;	
				}
				else
				{
					$fileName = $filees_stf_log_business.'/'.$email;	
					$filees   = $filees_stf_log_business;	
				}
   
				$open     = fopen($fileName, "r");
				$openMail = file_get_contents($fileName);
				
				if($open)
				{
					$whole_email = explode("ExclusiveLineBreak",$openMail);

					$email_data['to_email']  	= $whole_email[0];
					$email_data['date'] 		= $whole_email[1];
					$email_data['subject'] 		= $whole_email[2];
					$email_data['message']		= "";
					$file_name 					= explode('_', $email);
					$email_data['action_id'] 	= $file_name[1];
					$email_data['filename'] 	= $email;
					$emails_data[$email] 		= $email_data;
					$data['folderpath']         = $filees;

				} 
				else
				{
					// error opening file
					echo "error opening file";
				}
			}
			$data['emails'] = $emails_data;
			$data['folderpath'] = $filees;
		} 
		else
		{
			$this->session->set_flashdata('no_mails', '<div class="alert alert-info">No E-mails to view'.$this->closeAlert.'</div>');
			$data['emails'] = '';
		}

		if($action_id !== NULL)
		{
		   $data['actionId']   = $action_id;
		   $data['folderpath'] = $filees;
		}
		//print_r($data);
		$Result = [];
		if(!empty($data['emails']))
		{
			
			foreach($data['emails'] as $single)
			{
				if($data['actionId'] == $single['action_id'])
				//if(array_search($data['actionId'] , $single))
				{
					$Result =  $single;
					$Result['folderpath'] = $data['folderpath'];
				}
			}
			$resonseArray = json_encode($Result);
			echo $resonseArray;
			
		}
		else
		{
			echo 'No Emails';
		}
	}
   
   
	public function thread($entry_id=NULL, $action_id=NULL,$action_details=NULL)
	{
		/**
		 * Email Threads
		 * shows the email threads of the action
		 */
		$session_data = $this->session->userdata('logged_in'); 
		$user_type    = $session_data['usertype']; 
		$user_details = get_user_details($session_data['id']); 
		$utype        = get_user_type($user_type);
		$ustype       = $utype->type_name ;
		$ustype       = preg_replace('/\s+/', '', $ustype);
		$ustype       = strtolower($ustype);
		$uid          =  $session_data['id'];
		$db           = $this->session->userdata('database_name');
		$a_data       = $this->action_model->get_action_author($entry_id); 
		if($ustype == "businessaccountmanager")
		{
			$filees = "e-mails/ceocamer_cam_crm";
		}
		elseif($ustype == "businessaccount"){
			$filees = "e-mails/".$db;
		}
		else
		{
			if($db != "")
			{
				$filees = "e-mails/".$db."/".$uid;
			}
			else
			{
				$filees = "e-mails/ceocamer_cam_crm/".$uid; 
			}
		}	
	  
		$filees_business_log_stf = "e-mails/".$db;
		$filees_stf_log_business = "e-mails/".$db."/".$action_details;
	 
		$header['title'] = 'E-mail threads';
		$this->load->view('templates/header',$header);
		$data       = array();
		$email_data = array();
		
		$emails = $this->action_model->get_email_by_entry($entry_id, $filees,$filees_business_log_stf,$filees_stf_log_business);
		if(!empty($emails))
		{
			sort($emails);
		}
	
		if($emails != false)
		{
			$data['folderpath1'] = $filees;
		
			foreach($emails as $email)
			{	
				foreach($a_data as $ad)
				{
					if(file_exists ($filees_business_log_stf."/".$ad['action_author'].'/'.$email))
					{
						$fileName = $filees_business_log_stf."/".$ad['action_author'].'/'.$email;
						$filees   = $filees_business_log_stf."/".$ad['action_author'];
						$data['folderpath1'] = $filees;
					}
				}
				if(file_exists ($filees.'/'.$email))
				{	
					$fileName = $filees.'/'.$email;
				}
				elseif(file_exists ($filees_business_log_stf.'/'.$email))
				{
					$fileName = $filees_business_log_stf.'/'.$email;
					$filees   = $filees_business_log_stf;	
				}
				else
				{
					$fileName = $filees_stf_log_business.'/'.$email;	
					$filees   = $filees_stf_log_business;	
				}
   
				$open     = fopen($fileName, "r");
				$openMail = file_get_contents($fileName);
				
				if($open)
				{
					$whole_email = explode("ExclusiveLineBreak",$openMail);

					$email_data['to_email']  	= $whole_email[0];
					$email_data['date'] 		= $whole_email[1];
					$email_data['subject'] 		= $whole_email[2];
					$email_data['message']		= "";
					$file_name 					= explode('_', $email);
					$email_data['action_id'] 	= $file_name[1];
					$email_data['filename'] 	= $email;
					$emails_data[$email] 		= $email_data;
					$data['folderpath']         = $filees;

				} 
				else
				{
					// error opening file
					echo "error opening file";
				}
			}
			$data['emails'] = $emails_data;
			$data['folderpath'] = $filees;
		} 
		else
		{
			$this->session->set_flashdata('no_mails', '<div class="alert alert-info">No E-mails to view'.$this->closeAlert.'</div>');
			$data['emails'] = '';
		}

		if($action_id !== NULL)
		{
		   $data['actionId']   = $action_id;
		   $data['folderpath'] = $filees;
		}
		$this->load->view('email/email_threads', $data);
	}

	/*----------------incoming email----------------------*/


	public function incoming_email()
	{
		$time = $this->session->userdata('time');
		if(empty($time))
		{
			$time = get_systems_timezone();	
		}
		
		$session_data = $this->session->userdata('logged_in'); 
		$user_type    = $session_data['usertype']; 
		$user_details = get_user_details($session_data['id']); 
		$utype        = get_user_type($user_type);
		$ustype       = $utype->type_name ;
		$ustype       = preg_replace('/\s+/', '', $ustype);
		$ustype       = strtolower($ustype);
		$uid          = $session_data['id'];
		$db           = $this->session->userdata('database_name');
  
		$incoming_email = $this->accounts_model->systems_datas($id);
  
		foreach($incoming_email as $incoming_emails)
		{
			$incoming_emailss = $incoming_emails->incoming_email;
		}
		$incoming_sys_email = $this->accounts_model->systems_data();
	    foreach($incoming_sys_email as $incoming_emails)
		{
			$incoming_sys_emails = $incoming_emails->setting_value;
		}
		if(!empty($incoming_emailss))
		{
			$username = $incoming_emailss;
		}
		else
		{
			$username = $incoming_sys_emails;
		}
		if($ustype == "businessaccountmanager")
		{
			$filees = "e-mails/ceocamer_cam_crm";
			$pathss = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com";
		}
		elseif($ustype == "businessaccount")
		{
			$filees = "e-mails/".$db;
			$pathss = "email_files/".$db."/".$username;
		}
		else
		{
			if($db != "")
			{
				$filees = "e-mails/".$db."/".$uid;
				$pathss = "email_files/".$db."/".$username;
			}
			else
			{
				$filees = "e-mails/ceocamer_cam_crm/".$uid;
				$pathss = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com"; 
			}
		}		 
		$edate                = $this->input->post('edate');
		$content              = $this->input->post('content');
		$template['content']  = $content;
		$to_email             = $this->input->post('email');
		$subject              = $this->input->post('subject');
		$entry_id             = $this->input->post('entry_id');
		$head                 = get_headings_id($entry_id);
		$heading              = $head[0]['heading_assignment'];
		$heading              = rtrim($heading,", ");
		$heading              = explode(',',$heading);

		if (($key = array_search('3', $heading)) == false) 
		{
			array_push($heading,3);
			$data['heading_assignment'] = implode(',',$heading);
			$this->entry_model->update_heading_assignment($entry_id, $data);
		}
		$notes		          = $this->input->post('notes');
		$source               = $this->input->post('source');
		$body                 = $template['content']; //html_entity_decode($template['content'], ENT_COMPAT, 'ISO-8859-1'); //sunil added the html_entity_decode , april 3,2019
		$filename             = $this->input->post('file');
		$folder               =  $pathss.'/';
		$file                 = $folder.$filename;
		$file1                = basename($file);
		$attachments          = $this->input->post('attachment');
        $attachments          = explode(',',$attachments);
                
				$date = "process_";
				
				if (file_exists($file)) 
				{
					 $file_name = $folder.$date.$file1;
					 rename($file, $file_name);
					
				}else {
					echo "no file";
				}
				
		if(is_array($attachments)){
		    foreach($attachments as $attachment){
				$att_name = substr($attachment, strrpos($attachment, '/') + 1);
				if(!empty($att_name)){ 
				$attached 	.= "<span class='attachment-name text-primary' data-url='".$attachment."' data-target='#attachment_modal' data-toggle='modal'>".$att_name."</span>\n";
			}
		  }
		}
		
		
		$data                       =   array();
		$data['entry_id'] 		    = 	$entry_id;
		$data['action_source'] 	    = 	$source;
		$data['action_direction'] 	= 	"Incoming";
		$data['action_status'] 	    = 	"Completed";
		$data['action_type'] 		= 	'2';
		$data['action_content'] 	= 	$template['content'].'@_%'.$subject;
		$data['action_notes'] 	    = 	$notes;
		$data['date'] 	            = 	$time;
		$data['action_schedule'] 	= 	$time;
		$data['action_author'] 	    = 	$this->user_id;

		$action = $this->action_model->save_action_data($data);
		if($action !== false)
		{
			// save mail on server //
			$email_time = date("m/d/Y h:i:s A T",$edate);
			$date       =  'Date: '.$email_time;
			$mailname   =  $entry_id."_".$action."_".$time;
			$mailto 	=  "From: ".$to_email;
			$notes  	=  "ExclusiveLineBreakNotes: ".$notes;
			$saved_mail =  $mailto."ExclusiveLineBreak".$date."ExclusiveLineBreakSubject: ".$subject."ExclusiveLineBreak".$body; // $body.$content.$notes;
			$dir 		=  $filees."/"; // Full Path
			$myfile 	=  file_put_contents($dir.$mailname.".txt", $saved_mail.PHP_EOL , FILE_APPEND);
			$update     = $this->entry_model->update_entry_status($entry_id);

		}

	} 
	////////////code for note action///////////

	public function add_note()
	{
		/**
		 *  send Email to user
		*/
		$session_data = $this->session->userdata('logged_in'); 
		$time         = $this->session->userdata('time');
		if(empty($time))
		{
			$time = get_systems_timezone();	
		}
		$user_type    = $session_data['usertype']; 
		$user_details = get_user_details($session_data['id']); 
		$utype        = get_user_type($user_type);
		$ustype       = $utype->type_name ;
		$ustype       = preg_replace('/\s+/', '', $ustype);
		$ustype       = strtolower($ustype);
		$uid          =  $session_data['id'];
		$db           = $this->session->userdata('database_name');
 
		if($ustype == "businessaccountmanager")
		{
			$filees = "e-notes/ceocamer_cam_crm";
		}
		elseif($ustype == "businessaccount")
		{
			$filees = "e-notes/".$db;
		}
		else
		{
			if($db != "")
			{
				$filees = "e-notes/".$db."/".$uid;
			}
			else
			{
				$filees = "e-notes/ceocamer_cam_crm/".$uid; 
			}
		}	
	
		$entry_id       = $this->input->post('entry_id');
		$action_id      = $this->input->post('action_id');
		$notes		     = $this->input->post('notes');
		$notes          = htmlentities($notes);
		$source         = $this->input->post('source');
		$schedual       = $this->input->post('schedule');
		$conclude       = $this->input->post('concludes');
		$actiion_id     = $this->input->post('actiion_id');
		$data           = array();
		if($notes) 
		{
			//mail sent now save the action record
			$data['entry_id'] 		    = 	$entry_id;
			$data['action_source'] 	    = 	$source;
			$data['action_direction'] 	= 	"Internal";
			$data['action_status'] 	    = 	"Completed";
			$data['action_type'] 		= 	$action_id;
			$data['action_content'] 	= 	$notes;
			$data['action_notes'] 	    = 	$notes;
			$data['date'] 	            = 	$time;
			$data['action_schedule'] 	= 	$time;
			$data['action_author'] 	    = 	$this->user_id;
			if(!empty($schedual))
			{
				$action = $this->action_model->update_action_data($schedual,$actiion_id);	
			}
			$head                 = get_headings_id($entry_id);
			$heading              = $head[0]['heading_assignment'];
			$heading              = rtrim($heading,", ");
			$heading              = explode(',',$heading);
			if(!empty($conclude))
			{
				if (($key = array_search('2', $heading)) == false) 
				{
					array_push($heading,2);
					if (($key = array_search('1', $heading)) !== false) 
					{
						unset($heading[$key]);
					}
					$data1['heading_assignment'] = implode(',',$heading);
					$this->entry_model->update_heading_assignment($entry_id, $data1);
				}
			}
			if (($key = array_search('3', $heading)) !== false) 
			{
				unset($heading[$key]);
			}
			$data1['heading_assignment'] = implode(',',$heading);
		    $this->entry_model->update_heading_assignment($entry_id, $data1);
			$action = $this->action_model->save_action($data);

			if($action !== false)
			{
				// save mail on server //
				$date       =  'Date: '.$time;
				$mailname   =  $entry_id."_".$action."_".$time;
				$notes  	=  $notes;
				$saved_mail =  $entry_id."ExclusiveLineBreak".$date."ExclusiveLineBreak".$notes;
				$dir 		=  $filees."/"; // Full Path
				$myfile 	=  file_put_contents($dir.$mailname.".txt", $saved_mail.PHP_EOL , FILE_APPEND);
				
				$this->entry_model->update_entry_status($entry_id);
				echo "Action Record Saved";
			}
			else
			{
				echo "Error To Saving Note In Action Record";
			}
		}
		else
		{
		 	echo "There is an error to add note";
		}
	}
	
	function instructions()
	{
		$header['title']     =  'Instructions';
		$this->load->view('templates/header', $header);
		$this->load->view('email/instruction');
	}
	function system_improvement_tool()
	{
		$header['title']     =  'Instructions';
		$this->load->view('templates/header', $header);
		$session_data             = $this->session->userdata('logged_in'); 
		$data['action']           =  $this->input->get('request');
		$data['user_information'] = $this->accounts_model->get_user_information($session_data['id']);
		$data['email']            = $this->accounts_model->systems_data();
		$data['user_business_id'] = $this->accounts_model->get_business_id($session_data['id']);
		$this->load->view('email/system_improvement_view',$data);

	}
	function send_Bug_email()
	{
		$email         = $this->input->post('email'); 
		$bug_severity  = $this->input->post('bug_severity'); 
		$message_pri   = $this->input->post('message_pri'); 
		$debug_info    = $this->input->post('debug_info'); 
		$other_queries = $this->input->post('other_queries'); 
		$attachments   = $this->input->post('Content'); 
		$identy        = $this->input->post('identy');
		$feature_req   = $this->input->post('feature_req');
		$name          = $this->input->post('name');

		$hidden_us_detail_name  = $this->input->post('namess_input');
		$hidden_us_detail_email = $this->input->post('user_email_input');
		$hidden_us_detail_cmpny = $this->input->post('company_input');
		$hidden_us_detail_phone = $this->input->post('phone_input');
		$hidden_us_Triggered    = $this->input->post('Triggered');
		$config = Array(
            'protocol'  => 'sendmail',
			'mailpath'  => '/usr/sbin/sendmail',
			'charset'   => 'iso-8859-1',
			'mailtype'  => 'html'
		);
       
		//Load email library
        $this->email->initialize($config);
		$this->email->set_newline("\r\n");
		if(empty($identy))
		{
			$z = '<p><strong style="color:#737373;">Email: </strong> <a href="mailto:'.$email.'">'.$email.'</a></p>
			<p><strong>bug severity: </strong><span>'.$bug_severity.'</span></p>
			<p><strong>How often does it occur?: </strong><span>'.$message_pri.'</span> </p>
			<p><strong>what you were doing before the bug occur?: </strong><span>'.$debug_info.'</span></p>
			<p><strong>Any further questions, comments or issues: </strong><span>'.$other_queries.'</span> </p>

			<p><strong> User Name: </strong> </p>
			'.$hidden_us_detail_name.'
			<p><strong> User Email Address: </strong> </p>
			'.$hidden_us_detail_email.' 
			<p><strong> Company: </strong> </p>
			'.$hidden_us_detail_cmpny.'
			<p><strong> Phone Number: </strong> </p>
			'.$hidden_us_detail_phone.'
			<p><strong> Triggered From The Page*: </strong> </p>
			'.$hidden_us_Triggered.'';
		}
		else
		{
			$z = '<p><strong>Name: </strong><span>'.$name.'</span></p>
			<p><strong style="color:#737373;">Email: </strong> <a href="mailto:'.$email.'">'.$email.'</a></p>
			<p><strong>The feature request is for:  </strong><span>'.$feature_req.'</span> </p>
			<p><strong>Detailed description of the feature: </strong><span>'.$attachments.'</span></p>

			<p><strong> User Name: </strong> </p>
			'.$hidden_us_detail_name.'

			<p><strong> User Email Address: </strong> </p>
			'.$hidden_us_detail_email.' 
			<p><strong> Company: </strong> </p>
			'.$hidden_us_detail_cmpny.'
			<p><strong> Phone Number: </strong> </p>
			'.$hidden_us_detail_phone.' 
			<p><strong> Triggered From The Page*: </strong> </p>
			'.$hidden_us_Triggered.'';
		}

		$this->email->from($email);
		$this->email->to('cameron.gibbs@businessactioncomplete.com');
		if(empty($identy))
		{
			$this->email->subject('Bug Report');
		}
		else
		{
			$this->email->subject('Feature Request');
		}
		$this->email->message($z);
		$attached = "\nE-mail Attachments:\n";

		if(is_array($attachments))
		{
			foreach($attachments as $attachment)
			{
				$this->email->attach('./uploads/'.$attachment);
				$att_url   	 = base_url().'uploads/'.$attachment;
				$attached 	.= "<span class='attachment-name text-primary' data-url='".$att_url."' data-toggle='modal'>".$attachment."</span>\n";
			}
		}

		if($this->email->send())
		{		  
			$this->session->set_flashdata('email_sent',"<div class='alert alert-success'>Email sent successfully .".$this->closeAlert.'</div>'); 
		}
		else 
		{
			$this->session->set_flashdata("email_sent","Error in sending Email."); 
		}
	}
	
	public function updateEmailCategories()
	{
		$cat_id 				= $this->input->post('cat_id');
		$updates_cat			= $this->input->post('updates_cat');
		
		$Response = $this->cons_model->updateCategoryName($updates_cat, $cat_id);
		if($Response)
		{
			$responseArray = $this->cons_model->email_parts_category();
			$jsonstring = json_encode($responseArray);
			echo $jsonstring;
		}
	}
	
	function get_email_parts_content()
	{
		$id = $this->input->post('category_id');
		$responseArray =  $this->cons_model->get_email_parts_content_model($id);
		$jsonstring = json_encode($responseArray);
		echo $jsonstring;
	}
	
	public function updateEmailPart()
	{
		
		$cat_id				    = $this->input->post('template_cat_part');
		$part_id				= $this->input->post('template_part_part');
		$updated_part_title		= $this->input->post('updated_edit_parts');
		$updates_content        = $this->input->post('template_part_Content');
		$Response = $this->cons_model->updatePart($updated_part_title, $updates_content, $part_id);
		if($Response)
		{
			$responseArray = $this->cons_model->get_email_parts_content_model($cat_id);
			$jsonstring = json_encode($responseArray);
			echo $jsonstring;
		}
	}
	
	public function deleteCategories()
	{
		$cat_id = $this->input->post('cat_id');
		if($this->cons_model->delCategories($cat_id))
		{
			$responseArray = $this->cons_model->email_parts_category();
			$jsonstring = json_encode($responseArray);
			echo $jsonstring;
		}
	}
	
	public function deletePart()
	{
		$part_id = $this->input->post('part_id'); 
		$cat_id = $this->input->post('cat_id');
		
		$Response = $this->cons_model->delPart($part_id);
		if($Response)
		{
			$responseArray = $this->cons_model->get_email_parts_content_model($cat_id);
			$jsonstring = json_encode($responseArray);
			echo $jsonstring;
		}
	}
	
	public function movePart()
	{
		$part_id = $this->input->post('part_id');
		$cat_id = $this->input->post('cat_id');
		$primary_cat_id = $this->input->post('primary_cat_id');
		
		if($this->cons_model->movePart($cat_id, $part_id))
		{
			$responseArray = $this->cons_model->get_email_parts_content_model($primary_cat_id);
			$jsonstring = json_encode($responseArray);
			echo $jsonstring;
		}
	}
	public function getAllActiveCat()
	{
		$responseArray = $this->cons_model->email_parts_category();
		$jsonstring    = json_encode($responseArray);
		echo $jsonstring;
	}
	
	//GET ALL USER OR SUPPLIER EMAIL IDS
	public function getAllUserEmailForAddEmail()
	{
		$entry_user_id = $this->input->post('userIds');
		$test = array('1','32');
		$result = [];
		
		foreach($test as $key => $single_entry_user_id)
		{
			$this->db->select('*');
			$this->db->from('user_information');
			$this->db->where('user_id', $single_entry_user_id );
			$this->db->where("information_type like 'email_%'");
			$query = $this->db->get();
			if($query->num_rows() != 0)
			{
				$result[$key] =$query->result_array();
			}
		}
		
		$output_array =[];
		for ($i = 0; $i < count($result); $i++) 
		{
			for ($j = 0; $j < count($result[$i]); $j++) 
			{
				$output_array[] = $result[$i][$j];
			}
		}
		$output_array = json_encode($output_array);
		echo $output_array;
		//echo'<pre>';
		//	print_r($output_array);
	}
	
	public function get_Involved_Users_Emails_InEntry()
	{
		$users_ids = $this->input->post('Array');
		//$users_ids = array('3');
		
		foreach($users_ids as $Single_user_id)
		{
			$Result    =	get_user_emails($Single_user_id);
		}
		
		
		$responseArray = json_encode($Result, JSON_FORCE_OBJECT);
		echo $responseArray;
	}
}
