<?php  $session_data = $this->session->userdata('logged_in');
       $ses_timezone =$session_data['timezone_sess'];
	   $ses_currency =$session_data['currency'];
		$user_type = $session_data['usertype']; ?>
        <?php $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype); ?>
 <style>
body{
	background-color:#FFF !important;
}
.bars span{
		margin-left:30%	
	}
.table > thead > tr > th, .table > tbody > tr > th, 
.table > tfoot > tr > th, .table > thead > tr > td, 
.table > tbody > tr > td, .table > tfoot > tr > td {
	
    border: none aliceblue;
}
	</style>
   
            <?php if($ustype == 'businessaccount'){ ?>
 <div class="bars" style="background-color:#f0ad4e;">
 <?php
  echo '<span style="color:#fff;">You are logged into your Business Management Account</span>'; ?>
  </div>
  <?php } ?>
  <?php if($ustype == 'businessaccountmanager'){ ?>
   <div class="bars" style="background-color:#337ab7">
   <?php echo '<span style="color:#fff;">You are logged into the Business Action Complete - Owner Account</span>';?>
</div>
<?php }?>   
<?=(isset($error))?$error:''?>
	<?php $attributes = array('class' => 'form-signup', 'id'=>'create'); ?>
	<?php echo form_open('accounts/create_user', $attributes) ?>
    <div class="row">
     <div class="col-md-10">
      <?php if(isset($_REQUEST['uid'])){ ?>
      <h3 class="form-signup-heading">Create a New User</h3>
      <h4>Communication Assignment Wizard - Step 1</h4>
       <div class="row">
        <p>To create a new user fill in the fields below. You can copy and paste user information from the incoming communication by clicking on the light blue button "View Communication."</p>
        </div>
	 <?php }else{?>
     
      
    	 <h2 class="form-signup-heading">Create New User</h2>
         <?php }?>
     </div>
      <div class="col-md-2">
        <div class="gap"></div>
        <div class="gap"></div>
        <?php if(isset($_REQUEST['uid'])){ ?>
        <a class="btn btn-default" href="<?php echo base_url().'crmmails/viewmails'; ?>" style="float:right">Cancel</a>
        <?php }else{?>
       <a class="btn btn-default" style="float:right" href="<?php echo base_url('accounts/'); ?>">Cancel</a>
         </div>
          <?php }?>
     </div>
       <?php if(isset($_REQUEST['uid'])){?>
      <div class="row">
      <div class="col-md-2 u_email">
        <button name="s_email" id="select_email" class="link-class btn btn-info se_email" style="margin-top:15%">View Comunication</button>
         </div>
      <div class="col-md-10">
    <div class="col-md-10 su_email" style="height:50%;width:100%;overflow:scroll;overflow-y:scroll;overflow-x:scroll; margin-top:15%;">
 <a id="close" href="#" class="close">X</a>
    <script>
   jQuery(document).ready(function($) {
  jQuery('#close').click(function() {
    jQuery('.su_email').hide();
  });
});
	</script>
      <div  class ='col-md-5 demo'>
       <?php echo $_REQUEST['f_name'];?>
        </div>
        <div class='col-md-3 subject'>
        <?php echo $_REQUEST['subject'];?>
         </div>
	 <div class='col-md-3 date'>
       <?php echo  $_REQUEST['edate']?>

       </div>
		 <div class ='col-md-12 mess1' id='message3'>
          <?php echo $_REQUEST['uid'];?>
          <br />
         
		<?php
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
if($dbname){
 $dir = $_SERVER['DOCUMENT_ROOT'] . '/dev/action_station/email_files/' . $dbname . "/" . $username . '/';
}else{
 $dir = $_SERVER['DOCUMENT_ROOT'] . '/dev/action_station/email_files/ceocamer_cam_crm/camsaurustest@gmail.com/';	
}
 //$text = glob("{$dir}*.{txt,php}", GLOB_BRACE);
 $file_name = basename($_REQUEST['file_name']);
               // $file_name; // outputs 'file'
                 //$file = basename($file_name);
                 $sample = file_get_contents($dir.$file_name);
				$message = get_Contents($sample, '<message>', '<endmessage>');
                // file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/action_station/debug.txt", "sample contains : " . $sample . PHP_EOL, FILE_APPEND);
         // refine embedded css <style> to avoid bleeding or conficting with items outside email content
                    /*
                    (for debugging purposes) SID : dXUXB1SO4L UID : 39049 EmailID : scalesplus.cameron@gmail.com (font market sans)
                    (for debugging purposes) SID : v0JMYZwz2n UID : 39116 EmailID : scalesplus.cameron@gmail.com blue rectangles in every mail and buttons on the right

                    testcase bleeding css */
                    $a = '<style';
                    $uniqueClass = "su_email";
                    $html = $message;
                      if (strpos($message, $a) !== false) {
                          // echo 'Contains style which can bleed out of this div<br>';
                          /* $numberOfTags = substr_count($message, $a);
                          echo $numberOfTags.' style tags found'; */
                          
                          $html = $message;
                          $needle = $a;
                          $lastPos = 0;
                          $positions = array();

                          while (($lastPos = strpos($html, $needle, $lastPos))!== false) {
                              $positions[] = $lastPos;


                              $anglePos = strpos( $html, ">",$lastPos)+1;
                              $html = substr_replace($html,"<style type=\"text/less\">",$lastPos,$anglePos-$lastPos);
                              $anglePos = strpos( $html, ">",$lastPos)+1;
                              $html = substr_replace($html,".su_email"."{",$anglePos,0);
                              $endvalue = strpos($html,"</style",$lastPos);
                              $html = substr_replace($html,"}",$endvalue,0);

                              $lastPos = $lastPos + strlen($needle);
                          }

                      }
                      else{
                        // echo 'No style can bleed outside this div';

                      }
                     //end of refining
                     $message = $html;
                    
          echo $message;//$message;//$_REQUEST['message'];
          
		   if(isset($_REQUEST['attachment']) || isset($_REQUEST['attach_1']) || isset($_REQUEST['attach_2'])){
		   $attachs = $_REQUEST['attachment'];
    if(!empty($attachs)&& $attachs != "N/A"){?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js" ></script> 

     <div class="col-md-3 box" style="float:left">
    <?php
	echo "Attachments: "."<img style='width:100%; height:auto;' src='".$attachs."'/>";
	echo "</br>"; }?>
    </div>
    <?php 
    $attachs_pdf = $_REQUEST['attach_1'];
    $attachs_pdf = chop($attachs_pdf,"'");
	if(!empty($attachs_pdf) && $attachs_pdf != "N/A"){
	?>
    <div class="col-md-3 box" style="float:left">
	<embed src="<?php echo $attachs_pdf; ?>" width="800px" height="2100px" />
  <?php	echo "</br>"; }?>
  </embed>
  </div>
  <?php
    $attachs_doc = $_REQUEST['attach_2'];
	$attachs_doc = chop($attachs_doc, "'");
	$attachname = basename($attachs_doc);
	if(!empty($attachs_doc) && $attachs_doc != "N/A"){
	?>
    <div class="col-md-3 box" style="float:left">
	<strong>Attachment Name:</strong> <a href="<?php echo $attachs_doc; ?>"><?php echo $attachname?></a>
    </div>
		   <?php }}
		  ?>
        </div>
	 </div>
      </div>
      </div>
           <?php } ?>
     
     <?php if($this->session->flashdata('insert_response')){
    	 echo $this->session->flashdata('insert_response');
     }
     if($this->session->flashdata('user_exists')){
		echo $this->session->flashdata('user_exists');
	}
      if($this->session->flashdata('error')){
		echo $this->session->flashdata('error');
	}

   if($this->session->flashdata('User_Data_Match')){
   foreach($this->session->flashdata('User_Data_Match') as $mach){
    	echo $mach;
   }
    } ?>
      
         <div class="form-group">
         <fieldset style="margin-bottom:0px">
         
                <label for="usertype" class="">User Type</label>
                <?php $session_data = $this->session->userdata('logged_in'); ?>
            <?php $user_type = $session_data['usertype']; ?>
            <?php $utype = get_user_type($user_type);
                $ustype= $utype->type_name ?>

            <?php $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
             '<br>'.$ustype.'<br>';?>
            
             <?php if($ustype =='editor'){?>
             <select name="usertype" id="usertype" class="form-control">
            		<option value='3'>Author</option>
                    <option value='6'>Contributor</option>
                    <option value='2'>Customer</option>
                    <option value='9'>Supplier</option>
                </select>

                <?php }else if($ustype =='administrator'){ ?>
					 <select name="usertype" id="usertype" class="form-control">
                      <option value='5'>Editor</option>
            		<option value='3'>Author</option>
                    <option value='6'>Contributor</option>
                    <option value='2'>Customer</option>
                    <option value='9'>Supplier</option>
                </select>

				<?php }
                else
                    {
                ?>
              
                <select name="usertype" id="usertype" class="form-control" onchange="setTextField(this)">
                     <option value>Select</option>
                     <option value='1'>Super Admin</option>
                     <option value='4'>Administrator</option>
                     <option value='5'>Editor</option>
                     <option value='3'>Author</option>
                     <option value='6'>Contributor</option>
                     <?php if($ustype =='businessaccountmanager'){?>
                     <option value='8'>Business Account</option>
                     <?php }?>
                    <option value='2'   <?php if(isset($_REQUEST['uid'])){?>selected="selected" <?php } if(isset($entbtb)){ ?>selected="selected" <?php  } ?>>Customer</option>
                    <option value='9'<?php if(isset($entsup)){?>selected="selected" <?php }?>>Supplier</option>
               </select>
               
               <input id="make_text" type = "hidden" name = "make_text" value = "<?php if(isset($_REQUEST['uid'])){echo "Customer" ;} if(isset($entbtb)){ echo "Customer"; } if(isset($entsup)){ echo "Supplier"; }?>" />
		 <script type="text/javascript">
        function setTextField(ddl) {
            document.getElementById('make_text').value = ddl.options[ddl.selectedIndex].text;
        }
         </script>
                <?php } ?>
             </fieldset>
             <?php if(form_error('usertype')){ ?>
             <?php echo form_error('usertype'); } ?>
     <div class="col-md-12 8 box">
      
             <fieldset>
            <label for="first_name">First Name</label>
            <input type="hidden" name="information_type[]" value="first_name">
            <input type="text" name="information_text[]" id="first_name" value="" class="form-control">
        </fieldset>
        <fieldset>
            <label for="last_name">Last Name</label>
            <input type="hidden" name="information_type[]" value="last_name">
            <input type="text" name="information_text[]" id="last_name" value="" class="form-control">
        </fieldset>
      
       </div>
      
         <legend class="lge">Email Adresses:</legend>
        <div class="col-md-12 email_f">
        <div class="col-md-12 email_row">
        <div class="col-md-8 em">
        <label for="email" class="p">Email <span> 1</span></label>  
        <fieldset>
            <input type="hidden" name="information_type[]" value="email_primary" class="Primaryemail">
            <input type="email" name="information_text[]" id="email" value="  <?php if(isset($_REQUEST['uid'])){echo $_REQUEST['uid'];}?>" class="form-control">
            <div class="col-md-12 em em_label">
            <label for="emaillabel" class="pp">Email Label <b class="l_id"> 1</b></label> - <em style="font-size: 13.2px;">( Used internally to describe the email purpose when there is more than one email address )</em>
             <div class="col-md-8">
            <input type="email"  id="otherlabel" class="form-control a" placeholder="enter your email label here">
            </div></div>
        </fieldset>
        </div>
         <div class="col-md-2 primary">
        <fieldset>

        <input type="radio" checked="checked"  id="pri_text" name="Primary" value="email_primary" class="email_radio"><label for="primary_email" class="p">Primary</label>
         
      </fieldset>
      </div>
     <div class="col-md-2 primary1">
     	<span class="remove">&times;</span>
        </div>
        </div>

        <fieldset style="float:right">
            <button class="addanother"> +</button>
            <button class="addminus"> -</button>
        </fieldset>
        </div>
        <legend class="lge">Telephony:</legend>
        <div class="col-md-12 email_f">
        <div class="col-md-12 contact">
        <div class="col-md-3">
        <fieldset>
           <input type="radio"  class="radiogroup" name="radiogroup" value="Phone">
            <label for="phone">Phone</label>
        </fieldset>
        </div>
        <div class="col-md-3">
        <fieldset>
           <input type="radio" class="radiogroup" name="radiogroup" value="Fax">
            <label for="fax">Fax</label>
        </fieldset>
        </div>
        <div class="col-md-3">
        <fieldset>
            <input type="radio" class="radiogroup" name="radiogroup" value="Mobile">
             <label for="mobile">Mobile</label>
        </fieldset>
        </div>
         <div class="col-md-3">
     	<span class="remove1">&times;</span>
        </div>
        <div class="col-md-12">
        <fieldset>
            <input type="hidden" name="information_type[]" value="Phone" class="contact1">
            <input type="text" name="information_text[]" id="fmp" value="" class="form-control">
        </fieldset>
        </div>
       </div>
       <fieldset style="float:right">
            <button class="addmore">+</button>
              <button class="addmores-">-</button>
        </fieldset>
        </div>

        <div class="col-md-12" id="com_info">
        <div class="col-md-4">
        <fieldset>
           <input type="radio" class="comp_detail" id="cmny" name="comp_detail" value="Company">
            <label for="company">Company</label>
        </fieldset>
        </div>
        <div class="col-md-4">
        <fieldset>
             <input type="radio" class="comp_detail" name="comp_detail" value="NotForProfit">
             <label for="notforprofit">Not-For-Profit</label>
        </fieldset>
        </div>
        <div class="col-md-4">
        <fieldset>
           <input type="radio" class="comp_detail" name="comp_detail" value="GovernmentDepartment">
            <label for="govtdept">Government Department</label>
        </fieldset>
        </div>
        <div class="col-md-12">
        <fieldset>
        <input type="hidden" name="information_type[]" value="Company" id="comp1">
            <input type="text" name="information_text[]" id="comp" value="" class="form-control"  <?php if($ustype =='businessaccountmanager'){?> required="required"<?php } ?>>
        </fieldset>
        </div></div>
       <div class="col-md-12 website">
         <fieldset>
          <label for="website">Website Url</label>
         <input type="hidden" name="information_type[]" value="website">
            <input type="text" name="information_text[]" id="website_name" value="" class="form-control">
         </fieldset></div>
        <div class="col-md-12 8 box">
        <fieldset>
            <label for="posTitle">Position Title</label>
            <input type="hidden" name="information_type[]" value="position_title">
            <input type="text" name="information_text[]" id="posTitle" value="" class="form-control">
        </fieldset>
        </div>
       
          <legend style="margin-top:15px" class="lge">Delivery Address:</legend>
          <?php /*?> <input type="checkbox" name="billingtoo" id="BDcheckbox" >
<em>Check this box if  Billing Address and Delivery Address are the same.</em><br /><?php */?>
 
       <div class="col-md-12 adddress">
         <fieldset>
         <div class="col-md-12">
         <fieldset>
         
        <label for="Address1">Address Line 1<span class="required"></span></label>
            <input type="hidden" name="information_type[]" value="Delivery_Address">
            <input type="text" name="information_text[]" id="Address1" value="" class="form-control">
      
        <label for="Address2">Address Line 2<span class="required"></span></label>
            <input type="hidden" name="information_type[]" value="Delivery_Address2">
            <input type="text" name="information_text[]" id="Address2" value="" class="form-control">
        </fieldset>
        </div>

        <div class="col-md-3" id="country-select">
        <fieldset>
            <label for="country1">Country</label>
            <input type="hidden" name="information_type[]" value="country1">
            <select onchange="print_state('states1',this.selectedIndex);" name="information_text[]" id="country1" class="form-control"></select>
       <script type="text/javascript" language="javascript">
           print_country("country1");
		</script>

        </fieldset>

        </div>
        <div class="col-md-3" id="city-select">
        <fieldset>
            <label for="state1">State</label>
            <input type="hidden" name="information_type[]" value="state1">
            <select onchange="print_cities('cities2',this.selectedIndex);" name="information_text[]" id="states1" class="form-control"></select>
       <script type="text/javascript" language="javascript">
           print_state("states1");
		   </script>


        </fieldset>
        </div>
         <div class="col-md-3">
        <fieldset>
            <label for="city1">City/Suburb<span class="required"></span></label>
            <input type="hidden" name="information_type[]" value="city1">
            <input type="text" name="information_text[]" id="cities2" value="" class="form-control">
        </fieldset>
        </div>
        <div class="col-md-3">
        <fieldset>
            <label for="Zip/Postal Code1">Zip/Postal Code<span class="required"></span></label>
            <input type="hidden" name="information_type[]" value="ZipPostal_Code1">
            <input type="text" name="information_text[]" id="Zip/Postal1 Code1" value="" class="form-control pd">
        </fieldset>
        </div>
         </fieldset>
        </div>
        <style>
		.billing {
    margin-bottom: 3%;
}
		</style>
        
          <legend class="lge">Billing Address:</legend>
          <input type="checkbox" name="billingtoo" id="BDcheckbox" >
<em>Select to enter a separate billing address.</em><br />
       <div class="col-md-12 billing billaddress">
         <fieldset>
 <div class="col-md-12">
         <fieldset>
        <label for="Address1">Address Line 1<span class="required"></span></label>
            <input type="hidden" name="information_type[]" value="Billing_Address">
            <input type="text" name="information_text[]" id="Baddress" value="" class="form-control">
       
        <label for="Address2">Address Line 2<span class="required"></span></label>
            <input type="hidden" name="information_type[]" value="Billing_Address2">
            <input type="text" name="information_text[]" id="BAddress2" value="" class="form-control">
        </fieldset>
        </div>
       <div class="col-md-3">
        <fieldset>
            <label for="country">Country</label>
            <input type="hidden" name="information_type[]" id="bcountry" value="country">
             <select onchange="print_state('states',this.selectedIndex);" name="information_text[]" id="country" class="form-control"></select>
       <script type="text/javascript" language="javascript">
           print_country("country");
       </script>

        </fieldset>
        </div>
        <div class="col-md-3">
        <fieldset>
            <label for="state">State</label>
            <input type="hidden" name="information_type[]" value="state">
           <select onchange="print_cities('cities',this.selectedIndex);" name="information_text[]" id="states" class="form-control"></select>
       <script type="text/javascript" language="javascript">
           print_state("states");
       </script>
        </fieldset>
        </div>
        <div class="col-md-3">
        <fieldset>
            <label for="city">City/Suburb<span class="required"></span></label>
            <input type="hidden" name="information_type[]" value="city">
             <input type="text" name="information_text[]" id="cities" value="" class="form-control">

        </fieldset>
        </div>
         <div class="col-md-3">
        <fieldset>
            <label for="Zip/Postal Code">Zip/Postal Code<span class="required"></span></label>
            <input type="hidden" name="information_type[]" value="ZipPostal_Code">
            <input type="text" name="information_text[]" id="Zip/Postal Code" value="" class="form-control pb">
        </fieldset>
        </div>
       
         </fieldset>
        </div>
        <style>
		.chats{
			margin-top:3%;
		}
		</style>
        <legend class="lge chats">Instant Chat Service:</legend>
        <div class="col-md-12 email_f">
        <div class="col-md-12 chating">
        <div class="col-md-10">
        <fieldset>
            <label for="chatId">Chat ID</label>
            <input type="hidden" name="information_type[]" value="Skype" class="Social_chat">
            <input type="text" name="information_text[]" id="chatId" value="" class="form-control">
        </fieldset>
        </div>
        <div class="col-md-2 cht">
        <span class="remove2">&times;</span></div>
          <div class="col-md-12 chat_vat">

          <div class="col-md-6">
            <fieldset>
          <select class="form-control checkgroup" id="schat">
           <option value="">Select your Chat</option>
          <option class="radiogroup2" name="radiogroup2" value="Skype">Skype</option>
          <option  class="radiogroup2" name="radiogroup2" value="Whatsapp">Whatsapp</option>
          <option  class="radiogroup2" name="radiogroup2" value="Viber">Viber</option>
          <option  class="radiogroup2" name="radiogroup2" value="GoogleHangouts">GoogleHangouts</option>
          <option  class="radiogroup2" name="radiogroup2" value="Snapchat">Snapchat</option>
          <option  class="radiogroup2" name="radiogroup2" value="Groupme">Groupme</option>
           <option  class="radiogroup1 r1" name="radiogroup2" value="Other">Other</option>
          </select>
          </div>
          <div class="col-md-6 other">
          <fieldset>
           <input type="hidden" name="information_type[]" value="" class="other1">
            <input type="text" name="information_text[]" id="chatId" value="" class="form-control">
        </fieldset>
          </div>

        </div>

        </div>
        <fieldset style="float:right">

            <button class="addchat">+</button>
            <button class="addchat-">-</button>
        </fieldset>
        </div>

        <div class="col-md-12 8 box">

               <fieldset class="new_fields">
              <label for="eHeading">Heading Assignment</label>

              <!-- autolete [assign customer] -->
              <div class="form-control">
                  <img src="<?php echo base_url('images/'); ?>loading_spinner.gif" class="type_search_loader" style="">
                  <ul class="tag-labels"></ul>
                   <input type="hidden" name="information_type[]" value="heading_assignment">
                  <input type="text"  id="assignHeading" value="" class="form-control tag-input" data-html="true"/>
              </div>

              <input type="hidden" name="information_text[]" id="headings" value="" required>
              <!-- end [assign customer] -->
              <script type="text/javascript">
                  <?php if(is_array($headings)){ ?>
                    var js_headings = <?php echo json_encode($headings) ?>
                  <?php } ?>

              </script>
          </fieldset>
          </div>

        <div class="col-md-12">
        <fieldset>
            <label for="timezone">Timezone</label>
            <input type="hidden" name="information_type[]" value="timezone">
            <select name="information_text[]" id="timezone" class="form-control">
            <?php 
			  $time = get_current_user_timezone();
			  if(!empty($session_data['timezone_sess'])){
				  if(is_array($timezones)){
                        foreach($timezones as $key => $value){?>
						 <option value="<?php echo $key ?>" <?php echo $selected = ($session_data['timezone_sess'] == $key) ? 'selected="selected"' : '';?>><?php echo $value ?></option><?php 
						  }
                    }
			  }
			 
		 
				?>
                    
                     
            </select>

        </fieldset>
        </div>
        <div class="col-md-12">
        <fieldset>
            <label for="currency">Preferred Currency</label>
            <input type="hidden" name="information_type[]" value="preferred_currency" >
            <select name="information_text[]" id="currency" value="" class="form-control">
             <?php 
			  $currency = get_current_user_currency();
			  if(!empty($session_data['currency'])){
			         if(is_array($currencies)){
                        foreach($currencies as $key => $value){?>
						 <option value="<?php echo $key ?>" <?php echo $selected = ($session_data['currency'] == $key) ? 'selected="selected"' : '';?>><?php echo $value ?></option><?php  }
                    } 
			  }  ?>
            </select>


        </fieldset>
        </div>
       
       <div class="col-md-12 8 box">
        <fieldset>
            <label for="dob">Date Of Birth</label>
            <input type="hidden" name="information_type[]" value="date_of_birth">
            <input type="text" name="information_text[]" id="dob" value="" class="form-control">
        </fieldset>
        </div>
       
         <div class="col-md-12 8 box" >
          <?php if($ustype == "businessaccountmanager"){
			  function randomNumber($length) {
    $result = '';

    for($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return $result;
}
			  ?>
          <div class="col-md-12">
        
            <input type="hidden" name="Business_id" id="Business_id" value="<?php echo randomNumber(5); ?>" class="form-control">
          
        </div>
          
        <div class="col-md-12">
        
        
        <?php
			 function generateRandomString($length = 7) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
			 ?>
        
            <input type="hidden" name="DB_User_Name" id="DB_User_Name" value="<?php echo generateRandomString(); ?>" class="form-control">
          
        </div>
          <?php } ?>
        <div class="col-md-12">
        <div class="col-md-10">
         <?php $user_type = $session_data['usertype']; ?>
          <?php $utype = get_user_type($user_type);
                $ustype= $utype->type_name ?>

            <?php $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype); ?>
      <?php if($ustype == "businessaccountmanager"){?>
      
      <?php
	   function random_gen(){
$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $password = array(); 
    $alpha_length = strlen($alphabet) - 1; 
    for ($i = 0; $i < 8; $i++) 
    {
        $n = rand(0, $alpha_length);
        $password[] = $alphabet[$n];
    }
    return implode($password); 
    } ?>
      <input type='hidden' id='p' name="db_pass" class="form-control" value= "<?php echo random_gen();  ?>"/><br/>
      
<?php } ?>
</div>
        </div>
        
         <div class="col-md-12">
          <?php $user_type = $session_data['usertype']; ?>
          <?php $utype = get_user_type($user_type);
                $ustype= $utype->type_name ?>

            <?php $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype); ?>
      <?php if($ustype == "businessaccountmanager"){
		
		  ?>
      
      
         <fieldset>
                <input type="hidden" size="20" id="business_id" name="business_id" class="form-control" value="" />
                 </fieldset>
                 <?php } ?>
         </div>
         </div>
        
        <div class="col-md-12">
                     <fieldset>
                 <label for="username" class="">Username:</label>
                 <input type="text" size="20" autocomplete="off" id="username" name="username" class="form-control req_field" placeholder="User Name"  onfocus="this.removeAttribute('readonly');" />
             </fieldset>
             <?php if(form_error('username')){ ?>
             <?php echo form_error('username'); } ?>
             </div>
             <div class="col-md-12">
        <fieldset>
                 <label for="password" class="">Password:</label>
                 <input type="text" size="20"  id="passowrd" name="password" class="form-control req_field" placeholder="Password" autocomplete="off"  onfocus="this.removeAttribute('readonly');"  />
                 <?php /*?><input type="checkbox" onclick="mypassowrd();">Show Password<?php */?>
             </fieldset>
             <?php if(form_error('password')){ ?>
             <?php echo form_error('password'); } ?>
         </div>
         <div class="col-md-12">
        <fieldset>
            <label for="other">Notes</label>
            <input type="hidden" name="information_type[]" value="Notes">
            <textarea name="information_text[]" id="Notes" value="" class="form-control" rows="6" cols="6" style="overflow:scroll"></textarea>

        </fieldset>
        </div>
        <input type="hidden" name="create_user" value="create_user"  />
        <input type="hidden" name ="own_type" value ="<?php echo $ustype ?>" />
       <?php if(isset($entbtb)){?>
        <input type="hidden" name="fulll" value="<?php echo $entbtb?>" id = "addentry123" />
      <?php }
	  if(isset($entsup)){ ?>
		  <input type="hidden" name="fulll1" value="<?php echo $entsup?>" id = "addentry1234" />  
	<?php } ?>
        <?php if(isset($msg)){
		  $c_user = $msg;?>
			
        <input type="hidden" name="c_user" value="<?php echo $c_user ?>" id = "hide1" />
         <input type="hidden" name="s_email" value="<?php echo $_REQUEST['uid'] ?>" id = "hide2" />
           <input type="hidden" name="subject" value="<?php echo $_REQUEST['subject'] ?>" id = "hide3" />
            <input type="hidden" name="mess" value="<?php echo ""?>" id = "hide4" />
          <?php  if(isset($_REQUEST['attachment']) || isset($_REQUEST['attach_1']) || isset($_REQUEST['attach_2'])){ ?> 
             <input type="hidden" name="attachment" value="<?php echo $_REQUEST['attachment'] ?>" id = "hide5" />
              <input type="hidden" name="attachment_pdf" value="<?php echo $_REQUEST['attach_1'] ?>" id = "hide6" />
               <input type="hidden" name="attachment_doc" value="<?php echo $_REQUEST['attach_2'] ?>" id = "hide7" />
               <?php } ?>
             <input type="hidden" name="edate" value="<?php echo $_REQUEST['edate'] ?>" id = "hide8" />
              <input type="hidden" name="fname" value="<?php echo $_REQUEST['f_name'] ?>" id = "hide9" />
              <input type="hidden" name="f_file" value="<?php echo $_REQUEST['file_name'] ?>" id = "hide10" />
              
        <?php } ?>
        <input type="hidden" name="entrubtn" value="<?php echo  $entbtb;?>" id = "entbtbs" />
         <input type="button" name="btn" value="Create User" class="btn btn-lg btn-primary btn-block" id="sbm" data-toggle="modal" data-target="#confirm-submit"/>

    </form>
    
<div class="gap"></div>
 <div class="row"><a class="btn btn-default" style="float:right" href="<?php echo base_url('accounts'); ?>">Cancel</a></div>
 <div class="gap"></div>

<style>
.dilog{
	width:800px;
	display:block;
	overflow:hidden;
}
	
</style>
<div class="modal fade col-md-12 ab" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog dilog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm Submit
            </div>
            <div class="alertresp" style="display:none"></div>
            <div class="modal-body col-md-12">
                Are you sure you want to submit the following details?
                <table class="table">
                 <tr>
                        <th>User Type</th>
                        <td id="usertype1"></td>
                    </tr>
                    
                    <tr>
                        <th>First Name</th>
                        <td id="first_name1"></td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td id="last_name1"></td>
                    </tr>
                     <tr>
                        <th>Email Addresses</th>
                        <td id="email1"></td>
                    </tr>
                    <tr>
                        <th>Telephony</th>
                        <td id="fmp1"></td>
                    </tr>
                    <tr>
                        <th>Company</th>
                        <td id="cmpnyname"></td>
                    </tr>
                    <tr>
                        <th>Website Url</th>
                        <td id="website1"></td>
                    </tr>
                    <tr>
                        <th>Position Title</th>
                        <td id="posTitle1"></td>
                    </tr>
                    <tr>
          
                    <th>Delivery Address:</th> 
                    </tr>
                   <tr>
                        <th>Address1</th>
                        <td id="Address111"></td>
                    </tr>
                   <tr>
                        <th>Address2</th>
                        <td id="Address222"></td>
                    </tr>
                     <tr>
                         <th>Country</th>
                         <td id="country111"></td>
                         <th>State</th>
                         <td id="states111"></td>
                         <th>City/Suburb </th>
                         <td id="cities111"></td>
                         <th>Zip/Postal Code</th>
                         <td id="pd1"></td>
                         
                    </tr>
                   
                    <tr class="billingaddress">
                    
                     <th>Billing Address</th></tr>
                        <tr class="billingaddress">
                        <th>Address1</th>
                        <td id="Address132"></td>
                    </tr>
                   <tr class="billingaddress">
                        <th>Address2</th>
                        <td id="Address22"></td>
                    </tr>
               
                     <tr class="billingaddress">
                         <th>Country</th>
                         <td id="country11"></td>
                         <th>State</th>
                         <td id="states11"></td>
                         <th>City/Suburb </th>
                         <td id="cities11"></td>
                         <th>Zip/Postal Code</th>
                         <td id="pb1"></td>
                         
                    </tr>
                   
                     <tr> 
                    <th>Instant Chat Service</th>
                    </tr>
                    <tr>
                    <th>Chat Id</th>
                    <td id="chatId1"></td>
                    </tr>
                    
                     <tr>
                     <td is="schat1"></td>
                     </tr>
                     <tr>
                     <th>Heading Assignment</th>
                     <td id="assignHeading1"></td>
                     </tr>
                     <tr>
                     <th>Timezone</th>
                     <td id="timezone1"></td>
                     </tr>
                     <tr> 
                     <th>Preferred Currency</th>
                     <td id="currency1"></td>
                     </tr>
                     <tr id="aab">
                     <th>Date Of Birth</th>
                     <td id="dob1"></td>
                     </tr>
                     <tr>
                        <th>Username</th>
                        <td id="username1"></td>
                    </tr>
                     <tr>
                        <th>Password</th>
                        <td id="passowrd1"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" id="submit" class="btn btn-success success mysucess">Submit</a>
            </div>
        </div>
    </div>
</div>  

<?php if(isset($_REQUEST['uid'])){?>
<div style="height:325px; width:100%; clear:both; ">
<?php }?>


