<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Email extends CI_Controller {
	/**
	 * Email controller
	 *
	 * @extends CI_Controller
	 */
	private $user_id = null;
 	public function __construct(){

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
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$this->entry_model->db = $this->load->database($config_app,TRUE);
			$this->cons_model->db = $this->load->database($config_app,TRUE);
			$this->action_model->db = $this->load->database($config_app,TRUE);
		}
		$this->session_data 	= $this->session->userdata('logged_in');
	  	$this->user_id    		= $this->session_data['id'];

	  	if(is_logged_in() === false){
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
		
		}
	}
	public function index($entry, $action, $ent_id=NULL){
		/**
		* email action for entry
		*/
		error_reporting(E_ERROR | E_PARSE);
		$this->load->helper('directory');
		$header_data['title'] =  "Email";
		$this->load->view('templates/header', $header_data);
		 $session_data = $this->session->userdata('logged_in');
 $user_type = $session_data['usertype'];
 $user_details = get_user_details($session_data['id']);
 $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
           $uid =  $session_data['id'];
            $db = $this->session->userdata('database_name');
			if($ustype == "businessaccountmanager"){
		$filees = "e-mails/ceocamer_cam_crm";
	 }
	 elseif($ustype == "businessaccount"){
	 $filees = "e-mails/".$db;
	
	 }else{
		 if($db != ""){
	 $filees = "e-mails/".$db."/".$uid;
		 }else{
			 $filees = "e-mails/ceocamer_cam_crm/".$uid; 
		 }
	 }		 
		
		$previous_mail = $this->action_model->get_previous_mail($entry, $filees);

		if($previous_mail != false)
		   $data['previous_mail'] = $this->action_model->get_mail_contents($previous_mail, $filees);

  		$data['entry_id'] 	    =  $entry;
		$data['action_id']      =  $action;
		$data['entry'] 		    =  $this->entry_model->entry_details($entry);
		$data['showAllEntries'] =  TRUE;
		$data['timezones']      =  $this->timezones->getTimezones();
		$data['entry_model']    =  $this->entry_model;
		$data['action']		    =  $this->entry_model->action_types();
		$data['parts_cate']     =  $this->cons_model->email_parts_category();
		$data['action_page']    =  TRUE;
		$data['attachments']    =  $this->load->view('email_attachments', NULL, TRUE);
		$data['countries'] 		= $this->currency_converter->getCountries();
		$data['session_currency'] = $this->session_data['currency'];
		$data['out_username'] = $this->accounts_model->ftch_outsystem_data();
		$data['out_email'] = $this->accounts_model->systems_outdata();
		$data['entry_count'] = $cont;
		$data['ent_id'] = $ent_id;
		$data['em_file'] 	    =  $previous_mail;
		//Load view of for adding new emails
		//$data['add_email'] = $this->load->view('accounts/add_email', NULL, TRUE);
		// load email views
		
		
		$this->load->view('email/email_view', $data);

	}

	public function emailparts(){
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

	public function emailpart_content(){
		/**
		 *  get Email Part Conatent (construction aids)
		*/
		$content_id = $this->input->post('content_id');
		$data  	  =  $this->cons_model->email_parts_by_id($content_id);
		$jsonstring = json_encode($data);
		echo $jsonstring;
	}

	public function add_email_aids(){
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
		  	$msg = array("msg" => 'Saved');
		else
			$msg = array("msg" => 'Error in saving construction aid! Please Try again.');

		$jsonstring = json_encode($msg);
		echo $jsonstring;
	}
	
	public function pro_data(){
		/**
		 *  Add new conctruction Aid
		*/
		  $i_id	          = $this->input->post('selectbx_id');
		  $entids	      = $this->input->post('entids');
		
		 $product_details = get_quote_details_in_entry($entids);
		 $quotes          = $product_details['quotes'];
		 foreach($quotes as $key => $qot)
   {
      if ( $qot['inventory_id'] === $i_id ){
        
   }else{
	   unset($quotes[$key]); 
   }}
		 
		 $productName     = getProductInfo($i_id); 
		 $supplier_id     = $productName[0]->supplier_id;
		 $suplierid       = rtrim($supplier_id, ',')	;
		 $supplier_ar     = explode(',', $supplier_id);
		 $supplier_ari    = array_filter($supplier_ar);
		 $sup_name        = array();
		 $supplier_info1   = array();
			foreach($supplier_ari as $sp_id){
			 $supplier_info = get_user_details($sp_id);
			array_push($supplier_info1,$supplier_info);
			}
		 
		 $aar             = array_merge($quotes, $productName);
		 
		 $jsonstring      = json_encode($aar);
		 echo $jsonstring;
	}
	
	public function add_email_categories(){
		/**
		 *  Add new conctruction Aid
		*/
		//$category_name              = $this->input->post('Cat_name');
		$data['category_name']	    = $this->input->post('Cat_name');
		$data['category_status']	= 'Active';
		$session_data 		        = $this->session->userdata('logged_in');
		$data  =  $this->cons_model->save_cat($data);
		$data1 = $this->cons_model->email_parts_category();
		//$category_name = array("category_name" => $category_name);
		$jsonstring = json_encode($data1);
		echo $jsonstring;
	}
	

	public function send_email(){
		/**
		 *  send Email to user
		*/
		 $time = $this->session->userdata('time');
		
		if(empty($time)){
	    $time = get_systems_timezone();	
		}
 $session_data = $this->session->userdata('logged_in');
 $user_type = $session_data['usertype'];
 $user_details = get_user_details($session_data['id']);
 $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
           $uid =  $session_data['id'];
            $db = $this->session->userdata('database_name');
			if($ustype == "businessaccountmanager"){
		$filees = "e-mails/ceocamer_cam_crm";
	 }
	 elseif($ustype == "businessaccount"){
	 $filees = "e-mails/".$db;
	
	 }else{
		 if($db != ""){
	 $filees = "e-mails/".$db."/".$uid;
		 }else{
			 $filees = "e-mails/ceocamer_cam_crm/".$uid; 
		 }
	 }		 
	 
		$template['content']  = $this->input->post('content');
		$to_email        =  $this->input->post('nwemails');
		if($to_email == 'N/A'){
		$to_email        = $this->input->post('email');
		}
		$subject         = $this->input->post('subject');
		$entry_id        = $this->input->post('entry_id');
		$action_id       = $this->input->post('action_id');
		$notes		     = $this->input->post('notes');
		$attachments     = $this->input->post('attachment');
		$source          = $this->input->post('source');
		$data = array();

		//configure mail setting
		$config = Array(
            'protocol'  => 'sendmail',
			'mailpath'  => '/usr/sbin/sendmail',
			'charset'   => 'iso-8859-1',
			'mailtype'  => 'html'
		);
       
	
		//Load email library
        $this->email->initialize($config);
		$this->email->set_newline("\r\n");
		$outemail = $this->accounts_model->systems_outdata();
		if(!empty($outemail)){
		foreach($outemail as $outemails){
		$outemailss = $outemails->setting_value;	
		  }
		 }else{
		$outemailss = "crm@camsaurus.com";	
		}
		$this->email->from($outemailss);
        $this->email->to($to_email);
        $this->email->subject($subject);
		$body = $this->load->view('mail_templates/default',$template,TRUE);
        $this->email->message($body);
		$attached = "\nE-mail Attachments:\n";

		if(is_array($attachments)){

			foreach($attachments as $attachment){
				$this->email->attach('./uploads/'.$attachment);
				$att_url   	= base_url().'uploads/'.$attachment;
				$attached 	.= "<span class='attachment-name text-primary' data-url='".$att_url."' data-toggle='modal'>".$attachment."</span>\n";
			}
		}
		


		if($this->email->send()) {
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

			$action = $this->action_model->save_action($data);

			if($action !== false){
				// save mail on server //
				$date       =  'Date: '.$time;
				$mailname   =  $entry_id."_".$action."_".$time;
				$mailto 	=  "To: ".$to_email;
				$notes  	=  "Notes: ".$notes;  //ExclusiveLineBreak is a custom line break to use instead of \n to save in the file
				// It saves from ambiguity
				/* testcase using "\n" instead of ExclusiveLineBreak spoils the email in email_threads page
					  (for debugging purposes) SID : aoFnmXQkp8 UID : 40477 EmailID : scalesplus.cameron@gmail.com
					   */
				$saved_mail =  $mailto."ExclusiveLineBreak".$date."ExclusiveLineBreakSubject: ".$subject."ExclusiveLineBreak".$body.$attached.$notes;
				$dir 		=  $filees."/"; // Full Path
				$myfile 	=  file_put_contents($dir.$mailname.".txt", $saved_mail.PHP_EOL , FILE_APPEND);
				$this->entry_model->update_entry_status($entry_id);
				echo "Mail Sent & Action Record Saved";

			}else
				echo "Mail Sent But Error in Saving Action Record";
		 }else
		  
		 	echo "Error in sending email please try again";
	}

	public function schedule($entryId = NULL, $actionId = NULL){
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

	public function save_next_action(){
		/**
		*  save the next scheduled action
		*/
		$time = $this->session->userdata('time');
		$data['entry_id'] 		    = 	$this->input->post('entryId');
		$data['action_type']		=   $this->input->post('actionId');
		$data['action_source'] 		= 	$this->input->post('source');
		$data['action_direction'] 	= 	"Outgoing"; 	// to be confirmed
		$data['action_status'] 		= 	"Scheduled";
		$data['action_notes'] 	    = 	$this->input->post('notes');
		$data['action_content'] 	= 	'';
		$data['date'] 	    		= 	$time;
		$data['action_schedule'] 	= 	$this->input->post('date');
		$data['action_author'] 		= 	$this->user_id;

		$action = $this->action_model->save_action($data);
		$this->entry_model->update_entry_status($data['entry_id']);

		if($action !== false)
			echo "true";
		else
			echo "false";
	}

	public function thread($entry_id=NULL, $action_id=NULL){
		/**
		 * Email Threads
		 * shows the email threads of the action
		 */
		  $session_data = $this->session->userdata('logged_in'); ?>
<?php $user_type = $session_data['usertype']; ?>
<?php $user_details = get_user_details($session_data['id']); ?>
<?php $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
           $uid =  $session_data['id'];
            $db = $this->session->userdata('database_name');
			if($ustype == "businessaccountmanager"){
		$filees = "e-mails/ceocamer_cam_crm";
	 }
	 elseif($ustype == "businessaccount"){
	 $filees = "e-mails/".$db;
	
	 }else{
		 if($db != ""){
	 $filees = "e-mails/".$db."/".$uid;
		 }else{
			 $filees = "e-mails/ceocamer_cam_crm/".$uid; 
		 }
	 }	
	
		$header['title'] = 'E-mail threads';
		$this->load->view('templates/header',$header);
		$data = array();
		$email_data = array();
		
		$emails = $this->action_model->get_email_by_entry($entry_id, $filees);

		if($emails != false){

			foreach($emails as $email){
				$fileName = $filees.'/'.$email;
				$open     = fopen($fileName, "r");

				if($open){
					// file opened
					$i = 0;

					while(($line = fgets($open)) !== false){
						$whole_email[$i] = $line;
						$i++;
					}
					$email_data['to_email']  	= $whole_email[0];
					$email_data['date'] 		= $whole_email[1];
					$email_data['subject'] 		= $whole_email[2];
					$email_data['message']		= "";
					$file_name 					= explode('_', $email);
					$email_data['action_id'] 	= $file_name[1];
					$email_data['filename'] 	= $email;
					$emails_data[$email] 		= $email_data;
					$data['folderpath'] = $filees;

				} else{
					// error opening file
					echo "error opening file";
				}
			}
			$data['emails'] = $emails_data;
			$data['folderpath'] = $filees;

		} else{
			$this->session->set_flashdata('no_mails', '<div class="alert alert-info">No E-mails to view'.$this->closeAlert.'</div>');
			$data['emails'] = '';
		}

		if($action_id !== NULL)
			$data['actionId'] = $action_id;
$data['folderpath'] = $filees;
		$this->load->view('email/email_threads', $data);
	}

/*----------------incoming email----------------------*/


public function incoming_email(){
	$time = $this->session->userdata('time');
		
		if(empty($time)){
	    $time = get_systems_timezone();	
		}
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

		        $content = $this->input->post('content');
				
			   	$template['content']  = $content;
		        $to_email             = $this->input->post('email');
		        $subject              = $this->input->post('subject');
		        $entry_id             = $this->input->post('entry_id');
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
				$notes		          = $this->input->post('notes');
				/*$attachments          = $this->input->post('mail_attach');
				$attachments_pdf          = $this->input->post('mail_attach_pdf');
				$attachments_doc          = $this->input->post('mail_attach_doc');*/
				$source               = $this->input->post('source');
				$body                 = $template['content']; //html_entity_decode($template['content'], ENT_COMPAT, 'ISO-8859-1'); //sunil added the html_entity_decode , april 3,2019
				 $filename             = $this->input->post('file');
			
				
				$folder               =  $pathss.'/';
				
				 $file                 = $folder.$filename;
				
				 $file1                = basename($file);
				
				$attachments           = "N/A";
				$date = "process_";
				if (file_exists($file)) {
					 $file_name = $folder.$date.$file1;
					
					 rename($file, $file_name);
					
					
				}else {
					echo "no file";
				}
				
				if(is_array($attachments)){

			foreach($attachments as $attachment){
				$this->email->attach('./uploads/'.$attachment);
				$att_url   	= base_url().'uploads/'.$attachment;
				$attached 	.= "<span class='attachment-name text-primary' data-url='".$att_url."' data-toggle='modal'>".$attachment."</span>\n";
			}
		}
		/*if(is_array($attachments_pdf)){

			foreach($attachments_pdf as $attachments_pdfs){
				$this->email->attach('./uploads/'.$attachments_pdfs);
				$att_url   	= base_url().'uploads/'.$attachments_pdfs;
				$attached_pdf 	.= "<span class='attachment-name text-primary' data-url='".$att_url."' data-toggle='modal'>".$attachments_pdfs."</span>\n";
			}
		}
		if(is_array($attachments_doc)){

			foreach($attachments_doc as $attachments_docs){
				$this->email->attach('./uploads/'.$attachments_docs);
				$att_url   	= base_url().'uploads/'.$attachments_docs;
				$attached_doc 	.= "<span class='attachment-name text-primary' data-url='".$att_url."' data-toggle='modal'>".$attachments_docs."</span>\n";
			}
		}
*/
			$data = array();

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
			if($action !== false){
				// save mail on server //
				$date       =  'Date: '.$time;
				$mailname   =  $entry_id."_".$action."_".$time;
				$mailto 	=  "From: ".$to_email;
				$notes  	=  "ExclusiveLineBreakNotes: ".$notes;
				$saved_mail =  $mailto."ExclusiveLineBreak".$date."ExclusiveLineBreakSubject: ".$subject."ExclusiveLineBreak".$body.$notes; // $body.$content.$notes;
				$dir 		=  $filees."/"; // Full Path
				$myfile 	=  file_put_contents($dir.$mailname.".txt", $saved_mail.PHP_EOL , FILE_APPEND);
				$update     = $this->entry_model->update_entry_status($entry_id);

				}


				}
////////////code for note action///////////

	public function add_note(){
		/**
		 *  send Email to user
		*/
		$session_data = $this->session->userdata('logged_in'); 
		$time = $this->session->userdata('time');
		if(empty($time)){
		$time = get_systems_timezone();	
		}?>
<?php $user_type = $session_data['usertype']; ?>
<?php $user_details = get_user_details($session_data['id']); ?>
<?php $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
           $uid =  $session_data['id'];
            $db = $this->session->userdata('database_name');
			if($ustype == "businessaccountmanager"){
		$filees = "e-notes/ceocamer_cam_crm";
	 }
	 elseif($ustype == "businessaccount"){
	 $filees = "e-notes/".$db;
	
	 }else{
		 if($db != ""){
	 $filees = "e-notes/".$db."/".$uid;
		 }else{
			 $filees = "e-notes/ceocamer_cam_crm/".$uid; 
		 }
	 }	
	
		 $entry_id       = $this->input->post('entry_id');
		 $action_id      = $this->input->post('action_id');
		 $notes		     = $this->input->post('notes');
		 $source         = $this->input->post('source');
		 $schedual        = $this->input->post('schedule');
		$data = array();
         if($notes) {
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
			if(!empty($schedual)){
			$action = $this->action_model->update_action_data($schedual,$entry_id);	
			}

			$action = $this->action_model->save_action($data);

			if($action !== false){
				// save mail on server //
				$date       =  'Date: '.$time;
				$mailname   =  $entry_id."_".$action."_".$time;
				//$mailto 	=  "To: ".$to_email;
				$notes  	=  $notes;
				$saved_mail =  $entry_id."\n".$date."\n".$notes;
				$dir 		=  $filees."/"; // Full Path
				$myfile 	=  file_put_contents($dir.$mailname.".txt", $saved_mail.PHP_EOL , FILE_APPEND);
				$this->entry_model->update_entry_status($entry_id);
				echo "Action Record Saved";

			}else
				echo "Error To Saving Note In Action Record";
		 }else
		 	echo "There is an error to add note";
	}
	
	function instructions(){
		$header['title']     =  'Instructions';
		$this->load->view('templates/header', $header);
		$this->load->view('email/instruction');
	}
}
