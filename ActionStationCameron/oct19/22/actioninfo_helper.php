<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ########## get action data using entry-id ########### */
    
if ( ! function_exists('get_action_data')){
    function get_action_data($entry_id){
	      $data = new stdClass;
        //get main CodeIgniter object
        $ci =& get_instance();
       
        //load databse library
        $ci->load->database();
	      
 		$dbname = $ci->session->userdata('database_name');
		$db_usr  = $ci->session->userdata('db_name');
		$db_pass = $ci->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$ci->db = $ci->load->database($config_app,TRUE);
		}
        //get data from database
        $query = $ci->db->select('action_record.*, action_types.atype_id, action_types.atype_name')->from('action_record')->join('action_types','action_record.action_type = action_types.atype_id')->where('entry_id', $entry_id)->get();
        //$query = $ci->db->get_where('action_record',array('entry_id'=>$entry_id));
        // $user = array();
	
        if($query->num_rows() > 0){
		        $result = $query->result_array();
		        return $result;
        }else{
           return false;
        }
    }
}

/* ########## get the action icon using action-id and action-status ########### */

if(!function_exists('get_action_icon')){
  function get_action_icon($id, $a_status){
    $icon = '';
    switch($id){
      case 1: 
        $icon = 'Note'/*'<i class="fa fa-phone" aria-hidden="true"></i>'*/;
        break;
      case 2: 
        if($a_status == 'Scheduled')
          $icon = 'Scheduled Email'/*'<i class="fa fa-envelope" aria-hidden="true"></i>'*/;
        else
          $icon = 'Email'/*'<i class="fa fa-envelope" aria-hidden="true"></i>'*/;
        break;
      case 3:
        $icon = 'Chat'/*'<i class="fa fa-comments-o" aria-hidden="true"></i>'*/;
        break;
      case 4: 
        $icon = 'Fax'/*'<i class="fa fa-fax" aria-hidden="true"></i>'*/;
        break;
      case 5: 
        $icon = 'Electronic Communication'/*'<i class="fa fa-mobile" aria-hidden="true"></i>'*/;
        break;
      case 6: 
        $icon = 'Physical communication'/*'<i class="fa fa-handshake-o" aria-hidden="true"></i>'*/;
        break;
      case 7: 
        $icon = 'Product Sourcing / Research'/*'<i class="fa fa-product-hunt" aria-hidden="true"></i>'*/;
        break;
      case 8: 
        $icon = 'Quote Request';
        break;
      case 9: 
        $icon = 'Quote';
        break;
      case 10: 
        $icon = 'Order'/*'<i class="fa fa-shopping-basket" aria-hidden="true"></i>'*/;
        break;
      case 11:
        $icon = 'Cancel Order'/*'<span class="glyphicon glyphicon-remove-circle"></span><span class="glyphicon glyphicon-shopping-cart"></span>'*/; 
        break;
      case 12: 
        $icon = 'Return';
        break;
      case 13: 
        $icon = 'Repair'/*'<i class="fa fa-gavel" aria-hidden="true"></i> <i class="fa fa-wrench" aria-hidden="true"></i>'*/;
        break;
      case 14: 
        $icon = 'Replace'/*'<i class="fa fa-exchange" aria-hidden="true"></i>'*/;
        break;
      case 15: 
        $icon = 'Refund'/*'<i class="fa fa-money" aria-hidden="true"></i>'*/;
        break;
      case 16: 
        $icon = 'Invoice'/*'<i class="fa fa-id-card-o" aria-hidden="true"></i>'*/;
        break;
      case 17: 
        $icon = 'Payment'/*'<i class="fa fa-paypal" aria-hidden="true"></i>'*/;
        break;
      case 18: 
        $icon = 'Pack'/*'<i class="fa fa-suitcase" aria-hidden="true"></i>'*/;
        break;
      case 19: 
        $icon = 'Dispatch'/*'<i class="fa fa-truck" aria-hidden="true"></i>'*/;
        break;
      case 20: 
        $icon = 'Administration'/*'<i class="fa fa-user-circle" aria-hidden="true"></i>'*/;
        break;
      case 21: 
        $icon = 'Dead Lead';
        break;
      case 23: 
        $icon = ' Entry Created Manually'/*'<span class="glyphicon glyphicon-edit"></span>'*/; 
        break;
		case 24: 
        $icon = 'Others';
        break;
		 case 25: 
        $icon = 'Call';
        break;
		case 26: 
        $icon = 'Delete Entry'/*'<span class="glyphicon glyphicon-edit"></span>'*/; 
        break;
		case 27: 
        $icon = 'Entry Created By Email Wizard'/*'<span class="glyphicon glyphicon-edit"></span>'*/; 
        break;
      default:
        break;
    }
    return $icon;
  }
}

/* ######## get action tyoe by using action id ######### */

if ( ! function_exists('get_action_type')){
  function get_action_type($action_id){
    //get main CodeIgniter object
    $ci =& get_instance();
    //load databse library
    $ci->load->database();
	    
 		$dbname = $ci->session->userdata('database_name');
		$db_usr  = $ci->session->userdata('db_name');
		$db_pass = $ci->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$ci->db = $ci->load->database($config_app,TRUE);
		}
    // $user = array();
	  $query = $ci->db->select('atype_name');
   	$query = $ci->db->from('action_types');
    $query = $ci->db->where('atype_id',$action_id);
    
    // $icon = get_action_icon($action_id);

    return $ci->db->get()->row()->atype_name;
   }
}

/* ######## fetch email contents by using email-name ######### */

if( ! function_exists('fetch_email_contents')){
    function fetch_email_contents($email_name){
		$data = new stdClass;
        //get main CodeIgniter object
        $ci =& get_instance();
       
        //load databse library
        $ci->load->database();
 $session_data = $ci->session->userdata('logged_in'); ?>
<?php $user_type = $session_data['usertype']; ?>
<?php $user_details = get_user_details($session_data['id']); ?>
<?php $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
           $uid =  $session_data['id'];
            $db = $ci->session->userdata('database_name');
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
        $fileName = $filees.'/'.$email_name;
		$open = fopen($fileName, "r");
		$openMail = file_get_contents($fileName);
        if($open){
            // file opened
/*             $i = 0;
            while(($line = fgets($open)) !== false){
              $whole_email[$i] = $line; 
              $i++;
			} */
			$whole_email = explode("ExclusiveLineBreak",$openMail);
			file_put_contents("actioninfodebug.txt",print_r($whole_email,true));
            $email_data['to_email'] = $whole_email[0];
            $email_data['date'] = $whole_email[1];
            $email_data['subject'] = $whole_email[2];
            $email_data['message'] = $whole_email[3];
            $attachments = '';
            
            for($j = 5; $j < count($whole_email); $j++){
              $attachments .= $whole_email[$j] .',';
            }

            $attachments = substr($attachments, 0, strpos($attachments, ",Notes:"));
            
            $email_data['email_attachments'] = $attachments;
            $count = count($whole_email);
            $notes = str_replace('Notes: ', '', $whole_email[$count-1]);
            $email_data['notes']= $notes;
            return $email_data;
        } else{
            // error opening file
            return false;
        }
  }

}
//##### helper for action note #######///////////
if( ! function_exists('fetch_note_contents')){
    function fetch_note_contents($note_name){
		$data = new stdClass;
        //get main CodeIgniter object
        $ci =& get_instance();
       
        //load databse library
        $ci->load->database();
 $session_data = $ci->session->userdata('logged_in'); ?>
<?php $user_type = $session_data['usertype']; ?>
<?php $user_details = get_user_details($session_data['id']); ?>
<?php $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
           $uid =  $session_data['id'];
            $db = $ci->session->userdata('database_name');
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
		
        $fileName = $filees.'/'.$note_name;
        $open = fopen($fileName, "r");
        if($open){
            // file opened
            $i = 0;
            while(($line = fgets($open)) !== false){
              $note[$i] = $line; 
              $i++;
            }
            $count = count($note);
            $notes = str_replace('Notes: ', '', $note[$count-1]);
            $n_ote['notes']= $notes;
            return $n_ote;
        } else{
            // error opening file
            return false;
        }
  }

}
// ###### helper functions for action templates ####### //

if( !function_exists('action_call_template') ){
  function action_call_template(){
    /**
     * template for call action
     */

  }
}
if( !function_exists('action_email_template') ){
  function action_email_template($action_details, $entry_model){
    /**
     * template for email template
     */
    // display email text and attachments //
	 $data = new stdClass;
        //get main CodeIgniter object
        $ci =& get_instance();
       
        //load databse library
        $ci->load->database();
 $session_data = $ci->session->userdata('logged_in'); ?>
<?php $user_type = $session_data['usertype']; ?>
<?php $user_details = get_user_details($session_data['id']); ?>
<?php $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
           $uid =  $session_data['id'];
            $db = $ci->session->userdata('database_name');
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
			
			 
            
    $email_name = $action_details['entry_id'] . '_' . $action_details['action_id'] . '_' . $action_details['date'] . '.txt';
    $entry_model->display_email_contents($filees, $email_name, $action_details['entry_id'], $action_details['action_id']);
  }
}
////template for  note///
if( !function_exists('action_note_template') ){
  function action_note_template($action_details, $entry_model){
    /**
     * template for email template
     */
    // display email text and attachments //
	 $data = new stdClass;
        //get main CodeIgniter object
        $ci =& get_instance();
       
        //load databse library
        $ci->load->database();
 $session_data = $ci->session->userdata('logged_in'); ?>
<?php $user_type = $session_data['usertype']; ?>
<?php $user_details = get_user_details($session_data['id']); ?>
<?php $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
           $uid =  $session_data['id'];
            $db = $ci->session->userdata('database_name');
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
			
	
    $note_name = $action_details['entry_id'] . '_' . $action_details['action_id'] . '_' . $action_details['date'].'.txt';
    $entry_model->display_note_contents($note_name, $action_details['entry_id'], $action_details['action_id'], $filees);
  }
}



if( !function_exists('action_chat_template') ){
  function action_chat_template(){
    /**
     * template fot chat action
     */
  }
}
if( !function_exists('action_fax_template') ){
  function action_fax_template(){
    /**
     * template fot fax action
     */
  }
}
if( !function_exists('action_electro_comm_template') ){
  function action_electro_comm_template(){
    /**
     * template fot electronic communication action
     */
  }
}
if( !function_exists('action_phys_comm_template') ){
  function action_phys_comm_template(){
    /**
     * template fot chat action
     */
  }
}
if( !function_exists('action_prod_source_template') ){
  function action_prod_source_template(){
    /**
     * template fot product source action
     */
  }
}
if( !function_exists('action_quote_request_template') ){
  function action_quote_request_template(){
    /**
     * template fot chat action
     */
  }
}
if( !function_exists('action_quote_template') ){
  function action_quote_template(){
    /**
     * template fot quote action
     */
  }
}
if( !function_exists('action_order_template') ){
  function action_order_template(){
    /**
     * template fot order action
     */
  }
}
if( !function_exists('action_cancel_order_template') ){
  function action_cancel_order_template(){
    /**
     * template fot cancel order action
     */
  }
}
if( !function_exists('action_return_template') ){
  function action_return_template(){
    /**
     * template fot return action
     */
  }
}
if( !function_exists('action_repair_template') ){
  function action_repair_template(){
    /**
     * template fot repair action
     */
  }
}
if( !function_exists('action_replace_template') ){
  function action_replace_template(){
    /**
     * template fot replace action
     */
  }
}
if( !function_exists('action_refund_template') ){
  function action_refund_template(){
    /**
     * template fot refund action
     */
  }
}
if( !function_exists('action_invoice_template') ){
  function action_invoice_template(){
    /**
     * template fot invoice action
     */
  }
}
if( !function_exists('action_payment_template') ){
  function action_payment_template(){
    /**
     * template fot payment action
     */
  }
}
if( !function_exists('action_pack_template') ){
  function action_pack_template(){
    /**
     * template fot pack action
     */
  }
}
if( !function_exists('action_dispatch_template') ){
  function action_dispatch_template(){
    /**
     * template fot dispatch action
     */
  }
}
if( !function_exists('action_administration_template') ){
  function action_administration_template($actionDetails){
    /**
     * template fot administration action
     */
    $ci =& get_instance();
	$ci->load->database();
 		$dbname = $ci->session->userdata('database_name');
		$db_usr  = $ci->session->userdata('db_name');
		$db_pass = $ci->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$ci->db = $ci->load->database($config_app,TRUE);
		}
    $ci->load->helper('products');
	 $ci->load->helper('userinfo');
    $ci->load->model('action_model');
	
    $actionContent  = $actionDetails['action_content'];
    $a_type         = substr($actionContent, strpos($actionContent, '|')+1 ); 
    $a_quotes       = substr($actionContent, 0, strpos($actionContent, '|'));
    $actionNotes    = $actionDetails['action_notes']; 
    $date           = $actionDetails['date'];
    $entry_id       = $actionDetails['entry_id'];
	
    if(strpos($actionNotes, '>^') !== false){ 
      $actionNotes    = substr($actionNotes, 0, strpos($actionNotes, '>^'));

    } 
	 if(strpos($actionNotes, '^<') !== false){
      $historyActId   = substr($actionNotes, 0, strpos($actionNotes, '^<')); 
      $actionNotes    = substr($actionNotes, strpos($actionNotes, '^<')+2);
      $historyNotes   = $ci->action_model->get_notes($historyActId);
      
      if(!empty($historyNotes)){
      $historyNotes   = (strpos($historyNotes, '^<') !== false)?preg_replace('/^.*<\s*/', '', $historyNotes):((strpos($historyNotes, '>^') !== false)?strstr($historyNotes, '>^', true):$historyNotes);
      $his_notes = $historyNotes;
	  }else{
		$his_notes = "N/A";  
	  }
    }
    echo "<li>Administration type: ";
    switch($a_type){
      case 'updated':
        echo "Updated  Product to Entry";
        
        $quotesData   = explode(',', str_replace(array('editedQuotes:', '|updated'), '', $actionContent));
        $productPrice = ''; 
		$invId = array();
        $inc = 0;
		
        foreach($quotesData as $data){
          if($inc == 4){
			 $product_name  = $data;
          }
		 elseif($inc == 3)
		 {
			 $product_url  = $data;
		 }
		elseif($inc === (count($quotesData)-3))
		 {
			 $suppllier  = $data;
		 }	 
		  elseif($inc == 0 || $inc == 1 || $inc == 2){
			
            preg_match("/\[([^\]]*)\]/", $data, $matches);
			$match_x = ""; 
				$ccv = 0;
				foreach($matches as $matche){
					if($ccv == 1){
						$match_x = $matche;
					}
					$ccv++;
				}
			$qdata   = explode(';', $match_x);
			$invId[] = $qdata[0];
            $qCurr   = preg_replace("/[^a-zA-Z]+/", "", $qdata[1]);
            $qPrice  = filter_var($qdata[1], FILTER_SANITIZE_NUMBER_INT);
            $productPrice .= ucfirst($qdata[2]). ': '.$qCurr.' '.$qPrice.' ';
          }
          
          $inc++;
        }
		
        $invId = array_unique($invId);
		
        $pid   = $invId[0];
        $productInfo = getProductInfo($pid); 
        echo "<div class='entry-history' style='display:none' data-productId='".$pid."'>";
		echo "<b>Previous Version</b><br>";
        echo "<b>Product Title :</b> " . $product_name .'<br>';
	 	echo "<b>Product URL:</b> ","<a class='ps-url' href='".$product_url."' target='_blank'>".$product_url."</a><br>";
		echo "<b>Stored Price:</b> " . $productPrice . '<br>';
        echo "<b>Product Notes:</b> ".$his_notes."<br>";
        echo "<b>Edited On:</b> ". date("H:i:s",strtotime($date)).", ". date('l j  F Y', strtotime($date));
        echo "</div>";
        break;
///////////////code for heading////////////////////
		 case 'edited':
        echo "Edited the Heading list";
        
        $quotesData   = explode(',', str_replace(array('editedHeading:', '|editeds'), '', $actionContent));
        $productPrice = ''; 
		$invId = array();
        $inc = 0;
        foreach($quotesData as $data){
			$name11   = preg_replace("/[^0-9]/", "",$data);
			$heading_name12[] = get_heading_text($name11);
			
		  if($inc == 0){
             $pid = $data;
          }
          if($inc === (count($quotesData)-1)){
			 $name1   = preg_replace("/[^a-zA-Z]+/", "", $data);
			 $heading_name1 = get_heading_text($name1);
          }
		 if($inc === (count($quotesData)-2))
		 {
			$heading_name2  = get_heading_text($data);
		 }
          $inc++;
        }
		
	$result = array(); 
    foreach ($heading_name12 as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, $value); 
    } 
    else { 
      $result[$key] = $value; 
    } 
  } 
       $result1 = array_map("unserialize", array_unique(array_map("serialize", $result)));
	   $heading_name =  array_merge($heading_name1,$heading_name2);
       $productInfo = geteditedheading($pid);
		
        echo "<div class='entry-heading-history' style='display:none' data-productId='".$pid."'>";
	    echo "<b>Previous Version</b><br>";
		
		 foreach($result1 as $heading_names){
		   foreach($heading_names as $hed_name){
			   if($hed_name != "Inactive Entry"){   
			 echo '<b>Heading Title: </b>'. $hed_name;
			 echo '<br>';
			   }
			 }
			   }
	 	echo "<b>Edited On:</b> ". date("H:i:s",strtotime($date)).", ". date('l j  F Y', strtotime($date));
        echo "</div>";

        break;
		
/////////////code for staff////////////////////
		
      
	  case 'added':
        echo "Added a Product to Entry";
        break;
      
	  case 'deleted':
	    $quotes = explode(',', substr($a_quotes, strpos($a_quotes, ':')+1));
        $price  = array();
        echo "Deleted a Product from Entry";
        echo "<li><div class='deletedquotes'>";
		foreach($quotes as $quote){
		if($quote === $quotes[0]){
              $quote   = substr($quote, strpos($quote, '[')+1, strpos($quote, ']'));
              $explode = explode(';', $quote);
              $invId   = $explode[0];
              $productInfo = getProductInfo($invId);
              
              if($productInfo !== false){
         echo "<span class='text-primary'>".$productInfo[0]->product_title." </span>";
		  }
		 }
		}
        echo "<div class='dq-details' style='display:none'>";
        echo "<div class='panel panel-danger'>";
        echo "<div class='panel-heading'>Deleted Product</div>";
        echo "<div class='panel-body'>";
        foreach($quotes as $quote){
          
          if($quote === $quotes[0]){
              $quote   = substr($quote, strpos($quote, '[')+1, strpos($quote, ']'));
              $explode = explode(';', $quote);
              $invId   = $explode[0];
              $productInfo = getProductInfo($invId);
              
              if($productInfo !== false){
                echo '<b>Product Title</b> - '.$productInfo[0]->product_title .'<br>';
                echo '<b>Product Url</b> - '.$productInfo[0]->product_url .'<br>';
				echo '<b>Product Note</b> - '.$productInfo[0]->notes .'<br>';
              }
              echo '<b>Stored Price</b> - ';
          }

          $row                = substr($quote, strpos($quote, '['), strpos($quote, ']'));
          $quoteDetails       = explode(';', $row); // $quote[0] > inventoryid ; $quote[1] > price ; $quote[2] > pricetype
          $price['amount']    = substr($quoteDetails[1], 0, strpos($quoteDetails[1], '('));
          $price['currency']  =  preg_replace("/[^a-zA-Z]+/", "", $quoteDetails[1]);
          echo ucfirst(str_replace(']', '', $quoteDetails[2])) . ': '. $price['currency'].' '.$price['amount'] . ' ';
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div></li>";
        break;
		
		case 'editeds':
        echo "Customer Added to Entry";
        break;
		case 'editedst':
        echo "Staff Added to Entry";
        break;
		case 'deleteds':
        echo "Supplier Deleted";
        break;
		case 'deletedc':
        echo "Customer Deleted from Entry";
        break;
		case 'editedsup':
        echo "Supplier Added to Entry";
        break;
		case 'deletedst':
        echo "Staff Deleted From Entry";
		break;
		case 'deleditedst':
		echo "Staff Deleted From The Entry";
		
		$quotesData   = explode(',', str_replace(array('delediteddStaff:', '|deleditedst'), '', $actionContent));
		$quotesData23   = explode('@', str_replace(array('delediteddStaff:', '|deleditedst'), '', $actionContent));
		array_splice($quotesData, 0, 1);
		
		for($i=0; $i<count($quotesData23); $i++){
			if($i != 0){
		$id = $quotesData23[$i];
		$a = get_user_details($id);
		if($a != ""){
			if(property_exists($a, "first_name") && property_exists($a, "last_name") ){
		$f_name = $a->first_name;
		$l_name = $a->last_name;
		 }else{
			$f_name = "N/A";
		$l_name = "";
		 }
		 if(property_exists($a, "first_name")){
		$f_name = $a->first_name;
		 }else{
			 $f_name = "";
		 }
		 if(property_exists($a, "last_name")){
			$l_name = $a->last_name; 
		 }else{
			$l_name = ""; 
		 }
		
		if(property_exists($a, "email_primary")){
			$email_primary = $a->email_primary;	
		}else{
			$email_primary = "N/A";
		}
		if(property_exists($a, "Phone")){
			$Phone = $a->Phone;	
		}else{
		$Phone = "N/A";	
		}
		if(property_exists($a, "timezone")){
			$timezone = $a->timezone;	
		}else{
			$timezone = "N/A";
		}
		
   echo "<li><div class='edited_del_staff'>";
		 echo "<span class='text-primary'>".$f_name." ".$l_name." </span>";
		 echo "<div class='edited_del_staff-details' style='display:none'>";
         echo "<div class='panel panel-danger'>";
         echo "<div class='panel-heading'>Deleted Staff</div>";
         echo "<div class='panel-body'>";
		 echo "<b>Staff Name:</b> ".$f_name." ".$l_name."</br>";
		 echo "<b>Staff Email:</b> ".$email_primary."</br>";
		 echo "<b>Staff Phone:</b> ".$Phone."</br>";
		 echo "<b>Staff Timezone:</b> ".$timezone;
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div></li>";
		}}else{
			$f_name = "N/A";
		$l_name = "";
		}
		 }
	
		echo "<li>Action Notes: Staff Deleted From The Entry </li></br>";
	    echo "<b>Action Taken - Administration</b>";
		
        echo "<li>Administration type:Staff Added To The Entry</li>";
		
		$a = get_staff_id($entry_id);
		foreach($a as $av){
			 $entry_idss = $av['staff_assignment'];
		}
		$entry_ids= explode(',' , $entry_idss);
		$i=0;
		foreach($entry_ids as $entry_idsss){
				if(in_array($entry_idsss,$quotesData)){
					unset($entry_ids[$i]);
				}
			$i++;
		}
		foreach($entry_ids as $entry_id){
		$id = $entry_id;
		$a = get_user_details($id);
		if($a != ""){
			if(property_exists($a, "first_name") && property_exists($a, "last_name") ){
		$f_name = $a->first_name;
		$l_name = $a->last_name;
		 }else{
			$f_name = "N/A";
		$l_name = "";
		 }
		if(property_exists($a, "first_name")){
		$f_name = $a->first_name;
		 }else{
			 $f_name = "";
		 }
		 if(property_exists($a, "last_name")){
			$l_name = $a->last_name; 
		 }else{
			$l_name = ""; 
		 }
		
		if(property_exists($a, "email_primary")){
			$email_primary = $a->email_primary;	
		}else{
			$email_primary = "N/A";
		}
		if(property_exists($a, "Phone")){
			$Phone = $a->Phone;	
		}else{
		$Phone = "N/A";	
		}
		if(property_exists($a, "timezone")){
			$timezone = $a->timezone;	
		}else{
			$timezone = "N/A";
		}
		if(property_exists($a, "Company")){
			$Company = $a->Company;
				  }else{
			 $Company = "N/A"; 
				  }
		
   echo "<li><div class='edited_add_staff'>";
		 echo "<span class='text-primary'>".$f_name." ".$l_name." </span>";
		 echo "<div class='edited_add_staff-details' style='display:none'>";
         echo "<div class='panel panel-danger'>";
         echo "<div class='panel-heading'>Added Staff</div>";
         echo "<div class='panel-body'>";
		 echo "<b>Staff Name:</b> ".$f_name." ".$l_name."</br>";
		 echo "<b>Staff Email:</b> ".$email_primary."</br>";
		 echo "<b>Staff Phone:</b> ".$Phone."</br>";
		 echo "<b>Staff Timezone:</b> ".$timezone."</br>";
		 echo "<b>Staff Company:</b> ".$Company."</br>";
		 echo "<a href='".base_url('accounts/edit/'.$id)."'>View Full Record</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div></li>";
			}else{
			$f_name = "N/A";
		$l_name = "";
	 	 }
		}
		
		
		break;
    }
	if($a_type == 'editeds'){
		$quotesData   = explode(',', str_replace(array('editedCustomer:', '|editeds'), '', $actionContent));
		array_splice($quotesData, 0, 1);
	
		foreach($quotesData as $entry_id){
			$id = $entry_id;
		    $a = get_user_details($id);
		if($a != ""){
			if(property_exists($a, "first_name")){
		$f_name = $a->first_name;
		 }else{
			 $f_name = "";
		 }
		 if(property_exists($a, "last_name")){
			$l_name = $a->last_name; 
		 }else{
			$l_name = ""; 
		 }
		
		if(property_exists($a, "email_primary")){
			$email_primary = $a->email_primary;	
		}else{
			$email_primary = "N/A";
		}
		if(property_exists($a, "Phone")){
			$Phone = $a->Phone;	
		}else{
		$Phone = "N/A";	
		}
		if(property_exists($a, "timezone")){
			$timezone = $a->timezone;	
		}else{
			$timezone = "N/A";
		}
		if(property_exists($a, "Company")){
			$Company = $a->Company;
				  }else{
			 $Company = "N/A"; 
				  }
		 
		
   echo "<li><div class='editedquotescust'>";
		 echo "<span class='text-primary'>".$f_name." ".$l_name." </span>";
		 echo "<div class='custedited-details' style='display:none'>";
         echo "<div class='panel panel-danger'>";
         echo "<div class='panel-heading'>Added customer</div>";
         echo "<div class='panel-body'>";
		 echo "<b>Customer Name:</b> ".$f_name." ".$l_name."</br>";
		 echo "<b>Customer Email:</b> ".$email_primary."</br>";
		 echo "<b>Customer Phone:</b> ".$Phone."</br>";
		 echo "<b>Customer Timezone:</b> ".$timezone."</br>";
		 echo "<b>Customer Company:</b> ".$Company."</br>";
		 echo "<a href='".base_url('accounts/edit/'.$id)."'>View Full Record</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div></li>";
		}
		 }	
	}
	
	////////////////for supplier////////////////////////
	if($a_type == 'editedst'){
		$quotesData   = explode(',', str_replace(array('editedStaff:', '|editedst'), '', $actionContent));
		array_splice($quotesData, 0, 1);
		foreach($quotesData as $entry_id){
		$id = $entry_id;
		$a = get_user_details($id);
		if($a != ""){
			if(property_exists($a, "first_name") && property_exists($a, "last_name") ){
		$f_name = $a->first_name;
		$l_name = $a->last_name;
		 }else{
			$f_name = "N/A";
		$l_name = "";
		 }
		
		if(property_exists($a, "email_primary")){
			$email_primary = $a->email_primary;	
		}else{
			$email_primary = "N/A";
		}
		if(property_exists($a, "Phone")){
			$Phone = $a->Phone;	
		}else{
		$Phone = "N/A";	
		}
		if(property_exists($a, "timezone")){
			$timezone = $a->timezone;	
		}else{
			$timezone = "N/A";
		}
		if(property_exists($a, "Company")){
			$Company = $a->Company;
				  }else{
			 $Company = "N/A"; 
				  }
		}else{
			$f_name = "N/A";
		$l_name = "";
		}
    echo "<li><div class='addedquotesstaff'>";
		 echo "<span class='text-primary'>".$f_name." ".$l_name." </span>";
		 echo "<div class='staffad-details' style='display:none'>";
         echo "<div class='panel panel-danger'>";
         echo "<div class='panel-heading'>Added Staff</div>";
         echo "<div class='panel-body'>";
		 echo "<b>Staff Name:</b> ".$f_name." ".$l_name."</br>";
		 echo "<b>Staff Email:</b> ".$email_primary."</br>";
		 echo "<b>Staff Phone:</b> ".$Phone."</br>";
		 echo "<b>Staff Timezone:</b> ".$timezone."</br>";
		 echo "<b>Staff Company:</b> ".$Company."</br>";
		 echo "<a href='".base_url('accounts/edit/'.$id)."'>View Full Record</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div></li>";
			
		}
	 }
	 
	 
	 if($a_type == 'deletedst'){
		$quotesData   = explode(',', str_replace(array('deletedStaff:', '|deletedst'), '', $actionContent));
		array_splice($quotesData, 0, 1);
		
		foreach($quotesData as $quotesData_id){
		$id = $quotesData_id;
		$a = get_user_details($id);
		if($a != ""){
			if(property_exists($a, "first_name") && property_exists($a, "last_name") ){
		$f_name = $a->first_name;
		$l_name = $a->last_name;
		 }else{
			$f_name = "N/A";
		$l_name = "";
		 }
		
		
		if(property_exists($a, "email_primary")){
			$email_primary = $a->email_primary;	
		}else{
			$email_primary = "N/A";
		}
		if(property_exists($a, "Phone")){
			$Phone = $a->Phone;	
		}else{
		$Phone = "N/A";	
		}
		if(property_exists($a, "timezone")){
			$timezone = $a->timezone;	
		}else{
			$timezone = "N/A";
		}
		if(property_exists($a, "Company")){
			$Company = $a->Company;
				  }else{
			 $Company = "N/A"; 
				  }
   echo "<li><div class='deletedquotesstaff'>";
		 echo "<span class='text-primary'>".$f_name." ".$l_name." </span>";
		 echo "<div class='satffdel-details' style='display:none'>";
         echo "<div class='panel panel-danger'>";
         echo "<div class='panel-heading'>Deleted Staff</div>";
         echo "<div class='panel-body'>";
		 echo "<b>Staff Name:</b> ".$f_name." ".$l_name."</br>";
		 echo "<b>Staff Email:</b> ".$email_primary."</br>";
		 echo "<b>Staff Phone:</b> ".$Phone."</br>";
		 echo "<b>Staff Timezone:</b> ".$timezone."</br>";
		 echo "<b>Staff Company:</b> ".$Company."</br>";
		 echo "<a href='".base_url('accounts/edit/'.$id)."'>View Full Record</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div></li>";
			}else{
			$f_name = "N/A";
		$l_name = "";
		}
		}
	 }
	 
	 
	 
	///////////for deleted user////////////////////
	if($a_type == 'deletedc'){
		$quotesData   = explode(',', str_replace(array('deletedCustomer:', '|deletedc'), '', $actionContent));
		
		$a = get_user_details($quotesData[1]);
		if($a != ""){
			if(property_exists($a, "first_name")){
		$f_name = $a->first_name;
		 }else{
			 $f_name = "";
		 }
		 if(property_exists($a, "last_name")){
			$l_name = $a->last_name; 
		 }else{
			$l_name = ""; 
		 }
		 
		  if(property_exists($a, "email_primary")){
			$email_primary = $a->email_primary;	
		}else{
			$email_primary = "N/A";
		}
		if(property_exists($a, "Phone")){
			$Phone = $a->Phone;	
		}else{
		$Phone = "N/A";	
		}
		if(property_exists($a, "timezone")){
			$timezone = $a->timezone;	
		}else{
			$timezone = "N/A";
		}
		if(property_exists($a, "Company")){
			$Company = $a->Company;
				  }else{
			 $Company = "N/A"; 
				  }
		  
  echo "<li><div class='deletedquotescust'>";
		 echo "<span class='text-primary'>".$f_name." ".$l_name." </span>";
		 echo "<div class='cust-details' style='display:none'>";
         echo "<div class='panel panel-danger'>";
         echo "<div class='panel-heading'>Deleted customer</div>";
         echo "<div class='panel-body'>";
		 echo "<b>Customer Name:</b> ".$f_name." ".$l_name."</br>";
		 echo "<b>Customer Email:</b> ".$email_primary."</br>";
		 echo "<b>Customer Phone:</b> ".$Phone."</br>";
		 echo "<b>Customer Timezone:</b> ".$timezone."</br>";
		 echo "<b>Customer Company:</b> ".$Company."</br>";
		 echo "<a href='".base_url('accounts/edit/'.$quotesData[1])."'>View Full Record</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div></li>";
			}else{
			 $f_name = "N/A";
			 $l_name = "";
		 }
	}
	
	//////////////for supplier////////////////////
	
	if($a_type == 'deleteds'){
		$quotesData   = explode(',', str_replace(array('deletedSupplier:', '|deleteds'), '', $actionContent));
		
		$a = get_user_details($quotesData[1]);
		if($a != ""){
			if(property_exists($a, "first_name")){
		$f_name = $a->first_name;
		 }else{
			 $f_name = "";
		 }
		 if(property_exists($a, "last_name")){
			$l_name = $a->last_name; 
		 }else{
			$l_name = ""; 
		 }
		 if(property_exists($a, "email_primary")){
			$email_primary = $a->email_primary;	
		}else{
			$email_primary = "N/A";
		}
		if(property_exists($a, "Phone")){
			$Phone = $a->Phone;	
		}else{
		$Phone = "N/A";	
		}
		if(property_exists($a, "timezone")){
			$timezone = $a->timezone;	
		}else{
			$timezone = "N/A";
		}
		 if(property_exists($a, "Company")){
			$Company = $a->Company;
				  }else{
			 $Company = "N/A"; 
				  }
		 
   echo "<li><div class='delquotessup'>";
		 echo "<span class='text-primary'>".$f_name." ".$l_name." </span>";
		 echo "<div class='supdel-details' style='display:none'>";
         echo "<div class='panel panel-danger'>";
         echo "<div class='panel-heading'>Deleted Supplier</div>";
         echo "<div class='panel-body'>";
		 echo "<b>Customer Name:</b> ".$f_name." ".$l_name."</br>";
		 echo "<b>Customer Email:</b> ".$email_primary."</br>";
		 echo "<b>Customer Phone:</b> ".$Phone."</br>";
		 echo "<b>Customer Timezone:</b> ".$timezone."</br>";
		 echo "<b>Customer Company:</b> ".$Company."</br>";
		 echo "<a href='".base_url('accounts/edit/'.$quotesData[1])."'>View Full Record</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div></li>";
	}else{
			 $f_name = "N/A";
			 $l_name = "";
		 }
	}
	if($a_type == 'editedsup'){
		$quotesData   = explode(',', str_replace(array('editedsupplier:', '|editedsup'), '', $actionContent));
		array_splice($quotesData, 0, 1);
		foreach($quotesData as $sup_id){
		$id = $sup_id;
		$a = get_user_details($id);
		if($a != ""){
			if(property_exists($a, "first_name") && property_exists($a, "last_name") ){
		$f_name = $a->first_name;
		$l_name = $a->last_name;
		 }else{
			$f_name = "N/A";
		$l_name = "";
		 }
		 
		if(property_exists($a, "first_name")){
			$f_name = $a->first_name;
		}else{
			$f_name = "";
			}
		
		if(property_exists($a, "email_primary")){
			$email_primary = $a->email_primary;	
		}else{
			$email_primary = "N/A";
		}
		if(property_exists($a, "Phone")){
			$Phone = $a->Phone;	
		}else{
		$Phone = "N/A";	
		}
		if(property_exists($a, "timezone")){
			$timezone = $a->timezone;	
		}else{
			$timezone = "N/A";
		}
		if(property_exists($a, "Company")){
			$Company = $a->Company;
				  }else{
			 $Company = "N/A"; 
				  }
		
  echo "<li><div class='addedquotessup'>";
		 echo "<span class='text-primary'>".$f_name." ".$l_name." </span>";
		 echo "<div class='sup-details' style='display:none'>";
         echo "<div class='panel panel-danger'>";
         echo "<div class='panel-heading'>Deleted Supplier</div>";
         echo "<div class='panel-body'>";
		 echo "<b>Supplier Name:</b> ".$f_name." ".$l_name."</br>";
		 echo "<b>Supplier Email:</b> ".$email_primary."</br>";
		 echo "<b>Supplier Phone:</b> ".$Phone."</br>";
		 echo "<b>Supplier Timezone:</b> ".$timezone."</br>";
		 echo "<b>Supplier Company:</b> ".$Company."</br>";
		 echo "<a href='".base_url('accounts/edit/'.$sup_id)."'>View Full Record</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div></li>";
			}else{
			$f_name = "N/A";
		$l_name = "";
		}
	} 
	 }
		 echo "<li>Action Notes: ".((empty($actionNotes))?'n/a':$actionNotes)."</li>";
	
  }
}
if( !function_exists('action_dead_lead_template') ){
  function action_dead_lead_template(){
    /**
     * template for dead lead action
     */ 
  }
}
if( !function_exists('action_manual_entry_template') ){
  function action_manual_entry_template($action_details, $p_count=NULL){
    /**
     * template fot manual entry action
     */
	 
	 if(strpos($action_details['action_notes'], '>^') !== false){ 
      $actionNotes    = substr($action_details['action_notes'], 0, strpos($action_details['action_notes'], '>^'));

    } 
	 if(strpos($action_details['action_notes'], '^<') !== false){
      $historyActId   = substr($action_details['action_notes'], 0, strpos($action_details['action_notes'], '^<')); 
      $actionNotes    = substr($action_details['action_notes'], strpos($action_details['action_notes'], '^<')+2);
      $historyNotes   = $ci->action_model->get_notes($historyActId);
      
      if(!empty($historyNotes)){
      $historyNotes   = (strpos($historyNotes, '^<') !== false)?preg_replace('/^.*<\s*/', '', $historyNotes):((strpos($historyNotes, '>^') !== false)?strstr($historyNotes, '>^', true):$historyNotes);
      $his_notes = $historyNotes;
	  }
	 }
	if(!empty($action_details['action_notes'])){
	$actionNotes = $action_details['action_notes'];	
	}else{
		$actionNotes = "N/A";
		}
   
    $entry_id = $action_details['entry_id'];

    $ci =& get_instance();
	$ci->load->database();
 		$dbname = $ci->session->userdata('database_name');
		$db_usr  = $ci->session->userdata('db_name');
		$db_pass = $ci->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$ci->db = $ci->load->database($config_app,TRUE);
		}
   
	 $ci->load->model('action_model');
	 $actionContent    = $action_details['action_entry'];
     $actionContent_array_value = substr($actionContent,0, strpos($actionContent, ':') );
	
     $staff            = substr($actionContent, strpos($actionContent, '|')+1 );
	 $cust             = substr($actionContent,0, strpos($actionContent, '|'));
	 $heading_asgn     = substr($actionContent, strpos($actionContent, '_')+1);
	 $staff_assign     =  explode(',', $staff);
	  if(!empty($cust)){
	 $cust_assign      =  explode(',', $cust);
	  }else{
		$cust_assign = "";  
	  }
	 $headingss_assign =  explode(',', $heading_asgn);
     if(!empty($cust_assign)){
		 $customerCount    = count($cust_assign);
	 }else{
	 $customerCount    = "";
	 }
	
     $headingCount     = count($headingss_assign);
	 if($headingCount == 2){
	  $staffCount       = count($staff_assign)-1;
	 }else{
		 $staffCount       = count($staff_assign); 
	 }
    if(!empty($customerCount)){
    echo "<li>".$customerCount.(($customerCount>1)?" Customers":" Customer")." Added</li>"; 
	}
    echo "<li>".$staffCount." Staff ".(($staffCount>1)?"Members":"member")." Assigned</li>";
    echo "<li>".$headingCount." Filter ".(($headingCount>1)?"Headings":"Heading")." Assigned</li>";

    if(isset($p_count)){
      echo "<li>".$p_count." Product".(($p_count > 1)?"s":"")." Added for consideration</li>";
      $p_count = null;
    }
	 echo '<li>Action Notes: '.strip_tags($actionNotes); ?>
	 <button type="button" class="btn btn-warning btn-xs ed_note act_entry" id="edit_notee" data-entry="<?php echo $entry_id; ?>" data-notevalue="<?php echo strip_tags($actionNotes); ?>">Edit Note</button>
	 
		<button type="button" class="btn btn-warning btn-xs edit-history-note act_entry" data-toggle="modal" data-target="#view_edits_modal">View Edit History</button>						
								<div class="current-note-info" style="display: none;">
										<b>Current Version</b><br>
                                      <b> Action Notes: </b> <?php echo strip_tags($actionNotes); ?>  
								</div>
	<?php echo '</li>'; 
  }
}

if( !function_exists('action_others_template') ){
  function action_others_template($actionDetails){
    /**
     * template fot others action
     */
	    echo " <li>Administration Type: Notes Edited  </li>";
	    echo "<div class='entry-note-history' style='display:none'>";
	    echo "<b>Previous Version</b><br>";
		echo "<b>Action Notes: </b>". strip_tags($actionDetails['action_notes']);	
        echo "</div>";
  }
}

if( !function_exists('show_template_by_action') ){
  function show_template_by_action($actionDetails, $entry_id, $entry_model, $p_count=NULL){
    /**
     * shows the action in their perspective global layout
     */
    $ci =& get_instance();
	$ci->load->database();
 		$dbname = $ci->session->userdata('database_name');
		$db_usr  = $ci->session->userdata('db_name');
		$db_pass = $ci->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$ci->db = $ci->load->database($config_app,TRUE);
		}
    $action_type = $actionDetails['action_type'];

    /** ********** function arguments/parameters ***********
        actionDetails =>  action_id, entry_id, action_type, action_source, action_direction, action_status, action_notes, action_content, date, action_schedule, action_author
        entry_model => instance of entry_model
        ************************************************* **/
    
    switch( $action_type ){
      case 2: //email
        action_email_template($actionDetails, $entry_model);
        break;
      case 20: // administration
        action_administration_template($actionDetails);
        break;
      case 23: //manual entry
        action_manual_entry_template($actionDetails, ((isset($p_count))?$p_count:NULL));
        break;
		case 27: //manual entry
        action_manual_entry_template($actionDetails, ((isset($p_count))?$p_count:NULL));
        break;
		case 24: //manual entry
        action_others_template($actionDetails, ((isset($p_count))?$p_count:NULL));
        break;
		 case 1: //note
        action_note_template($actionDetails, $entry_model);
        break;
      default:
        break;
    }
  }
}
if( !function_exists('get_action_notes') ){
  function get_action_notes($action_id){
    $ci =& get_instance();
	$ci->load->database();
 		$dbname = $ci->session->userdata('database_name');
		$db_usr  = $ci->session->userdata('db_name');
		$db_pass = $ci->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$ci->db = $ci->load->database($config_app,TRUE);
		}
    $query = $ci->db->select('action_notes')->from('action_record')->where('action_id', $action_id)->get();
    
    if($query->num_rows() > 0){
      $action_notes = $query->row()->action_notes;

      if(strpos($action_notes, '>^') === false){ 
        /**
         * if action notes have not been updated
         */
        return $action_notes;
        
      } else{
        $contents_array = explode('>^',$action_notes); 
        $newAction_id   = $contents_array[count($contents_array)-1]; 
        $get_notes      = $ci->db->select('action_id, action_notes')->from('action_record')->where('action_id', $newAction_id)->get();

        if($get_notes->num_rows() > 0){
          return array(
            'action_notes' => $get_notes->row()->action_notes,
            'action_id'    => $get_notes->row()->action_id
            );
        } else{
          return false;
        }
      }

    } else{
      return false;
    }
  }
}
///////////code to add productt date///////////

if( !function_exists('get_action_schedule') ){
  function get_action_schedule($action_id){
    $ci =& get_instance();
	$ci->load->database();
 		$dbname = $ci->session->userdata('database_name');
		$db_usr  = $ci->session->userdata('db_name');
		$db_pass = $ci->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$ci->db = $ci->load->database($config_app,TRUE);
		}
    $query = $ci->db->select('action_schedule')->from('action_record')->where('action_id', $action_id)->get();
    
    if($query->num_rows() > 0){
      $action_schedule = $query->row()->action_schedule;

      if(strpos($action_schedule, '>^') === false){ 
        /**
         * if action notes have not been updated
         */
        return $action_schedule;
        
      } else{
        $contents_array = explode('>^',$action_date); 
        $newAction_id   = $contents_array[count($contents_array)-1]; 
        $get_date      = $ci->db->select('action_id, action_schedule')->from('action_record')->where('action_id', $newAction_id)->get();

        if($get_date->num_rows() > 0){
          return array(
            'date' => $get_date->row()->action_schedule,
            'action_id'    => $get_date->row()->action_id
            );
        } else{
          return false;
        }
      }

    } else{
      return false;
    }
  }
}
///////code for edit date//////

  if(!function_exists('geteditdateInfo')){
	function geteditdateInfo($entry_id){
		$ci =& get_instance();
		$ci->load->database();
 		$dbname = $ci->session->userdata('database_name');
		$db_usr  = $ci->session->userdata('db_name');
		$db_pass = $ci->session->userdata('db_pass');
		if($dbname !="")
		{
			  $config_app = switch_database($dbname, $db_usr, $db_pass);
			$ci->db = $ci->load->database($config_app,TRUE);
		}
		$query = $ci->db->select('edit_date')->from('edit_history')->where('entry_id', $entry_id)->get();
		if($query->num_rows() > 0){
			return $query->result();
		} else
			return false;
	}
}
