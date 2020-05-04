<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
  <script type="text/javascript">
  
	var checkIfEntriesCompleted = function(count){
		var inAction = true;
		jQuery('#entry'+count).find('.action_status'+count).each(function(){
		 if(jQuery(this).html() == "Scheduled"){
		    inAction = false;
		  }
		});
	}
</script>
 <?php $session_data = $this->session->userdata('logged_in'); ?>
<?php //$user_type = $session_data['usertype']; ?>
<?php //$user_details = get_user_details($session_data['id']); ?>

          
<style>
 /*body{
	background-color:#FFF !important;
}*/
.en_no{
color: #fff;
    background-color: #337ab7;
    border-color: #2e6da4;	
}
.nous{
 width: 940px;
    margin: 0 auto;
    display: block;
    overflow: hidden;

}
#message3 {
    overflow-y: scroll;
}
</style>
<?php if($acountuser != "ac_user"){ ?>
<style>
.need_hide{
 display:none;
}
</style>

<?php } ?>
<?php 
$session_data = $this->session->userdata('logged_in'); 
//$sessin_timezone = $session_data['timezone_sess'];
$user_type = $session_data['usertype']; ?>
<?php $user_details = get_user_details($session_data['id']); ?>
<?php  if(isset($session_data['old_time'])){
   $old_time = $session_data['old_time'];
   }

 $utype = get_user_type($user_type);
	 $ustype =$utype->type_name ;
	 $ustype=preg_replace('/\s+/', '', $ustype);
            $ustype = strtolower($ustype);
$dbname = $this->session->userdata('database_name');
			 $id = $session_data['id'];
			  $incoming_email = $this->accounts_model->systems_datas($id);
			  if($dbname){
			 foreach($incoming_email as $incoming_emails){
			  $incoming_emailss = $incoming_emails->incoming_email;
			  } }
			  
$message = htmlentities($message, ENT_COMPAT,'ISO-8859-1', true); //$message1 is used instead for confirm communication assignment
 if($this->session->flashdata('user_deleted')){
			echo $this->session->flashdata('user_deleted'); 
	      }	
		  
?>
    <?php function getContents($str, $startDelimiter, $endDelimiter) {
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

 
?>
 <div class="enteries" id="showentry"></div>
<div class="row dfg">
 <?php	 if($acountuser != "ac_user"){?> 
        <div class="col-xs-10"><h3>Assign Communication to an Entry </h3> <h4> Communication Assignment Wizard - Step 2</h4> <div class="row"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p></div></div><?php } else{?>
			<div class="col-xs-10"><h3>Assigned User Entries </h3> </div>
			
		<?php }?>
        <div class="col-xs-2">
            <div class="gap"></div><div class="gap"></div>
            <?php if($acountuser != "ac_user"){?> 
            <a class="btn btn-default" style="float:right" target= "_self" href=<?php echo base_url('crmmails/viewmails?page=1'); ?>>Cancel</a>
            <a class="btn btn-default" target= "_self" href="javascript:history.back()">Back</a>
            <?php }else{?>
				 <a class="btn btn-default" style="float:right" target= "_self" href="<?php echo base_url('crmmails/viewmails?page=1'); ?>">Cancel</a>
			<?php }?>
        </div>
     
    </div>
    <?php if(isset($fname)){?>
		  <script>
  jQuery('body').on('click', '.se_email', function(e) {
e.preventDefault();
var s = jQuery(".su_email1").slideToggle("slow");
});
</script>
		 <div class="row">
      <div class="col-md-2 u_email">
        <button name="s_email1" id="select_email" class="link-class btn btn-info se_email" style="margin-top:15%">View Comunication</button>
         </div>
      <div class="col-md-10">
    <div class="col-md-10 su_email" style="height:50%;width:100%;overflow:scroll;overflow-y:scroll;overflow-x:scroll; margin-top:15%;">
    <a id="close" href="#" target= "_self" class="close">X</a>
    <script>
   jQuery(document).ready(function($) {
  jQuery('#close').click(function() {
    jQuery('.su_email').hide();
  });
});
	</script>
        <div  class ='col-md-5 demo'>
         <?php echo $fname;?>

        </div>
        <div class='col-md-3 subject'>
        <?php echo"Subject: ". $subj=  $subject?>
         </div>
	 <div class='col-md-3 date'>
       <?php echo  $edate?>

       </div>
		 <div class ='col-md-12 mess1' id='message3'>
         <?php //echo "From: ". $s_email = $fname;?>
        <br />
         <?php 
		   $file = $file;
		 //$mess    =  $_REQUEST['message'];
		// echo "Message: ".$mess;
		
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
				$f_file   = 'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/'.$file;
			}

		}
		 
		 $sample = file_get_contents($f_file);
		 $message1 = get_Contents($sample, '<message>', '<endmessage>');
		 // file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/action_station/debug.txt", "sample contains : " . $sample . PHP_EOL, FILE_APPEND);
         // refine embedded css <style> to avoid bleeding or conficting with items outside email content
                    /*
                    (for debugging purposes) SID : dXUXB1SO4L UID : 39049 EmailID : scalesplus.cameron@gmail.com (font market sans)
                    (for debugging purposes) SID : v0JMYZwz2n UID : 39116 EmailID : scalesplus.cameron@gmail.com blue rectangles in every mail and buttons on the right

                    testcase bleeding css */
                    $a = '<style';
                    $uniqueClass = "mess1";
                    $html = $message1;
                      if (strpos($message1, $a) !== false) {
                          // echo 'Contains style which can bleed out of this div<br>';
                          /* $numberOfTags = substr_count($message1, $a);
                          echo $numberOfTags.' style tags found'; */
                          
                          $html = $message1;
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
                     $message1 = $html;
					 echo 'message:'. $message1;
		?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js" ></script> 

<?php 
		 $attachs = $attachment;
	if(!empty($attachs)&& $attachs != "N/A"){
	echo "Attachments: "."<img src='".$attachs."'/>";
	echo "</br>"; }
    $attachs_pdf = $attachment_pdf;
    $attachs_pdf = chop($attachs_pdf,"'");
	if(!empty($attachs_pdf) && $attachs_pdf != "N/A"){
	?>
	<embed src="<?php echo $attachs_pdf; ?>" width="800px" height="2100px" />
  <?php	echo "</br>"; }
    $attachs_doc = $attachment_doc;
	$attachs_doc = chop($attachs_doc, "'");
	$attachname = basename($attachs_doc);
	if(!empty($attachs_doc) && $attachs_doc != "N/A"){
	?>
	<a href="<?php echo $attachs_doc; ?>" target= "_self">Attachement Name: <?php echo $attachname?></a>
		   <?php } ?>
        </div>
      </div>
      </div>
         </div>
		
<?php	}?>
    <?php $subject = str_replace("%20"," ",$subject);
	// $message = str_replace("%20"," ",$message);
	$message = htmlentities($message1, ENT_COMPAT,'UTF-8', true);
	 $file = str_replace("%20"," ",$file);
	?>
       <input type="hidden" name="addentry" value="entry" id="en"/>
       <input type="hidden" name="semail" value="<?php echo $sender ?>" id="em"/>
       <input type="hidden" name="subj" value="<?php echo $subject ?>" id="sb"/>
       <input type="hidden" name="mess" value="" id="mm"/>
       <input type="hidden" name="attach" value="<?php echo $attachment ?>" id="attach"/>
       <input type="hidden" name="attach_1" value="<?php echo $attachment_pdf ?>" id="attach_1"/>
       <input type="hidden" name="attach_2" value="<?php echo $attachment_doc ?>" id="attach_2"/>
       <input type="hidden" name="f_file" value="<?php echo $file ?>" id="ff"/>
       <input type="hidden" name="fname" value="<?php echo $fname ?>" id="fn"/>
       <input type="hidden" name="edate" value="<?php echo $edate ?>" id="ed"/>
       <input type="hidden" name="uid" value="<?php echo $ids ?>" id="idss"/>
       <input type="hidden" name="hiid" value="<?php echo $hids ?>" id="hidd"/>
	<?php //$this->view('dashboard_view', $emdata);
	if(isset($_REQUEST['entries_page'])){
		$url = "dashboard/?entries_page=".$_REQUEST['entries_page'];
    } elseif($hids!=0){
		 $url = 'dashboard/'.$hids;
	}else {
		$url = "dashboard";
	}
	
	?> 
   
   
<?php


	if(!empty($entry)){	
if(get_current_user_currency()== "")
				 {
					  $c_user_currency = "";
				 }else{
$c_user_currency = get_current_user_currency()->information_text;
				 }
				if(get_systems_currency() != ""){
		$old_currency = get_systems_currency();
		}elseif(get_userinfo_currency() != ""){
		 $old_currency = get_userinfo_currency();
		}else{
		$old_currency = "";
		}
if(is_array($entry)){

	$count = 0;
	
	$def_currency_symbol 	= $this->currency_converter->get_currency_symbol($session_currency);
 	 
 	if(!empty($entry)): 
 		$entry = array_reverse($entry);

 		foreach($entry as $ent):
 			/*
 			 * display entry data
 			 */
	 		$entry_id		=  $ent['entry_id'];
		    $users	  		=  $ent['user_assignment'];
		    $users          =  rtrim($users,',');
		  	$users 	  		=  array_map("intval", explode(",", $users));
		   	$users	  		=  array_reverse($users);
		   	$staff    		=  $ent['staff_assignment'];
		   	$staff 	  		=  array_map("intval", explode(",", $staff));
			$h_assigned  	= (array_key_exists($ent['heading_assignment'], $ent))?array():explode(",", $ent['heading_assignment']);
			?>
            <input type="hidden" class="ent_id" value="<?php echo $entry_id?>" />
            <?php 
			if( (array_key_exists('button_text', $ent)) && (!empty($ent['button_text'])) ){
				/**
				 * create h_titles variable which would contain all the heading titles
				 */
		   		$h_titles   =  (strpos($ent['button_text'], ',') ) ? explode(', ', $ent['button_text']) : array($ent['button_text']);
			}
			if($hids !=0){?>
            <style> .no_entry{ display:none;}</style>
            <?php } ?>
			
                
              <div class="ent_ttt">  
			<div class="row entry_row checkbox checkbox-circle panel panel-default" id="entry_container_<?php echo $count; ?>" style="margin-bottom:15px">
	    		
				<?php //$this->view('dashboard_view', $emdata);
	if(isset($_REQUEST['entries_page'])){
		$url = "dashboard/?entries_page=".$_REQUEST['entries_page'];
    } elseif($hids!=0){
		 $url = 'dashboard/'.$hids;
	}else {
		$url = "dashboard";
	}
	
	?> 

                <div class="checkbox checkbox-success checkbox-circle">
             <input type="hidden" value="<?php echo $fname?>"  class="e_email" />
             <input type="hidden" value="<?php echo $subject ?>" class="subj" />
             <input type="hidden" value="<?php echo $message ?>" class="content" />
			 <input type="hidden" name="edate" value="<?php echo $edate ?>" class="edatess"/>
             <input type="hidden" value="<?php echo $attachment ?>" class="content_attach" />
             <input type="hidden" value="<?php echo $attachment_pdf ?>" class="content_attach_pdf" />
             <input type="hidden" value="<?php echo $attachment_doc ?>" class="content_attach_doc" />
             <input type="hidden" value="<?php echo $file ?>" class="ff" />
	    			<input type="checkbox" class="entryCheck" style="margin-right:10px; opacity: 1;">
	    			<label class="entrylink">
						<b>Entry Type </b>= <?php echo $ent['entry_type']; ?> |
						<?php
						/**
						 * Fetch Entry Handler *****************
						 * Entry handler Name  ***************
						 */
					   	$last_s = end($staff);
            			echo "<b>Staff Assignement</b> = ";?>
						<?php $session_data = $this->session->userdata('logged_in'); ?>
				<?php $user_type = $session_data['usertype']; ?>
				<?php $utype = get_user_type($user_type);
				      $ustype =$utype->type_name 			?>
                <?php $ustype=preg_replace('/\s+/', '', $ustype);
                      $ustype = strtolower($ustype);
               '<br>'.$ustype.'<br>';
			 		  $sid=array();
					  $st_name =array();
					 
						foreach ($staff as $sf):
							/**
							 * display the staff members name and type
							 */
							
							 $hnd = get_user_details($sf);
							//$s_name = $hnd->first_name.$hnd->last_name;
							 array_push($sid, $sf);
							  if(!empty($hnd)){
							 if(property_exists($hnd, "first_name")){
								$first = $hnd->first_name;
							 }
							 else{
								  $first ="N/A";
							 }
							 if(property_exists($hnd, "last_name")){
							$last=  $hnd->last_name;
							 }else{
								  $last ="N/A";
							 }
							  $s_name = $first.$last;
								array_push($st_name, $s_name);
						    if($last_s == $sf){
								
								if(property_exists($hnd, "first_name")){
									echo $hnd->first_name;
								}else{
									echo "N/A";
								}
								if(property_exists($hnd, "last_name")){
									echo " ".$hnd->last_name;
              					echo " : ".get_user_type_by_id($sf)->type_name;
								}else{
									echo "N/A";
									echo " : ".get_user_type_by_id($sf)->type_name;
								}
								?>
								<?php }else{
								
								if(property_exists($hnd, "first_name") && property_exists($hnd, "last_name"))
									echo $hnd->first_name." ".$hnd->last_name. " : ".get_user_type_by_id($sf)->type_name.", ";
            				}
							  } endforeach;
						?></label>
                         <button type="button" class="btn btn-warning btn-xs need_hide"  data-entry="<?php echo $entry_id; ?>"  id ="staff" data-staff='<?php echo json_encode($st_name);?>' data-stid='<?php echo json_encode($sid);?>' data-container='<?php echo $count?>'>Edit Staff List</button> 

				</div>

				<!--################################-->
				<!--Assigned Filter Headings-->
				<!--################################-->
				<p>
				<?php
					echo "<b class='bb'>Assigned Filter Headings</b> - ";
					
					// afh btn btn-success [assigned filter headings] //
					if( (isset($h_titles)) && (is_array($h_titles)) && (count($h_titles) > 0) ){
						/**
						 * if h_titles variable exists then show all the assigned headings
						 * entries in action or inactive entry heading titles are default
						 */
						 
						$h_titles 		= array_filter(array_unique($h_titles)); 
						$heading_count 	= count($h_titles); $i = 0;
						$hedingsid = array(); 
						$titls =  array();
						foreach( $h_titles as $title ){
							/**
							 * display all heading title
							 */
							$string = $title;
							$string = str_replace('"', "", $string);
                            $string = str_replace("'", "", $string);
							array_push($titls, ucfirst($string));							 
							array_push($hedingsid, $h_assigned[$i]);
							
							if($action_page === TRUE)
							echo $title .((($heading_count != 1) && ($i != $heading_count-1))? ", ":" ");
							else
							if(!empty($sender)){
								 $ids;?>
								<a  href="<?php echo base_url().'crmmails/viewentry/'.$h_assigned[$i].'/'.$ids.'/'.$sender.'/'.$subject.'/'.$message.'/'.$file.'/'.$fname.'/'.$edate.'/'.$attachment; ?>" target= "_self"><button class='afh btn btn-primary'><?php echo ucfirst($title); ?></button></a>
                                 <?Php }
							else{
								echo "<a href='".base_url('dashboard/'.$h_assigned[$i])."' target= '_self'><button class='afh btn btn-primary'>". ucfirst($title) ."</button></a> ";
							}
							$i++;
							$heading_details = get_quote_details_in_entry($entry_id);
						} 
						//$label_heading = explode(',', $hedingsid);
						$i = 0;
						$h_titles = array() ;
						$heading_details = get_quote_details_in_entry($entry_id);
						}
					?>
	      		</p>

				<?php
				/**
				 * Users related to Entry *******************
				 * User informatiom *********************
				 * Product information ****************
				 *
				 */ ?>
				<div id="entry<?php echo $count ?>" class="entry">
					<ul>
						<div class="well">
							<?php
							$last_u = end($users);
							 $uuid=array();
					$ct_name =array();
							
								 ?>
			        			<div class="c-details">
			        			<div class="row">
				        			<div class="col-md-10 col-sm-12">
				        				<b>Customer Details</b>
				        			</div>
                                     <?php foreach($users as $us):
							 array_push($uuid, $us);
						 		$usr = get_user_details($us);
								if($usr != ""){
									if(property_exists($usr, "first_name")){
										$first = $usr->first_name;
									}
									if( property_exists($usr, "last_name")){
										$last = $usr->last_name;
									}else{
										$last ="";
									}
									$c_name = $first.$last;
									array_push($ct_name, $c_name);
										/*if(property_exists($usr, "first_name") && property_exists($usr, "last_name"))
										 $c_name =   $usr->first_name .$usr->last_name;*/
										
										
								}
								//array_push($ct_name, $c_name);
					 endforeach;
					   ?>
                                        <button type="button" class="btn btn-warning btn-xs need_hide" id ="customer" data-entry="<?php echo $entry_id; ?>" data-cust='<?php echo json_encode($ct_name);?>' data-container='<?php echo $count?>' data-stid='<?php echo json_encode($uuid);?>'  style="float:right">Add Customer</button>
                                  
				        			<div class="col-md-2 col-sm-12">
				        			</div>
				        		</div>
	                    		<li>
							    	<?php
									foreach($users as $us):
							
								/**
								 * display the assigned user details
								 * customer details section | global template
								 */
								if(!empty($us)){
									$usr = get_user_details($us);}
  
										  if(property_exists($usr, "first_name"))
										  {
											  $ff_name  = $usr->first_name;
										  }
										  else
										  {
											  $ff_name = "";
										  }
										  if(property_exists($usr, "last_name"))
										  {
											  $ll_name = $usr->last_name;
										  }
										  else
										  {
											  $ll_name = "";
										  }
										  if(property_exists($usr, "Company"))
										  {
											  $cc_name = $usr->Company;
										  }
										  else
										  {
											  $cc_name = "";	
										  }
										  echo "<b>";
										  if(!empty($ff_name))
										  {
											  echo $ff_name." ";
										  }
										  if(!empty($ll_name))
										  {
											  echo $ll_name;
										  }
										  if(empty($ff_name) && empty($ll_name))
										  {
											  echo "Company:".$cc_name;
										  }
										  echo "</b>";
										if(property_exists($usr, "Phone"))
											//echo "Phone: ".$usr->Phone.", ";
											echo '</br> Phone: <a href="tel:'.$usr->Phone.'" target= "_self">'.$usr->Phone.'</a>';
										if(property_exists($usr, "Fax"))
											echo "</br>Fax: ".$usr->Fax.", ";
											
										if(property_exists($usr, "email_primary")){
										
											//echo "Email: ".$usr->email_primary."</br>";
											echo'</br>Email: <a href = "mailto: '.$usr->email_primary.'" target= "_self">'.$usr->email_primary.'</a>';}
											if(property_exists($usr, "Skype"))
										
											echo "</br> Chat Id: ".$usr->Skype.",";
										if(property_exists($usr, "Whatsapp"))
											echo "</br>Chat Id: ".$usr->Whatsapp.",";
										if(property_exists($usr, "Viber"))
											echo "</br>Chat Id: ".$usr->Viber.", ";	
										if(property_exists($usr, "GoogleHangouts"))
											echo "</br>Chat Id: ".$usr->GoogleHangouts.",";	
											if(property_exists($usr, "Snapchat"))
											echo "</br>Chat Id: ".$usr->Snapchat.",";
											if(property_exists($usr, "Groupme"))
											echo "</br>Chat Id: ".$usr->Groupme.",";
										if(property_exists($usr, "Other"))
											echo "</br>Other: ".$usr->other.", ";
										if(property_exists($usr, "Delivery_Address")){
											$delivery = $usr->Delivery_Address;
											echo "<br>Delivery Address: ".$usr->Delivery_Address.", ";
											if(property_exists($usr, "city1")){
													$city = $usr->city1;
													echo $usr->city1.", ";
												}
												if(property_exists($usr, "state1")){
													$st = $usr->state1;
													echo $usr->state1.", ";
												}
												if(property_exists($usr, "country1")){
												$contr =$usr->country1;
												echo $usr->country1;
												}
												if(property_exists($usr, "ZipPostal_Code1")){
													$zip = $usr->ZipPostal_Code1;
													echo ", ".$usr->ZipPostal_Code1;
												}
											
										}
												
									if(property_exists($usr, "Billing_Address")){?>
                                         <!-- <input type="checkbox" id="check" value="1" style="opacity:1"/><b>Display Billing Address</b><br />-->
                                          <?php }
                                        
											if(property_exists($usr, "Billing_Address")){
												$billing =$usr->Billing_Address;
												 if($usr->Billing_Address != $delivery ){
												
											
											echo "</br>Billing Address: ".$usr->Billing_Address.", ";
											
											if(property_exists($usr, "city")){
												
												echo $usr->city.", ";
												
											}
											if(property_exists($usr, "state")){
											echo $usr->state.", ";
											}
											if(property_exists($usr, "country"))
											
											echo $usr->country;
											
										  if(property_exists($usr, "ZipPostal_Code")){
											
											echo ", ". $usr->ZipPostal_Code.", ";
											
										}
											}}
										
										?>
								</li>
			                	<!-- can be used in future <li class="entry-header-row">
								<?php if(property_exists($usr, "timezone")){
											
											if(is_array($timezones)){
												$utc_time_zone = $timezones[$usr->timezone];
												$utc_tz 	= substr($utc_time_zone, 0, strpos($utc_time_zone, ')'));
												$utc_tz 	= ltrim($utc_tz, '(');
												echo "Timezone: ".$utc_tz;
												$date_time 	= $this->timezones->getTime($usr->timezone);
											}
										}
										if(!empty($ac_user)){  ?>
									                          
									<a class="btn btn-warning btn-xs edit-customer need_hide" target= "_self" href="<?php echo base_url('accounts/edit/'.$us.'/'.$ac_user) ?>">Edit</a>
									<button type="button" class="btn btn-warning btn-xs delete-customer del-customer need_hide" data-customid="<?php echo $us; ?>" data-entryid="<?php echo $entry_id ?>" data-container='<?php echo $count?>'>Remove User</button>
                                    <?php } ?>
								<?php
									/*if(property_exists($usr, "timezone"))
										if(isset($utc_tz)){
											echo $utc_tz . " = ";
											$dt = explode(' ', $date_time);	//$dt[0] => date  $dt[1] => time
											echo $dt[1] . ', ' .date('l F d Y', strtotime($dt[0]));
											$dt = array(); // empty the array //
										}*/
									?>
							    </li> -->
							<?php  endforeach; ?>
                            
							</div>
							<?php
							/**
							 *
							 * Action Data **********************************
							 * All action performed on Entry *************
							 *
							 */
					
							/* show action history of entry_id */
							$a_data = get_action_data($entry_id); // a_data => action data of the indivisual entry_id
					
							if(is_array($a_data)):
								$len 	= count($a_data);
								$count1 = 0;
								/* session_currency > user's preferred currency */
								$session_currency; 
						
								//////////////////////// PRODUCTS SECTION /////////////////////////////

								echo "<!--<i class='fa fa-cart-arrow-down' aria-hidden='true'></i>--> </br><div class='row'><div class='col-md-10 col-sm-12 product'><b class='product_title'>Products Being Considered</b> </div>"; 
								

								/** product_details > [inventory_ids], [quotes], [action_id], [action_notes] */
								$product_details = get_quote_details_in_entry($entry_id); // get details of all products inside the entry 
								//print_r($product_details);
								
								if($product_details !== false){
									/**
									* show product details
									*/
									$quotes = $product_details['quotes'];
									
									echo "<div id='editProduct'></div>";
									/*<button type='button' class='btn btn-info btn-xs addnew-product' data-toggle='modal' data-target='#products_modal' data-entryid='".$entry_id."'>Add New</button>*/
									$consideredProducts = count($product_details['inventory_ids']);
									$pd_inc = 0;
									$price_array = array();
									foreach($product_details['inventory_ids'] as $i_id){ 
										/**
										 * display details of every individual product
										 */
										$productName 	= getProductInfo($i_id); ?>

										<div class='product-specs'>
                                           <?php  $supplier_id= $productName[0]->supplier_id; ?>
                                        <input type="hidden" value="<?php echo $i_id ?>" class="invt_id" />
											<li> Product Title: <b class='ps-title'><?php echo $productName[0]->product_title ?></b> 
                                   
											</li>
											<?php
											if(!empty($productName[0]->product_url)){
											echo "Product URL: <a  target= '_self' class='ps-url' href='".$productName[0]->product_url."' target='_blank'>".$productName[0]->product_url."</a><br>";
											}
											$priceCurrency = '';
									
											foreach($quotes as $product){
												
												if(@$product['inventory_id'] == $i_id){
				      								$priceCurrency = rtrim(substr($product['price'], strpos($product['price'], '[')+ 1), ']');
				      							}
				      						}
											echo "Stored Pricing Currency: <span class='ps-currency'>".$priceCurrency."</span> = ";
											
											foreach($quotes as $product){
												/**
												 * display quote prices stored in the database
												 */
												if(@$product['inventory_id'] == $i_id){ 
				      								$price = substr($product['price'], 0, strpos($product['price'], '['));
				      								$qid   = $product['quote_id']; 
		      								
			      								if( $c_user_currency != $session_currency ){
			  										$c_convertedPrice 	= $this->currency_converter->convert_v($price, $priceCurrency, $session_currency);
			  										$c_curr_symbol 		= $this->currency_converter->get_currency_symbol($session_currency);
			  									}
			  									$p_type = $product['price_type'];

			  									echo "<span class='ps-".$p_type."'>";

			  									if($c_user_currency != $session_currency){
			  										/**
			  										 * if session currency and default user currency is different
			  										 * use session currency for quote prices
			  										 */
													 if($priceCurrency =='INR'){
													$sym = 'INR';
													}else{
														$sym = '$';
													}
													echo ucfirst($p_type) .': <span class="cc-price" data-toggle="modal" data-target="#CurrencyConverterPopUp"><span class="curr_code hidden">'.$old_currency.'</span><span class="ccp_dcs">'.' </span></span>  <span class="cc-price" data-toggle="modal" data-target= "#CurrencyConverterPopUp"><span class="curr_code hidden">'.$priceCurrency.'</span><span class="ccp_dcs">'.'</span> <span class="ccp_price" data-qid="'.$qid.'">'.$sym. " ".$price.'</span> ';
												
			  									
			  									} else{
			  										/**
			  										 * same currencies | use default currency for quote prices
			  										 */
			  										echo ucfirst($p_type) . ': <span class="cc-price" data-toggle="modal" data-target="#CurrencyConverterPopUp"><span class="curr_code hidden">'.$priceCurrency.'</span><span class="ccp_dcs">'.$priceCurrency.' </span><span class="ccp_price" data-qid="'.$qid.'">' . $price .'</span></span> ';
			  									}
			  									echo "</span>";
			  									
			  									$ex_price = $p_type.': '.$priceCurrency.' '.$price.' ';
				  								$price_array[] = $ex_price;
			  									$p_type = "";
			  								} 
											$c_convertedPrice 	= '';
											$c_curr_symbol 		= '';
										}
										echo "<br>";
										
										// by me echo "Operational Currency: "; if(isset($session_currency)){echo $session_currency." = ";}else { echo $c_user_currency." = ";}
                                        
										//foreach($quotes as $product){
											/**
											 * display the user currency converted quote prices
											 */

										//	if(@$product['inventory_id'] == $i_id){
				  							//	$price 		= substr($product['price'], 0, strpos($product['price'], '['));
				  							//	$userPrice 	= $this->currency_converter->convert_v($price, $priceCurrency, $c_user_currency);

				  						//		if( $c_user_currency != $session_currency ){
													//	$sessionPrice 	= $this->currency_converter->convert_v($price, $priceCurrency, $session_currency);
					//$c_curr_symbol 	= $this->currency_converter->get_currency_symbol($c_user_currency);
												//	}
			  								
				  							//	if($c_user_currency != $session_currency){
												//	if($session_currency == 'INR' || $c_user_currency == 'INR'){
												//	$sym = 'INR';
												//	}else{
												//		$sym = '$';
												//	}
				  									//echo ucfirst($product['price_type']) . ': <span class="cc-price" data-toggle="modal" data-target="#CurrencyConverterPopUp"><span class="curr_code hidden">'.$session_currency.'</span><span class="ccp_dcs">'.' </span></span> <span class="cc-price" data-toggle="modal" data-target= "#CurrencyConverterPopUp"><span class="curr_code hidden">'.$c_user_currency.'</span><span class="ccp_dcs">'.'</span> <span class="ccp_price">'.$sym." " .$price*$sessionPrice.'<span class="ccp_price"></span> ';
				  								
				  								//} else{
				  									//echo ucfirst($product['price_type']) . ': <span class="cc-price" data-toggle="modal" data-target= "#CurrencyConverterPopUp"><span class="curr_code hidden">'.$c_user_currency.'</span><span class="ccp_dcs">'.$c_user_currency.'</span> <span class="ccp_price">'.$userPrice.'</span></span> ';
				  								//}


				  							//} 
											//$c_convertedPrice 	= '';
											//$c_curr_symbol 		= '';
										//}
										
								
										$priceCurrency = '';?>
                                        Product Discount: <b class='ps-discount'><?php echo preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $productName[0]->product_disconunt)?></b></br>
                                        <div class='notes'>
                                        <?php $action_id = $product_details['action_id'][0]?>
													<?php $action_note = get_action_notes($product_details['action_id'][0]); 														
														if(is_array($action_note)){
															$action_note = $action_note['action_notes'];
															
														}
														$action_note = ((strpos($action_note, '^<') !== false)?preg_replace('/^.*<\s*/', '', $action_note):((strpos($action_note, '>^') !== false)?strstr($action_note, '>^', true):$action_note)); 
														$pop_note = $action_note;
														
														
														$action_note = '';
														
														 
														
													?>
                                                    </div>
                                                   <?php 
												 
													//code for product added date
												 $action_schedule = get_action_schedule($product_details['action_id'][0]); 														
														if(is_array($action_schedule)){
															$action_schedule = $action_schedule['action_schedule'];
															
														}
														$action_schedule = ((strpos($action_schedule, '^<') !== false)?preg_replace('/^.*<\s*/', '', $action_schedule):((strpos($action_schedule, '>^') !== false)?strstr($action_schedule, '>^', true):$action_schedule)); 
														if(isset($session_data['old_time'])){
												
							$time = new DateTime($action_schedule,  new DateTimeZone($old_time));
							$time->setTimezone(new DateTimezone($session_data['timezone_sess']));
							$aa_time =  $time->format('H:i:s l j  F Y ');
						 }else{
						$aa_time = date("H:i:s",strtotime($action_schedule)).", ". date('l j  F Y', strtotime($action_schedule));	 
						 }
														
														echo 'Product Added: <span class="action-notes" data-actionid="'.((is_array($action_schedule)?$action_schedule['action_id'].'">':'">')).$action_schedule.'</span>';
														$action_schedule = '';
														if($action_schedule = "")
														{
															echo 'Product Added: <span class="action-notes">No date</span>';
														}
														
														if($productName[0]->notes!=""){
														echo '</br>Product Notes: <span class="action-notes p-note" data-actionid="'.((is_array($action_note)?$action_note['action_id'].'">':'">')).$productName[0]->notes.'</span><br>';
														}
														
														else{
															echo '</br> <span class="p-note">Product Note:  No Notes Found</span>';
														}
														
														
													?>
												 <?php  if(!empty($ac_user)){ ?>
                                                      <br /> <b>Product Supplier Detail:</b></br>  <?php  
											  $supid=array();
												$sup_name =array();
											 $suplierid = rtrim($supplier_id, ',')	;
											 $supplier_ar = explode(',', $supplier_id);
											$supplier_ari = array_filter($supplier_ar);
											$name_count=1;
											foreach($supplier_ari as $sp_id){
												$supplier_info = get_user_details($sp_id);
												if(property_exists($supplier_info, "first_name")){
										 $firstname =   $supplier_info->first_name ;
											
										}else{
											 $firstname = "";
										}
										if(property_exists($supplier_info, "last_name")){
											$lastname = $supplier_info->last_name;
										}else{
											$lastname = "";
										}
												 array_push($supid, $sp_id);
												 array_push($sup_name, $firstname.$lastname);
											}?>
											        <button type="button" class="btn btn-warning btn-xs need_hide"  data-inventory="<?php echo $i_id; ?>"  id ="suppl" data-suplier='<?php echo json_encode($sup_name);?>' data-supid='<?php echo json_encode($supid);?>' style="float:right" data-entryid='<?php echo $entry_id ?>' data-container='<?php echo $count?>'>Edit Supplier List</button> 
                                                 <?php foreach($supplier_ari as $sp_id){
											 array_push($supid, $sp_id);
											$supplier_info = get_user_details($sp_id);
										if(!empty($supplier_info)){
											if(property_exists($supplier_info, "first_name")){
										 $firstname =   $supplier_info->first_name ;
											
										}else{
											 $firstname = "";
										}
										if(property_exists($supplier_info, "last_name")){
											$lastname = $supplier_info->last_name;
										}else{
											$lastname = "";
										}
										array_push($sup_name, $firstname.$lastname);
                                        if(property_exists($supplier_info, "first_name") ){?>
                                      <?php  if(property_exists($supplier_info, "first_name")){
										echo "Supplier ".$name_count.":  ".$supplier_info->first_name." ";}
										if(property_exists($supplier_info, "last_name")){
											echo "".$supplier_info->last_name."";}}
										
										}else{
												echo "Not avavilabel";
											}?>
                                             <select class="btn btn-warning btn-xs need_hide">  
                                <option>Contact</option>  
                            <?php 
							if(property_exists($supplier_info, "Phone")){
											echo '<option value="'.$supplier_info->Phone.'"><a href="tel:'.$supplier_info->Phone.'" target= "_self">Phone</a></option> ';}
										
											
										if(property_exists($supplier_info, "email_primary")){
										echo'<option value="'.$supplier_info->email_primary.'"><a href = "mailto: '.$supplier_info->email_primary.'" target= "_self">Email</a></option>';}
											
							?>
                            </select>
											<a class="btn btn-warning btn-xs edit-customer need_hide" target= "_self" href="<?php echo base_url('accounts/edit/'.$sp_id) ?>">Edit</a>
									<button type="button" class="btn btn-warning btn-xs delete-supplier del-supplier need_hide" data-supplierid="<?php echo $sp_id; ?>" data-inventory_id="<?php echo $i_id ?>" data-entryid='<?php echo $entry_id ?>' data-container='<?php echo $count?>'>Remove Supplier</button><br>
                               
										<?php	$name_count++;} ?>
                                       <br /><button type='button' class='btn btn-warning btn-xs edit-product need_hide'  data-entryid='<?php echo $entry_id ?>' data-actionid='<?php echo $product_details['action_id'][$pd_inc] ?>'>Edit</button> 
								       <button type='button' class='btn btn-warning btn-xs delete-product need_hide' data-value='' data-toggle='modal' data-target='#product_delete_modal' data-entryid='<?php echo $entry_id ?>' data-actionid='<?php echo $product_details['action_id'][$pd_inc]?>'>Remove Product</button>
                                                 <?php } ?>
										<div class="current-prod-info" style="display: none;">
										<b>Current Version</b><br>
                                        <b>Product Title: </b><?php echo $productName[0]->product_title  ?><br>
                                        <b>Product URL:</b>
                                        <?php echo "<a class='ps-url' href='".$productName[0]->product_url."' target='_blank'>".$productName[0]->product_url."</a><br>";?>
                                        <b>Stored Price:</b>
                                       
                                        
									<?php foreach($price_array as $data1){
										echo $data1;
										}
										
										 ?> 
                                         <br />
										 <b>Product Note: </b><?php echo $pop_note ?><br />
                                       <?php  
									   $edit_date = geteditdateInfo($entry_id); 														
														foreach($edit_date as $editdate)
														{
														$date = $editdate->edit_date;
														
														}
							 echo "<b>Edited On:</b> ". date("H:i:s",strtotime($date)).", ". date('l j  F Y', strtotime($date));
										 				
									  ?>
								</div>
                              
                                <div class="current-head-info" style="display: none;">
										<b>Current Version</b><br>
                                        <?php $arr =geteditedheading($entry_id);
										print_r($arr);
		  						//$tb=sizeof($arr);
								$keys = array_keys($arr);
								$output = '';
								for($i = 0; $i < count($arr); $i++) {
								
									 $keys[$i] . "<br>";
								
									foreach($arr[$keys[$i]] as $key => $value) {
								 $value;
										//echo '<b>Heading Title: </b>'. $value . "<br>";
										
										$hed_text=get_heading_text($value);
										$keys = array_keys($hed_text);
										for($j = 0; $j < count($hed_text); $j++) {
								
									 $keys[$i] . "<br>";
								
									foreach($hed_text[$keys[$j]] as $key => $value) {
											echo '<b>Heading Title: </b>'. $value . "<br>";
									}
									 }	
									}
								
									echo "<br>";
								
								}?>
                                        
								</div>
				<?php                                                   
									echo "</div>";
									$pd_inc++;
								}
				
							} else{
								
								echo "<li> No Products</li>";
								
							}	
							echo "</div>";
							echo "</div>";
					 			$action_ids ="";
							foreach ($a_data as $ad):
							 $action_ids = $ad['action_id'];
						   		/**
						   		* actions section
						   		* display the actions data
						   		*/
					   			echo "<div class='entry-action".((($count1 != 0) && ($count1 != $len-1) && ($action_page === FALSE))?' history'.$count.' collapse history-cont':'')."'>";
								
					            $action_status = $ad['action_status'];
					            if($action_page == FALSE){
									
									if($ad['action_direction'] !== "Incoming"){
					            	echo "<b>".(($action_status == 'Completed')?'Action Taken':(($action_status == 'Scheduled')?'Action Scheduled <a href="'.base_url().strtolower(str_replace(' ', '_', $ad['atype_name'])).'/'.$entry_id.'/'.$ad['atype_id'].'" class="btn btn-warning btn-xs need_hide" style="float:right">Perform Action</a>' : ' Action Cancelled')). ' - '.get_action_icon($ad['action_type'], $ad['action_status'])."</b> ";
									}
									else{
										 echo "<b>".(($action_status == 'Completed')?'Action Taken':(($action_status == 'Scheduled')?'Action Scheduled <a href="'.base_url().strtolower(str_replace(' ', '_', $ad['atype_name'])).'/'.$entry_id.'/'.$ad['atype_id'].'" class="btn btn-warning btn-xs need_hide" style="float:right">Perform Action</a>' : ' Action Cancelled')). ' - '.$ad['action_direction']." ".$ad['atype_name']."</b> ";
									}
					            } 
								
					            
					            
					            // echo "<div class='panel-body'>";

					            if(isset($ad['action_source'])):
								
									//echo  '<li><a href="'.$ad['action_source'].'">'.$ad['action_source'].'</a></li>';
								endif;
							    $a_time = date("H:i:s",strtotime($ad['date'])).", ". date('l j  F Y', strtotime($ad['date']));
								$s_time = date("H:i:s",strtotime($ad['action_schedule'])).", ". date('l j  F Y', strtotime($ad['action_schedule']));
								$author = get_user_details($ad['action_author']);
								//echo   '<li><b>'.$ad['action_direction'].', '.get_action_type($ad['action_type']).' - '.$a_time.'</b> </li>';
								echo show_template_by_action($ad, $entry_id, $entry_model, ( (isset($consideredProducts))?$consideredProducts:null));
					 			$consideredProducts = null;

					 			if(property_exists($author, "first_name"))
									$aut_name	=	$author->first_name;
								else
									$aut_name	=	"";
								
								
								echo (($action_status !== 'Cancelled')?'<li><span class="action_status'.$count.'">'.$ad['action_status'].' By</span> - '.$aut_name.' - '.$a_time.' </li>':'');
								
								if(($action_status !== 'Completed')){
									echo "<li>Scheduled Date: <b>".$s_time."</b>";
									
									if($action_page == FALSE){
						            	echo (($action_status == 'Scheduled')?'<button data-entryId="'.$entry_id.'" data-actionId="'.$ad['action_id'].'" class="btn btn-default btn-xs cancel-action need_hide" style="float:right" data-toggle="modal" data-target="#cancel_action_modal">Cancel Action</button>':'');
						            }
						            echo "</li>"; 
								}
								
								echo (($action_status === 'Cancelled')?'<li><span class="action_status'.$count.'">'.$ad['action_status'].' By</span> - '.$aut_name.' - '.$a_time.' </li>':'');

								echo "</div>";

								if(($action_page === FALSE) && ( $count1 == (count($a_data)-1) ) && (count($a_data) > 2) ){
								?>
									<span class="historyB<?php echo $count ?>">
										<b>
											<p class="historyLink expandHistory" id="historyU<?php echo $count ?>" data-linkid="<?php echo $count ?>" data-toggle="collapse" data-target=".history<?php echo $count ?>" >
												....Expand History <i class="fa fa-angle-double-right" aria-hidden="true"></i>
											</p>	
										</b>
									</span>
								<?php } ?>
								<?php
								//echo '<div class="'.(($action_page === FALSE)?"history".$count." collapse":""). '">';
					
								//echo "</div>";
							   	$count1++;
		         			endforeach;
						endif; 
						
						?>
						<span class="historyU<?php echo $count ?> collapsHistory" style="display: none;">
							<b>
								<p class="historyLink expandHistory" id="historyB<?php echo $count ?>" data-linkid="<?php echo $count ?>" data-toggle="collapse" data-target=".history<?php echo $count?>" >
									<i class="fa fa-angle-double-left" aria-hidden="true"></i> 
									...Collapse History 
								</p>
							</b>
						</span>
                        </div>
	 				</ul>
					<script type="text/javascript">
						checkIfEntriesCompleted(<?php echo $count; ?>);
					</script>

					<?php

					/**
					 *
					 * Action list****************************
					 * Edit entry **************************
					 * Edit entry histroy*****************
					 */

                     if(!empty($ac_user)){
					if($action_page === FALSE){ 
                   ?>
                    <strong style="padding-left:20px"> <span class="need_hide"> Take Action</span></strong>
						<div class="dropdown actionSelect" style="padding-left:20px;margin-top: 5px;">
                       
                        <?php  if(is_array($action)):
								  	$cont = 0;
									
								  	foreach ($action as $act):
						  				$actnm = strtolower($act['atype_name']);
										$actnm = str_replace(' ', '_', $actnm);
										if($act['atype_id']=="26"){
											$a = 7;
										echo '<li role="presentation" class="btn btn-warning btn-xs need_hide" style="margin-right:5px;"><a role="menuitem" style="color:#fff;" tabindex="-1" target= "_self" href="'.base_url().$actnm.'/'.$entry_id.'/'.$act['atype_id'].'?a='.$a.'">'. $act['atype_name'].'</a></li>';
										}
										$cont++;
								   	endforeach;

								endif;
                                } 
								?>
                                
				</div>
                <?php } ?>
                <!--******Entry Div Ends-****test*********-->
               
                <input type="hidden"  class="action" value="<?php if(isset($action_ids)){echo $action_ids;}?>"/>
                 
                 <?php if($acountuser != "ac_user"){?>
                 <button type="button" class="link-class btn btn-info comuni" data-entry="<?php echo $entry_id; ?>" data-heading='<?php echo json_encode($titls) ?>' data-headingid='<?php echo json_encode($hedingsid) ?>' id="assign_heading">Confirm Communication Assignment</button>
          <?php } ?>       
			</div></div><!--******ROW Div Ends-test*************-->
           
		<?php
		$count++;
		endforeach; 
	endif;
	
	?>
     <div class="enteries"></div>
    <div class="no_enter" style="text-align:center">
     <?php if($acountuser != "ac_user"){?>
    <button name="enttt" value="entries" class="link-class btn btn-info en_no">Select Other Cases or Add New Case</button></div>
<?php }}
}

else{
	 if(isset($acountusers) && $acountuser != "ac_user"){?> 
	
	
    <?php
//$add_entry = 'entry';
	?>

    
        <input type="hidden" name="addentry" value="entry" id="en"/>
        <input type="hidden" name="semail" value="<?php echo $sender ?>" id="em"/>
        <input type="hidden" name="subj" value="<?php echo $subject ?>" id="sb"/>
        <input type="hidden" name="mess" value="" id="mm"/>
        <input type="hidden" name="attach" value="<?php echo $attachment ?>" id="attach"/>
        <input type="hidden" name="attach_1" value="<?php echo $attachment_pdf ?>" id="attach_1"/>
        <input type="hidden" name="attach_2" value="<?php echo $attachment_pdf ?>" id="attach_2"/>
        <input type="hidden" name="f_file" value="<?php echo $file ?>" id="ff"/>
        <input type="hidden" name="fname" value="<?php echo $fname ?>" id="fn"/>
        <input type="hidden" name="edate" value="<?php echo $edate ?>" id="ed"/>
        <input type="hidden" name="uid" value="<?php echo $ids ?>" id="idss"/>
        <input type="hidden" name="hiid" value="<?php echo $hids ?>" id="hidd"/>
	<?php //$this->view('dashboard_view', $emdata);
	if(isset($_REQUEST['entries_page'])){
		$url = "dashboard/?entries_page=".$_REQUEST['entries_page'];
    } elseif($hids!=0){
		 $url = 'dashboard/'.$hids;
	}else {
		$url = "dashboard";
	}
	
	
    if(isset($fname)){?>
  <div class="gap"></div>
   <div class="gap"></div>
    <?php }?>
    
	<script>
	jQuery(document).ready(function(e) {
	jQuery(".ent_ttt").css("display","none");
	jQuery(".no_enter").css("display","none");
	jQuery(".dfg").css("display","none");
	var suj            = jQuery('#sb').val();
	var s_email        = jQuery('#em').val();
	var mess           = jQuery('#mm').val();
	var attachment     = jQuery('#attach').val();
	var attachment_pdf = jQuery('#attach_1').val();
	var attachment_doc = jQuery('#attach_2').val();
	var f_file         = jQuery('#ff').val();
	var ff             = jQuery('#fn').val();
	var edd            = jQuery('#ed').val();
	var enn            = jQuery('#en').val();
	var idss           = jQuery('#idss').val();
	var hdds           = jQuery('#hidd').val();
	var url            = window.location.href+"/";
	alert("<?php echo base_url($url)?>");
	 jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url($url)?>",
				data: {'semail': s_email, 'subject': suj, 'mmm': mess, 'file': f_file, 'fname': ff, 'edate': edd, 'a_entry': enn, 'd2': 1 , 'pagination_url': url, 'uid': idss, 'hid': hdds, 'attachment': attachment, 'attachment_pdf': attachment_pdf, 'attachment_doc': attachment_doc},
				success: function(res){
					alert(res);
					//location.reload(true);
					jQuery(".enteries").html(res);
					}
					error: function(xhr, status, error) {
  var err = eval("(" + xhr.responseText + ")");
  alert(err.Message);
}
				});
  });
	</script>
    
    <?php }?>  <div class="btn_adds"><a target= "_self" href="<?php echo base_url('/entry/?addentry='.$u_entry.'&uid='.$ids.'&semail='.$sender.'&subject='.$subject.'&message='.''.'&edate='.$edate.'&f_name='.$fname.'&f_file='.$file) ?>" type="button" class="btn btn-default create btn-warning">Create New Entry </a>
      <button name="enttt" value="entries" class="link-class btn btn-info en_no">Select Existing Entry</button><?php }?></div>
	  
 
<script>
 /////////button click //////////////////
 jQuery('.en_no').click(function(){
	jQuery(".ent_ttt").css("display","none");
	jQuery(".btn_adds").css("display","none");
	jQuery(".no_enter").css("display","none");
	jQuery(".nous").css("display","none");
	jQuery(".dfg").css("display","none");
	var suj            = jQuery('#sb').val();
	var s_email        = jQuery('#fn').val();
	var mess           = jQuery('#mm').val();
	var attachment     = jQuery('#attach').val();
	var attachment_pdf = jQuery('#attach_1').val();
	var attachment_doc = jQuery('#attach_2').val();
	var f_file         = jQuery('#ff').val();
	var ff             = jQuery('#fn').val();
	var edd            = jQuery('#ed').val();
	var enn            = jQuery('#en').val();
	var idss           = jQuery('#idss').val();
	var hdds           = jQuery('#hidd').val();
	var url            = window.location.href+"/";
	var select_ent     = 'select_ent';
	 jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url($url)?>",
				data: {'semail': s_email, 'subject': suj, 'mmm': mess, 'file': f_file, 'fname': ff, 'edate': edd, 'a_entry': enn, 'd2': 1 , 'pagination_url': url, 'uid': idss, 'hid': hdds, 'attachment': attachment, 'attachment_pdf': attachment_pdf, 'attachment_doc': attachment_doc, 'select-ent': select_ent},
				success: function(res){
					//location.reload(true);
					
					jQuery(".enteries").html(res);
					}
				});
				});
</script>



<!-- ############# Call modals ############ -->

<?php if(!empty($remove_user_id) || !empty($entry)) {
	$data['c_user_currency'] = $c_user_currency;
	$this->load->view('entry/entrylist_modals', $data);
	$this->view('currency/currency_selector'); 
}else{
	echo "<div class='nous'>No Entries Assigned to This User!</div>";
	 if($acountuser != "ac_user"){?> 
     
     <?php }
}
?>
<?php
if(!empty($hids)){
		 $url = 'crmmails/viewentry_1/'.$hids;
}
?>

				
                <script>
				
					//////////////////////////Collect email data and send it to controller/////////////////////////////
					var sendEmail = function(entry_id) {
					
					var notes  	         =  "No notes saved";
				   var subject             =   jQuery('.subj').val();
				   var edate               =   jQuery('.edatess').val();
				   var email               =   jQuery('.e_email').val();
				   var mail_content        =   <?php echo json_encode($message1, JSON_HEX_TAG); ?>; //done by sunil, passed message1 directly, no need to write it in .content, no need to encrypt or decrypt, simply pass it on.Don't forget the extra semicolon! //$('.content').val();
				   var mail_attachment     =   jQuery('.content_attach').val();
				   var mail_attachment_pdf =   jQuery('.content_attach_pdf').val();
				   var mail_attachment_doc =   jQuery('.content_attach_doc').val();
				   var actionid            =   jQuery('.action').val();
				   var entry_id            =   entry_id;
				   var filename            =   jQuery('.ff').val();
				   var attachment          =   mail_attachment+','+mail_attachment_pdf+','+mail_attachment_doc;
				  

				  jQuery.ajax({
				  type: "POST",
				  url: "<?php echo base_url('email/incoming_email/')?>",
							  data: {'email': email, 'content': mail_content, 'entry_id': entry_id, 'action_id': actionid, 'notes': notes,'subject': subject, 'attachment': attachment, 'file': filename, 'edate': edate, 'source': $(location).attr("href")
							  },
							  success: function(resp) {
								  return true;
							  },
							  error: function(xhr, textStatus, errorThrown){
									  return false;
							  }
							  
							  })
				  return true; 
					   
				  
}
//////////////////////////Send email//////////////////////////////////////////////////

	jQuery(document.body).on('click', '.comuni', function(e){	
		e.preventDefault();
	var id = jQuery(this).data('entry');

	if(sendEmail(id)){
		alert("Assigned Sucessfully");
		//console.log('test');
				setTimeout(function() {
					 window.location.replace("<?php echo base_url(); ?>crmmails/viewmails?page=1");
				}, 500);
				 
				}else{
					console.log('error');
				}
	
});

				</script>
                <script>
 jQuery('.btn-primary').click(function(){
	
	var suj     = jQuery('#sb').val();
	var s_email = jQuery('#em').val();
	var mess    = jQuery('#mm').val();
	var f_file  = jQuery('#ff').val();
	var ff      = jQuery('#fn').val();
	var edd     = jQuery('#ed').val();
	var enn     = jQuery('#en').val();
	var idss    = jQuery('#idss').val();
	var hdds    = jQuery('#hidd').val();
	var url     = window.location.href+"/"+hdds;
	
	
	 jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url($url)?>",
				data: {'semail': s_email, 'subject': suj, 'mmm': mess, 'file': f_file, 'fname': ff, 'edate': edd, 'a_entry': enn, 'd2': 1 , 'pagination_url': url, 'uid': idss, 'hid': hdds},
				success: function(res){
					//location.reload(true);
					jQuery(".enteries").html(res);
					}
				});
				});



jQuery('.edit-product').on('click', function(){
			// collect product details
			var p_parent	    = jQuery(this).parents('.product-specs');
			var p_title 	    = p_parent.find('.ps-title').html();
			var p_url 		    = p_parent.find('.ps-url').html();
			var p_discount 	    = p_parent.find('.ps-discount').html();
			var invt_id		    = p_parent.find('.invt_id').val();
			var p_currency      = p_parent.find('.ps-currency').html();
			var p_retail 		= p_parent.find('.ps-retail .ccp_price').html();
			var retail_qid  	= p_parent.find('.ps-retail .ccp_price').data('qid');
			var p_wholesale 	= p_parent.find('.ps-wholesale .ccp_price').html();
			var wholesale_qid  	= p_parent.find('.ps-wholesale .ccp_price').data('qid');
			var p_cost			= p_parent.find('.ps-cost .ccp_price').html();
			var cost_qid  		= p_parent.find('.ps-cost .ccp_price').data('qid');
			var baseUrl 		= '<?php echo base_url(); ?>';
			var product_notes   = p_parent.find('.p-note').text();
			var entry_sec 		= jQuery(this).parents('.entry_row:eq(0)').attr('id');
			var e_id            = jQuery(this).data('entryid');
			var a_id            = jQuery(this).data('actionid');

			/* create the form of collected values */
			jQuery('#editProduct').html('');
			jQuery('#editProduct').append("<form class='form-horizontal' id='editPFrom' method='post' action='"+baseUrl+"dashboard/editproduct/"+e_id+'/'+a_id+"'></form>");
			var form = jQuery('#editProduct form');
			form.append('<input type="hidden" name="product_name" value="'+p_title+'">');
			form.append('<input type="hidden" name="product_url" value="'+p_url+'">');
			
			form.append('<input type="hidden" name="invt_id" value="'+invt_id+'">');
			
			form.append('<input type="hidden" name="productCurrency" value="'+p_currency+'">');

			form.append('<input type="hidden" name="retail_price" value="'+p_retail+'">');
			form.append('<input type="hidden" name="retail_qid" value="'+retail_qid+'">');

			form.append('<input type="hidden" name="wholesale_price" value="'+p_wholesale+'">');
			form.append('<input type="hidden" name="wholesale_qid" value="'+wholesale_qid+'">');

			form.append('<input type="hidden" name="cost_price" value="'+p_cost+'">');
			form.append('<input type="hidden" name="cost_qid" value="'+cost_qid+'">');
			form.append('<input type="hidden" name="discount" value="'+p_discount+'">');
			form.append('<input type="hidden" name="entry_sectionp" value="'+entry_sec+'">');

			form.append('<input type="hidden" name="action_notes" value="'+jQuery.trim(product_notes)+'">');

			form.submit();

			// set the pre-defined values in the form 
			pro_modal.find('#p_name').val(p_title);
			pro_modal.find('#p_url').val(p_url);
			pro_modal.find('.cs_select').html(p_currency);
			jQuery('#product_actions').find('input[name="currency"]').val(p_currency);
			pro_modal.find('#retail_price').val(p_retail);
			pro_modal.find('input[name="retail_qid"]').val(retail_qid);
			pro_modal.find('#wholesale_price').val(p_wholesale);
			pro_modal.find('input[name="wholesale_qid"]').val(wholesale_qid);
			pro_modal.find('#cost_price').val(p_cost);
			pro_modal.find('input[name="cost_qid"]').val(cost_qid);

			// set entry id input field
			
			pro_modal.find('#p_entryId').val(e_id);
			pro_modal.find('#p_actionId').val(a_id);
		});




jQuery('.delete-product').on('click',function(){
	
			var deleteBtn 	= jQuery(this);
			var actionid  	= jQuery(this).data('actionid'); 
			var entryId   	= jQuery(this).data('entryid');
			var del_modal 	= jQuery('#product_delete_modal');
      
			del_modal.find('#del_entry_only').on('click', function(){
				
				// collect data
				var productDiv 	= deleteBtn.parents('.product-specs');
				var retId 		= productDiv.find('.ps-retail .ccp_price').data('qid');
				var whoId 		= productDiv.find('.ps-wholesale .ccp_price').data('qid');
				var cosId 		= productDiv.find('.ps-cost .ccp_price').data('qid');
				var delFrom 	= jQuery(this).data('value');

				jQuery.ajax({
					type: 'POST',
					url:  '<?php echo base_url('dashboard/delete_product'); ?>',
					data: {actionId: actionid, entryid: entryId, retailQid: retId, wholesaleQid: whoId, costQid: cosId, deleteFrom: delFrom},
					success: function(res){
						console.log(res);
						if(res === 'success'){
							del_modal.find('.alert').remove();
							del_modal.find('.modal-body').append('<br><div class="alert alert-success">Product deleted from the entry successfully!<a href="#" target= "_self" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');
							setTimeout(function(){
								jQuery('#overlay').css({'display': 'block'});
								window.location.reload();
							},500);

						} else if(res === 'error'){
							del_modal.find('.alert').remove();
							del_modal.find('.modal-body').append('<br><div class="alert alert-danger">Some error occured while saving the product, Please Try Again!</div>');
						}
					},
					error: function(err){
						console.log(err.error.toSource());
						console.log(err.toSource());
					}
				});
			});
		});
jQuery('.del-customer').on('click',function(){
	 u_id     = jQuery(this).data("customid");
	 en_id     = jQuery(this).data("entryid");
	 entrycont="";
	 conte="";

conte = jQuery(this).data('container');
entrycont = "#entry_container_";
	 if(confirm("You have selected to remove a user from an entry, this action can't be undone. The only way to reverse this action is to add the user again manually. Do you wish to proceed?"))
	 {
	 jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url('crmmails/user_remove/')?>",
				data: {'u_id': u_id, 'entry_id': en_id},
				dataType: "json",
				success: function(resp){
					if(resp.result == 0){
						alert("You cannot leave a case without a customer. You either need to delete the whole case or add another customer before removing the current customer.");
					 }
					 else if(resp.result == 1){
					window.location.reload();
					}
					}
				})
				jQuery('html,body').animate({
                scrollTop: $(entrycont+conte).offset().top
            }, 1000);
	 }
		});	
		
		/*code to get text box value edit staff*/
//////////////////////////////////////////
    jQuery(document.body).on('click', '#staff', function(){
		//staffid = $('#staffIds').val();
		jQuery("#s_entryId").val(jQuery(this).data('entry'));
		var s_names = jQuery(this).data('staff');
		var sid = jQuery(this).data('stid');
		jQuery("#add_staff_modal").modal("show");
		current_sid =  jQuery("#add_staff_modal").find("#staffIds");	
       var containerid = jQuery(this).data('container');	
		container = jQuery("#add_staff_modal").find("#containerid");
		jQuery("#add_staff_modal").find(".tag-labels").empty();
		current_sid.val("");
	   for (i = 0; i < sid.length; i++) { 
	  
		jQuery("#add_staff_modal").find(".tag-labels").append("<li class='tag-choice'><span class='tag-label'>"+s_names[i]+"</span><a class='tag-close removeUser' target= '_self'><span class='text-icon'>×</span><span class='hidden labelValue'>"+sid[i]+"</span></a></li>");
          current_sid.val( current_sid.val() + sid[i] + ',' );
		 };
		 container.val(containerid);
	})
//////////////////////////////////////////
/*code to get text box value edit staff*/
//////////////////////////////////////////
jQuery(document.body).on('click', '#save_staff', function(e){
	e.preventDefault();
	var entry = jQuery('#s_entryId').val();
	entrycont="";
	conte="";
	
var conte = jQuery('#containerid').val();
	entrycont = "#entry_container_";
	var staff = jQuery.trim($('#staffIds').val());
	var staffid = staff.slice(-1);
 if(staffid == ',') {
 var staff = staff.slice(0, -1);

} 
	var durl = document.URL;
	//var acessToken = durl.split('?')[1].split('=')[1];
	if (durl.indexOf('addentry=entry') > -1) {
 	var eurl = true;
} else {
    eurl = false;
}
	jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url('entry/update_staff_assign/')?>",
				data: {'staffid': staff, 'entryId': entry},
				success: function(res){
					window.location.reload();
					
				}
			});
			jQuery('html,body').animate({
                scrollTop: jQuery(entrycont+conte).offset().top
            }, 1000);
	})	;
	jQuery(function(){
	var searchUrl = '<?php echo base_url("entry/search"); ?>';
		jQuery('.type_search_loader').hide();
		jQuery('#addStaff').userAutocomplete('staff', $('#staffIds'), searchUrl, 'multiple');
		jQuery('#addCustomer').userAutocomplete('customer', $('#customerIds'), searchUrl, 'multiple');
		jQuery("ul.ui-autocomplete").css({'z-index': '1050'});})
		
		///////////////////////////////////////////////
		
		////////////ADD customer modal///////////////////////////
		jQuery(document.body).on('click', '#customer', function(){
		//staffid = $('#staffIds').val();
		jQuery("#c_entryId").val(jQuery(this).data('entry'));
		var s_names = jQuery(this).data('cust');
		var sid = jQuery(this).data('stid');
		jQuery("#add_customer_modal").modal("show");
		current_sid =  jQuery("#add_customer_modal").find("#customerIds");	
       var containerid = jQuery(this).data('container');	
		container = jQuery("#add_customer_modal").find("#containerid");
		jQuery("#add_customer_modal").find(".tag-labels").empty();
		current_sid.val("");
	   for (i = 0; i < sid.length; i++) { 
	  
		jQuery("#add_customer_modal").find(".tag-labels").append("<li class='tag-choice'><span class='tag-label'>"+s_names[i]+"</span><a class='tag-close removeUser' target= '_self'><span class='text-icon'>×</span><span class='hidden labelValue'>"+sid[i]+"</span></a></li>");
          current_sid.val( current_sid.val() + sid[i] + ',' );
		 };
		 container.val(containerid);
	})
	//save customer////////////
	//////////////////////////////////////////////
	
	jQuery(document.body).on('click', '#save_customer', function(e){
	e.preventDefault();
	var entry = jQuery('#c_entryId').val();
	entrycont="";
	conte="";

conte = jQuery('#containerid').val();
entrycont = "#entry_container_";
	var customer = jQuery.trim($('#customerIds').val());
	var custid = customer.slice(-1);
 if(custid == ',') {
 var customer = customer.slice(0, -1);

} 
	var durl = document.URL;
	//var acessToken = durl.split('?')[1].split('=')[1];
	if (durl.indexOf('addentry=entry') > -1) {
 	var eurl = true;
} else {
    eurl = false;
}
	jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url('entry/update_customer_assign/')?>",
				data: {'custid': customer, 'entryId': entry},
				success: function(res){
					window.location.reload();
				}
			});
			jQuery('html,body').animate({
                scrollTop: $(entrycont+conte).offset().top
            }, 1000);
	})
	
	//////////////////////////to delete customer//////////////////////
		jQuery('.del-supplier').on('click',function(){
	 u_id     = jQuery(this).data("supplierid");
	 en_id     = jQuery(this).data("inventory_id");
	 entry_id     = jQuery(this).data("entryid");
	 entrycont="";
	 conte="";

conte = jQuery(this).data('container');
entrycont = "#entry_container_";
	if(confirm("You have selected to remove a supplier from an entry, this action can't be undone. The only way to reverse this action is to add the supplier again manually. Do you wish to proceed?"))
	 {
	 jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url('entry/supplier_remove/')?>",
				data: {'u_id': u_id, 'inventory_id': en_id, 'entryid':entry_id},
				dataType: "json",
				success: function(res){
				//alert(resp);
					if(res.result == 0){
						//alert(res.oneuser);
						alert("You cannot leave a case without a Supplier. You either need to delete the whole case or add another Supplier before removing the current Supplier.");
					 }
					else if(res.result == 1){
						//alert(res.removeuser);
					window.location.reload();
				
					}
					}
				});
				jQuery('html,body').animate({
                scrollTop: jQuery(entrycont+conte).offset().top
            }, 1000);
	 }
		});		
			
	
			/*code to get text box value edit supplier*/
//////////////////////////////////////////
    jQuery(document.body).on('click', '#suppl', function(){
		//staffid = $('#staffIds').val();
		jQuery("#sup_entryId").val(jQuery(this).data("entryid"));
		jQuery("#inventory_id").val(jQuery(this).data('inventory'));
		var containerid = jQuery(this).data('container');	
		container = jQuery("#add_supplier_modal").find("#containerid");
		var sup_names = jQuery(this).data('suplier');
		var supid = jQuery(this).data('supid');
		jQuery("#add_supplier_modal").modal("show");
		current_supid =  jQuery("#add_supplier_modal").find("#supplierIds");	
       current_supid.val("");
        jQuery("#add_supplier_modal").find(".tag-labels").empty();
	   for (i = 0; i < supid.length; i++) { 
	  
		jQuery("#add_supplier_modal").find(".tag-labels").append("<li class='tag-choice'><span class='tag-label'>"+sup_names[i]+"</span><a class='tag-close removeUser' target= '_self'><span class='text-icon'>×</span><span class='hidden labelValue'>"+supid[i]+"</span></a></li>");
          current_supid.val( current_supid.val() + supid[i] + ',' );
		  container.val(container.val() + containerid);
		 };
	})
//////////////////////////////////////////
/*code to get text box value edit staff*/
//////////////////////////////////////////
jQuery(document.body).on('click', '#save_supllier', function(e){
	e.preventDefault();
	var entryid = $("#sup_entryId").val();
	entrycont="";
	conte="";

conte = jQuery('#containerid').val();
entrycont = "#entry_container_";
	var inventory = jQuery('#inventory_id').val();
	var supplier = jQuery.trim($('#supplierIds').val());
	var suplierid = supplier.slice(-1);
 if(suplierid == ',') {
 var suplierId = supplier.slice(0, -1);

} 
	var durl = document.URL;
	//var acessToken = durl.split('?')[1].split('=')[1];
	if (durl.indexOf('addentry=entry') > -1) {
 	var eurl = true;
} else {
    eurl = false;
}
	jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url('entry/update_supplier_assign/')?>",
				data: {'supid': suplierId, 'inventoryId': inventory, 'entryid':entryid},
				success: function(res){
					location.reload();
				}
			});
			 jQuery('html,body').animate({
                scrollTop: $(entrycont+conte).offset().top
            }, 1000);
	})
				</script>
              