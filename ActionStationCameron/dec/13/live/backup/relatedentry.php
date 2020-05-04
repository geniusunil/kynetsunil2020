<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style>
 /*.container .tab {
    width: 0px !important;
   
}

.tab{margin:0px !important;
display: table-cell !important; }*/
.ulink {
    background:none!important;
     border:none; 
     padding:0!important;
    
    margin: 0px 10px;
    font-family:arial,sans-serif;
     color:#069;
     text-decoration:underline;
     cursor:pointer;
}
</style>
<?php $session_data   = $this->session->userdata('logged_in');
      $user_type      = $session_data['usertype']; 
      $user_details   = get_user_details($session_data['id']); 
      $utype          = get_user_type($user_type);
	  $ustype         = $utype->type_name ;
	  $ustype         = preg_replace('/\s+/', '', $ustype);
      $ustype         = strtolower($ustype);
	  $dbname         = $this->session->userdata('database_name');
	  $time           = $this->session->userdata('time');
	  $session_curen  = $this->session->userdata('currency');
	  $id             = $session_data['id'];
	  $incoming_email = $this->accounts_model->systems_datas($id);
		 if($dbname){
			 foreach($incoming_email as $incoming_emails){
			  $incoming_emailss = $incoming_emails->incoming_email;
			  }
			 }?>
  <?php function getContents($str, $startDelimiter, $endDelimiter) {
  $contents             = array();
  $startDelimiterLength = strlen($startDelimiter);
  $endDelimiterLength   = strlen($endDelimiter);
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
 <?php //$message = htmlentities($message, ENT_COMPAT,'ISO-8859-1', true); 
 
?>
 	<?php 
	  if($ustype == 'businessaccountmanager'){
			$f_file   =  'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/'.$filename;	
		}else{
			if($dbname){
			if(empty($incoming_emailss)){
			 $f_file   =  'email_files/'.$dbname."/".$username.'/'.$filename;
		}else{
			$id = $session_data['id'];	
	        $f_file   =  'email_files/'.$dbname."/".$username.'/'.$filename;	
		}	
			}else{
				$id = $session_data['id'];	
				$f_file   =  'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/'.$filename;
			}

		}
		 
		 $sample = file_get_contents($f_file);
		 $f_file;
	 $message = getContents($sample, '<message>', '<endmessage>');
		?>
        
  <div class="modal fade" id="add_customer_modal" tabindex="-1" role="dialog" aria-labelledby="addNewStaff">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="addNewCustomer">
					Select User
					
				</h4>
			</div>
			<form id="product_actions" method="post">
				<input type="text" value="" name="currency" style="display:none">
				<div class="modal-body">
					
					<fieldset >
			            <label for="addCustomer" title="got me">Assign email to an existing user - start typing user information to get a match</label>
			            <!-- autocomplete [assign customer] -->
			            <div class="form-control">
			                <img src="<?php echo base_url('images/'); ?>loading_spinner.gif" class="type_search_loader" style="">
			                <ul class="tag-labels modal-autocomplete"></ul>
			                <input type="text" name="assignCustomer" id="assignCustomer" class="form-control tag-input" data-html="true"/>
			            </div>
                        <?php $hids = 0; 
						 $sse = preg_replace('/\s+/', '', $senderemail);
						?>
                            <input type="hidden" name="cAssign[]" id="customerIds" value="" required>
                            <input type="hidden" id="email" value="<?php echo $fname?>" required>
                            <input type="hidden" id="subject" value="<?php echo $subject?>" required>
                            <?php foreach( $message as  $mess){?>
                            <input type="hidden" id="mm" value="<?php echo $mess?>" required><?php }?>
                            <input type="hidden" id="attach" value="<?php echo $attachment?>" required>
                            <input type="hidden" id="attach_1" value="<?php echo $attach_pdf?>" required>
                            <input type="hidden" id="attach_2" value="<?php echo $attach_doc?>" required>
                            <input type="hidden" id="ff" value="<?php echo $filename?>" required>
                            <input type="hidden" id="e_date" value="<?php echo $edate?>" required>
                            <input type="hidden" id="f_name" value="<?php echo $fname?>" required>
                            <input type="hidden" id="he_id" value="<?php echo $hids?>" required>
                             
			            <!-- end [assign customer] -->
			          </fieldset>
                     
					
					<input type="text" name="entry_id" id="c_entryId"  style="display:none">
                <!---<a id="createnew" href="<?php// echo base_url('accounts/create'); ?>" class="btn btn-primary" >Create New Staff</a>---->
				</div>
				<div class="modal-footer">
					<div class="text-right">
						<img src="<?php echo base_url('images/loading_spinner.gif'); ?>" class="saving-gif" style="display:none">
					
						<button id="save_customer" name="product_action" class="btn btn-primary" value="">Save</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</form>
			<form target="_self" action="<?php echo base_url('/accounts/create_user')?>" method="post" style="width:120px">
                        <input type="hidden" name="c_user"  value="<?php echo $create?>">
                        <input type="hidden" name="uid"  value="<?php echo $senderemail?>">
                        <input type="hidden" name="subject" value="<?php echo $subject?>">
                            <?php /*?><?php $message = htmlentities($message, ENT_COMPAT,'ISO-8859-1', true) ?><?php */?>
                            <?php foreach( $message as  $mess){?>
                        <input type="hidden" id="mm" value="<?php echo $mess?>" required><?php }?>
                            <?php if(!empty($attachment) || !empty($attach_pdf) || !empty($attach_doc)){ ?>
                        <input type="hidden" name="attachment" value="<?php echo $attachment?>">
                        <input type="hidden" name="attach_1" value="<?php echo $attach_pdf?>" >
                        <input type="hidden" name ="attach_2" value="<?php echo $attach_doc?>">
                            <?php  } ?>
                        <input type="hidden" name="f_name" value="<?php echo $fname?>">
                        <input type="hidden" name="file_name" value="<?php echo $filename?>">
                        <input type="hidden" name="edate" value="<?php echo $edate?>">
                        <input type="submit" name="submit" class="btn btn-warning entry" style="float:left;position: absolute; top: 13em; left: 1em;" value="Create New">
                    </form>
		</div>
	</div>
</div><!----end staff model---->
<div class="col-md-6 edata">

<table class="tab">
<?php 
if($rel_user != ""){?>
<style>
#usid {
    margin-top: 16px;
}
</style>
<h3>Email Matched to the Following User </h3>
<h4>Select the user's name or email address to proceed.</h4>
<th>Name1</th>
<th>Email</th>

				
                <?php
				 $uid ="";
				
				foreach($rel_user as $user_d)
				{?>
					<tr>
            <?php   $id          = $user_d->user_id;
				    $mail2[]     = $id;
				    $senderemail = preg_replace('/\s+/', '', $senderemail);
					$usrinfo     = get_user_details($id);
					
					if (!empty($usrinfo)) {
						if(!empty ($hids))
						{
							$h_id = $hids;
						}else{
							$h_id = 0;
						}?>

                        <input type="hidden" id="us_id" value="<?php echo $id?>">
                         <tr> <td>
                    <form action="<?php echo base_url('/crmmails/viewentry_1')?>" method="post">
                        <input type="hidden" name="uid"  value="<?php echo $senderemail?>">
                        <input type="hidden" name="subject" value="<?php echo $subject?>">
                        <?php  $message = htmlentities($message, ENT_COMPAT,'ISO-8859-1', true); ?>
                            <?php foreach( $message as  $mess){?>
                        <input type="hidden" id="mm" value="<?php echo $mess?>" required><?php }?>
                        <input type="hidden" name="attach" value="<?php echo $attachment?>">
                        <input type="hidden" name="attach_1" value="<?php echo $attach_pdf?>" required>
                        <input type="hidden" name="attach_2" value="<?php echo $attach_doc?>" required>
                        <input type="hidden" name="f_name" value="<?php echo $fname?>">
                        <input type="hidden" name="file_name" value="<?php echo $filename?>">
                        <input type="hidden" name="edate" value="<?php echo $edate?>">
                        <input type="hidden" name="us_id"id="us_id" value="<?php echo $id?>">
                        <input type="hidden" name ="he_id" id="he_id" value="<?php echo $h_id?>" required>
                        <input type="submit" name="submit" <?php if(array_key_exists('first_name', $usrinfo) || array_key_exists('last_name', $usrinfo)){?> class="ulink"<?php } ?> value="<?php 
							if(array_key_exists('first_name', $usrinfo) || array_key_exists('last_name', $usrinfo)){
								echo $usrinfo->first_name." ".$usrinfo->last_name; ;
							}
							 ?>"></td>
                               <td>
							   
                            <?php if(array_key_exists('email_primary', $usrinfo)){  echo $usrinfo->email_primary;}?>
                       </form> </td>
                       <?php /*?> <td><a href="<?php echo base_url().'crmmails/viewentry/'.$h_id.'/'.$id.'/'.$senderemail.'/'.$subject.'/'.$message.'/'.$filename.'/'.$fname.'/'.$edate?>" style="margin-right:10px;" target="_blank"><?php if(array_key_exists('first_name', $usrinfo)){
							 echo $usrinfo->first_name;
							 }else{
								 echo "N/A";
							 } 
						if(array_key_exists('last_name', $usrinfo)){
							echo " ".$usrinfo->last_name;
							} else{
								echo "N/A";
								}?></a></td>
                       <td><a href="<?php echo base_url().'crmmails/viewentry/'.$h_id.'/'.$id.'/'.$senderemail.'/'.$subject.'/'.$message.'/'.$filename.'/'.$fname.'/'.$edate?>" style="margin-right:10px;"  target="_blank"><?php if(array_key_exists('email_primary', $usrinfo)){  echo $usrinfo->email_primary;} else { echo "N/A";}?></a></td><?php */?>
                           </tr><tr><td><button type = "button" class="btn btn-warning" data-id="<?php echo $id; ?>"data-user = "<?php if(array_key_exists('last_name', $usrinfo)){ echo $usrinfo->first_name.$usrinfo->last_name; 
						   }?>" id ="usid">Select a Different User</button></td></tr>
                      
                     <?php }   ?>
                     <tr> <td> <div class="userlist"></div></td></tr>
                      </tr>
					<?php } 
					       }else{
						   ?>
						   
						   <?php 
						  $subject       = str_replace("%20"," ",$subject);
						  $message       = str_replace("%20"," ",$message);
						  $edate         = trim($edate);
						if(!empty($cust_ids)){
							$senderemail = preg_replace('/\s+/', '', $senderemail);
							$cust_ids    = json_decode($cust_ids);
							foreach($cust_ids as $aa){
							$id      = $aa;
							$usrinfo = get_user_details($id);
												
						if(!empty($usrinfo)){
						if(!empty ($hids))
						{
						 $h_id = $hids;
						}else{
							$h_id = 0;
						}  ?>

                    <input type="hidden" id="us_id" value="<?php echo $id?>">
                         <tr> <td>
                    <form action="<?php echo base_url('/crmmails/viewentry_1')?>" method="post">
                        <input type="hidden" name="uid"  value="<?php echo $senderemail?>">
                        <input type="hidden" name="subject" value="<?php echo $subject?>">
                            <?php foreach( $message as  $mess){?>
                        <input type="hidden" id="mm" value="<?php echo $mess?>" required><?php }?>
                        <input type="hidden" name="attach" value="<?php echo $attachment?>">
                        <input type="hidden" name="attach_1" value="<?php echo $attach_pdf?>">
                        <input type="hidden" name="attach_2" value="<?php echo $attach_doc?>">
                        <input type="hidden" name="f_name" value="<?php echo $fname?>">
                        <input type="hidden" name="file_name" value="<?php echo $filename?>">
                        <input type="hidden" name="edate" value="<?php echo $edate?>">
                        <input type="hidden" name="us_id"id="us_id" value="<?php echo $id?>">
                        <input type="hidden" name ="he_id" id="he_id" value="<?php echo $h_id?>" required>
                        <input type="submit" name="submit" <?php if(array_key_exists('first_name', $usrinfo) || array_key_exists('last_name', $usrinfo)){ ?> class="ulink"<?php }?> value="<?php if(array_key_exists('first_name', $usrinfo)){
							echo $usrinfo->first_name;
						  }

						if(array_key_exists('last_name', $usrinfo)){
							echo " ".$usrinfo->last_name;
							} ?>">
                               
                        <input type="submit" name="submit" class="ulink" value="<?php if(array_key_exists('email_primary', $usrinfo)){  echo $usrinfo->email_primary;}?>">
                       </form> </td></tr><tr><td><p style="color:#F00"> Select User to Resume Wizard </p> </td>  </tr> 
                       
                     <?php }
						     }
						       }
					
						$create        = "c_user";
						$senderemail   = preg_replace('/\s+/', '', $senderemail);
						$subject       = str_replace("%20"," ",$subject);
						$message       = str_replace("%20"," ",$message);
						$edate         = trim($edate);
						?>
						<!--echo "<td>". anchor('accounts/create_user', 'Create New', array('class' => 'btn btn-warning entry')).'</td>';-->  
                       <tr> <td>
                    <form target="_self" action="<?php echo base_url('/accounts/create_user')?>" method="post" style="width:120px">
                        <input type="hidden" name="c_user"  value="<?php echo $create?>">
                        <input type="hidden" name="uid"  value="<?php echo $senderemail?>">
                        <input type="hidden" name="subject" value="<?php echo $subject?>">
                            <?php /*?><?php $message = htmlentities($message, ENT_COMPAT,'ISO-8859-1', true) ?><?php */?>
                            <?php foreach( $message as  $mess){?>
                        <input type="hidden" id="mm" value="<?php echo $mess?>" required><?php }?>
                            <?php if(!empty($attachment) || !empty($attach_pdf) || !empty($attach_doc)){ ?>
                        <input type="hidden" name="attachment" value="<?php echo $attachment?>">
                        <input type="hidden" name="attach_1" value="<?php echo $attach_pdf?>" >
                        <input type="hidden" name ="attach_2" value="<?php echo $attach_doc?>">
                            <?php  } ?>
                        <input type="hidden" name="f_name" value="<?php echo $fname?>">
                        <input type="hidden" name="file_name" value="<?php echo $filename?>">
                        <input type="hidden" name="edate" value="<?php echo $edate?>">
                        <input type="submit" name="submit" class="btn btn-warning entry" value="Create New">
                       </form> </td>
                      
                        <td><button type= "button" class="btn btn-warning" id ="usid">Select User</button></td></tr>
                         <tr> <td> <div class="userlist">
                     </div></td></tr>
                       <?php } ?>
                      </table>
                 
                </div>
                <style>
				.edata{
					position:fixed !important;	
				}
				</style>
<script>
 $( "#assignCustomer" ).keyup(function( event ) 
			{
			  $('.tooltip').hide();
			});
	  // var jQuery = $.noConflict();
       var searchUrl = '<?php echo base_url("entry/search"); ?>';
		jQuery('.type_search_loader').hide();
		jQuery('#assignCustomer').userAutocomplete1('customer', jQuery('#customerIds'), searchUrl, 'multiple');
		jQuery("ul.ui-autocomplete").css({'z-index': '1050'});
		
        jQuery(document.body).on('click', '#usid', function(e){
			e.preventDefault();
			jQuery("#c_entryId").val(jQuery(this).data('id'));
			var s_names = jQuery(this).data('user');
			var sid = jQuery(this).data('id');
			jQuery("#add_customer_modal").modal("show");
			current_sid =  jQuery("#add_customer_modal").find("#customerIds");
	  })
//////////////////////////////////////////
/*code to get text box value edit staff*/
//////////////////////////////////////////
	jQuery(document.body).on('click', '#save_customer', function(e){
		e.preventDefault();
		var email      = jQuery('#email').val();
		var subject    = jQuery('#subject').val();
		var message    = jQuery('#mm').val();
		var attachmnt  = jQuery('#attach').val();
		var attach_pdf = jQuery('#attach_1').val();
		var attach_doc = jQuery('#attach_2').val();
		var filename   = jQuery('#ff').val();
		var s_name     = jQuery('#f_name').val();
		var dates      = jQuery('#e_date').val();
		var hh         = jQuery('#he_id').val();
		staff          = jQuery.trim(jQuery('#customerIds').val());
		staffid        = staff.slice(-1);
		if(staffid == ',') {
	  	staff = staff.slice(0, -1);
		} 
		jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url('crmmails/userlist/')?>",
				data:{'id': staff, 'email': email, 'title': subject, 'mess': message, 'file': filename, 'f_name': s_name, 'e_date': dates, 'hid': hh, 'attachment': attachmnt, 'attachment_pdf': attach_pdf, 'attachment_doc': attach_doc},
				success: function(res){
					jQuery(".userlist").html(res);
					jQuery('#add_customer_modal').modal('hide');
					}
				});
	
	}) 

</script>