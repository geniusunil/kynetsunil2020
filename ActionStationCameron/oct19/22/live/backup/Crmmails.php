<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crmmails extends CI_Controller
{

    public function __construct()
    {
        /** constructor()
         *  call to different helpers, libraries and models
         */
        parent::__construct();
        // Load session library
        error_reporting(E_ERROR | E_PARSE);
        $this->load->dbforge();
        $this->load->library('session');
        //$this->load->library('currency_converter');
        $this->load->library('form_validation');
        $this->load->library('timezones');
        // load helper classes //
        $this->load->helper('email');
        $this->load->helper('settings');
        $this->load->model('crmmails_model');
        $this->load->model('entry_model');
        $this->load->helper('userinfo');
        $this->load->helper('products');
        $this->load->helper('heading');
        $this->load->helper('filecontet');
        $this->load->helper('accesscontrol');
        $this->user_data = $this->session->userdata('logged_in');
        $this->user_id   = $this->user_data['id'];
        $this->load->model('Entry_model');
        $this->load->model('Heading_model');
        $this->load->model('accounts_model');
        $this->load->model('product_model');
        $this->load->model('action_model');
        $this->load->library('pagination');
        $this->load->helper('common');
        $dbname  = $this->session->userdata('database_name');
        $db_usr  = $this->session->userdata('db_name');
        $db_pass = $this->session->userdata('db_pass');

        if ($dbname != "") {
            $config_app = switch_database($dbname, $db_usr, $db_pass);
            $this->Entry_model->db    = $this->load->database($config_app, true);
            $this->entry_model->db    = $this->load->database($config_app, true);
            $this->Heading_model->db  = $this->load->database($config_app, true);
            $this->accounts_model->db = $this->load->database($config_app, true);
            $this->product_model->db  = $this->load->database($config_app, true);
            $this->crmmails_model->db = $this->load->database($config_app, true);
            $this->action_model->db   = $this->load->database($config_app, true);

        }

    }

public function index()
    {
     $this->load->view('email/email_crm');
    }

public function loadViewmails()
    {
        $session_data = $this->session->userdata('logged_in');
        $user_type    = $session_data['usertype'];
        $uid          = $session_data['id'];
        $utype        = get_user_type($user_type);
        $ustype       = $utype->type_name;

        if ($user_type == 8) {
            $infos = $this->accounts_model->system_data($ustype);
            if ($infos) {
                $email = $infos[0]->setting_value;
                $this->session->set_userdata('emails', $email);
            }
            $inf_pass = $this->accounts_model->system_data_pass($ustype);
            if ($inf_pass) {
                $pswd = $inf_pass[0]->setting_value;
                $this->session->set_userdata('password', $pswd);
            }
        }
        $assigned_headings  = $this->entry_model->get_assigned_headings();
        $data['entry_type'] = $this->entry_model->entry_types();
        $data['headings']   = $assigned_headings; //$this->heading_model->get_headings();
        $data['title']      = "Incoming Communication";
        $data['sys_pass']   = $this->session->userdata('password');
        $data['sys_email']  = $this->session->userdata('emails');
        $this->load->view('templates/header', $data);
        $this->load->view('email/emails_view', $data);

    }
 public function viewmails()
    {
        file_put_contents("/debug2.txt", "running viemails" . PHP_EOL, FILE_APPEND);
        $session_data = $this->session->userdata('logged_in');
        $user_type    = $session_data['usertype'];
        $uid          = $session_data['id'];
        $utype        = get_user_type($user_type);
        $ustype       = $utype->type_name;
        
        if ($user_type == 8) {
            $infos = $this->accounts_model->system_data($ustype);
            if ($infos) {
                $email = $infos[0]->setting_value;
                $this->session->set_userdata('emails', $email);
            }
            $inf_pass = $this->accounts_model->system_data_pass($ustype);
            if ($inf_pass) {
                $pswd = $inf_pass[0]->setting_value;
                $this->session->set_userdata('password', $pswd);
            }
        }
        $data['users_list'] = get_customer_by_name();
        $assigned_headings  = $this->entry_model->get_assigned_headings();
        $data['entry_type'] = $this->entry_model->entry_types();
        $data['headings']   = $assigned_headings; //$this->heading_model->get_headings();
        $data['title']      = "Incoming Communication";
        $data['sys_pass']   = $this->session->userdata('password');
        $data['sys_email']  = $this->session->userdata('emails');
        $this->load->view('templates/header', $data);
        file_put_contents("debug2.txt", "before emails view" . PHP_EOL, FILE_APPEND);
        $this->load->view('email/emails_view', $data);
        file_put_contents("debug2.txt", "after emails view" . PHP_EOL, FILE_APPEND);
    }
 public function viewdetail()
    {
        $email               = trim($this->input->post('useremail'));
        $data['senderemail'] = $this->input->post('senderemail');
        $data['filename']    = $this->input->post('file_name');
        $data['subject']     = $this->input->post('subject');
        $message             = $this->input->post('message');
        $data['message']     = html_entity_decode($message, ENT_COMPAT, 'ISO-8859-1');
        $data['attachment']  = $this->input->post('attachments');
        $data['attach_pdf']  = $this->input->post('attachment_pdf');
        $data['attach_doc']  = $this->input->post('attachment_doc');
        $data['edate']       = $this->input->post('e_date');
        $data['fname']       = $this->input->post('f_name');
        $data['rel_user']    = $this->crmmails_model->userdetail($email);
        $data                = $this->load->view('email/relatedentry', $data, true);
        $this->output->set_output($data);
    }
 public function viewentry($h_id, $userIds, $senderemail, $subject, $message, $filename, $fname, $dates)
    {
        $data['title'] = "User Entry";
        $this->load->view('templates/header', $data);
        $this->load->model('entry_model');
        $this->load->model('heading_model');
        $this->load->model('accounts_model');

        $dbname = $this->session->userdata('database_name');
        $db_usr = $this->session->userdata('db_name');
        $db_pass = $this->session->userdata('db_pass');
        if ($dbname != "") {
         $config_app               = switch_database($dbname, $db_usr, $db_pass);
         $this->entry_model->db    = $this->load->database($config_app, true);
         $this->heading_model->db  = $this->load->database($config_app, true);
         $this->accounts_model->db = $this->load->database($config_app, true);
        }

        $user_ids_string   = $userIds;
        $entriesArray      = $this->entry_model->get_entry_by_userId($user_ids_string);
        $data['entry']     = $entriesArray;
        $data['headings']  = $this->heading_model->get_headings();
        $data['countries'] = $this->currency_converter->getCountries();
        $data['action']    = $this->entry_model->action_types();
        $data['timezones'] = $this->timezones->getTimezones();
        $data['utcTime']   = $this->timezones->getUtcTime();

        $userInfo = $this->accounts_model->get_user_information($user_ids_string);
        if (array_key_exists('preferred_currency', $userInfo)) {
            $user_currency = $userInfo['preferred_currency'];
        }
        // getting the currency values //
        if (isset($user_currency)) {
            $data['user_currency'] = $user_currency;
        }
        // getting user timezone //
        if (array_key_exists('timezone', $userInfo)) {
            $data['timezone'] = $userInfo['timezone'];
        }

        $data['action_page']      = false;
        $data['entry_model']      = $this->entry_model;
        $data['session_currency'] = $this->user_data['currency'];
        $data['sender']           = $senderemail;
        $data['subject']          = $subject;
        $data['message']          = $message;
        $data['file']             = $filename;
        $data['fname']            = $fname;
        $data['edate']            = $dates;
        $data['ids']              = $user_ids_string;
        $data['hids']             = $h_id;

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
           echo  $file                 = $folder.$data['filename'];
            $file1                = basename($file);
            $date = "process_";
            if (file_exists($file)) {
                $file_name = $folder.$date.$file1;
                rename($file, $file_name);
               
           }
           exit();
        $this->load->view('email/user_entry', $data);

    }

 public function userlist()
    {
        $id['usreid']   = $this->input->post('id');
        $senderemail    = $this->input->post('email');
        $senderemail    = preg_replace('/\s+/', '', $senderemail);
        $subject        = $this->input->post('title');
        $message        = $this->input->post('mess');
        $message        = htmlentities($message, ENT_COMPAT, 'ISO-8859-1');
        $file           = $this->input->post('file');
        $fname          = $this->input->post('f_name');
        $dates          = $this->input->post('e_date');
        $hid            = $this->input->post('hid');
        $attachment     = $this->input->post('attachment');
        $attachment_pdf = $this->input->post('attachment_pdf');
        $attachment_doc = $this->input->post('attachment_doc');
        $usid           = rtrim($id['usreid'], ", ");
        $usid           = explode(',', $usid);?>

	 <tr ><td width='200px'><b>User name </b></td><td><b>User email</b></td></tr>
       <?php foreach ($usid as $us_id) { 
            $userInfo = get_user_details($us_id);
            if( $userInfo != Null){?>      
         <tr> 
                  <td>  <form action="<?php echo base_url('/crmmails/viewentry_1') ?>" method="post" class="select_users">
                           <input type="hidden" name="uid"  value="<?php echo $senderemail ?>">
                           <input type="hidden" name="subject" value="<?php echo $subject ?>">
                           <input type="hidden" name="message" value="<?php echo $message ?>">
                           <input type="hidden" name="attach" value="<?php echo $attachment ?>">
                           <input type="hidden" name="attach_1" value="<?php echo $attachment_pdf ?>">
                           <input type="hidden" name="attach_2" value="<?php echo $attachment_doc ?>">
                           <input type="hidden" name="f_name" value="<?php echo $fname ?>">
                           <input type="hidden" name="file_name" value="<?php echo $file ?>">
                           <input type="hidden" name="edate" value="<?php echo $dates ?>">
                           <input type="hidden" name="us_id"id="us_id" value="<?php echo $us_id ?>">
                           <input type="hidden" name ="he_id" id="he_id" value="<?php echo $hid ?>" required>
                           <input type="submit" name="submit" class="ulink" value="<?php if (array_key_exists('first_name', $userInfo)) {
                echo $userInfo->first_name;
            } else{ echo "No first Name " ; }
            if (array_key_exists('last_name', $userInfo)) {
                echo " " . $userInfo->last_name;
            } ?>"/></td></form>
            <td>       <form action="<?php echo base_url('/crmmails/viewentry_1') ?>" method="post" class="select_users">
                        <input type="hidden" name="uid"  value="<?php echo $senderemail ?>">
                        <input type="hidden" name="subject" value="<?php echo $subject ?>">
                        <input type="hidden" name="message" value="<?php echo $message ?>">
                        <input type="hidden" name="attach" value="<?php echo $attachment ?>">
                        <input type="hidden" name="attach_1" value="<?php echo $attachment_pdf ?>">
                        <input type="hidden" name="attach_2" value="<?php echo $attachment_doc ?>">
                        <input type="hidden" name="f_name" value="<?php echo $fname ?>">
                        <input type="hidden" name="file_name" value="<?php echo $file ?>">
                        <input type="hidden" name="edate" value="<?php echo $dates ?>">
                        <input type="hidden" name="us_id"id="us_id" value="<?php echo $us_id ?>">
                        <input type="hidden" name ="he_id" id="he_id" value="<?php echo $hid ?>" required>
                        <input type="submit" name="submit" class="ulink" value="<?php if (array_key_exists('email_primary', $userInfo)) {echo $userInfo->email_primary;}else{echo "No Email "; } ?>">
                        </td></form></tr>
		<?php 
			}else{
				echo '<tr><td><p style="color:red"> No Selected User Fonund</p></td></tr>';
            } 
              }
    }
public function deletfile()
    {
        $file         = $this->input->post('filename');
        $img_file     = $this->input->post('img_files');
        $img_id_paths = $this->input->post('img_id_pathss');
        $file         = str_replace("<", "&lt;", $file);
        $file         = str_replace(">", "&gt;", $file);
        $dbname       = $this->session->userdata('database_name');
        $session_data = $this->session->userdata('logged_in');
        $id           = $session_data['id'];
        $user_type    = $session_data['usertype'];
        $utype        = get_user_type($user_type);
        $ustype       = $utype->type_name;
        $ustype       = preg_replace('/\s+/', '', $ustype);
        $ustype       = strtolower($ustype);

        $incoming_email = $this->accounts_model->systems_datas($id);
        foreach ($incoming_email as $incoming_emails) {
            $incoming_emailss = $incoming_emails->incoming_email;
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

        if ($ustype == 'businessaccountmanager') {
            $dir =  'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/';
        } else {
            $dir = 'email_files/' . $dbname . "/" . $username . '/';
            $debugpath = 'email_files/' . $dbname . "/" . $username . '/' . 'DebugFiles' . '/';
        }

        $file_name = $this->input->post('id');
        $file_name = explode(",", $file_name);

        file_put_contents("debug.txt", "trying to delete" . print_r($file_name, true) . PHP_EOL, FILE_APPEND);
        foreach ($file_name as $filesss) {

            $filesss  = trim($filesss);
            $ff       = strstr($filesss, '20', true);
            $deb_path = $debugpath . $ff . '/' . 'debugFiles' . '/';
            $deb      = $deb_path . "MainDebug.txt";
            unlink($deb);
            rmdir($deb_path);
            rmdir($debugpath . $ff);
            $path   = $dir . $filesss;
            $delete = unlink($path);

        }

        if (file_exists($img_id_paths)) {
            $objects = scandir($img_id_paths);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($filees . "/" . $object)) {
                        rmdir($img_id_paths . "/" . $object);
                    } else {
                        unlink($img_id_paths . "/" . $object);
                    }

                }
            }
            rmdir($img_id_paths);
        }
        $assigned_headings  = $this->entry_model->get_assigned_headings();
        $data['entry_type'] = $this->entry_model->entry_types();
        $data['headings']   = $assigned_headings; //$this->heading_model->get_headings();
        $data['title']      = "Incoming Communication";
        $data['email_date'] = $this->entry_model->get_file_date();
        $this->load->view('templates/header', $data);
        $this->load->view('email/emails_view', $data);

      }

 public function do_not_traks()
    {
        $time = $this->session->userdata('time');	
		if(empty($time)){
	    $time = get_systems_timezone();	
	}
		 $session_data = $this->session->userdata('logged_in'); 
         $user_type    = $session_data['usertype']; 
         $user_details = get_user_details($session_data['id']); 
         $utype        = get_user_type($user_type);
	     $ustype       = $utype->type_name ;
	     $ustype       = preg_replace('/\s+/', '', $ustype);
         $ustype       = strtolower($ustype);
         $uid          =  $session_data['id'];
         $db           = $this->session->userdata('database_name');
			
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
     $file_name  = $this->input->post('id');
     $file_name1 = explode(",", $file_name);
     $folder     =  $pathss.'/';

            foreach($file_name1 as $filename ){  	
                $file         = $folder.trim($filename);
			    $file1        = basename($file);
                $date         = "trashed_";
                $file_name12  = $folder.$date.$filename;
                $oldDir       = $file;
                $newDir       = $file_name12 ;  
                if (file_exists($oldDir)) {
                rename($oldDir, $newDir);
                }
            }
      }

 public function restore_trash_email()
      {
        $time = $this->session->userdata('time');
          if(empty($time)){
          $time = get_systems_timezone();	
          }
        $session_data = $this->session->userdata('logged_in'); 
        $user_type    = $session_data['usertype']; 
        $user_details = get_user_details($session_data['id']); 
        $utype        = get_user_type($user_type);
        $ustype       = $utype->type_name ;
        $ustype       = preg_replace('/\s+/', '', $ustype);
        $ustype       = strtolower($ustype);
        $uid          =  $session_data['id'];
        $db           = $this->session->userdata('database_name');
              
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
      $pathss  = "email_files/".$db."/".$username;
       }else{
           if($db != ""){
       $filees = "e-mails/".$db."/".$uid;
       $pathss = "email_files/".$db."/".$username;
           }else{
               $filees = "e-mails/ceocamer_cam_crm/".$uid;
               $pathss = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com"; 
           }
       }		 
  
       $file_name = $this->input->post('id');
       $file_name = explode(",", $file_name); 
          foreach($file_name as $filename1){
                  $filename             = str_replace('trashed_', '', $filename1);
                  $folder               =  $pathss.'/';
                  $file                 = $folder.trim($filename1);
                  $file1                = basename($file);
                  if (file_exists($file)) {
                       $file_name1 = $folder.$filename;
                       rename($file, $file_name1);	
                  }
                   }
        }

    public function viewentry_1()
    { 
        $data['title'] = "User Entry";
        $this->load->view('templates/header', $data);
        $this->load->model('entry_model');
        $this->load->model('heading_model');
        $this->load->model('accounts_model');
        $this->load->helper('userinfo');
        $dbname            = $this->session->userdata('database_name');
        $db_usr            = $this->session->userdata('db_name');
        $db_pass           = $this->session->userdata('db_pass');
        $this->user_data   = $this->session->userdata('logged_in');
        $session_time_zone = $this->session->userdata('time');
        $this->user_id = $this->user_data['id'];
        if ($dbname != "") {
            $config_app = switch_database($dbname, $db_usr, $db_pass);
            $this->entry_model->db    = $this->load->database($config_app, true);
            $this->heading_model->db  = $this->load->database($config_app, true);
            $this->accounts_model->db = $this->load->database($config_app, true);
        }
        if (null !== $this->input->post('us_id')) {
            $user_ids_string = $this->input->post('us_id');
        } else {
            $user_ids_string = $this->input->get('us_id');
        }

        $entriesArray      = $this->entry_model->get_entry_by_userId($user_ids_string);
        $data['entry']     = $entriesArray;
        $data['headings']  = $this->heading_model->get_headings();
        $data['countries'] = $this->currency_converter->getCountries();
        $data['action']    = $this->entry_model->action_types();
        $data['timezones'] = $this->timezones->getTimezones();
        $data['utcTime']   = $this->timezones->getUtcTime();

        $userInfo = $this->accounts_model->get_user_information($user_ids_string);
        if (is_array($userInfo) && array_key_exists('preferred_currency', $userInfo)) {
            $user_currency = $userInfo['preferred_currency'];
        }
        // getting the currency values //
        if (isset($user_currency)) {
            $data['user_currency'] = $user_currency;
        }
        // getting user timezone //
        if (is_array($userInfo) && array_key_exists('timezone', $userInfo)) {
            $data['timezone'] = $userInfo['timezone'];
        }

        $data['action_page']      = false;
        $data['entry_model']      = $this->entry_model;
        $data['session_currency'] = $this->user_data['currency'];
        $data['sender']           = $this->input->post('uid');
        $data['subject']          = $this->input->post('subject');
        $data['acountuser']       = $this->input->post('acountuser');
        $message                  = $this->input->post('message');
        $data['message']          = html_entity_decode($message, ENT_COMPAT, 'ISO-8859-1');
        $data['attachment']       = $this->input->post('attach');
        $data['attachment_pdf']   = $this->input->post('attach_1');
        $data['attachment_doc']   = $this->input->post('attach_2');
        $data['file']             = $this->input->post('file_name');
        $data['fname']            = $this->input->post('f_name');
        $data['edate']            = $this->input->post('edate');
        $data['ids']              = $user_ids_string;
        $data['hids']             = $this->input->post('he_id');
        if (null != $this->input->post('u_id')) {
            $data['ac_user']        = $this->input->post('acountuser');
            $data['remove_user_id'] = $this->input->post('u_id');
        } else {
            $data['ac_user']        = 'acountuser';
            $data['remove_user_id'] = '';
        }
        $this->load->view('email/user_entry', $data);
    }

 public function hide_entry()
    {
        $ent_id = $this->input->post('ent_id');
        $this->Entry_model->hide_entry($ent_id);
    }
public function user_remove()
    {
        $this->load->helper('settings');
        $this->load->model('action_model');
        $dbname  = $this->session->userdata('database_name');
        $db_usr  = $this->session->userdata('db_name');
        $db_pass = $this->session->userdata('db_pass');
        $time    = $this->session->userdata('time');
        if ($dbname != "") {
            $config_app = switch_database($dbname, $db_usr, $db_pass);
            $this->action_model->db = $this->load->database($config_app, true);
        }
        $time           = $this->session->userdata('time');
        $remove_user_id = $this->input->post('u_id');
        $entry_id       = trim($this->input->post('entry_id'));
        $user_assm      = $this->entry_model->get_customers_in_entry($entry_id);
        $usercount      = count($user_assm);
        if ($usercount != 1) {
            if (($key = array_search($remove_user_id, $user_assm)) !== false) {
                unset($user_assm[$key]);
                $data['user_assignment'] = implode(',', $user_assm);

                $userInfo = $this->entry_model->remove_user($data, $entry_id);
                if ($userInfo != false) {
                    $custid = implode(',', $user_assm);

                    $action = array();
                    $action['entry_id']          = $entry_id;
                    $action['action_type']       = 20;
                    $action['action_source']     = base_url('dashboard');
                    $action['action_direction']  = "Internal"; // to be confirmed
                    $action['action_status']     = "Completed";
                    $action['action_notes']      = '';
                    $action['action_content']    = 'deletedCustomer:' . $entry_id . ',' . $custid . '|deletedc';
                    $action['date']              = $time;
                    $action['action_schedule']   = $time;
                    $action['action_author']     = $this->user_id;
                    $addedActionId               = $this->action_model->save_action($action);
                    $historyData = array(
                        "entry_id" => $entry_id,
                        "action_id" => (($addedActionId !== false) ? $addedActionId : 0),
                        "edit_date" => $time,
                    );
                    $saveEditHistory = $this->action_model->insert_edit_history($historyData);
                    //////////////////////////////
                    $resp = array('result' => 1, 'removeuser' => true);
                    echo json_encode($resp);
                    exit();

                } else {
                    $resp = array('result' => 2, 'error' => true);
                    echo json_encode($resp);
                    exit();
                }
            }
        } else {
            $resp = array('result' => 0, 'oneuser' => true);
            echo json_encode($resp);
            exit();

        }

    }

function auto_create(){
 $user_fname = trim($this->input->post('fname'));
 $user_lname = trim($this->input->post('lname'));
 $user_email = trim($this->input->post('quote_email'));
 $name       = $this->accounts_model->get_customer_by_name($user_fname,$user_email);
 $resp       = $name[0]['user_id'];
if(!empty($resp)){
    echo json_encode($resp);
    exit();
}else{
    echo json_encode("empty");
    exit();  
}
 
}

}