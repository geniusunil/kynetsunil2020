<script>
// alert("before nc Version: "+jQuery.fn.jquery);
        var $x = jQuery.noConflict();
        </script>
<?php 


defined('BASEPATH') or exit('No direct script access allowed');
        // echo  "emails view run\n";

  $session_data   = $this->session->userdata('logged_in');
  if($session_data['id'] == ''){
    echo '<a href="'.base_url().'" style="
    text-decoration: none;
"><span style="color:#fff;background: indianred;padding: 5px;margin: 0 auto;width: 122%;display: table;text-align: center;font-weight: 900;">Oops! You are not logged in! Please login and then continue ....</span></a>';
  }
	$user_type      = $session_data['usertype'];
	$id             = $session_data['id'];
	$utype          = get_user_type($user_type);
	$ustype         = $utype->type_name;
	$ustype         = preg_replace('/\s+/', '', $ustype); 
	$ustype         = strtolower($ustype);
	$dbname         = $this->session->userdata('database_name');
	$incoming_email = $this->accounts_model->systems_datas($id);

	if ($dbname) {
	 foreach ($incoming_email as $incoming_emails) {
	  $incoming_emailss        = $incoming_emails->incoming_email;
	  $incoming_email_password = $incoming_emails->incoming_email_password;
		}
	}
	  $incoming_sys_email = $this->accounts_model->systems_data();
	  $system_data_pswd   = $this->accounts_model->system_data_pswd();

	foreach ($incoming_sys_email as $incoming_emails) {
	  $incoming_sys_emails = $incoming_emails->setting_value;
	}	
	foreach ($system_data_pswd as $system_data_pswds) {
	  $incoming_sys_email_pass = $system_data_pswds->setting_value;
	}	
	if (!empty($incoming_emailss)) {
	   $username   = $incoming_emailss;
	   $pass_email = $incoming_email_password;
	} else {
	   $username   = $incoming_sys_emails;
	   $pass_email = $incoming_sys_email_pass;
	} ?>
  <?php
  if($_GET['trashpage']==''){
    ?>
    <style>

.email_view1{
  display: none;
}
.descrip2{
  display: none;
}

.delcheck1{
  display: none;
}
.delcheck{
	display: block;
}
.chk_boxes1{
	display: none;
}
.chk_boxes{
	display: inline-block;
}
.trash-pagination{
  display: none;
}
.Restore{
  display: none;
}
.p_e_del{
  display: none;
}
.back_incoming{
  display: none;
}
</style> 
    <?php
  }
  else{
    ?>
<style>

.email_view{
  display: none;
}
.descrip{
  display: none;
}
.delcheck1{
  display: block;
}
.delcheck{
display: none;
}
.chk_boxes1{
	display: inline-block;
}
.chk_boxes{
	display: none;
}
.simple-pagination{
  display: none;
}
.Trashed{
  display: none;
}
.dd{
  display: none;
}

</style> 

    <?php
  } ?>

<style>
body .cancel{
	color: #333!important;
}
.main{
	display:block !important;
	overflow:hidden !important;
	min-width:100% !important;
	padding:20px !important;
	background-image:none !important;
}
body a{
    color:#333 !important;
}
.ui-dialog.ui-corner-all.ui-widget.ui-widget-content.ui-front.ui-draggable.ui-resizable{
 width:750px !important;	
}

.hrrr {
	border: 0;
	clear:both;
	width: 96%;
	background-color:#bfbbbb;
	height: 1px;
}
.col-xs-5 {
    width: 41.66666667% !important;
	position: relative !important;
    min-height: 1px !important;
    padding-left: 5px !important;
    padding-right: 5px !important;
}
.col-md-3{width:30% !important;}
.cancel{color: #333;!important;
	background-color: #fff; !important;
	border-color: #ccc; !important;
	text-decoration:none !important}
.cancel:hover{background:#cccccc;}
.subject{margin:0px !important}
#zxcv{
  width:50% !important;
  }
</style>

<?php  if (!empty($this->session->userdata('sys_email'))) {
       $s_email = $this->session->userdata('sys_email'); 
      }
    
    if ($s_email == "camsaurustest@gmail.com" && $ustype !== 'businessaccountmanager') {?>
<style>
.incoming a{  margin-left:1% !important; }
</style>
    
    <div class="row">
<style>
.bars span{ margin-left:35% !important; }
</style>

	<?php if ($ustype == 'businessaccount') { ?>
    <div class="bars" style="background-color:#f0ad4e !important;">
    <?php echo '<span style="color:#fff;">You are logged into your Business Management Account</span>'; ?>
    </div>
    <?php }
    if ($ustype == 'businessaccountmanager') { ?>
    <div class="bars" style="background-color:#337ab7 !important">
    <?php echo '<span style="color:#fff;">You are logged into the Business Action Complete - Owner Account</span>'; ?>
    </div>
    <?php }?>
    <div class="col-xs-7"><h3>The Incoming Communications</h3></div>
    <div class="col-xs-3"><h3 class ="assig">User Creation/Assignment</h3></div>
<style>
.assig{
    position:fixed !important;
 }
</style>
    
    <div class="col-xs-2 ">
    <div class="gap"></div><div class="gap"></div>
    <a target="_self" class="btn btn-default cancel" style="float:right" href="<?php echo base_url(); ?>">Cancel</a>
    </div>
    
    <div class="col-md-12 bar" style="display:none;">
    <hr class="hrrr"/>
    </div>
       </div>
       
    <div class="gap"></div>
    <div class="gap"></div>
    
    <div class="incoming" style="text-align:center;">
    <?php echo "No Incoming Email Settings found";
    echo anchor('accountsetting', 'Add Now'); ?>
    </div>
    <?php } else {?>
    
    <div class="col-md-12">
    <div class="row">
<style>
.bars span{  margin-left:35% }
</style>
 
	<?php if ($ustype == 'businessaccount') {?>
    <div class="bars" style="background-color:#f0ad4e;">
    <?php echo '<span style="color:#fff;">You are logged into your Business Management Account</span>'; ?>
    </div>
    <?php }
    if ($ustype == 'businessaccountmanager') {?>
    <div class="bars" style="background-color:#337ab7">
    <?php echo '<span style="color:#fff;">You are logged into the Business Action Complete - Owner Account</span>'; ?>
    </div>
    <?php } ?>

    <div class="col-xs-7"><h3>Incoming Email Communications</h3></div>
    <div class="col-xs-3"><h3 class ="assig">User Creation/Assignment</h3></div>
<style>
.assig{ position:fixed !important; }
</style>

    <div class="col-xs-2">
    <div class="gap"></div><div class="gap"></div>
     <a target="_self" class="btn btn-default cancel" style="float:right" href="<?php echo base_url(); ?>">Exit</a>
    </div>
    
    <div class="col-md-12 bar" style="display:none;">
     <hr class="hrrr"/>
    </div>
    </div>
    
    <div class="row descrip">
    <div class="col-xs-5">
     <p style="font-size:14px !important;">New emails that need to be manually assigned to cases.</p>
    </div> </div>
    
    <div class="row descrip2">
    <div class="col-xs-5">
      <p style="font-size:14px !important;">Trashed emails...</p>
    </div> 
    </div>

</div>
<style>
.dd { font-size: 14px !important; }
.Trashed {  font-size: 14px !important; }
</style>

    <div class='col-md-6 bbb'> 
    <input type="checkbox" class="chk_boxes" label="check all"  />
		 <input type="checkbox" class="chk_boxes1" label="check all"  /><b>Select all on page</b>&nbsp;
		<span class="hidden">
<?php
      $custm_id = ''; //get_Contents($sample, '<userid>', '<enduserid>');
      $emailUID = get_Contents($sample, '<uid>', '<enduid>');
		$a = get_Contents($sample, '<id>', '<endid>');						  
      echo "<br>(for debugging purposes) for the email above SID : $a"; 
      echo " UID : $emailUID"; 
      echo " EmailID : $username"; ?></span>
		
    <button value="delete" class="btn btn-danger dd" name="delete" >Do No Track</button>
    <button type="button" value="button" class="btn btn-warning Trashed" name="Trashed" >Trashed Emails</button>
    <button type="button" value="button" class="btn btn-warning back_incoming" name="Incoming Communication" >Incoming Communication</button>
    <button type="button" value="button" class="btn btn-danger Restore" name="Restore" >Restore</button>
    <button type="button" value="button" class="btn btn-danger p_e_del" name="Permanently Delete" >Permanently Delete</button>
    </div>
<style>

</style>
    <div class="col-md-3">
	<div id="progressbar" style="border:1px solid #ccc; border-radius: 5px; "></div>
		<!-- Progress information -->
		<br>
    <div id="information" ></div>
    <div id="dateerror" ></div>
	</div>

<iframe id="loadarea" src="../email_crm.php" style="display:none;"></iframe><br />

<script>
jQuery(function() {
    jQuery('.chk_boxes').click(function() {
        jQuery('.delcheck').prop('checked', this.checked);
    });
});
	jQuery(function() {
    jQuery('.chk_boxes1').click(function() {
        jQuery('.delcheck1').prop('checked', this.checked);
    });
});
</script>

<div class="col-md-12">
<div class="col-md-8" id="zxcv">

<?PHP if ($ustype == 'businessaccountmanager') {
          $relativeDir  = 'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/';
          $dir          = 'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/';
          $imapusername = 'camsaurustest@gmail.com';
          $imappassword = 'camsaurus';
    }else {
      if ($dbname) {
          $relativeDir  = 'email_files/' . $dbname . "/" . $username . '/';
          $dir          = 'email_files/' . $dbname . "/" . $username . '/';
          $imapusername = $username;
          $imappassword = $pass_email;

       }else {
          $relativeDir  = 'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/';
          $dir          = 'email_files/ceocamer_cam_crm/camsaurustest@gmail.com/';
          $imapusername = 'camsaurustest@gmail.com';
          $imappassword = 'camsaurus';
        }
    }

    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/dev/action_station/debug.txt", "looking for email files at " . $dir . PHP_EOL, FILE_APPEND);
    $textwithprocessed = glob("{$dir}*.{txt}", GLOB_BRACE);


    function sanitizeNames(&$text,$dir){
      // echo "dir is $dir";
       $i=0;
      foreach($text as $t){
        $text[$i]=str_replace(" ","",$t);
   
        $counttrash = substr_count($text[$i], "trashed_");
        $j=1;
        while($counttrash>1 && $j<$counttrash){
          $trashpos = strpos($text[$i],"trashed_");
          $text[$i] = substr_replace($text[$i], "", $trashpos, strlen("trashed_"));

          $j++;
        }

        $countprocess = substr_count($text[$i], "process_");
        $j=1;
        while($countprocess>1 && $j<$countprocess){
          $processpos = strpos($text[$i],"process_");
          $text[$i] = substr_replace($text[$i], "", $processpos, strlen("process_"));

          $j++;
        }
      
         if (file_exists($t) && $t!=$text[$i]) {
          rename($t, $text[$i]);
          }
        $i++;
      } 
  } 

    sanitizeNames($textwithprocessed,$dir);
 
    $textonlyprocessed = glob("{$dir}process_*.{txt}", GLOB_BRACE);
    $textonlytrashed = glob("{$dir}trashed_*.{txt}", GLOB_BRACE);
    $text = array_diff($textwithprocessed, $textonlytrashed);
    $text = array_diff($text, $textonlyprocessed);

    // echo "no of text emails:". count($text);
    // $text = $textwithprocessed;
    // $text = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $dir_path).'*.txt');
    //next line edited by sunil
    // usort($text, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));
    // usort($text, create_function('$a,$b', 'return extractUnixTimeStamp($b) - extractUnixTimeStamp($a);'));

    function extractUnixTimeStamp($filename){
     
      if (strpos($filename, 'trashed_') !== false) {
        $filename = substr(substr($filename,strrpos($filename,"/")),19,-4);

    }
    else{
      $filename = substr(substr($filename,strrpos($filename,"/")),11,-4);

    }
      return $filename;
    }
    function sortByReceiveDate($text){
      $keyvaluetext = Array();
      foreach($text as $t){
        $keyvaluetext[$t]= extractUnixTimeStamp($t);
      }
    
      arsort($keyvaluetext);
      return array_keys($keyvaluetext);
    }
   
    // sanitizeNames($text);
    // sanitizeNames($textonlytrashed);
    $text = sortByReceiveDate($text);
    $textonlytrashed = sortByReceiveDate($textonlytrashed);
    
    //edit over 
	if ($text !== false) {
	    $filecounts = count($text);
        $filecount  = ceil($filecounts / 10);
    }
    $count = 1;
    $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}Inbox';
    $inbox    = imap_open($hostname, $imapusername, $imappassword);
	
    if ($inbox == false) { ?>   
    <div class="gap"></div>
    <div class="incoming">
      <p style="color:#F00;">Your E-mail is not IMAP enable,please make sure the incoming E-mail that you use should be IMAP enable.</p>
      <a target="_self" href="<?php echo base_url('email/instructions'); ?>">Click here for instructions...</a>
    </div>
<?php }else{
        $ind = $this->input->get('page');
        $_SESSION['last_page_no'] = $ind;
  
        $ind1  = $ind* 10 - 10;
        $start = 0;
        $end   = 10;
		
      if ($ind == 1) {
        $start = 0;
        $end   = 10;
        }
      if ($ind > 1) {
        $start = $ind1;
        $end   = 10 * $ind;
        if ($end > $filecounts - 1) {
          $end = $filecounts - 1;
            }
        }
  

    for ($i = $start; $i < $end; $i++) { //loop for showing the emails one by one
       if ($text != "") {
        $files        = $text[$i];
        $file         = basename($files);
        $text_file_id = substr_replace($file, "", 10);
             if (!empty($result)) {
                }

               $file_name = basename($files);
               $file_name; // outputs 'file'
               $arr = explode('_', ltrim($file_name));

               $arr[0]; // will print Test
               $sample    = file_get_contents($files);
               $xxx       = get_Contents($sample, '<To>', '<endTo>');
               $em        = trim($xxx);

               if ($arr[0] == 'process') {
                unset($text[$i]);
                $text = array_values($text);
                $i = $i-1; 
            } 
            ?>
<style>
.col-md-1.bbb {
    top: 0.5em;
    left: 0.8em;
}
</style> 
          <?php      
        if ($arr[0] !== 'process') {   
         if (!empty($file_name)) {?>
         <div class='another'>
         <div class='col-md-1 bbb'>
  <input name="checkboxes" type="checkbox" class="delcheck" value="<?php echo $file_name ?>">
			 <span class="hidden">
<?php
      $custm_id = ''; //get_Contents($sample, '<userid>', '<enduserid>');
      $emailUID = get_Contents($sample, '<uid>', '<enduid>');
		$a = get_Contents($sample, '<id>', '<endid>');						  
      echo "<br>(for debugging purposes) for the email above SID : $a"; 
      echo " UID : $emailUID"; 
      echo " EmailID : $username"; ?></span>
 </div>
<div class="col-md-11 email_view"  id="abd" data-id="<?php echo $file_name ?>">
<style>
.chk_boxes { margin-left: 3% !important; }
.chk_boxes1 {
margin-right: 6px !important;
margin-left: 2.5% !important;
}
</style>

 <div class="em_head">
     
  <?php $sample    = file_get_contents($files);?>
      <!-- demo start here -->
	 <div  class   ='col-md-3 demo'>
  <?php $address   = get_Contents($sample, '<eid>', '<endeid>');
        $addresses = $address;
        echo '<b>' . iconv_mime_decode($addresses) . '</b>';?>
	  <input type  ="hidden" class="fname" value="<?php echo $addresses ?>">
 </div> <!-- demo ends here -->
        <!-- subject start here -->           
    <div class='col-md-3 subject'>
<?php $sbject = get_Contents($sample, '<subject>', '<endsubject>');
     echo iconv_mime_decode($sbject);?>
	 <input type="hidden" name="subject" class="sbs" value="<?php echo $sbject ?>">
	 </div>
     <!-- subject ends here -->
     <!-- date start here -->
	<div class='col-md-3 date'>
<?php $datee = get_Contents($sample, '<Date>', '<endDate>');
$dt = new DateTime('@'.substr($datee,1));
$dt->setTimeZone(new DateTimeZone('Australia/Perth'));
echo $dt->format('D, j F, Y, g:i a');  ?>
	 <input type="hidden" class="e_date" value="<?php echo $datee ?>"/>
   </div>
   <!-- date ends here -->
    </div>
	
	
	
	
   <div class="col-md-1 end_btn"> <span id="close" class="close " style="display:none">X</span></div>

   <div class ='col-md-12 mess' id='message' style="height:100%;width:100%;overflow:scroll;overflow-y:hidden;overflow-x:scroll;">
   <div class="main_msg <?php echo "mail".$i; ?>">
   
              <?php   		
				    $eid = get_Contents($sample, '<eid>', '<endeid>');
                     echo '<input type="hidden" class="hidden" value="' . $eid . '">';
					 $message  = get_Contents($sample, '<message>', '<endmessage>');
//                    echo "<pre>$message</pre>";
                     $file_ids = get_Contents($sample, '<id>', '<endid>'); 
					 $file_id  = $file_ids;
                     $a        = $file_id;
                     $a        = trim($a);
					
					  	if( function_exists('refine_message')){ // For mails which are not trashed
							echo refine_message($message); 
							
						}
					  else{
						  echo 'no mails';
					  }
                       
					
                     $file_ids = get_Contents($sample, '<id>', '<endid>');
                     $file_id  = $file_ids;
                     $a        = $file_id;
                     $a        = trim($a);

                   if ($ustypees == "businessaccountmanager") {
                     $filees = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com/attachments/".$a;
                       }elseif ($ustypees == "businessaccount") {
                         $filees = "email_files/".$dbname."/".$username."/attachments/".$a;
                           } else {
                             if ($dbname != "") {
                                $filees = "email_files/".$dbname."/".$username."/".$uid."/attachments/".$a;
                              }else {
                                $filees = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com/attachments/".$a;
                             }
                         }
						 
                     echo '<input type="hidden" class="hide" id="img_id_path" value="'.$filees.'">';
					 
                        if ($filees) {
                            $directory  = $_SERVER['DOCUMENT_ROOT'].'/dev/action_station/'.$filees.'/';
                     file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/dev/action_station/debug.txt", "looking for attachments files at " . $directory . PHP_EOL, FILE_APPEND);
                            $images     = glob("{$directory}*.{jpg,jpeg,gif,ico,png,JPG,JPEG,GIF,ICO,PNG}", GLOB_BRACE);
                            $pdf        = glob("{$directory}*.{pdf,xml,html,PSP}", GLOB_BRACE);
                            $filo       = glob("{$directory}*.{docx,DOCX,txt,log,zip,php,js}", GLOB_BRACE);
                            $filother   = glob("{$directory}*", GLOB_BRACE);
                     file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/dev/action_station/debug.txt", "content of pdf variable is : " . print_r($pdf, true) . PHP_EOL, FILE_APPEND);
                     if (!is_dir($directory)) {
                    echo "no directory";
                      
                      }
						if ($images || $pdf || $filo) {
                            echo "<br> <b>Attachments :</b> <br>";
                           }

                        if (count($images)) {
                          foreach ($images as $image) {
                            $image  = basename($image);
                            echo '<input type="hidden" class="hide" id="img_id" value="' . $image . '">';
                            $image_file_id = substr_replace($image, "", 10);
                            $url           = base_url() . $filees;
                            $path_image    = $url . '/' . $image;?>
                            <input type="hidden" class="attach" value="<?php echo $path_image ?>'"  />
                            
<?php file_put_contents($_SERVER['DOCUMENT_ROOT'] . "email_files/" . $dbname . "/" . $username . "/attachments/" . $a . "/emailsViewDebug.txt", "textfileID imagefileID equalOrNot : " . $text_file_id . " " . $image_file_id . " " . ($text_file_id == $image_file_id) . PHP_EOL, FILE_APPEND);
      file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/dev/action_station/debug.txt", "textfileID imagefileID equalOrNot : " . $text_file_id . " " . $image_file_id . " " . ($text_file_id == $image_file_id) . PHP_EOL, FILE_APPEND);

                echo "<img  src='" . $path_image . "' style=\"width: 500px; height:auto; \"/>";
                                    echo "<br>";
                                }
                            }
                      if ($filo) {
						 foreach ($filo as $filos) {
                            $filoss   = basename($filos);
                            $url      = base_url() . $filees;
                            $path_doc = $url . '/' . $filoss;?>
                           <input type="hidden" class="attach_2" value="<?php echo $path_doc ?>'"  />
                           <a target="_blank" href="<?php echo $path_doc ?>"><?php echo $filoss; ?></a>
                            <br>
<?php }
          }
                if ($pdf) {
                     foreach ($pdf as $pdfs) {
                            $pdfs     = basename($pdfs);
                            $url      = base_url() . $filees;
                            $path_pdf = $url . '/' . $pdfs;?>
                            <input type="hidden" class="attach_1" value="<?php echo $path_pdf ?>'"  />
                            <!-- <embed src="" width="800px" height="2100px" /> -->
                            <a target="_blank" target="_blank" href="<?php echo $path_pdf ?>"><?php echo $pdfs; ?></a>
                            <br>
<?php }
          }
		     } ?>
                  


       </div><!-- end of div main_msg -->       
      </div>    
   <!-- end of div mess -->
        </div><!-- end of email view -->
    </div><!-- end of email view(another) -->
        
<?php }
  } ?>
  
<?php   $count++;   }
    }
    ?>
    <style>
.col-md-1.bbb {
    top: 0.5em;
    left: 0.8em;
}
</style> 
    <div class="email_viewchange view11">
  <?php

  $filecountstrash = count($textonlytrashed);
  // echo "filecountstrash $filecountstrash\n";
  $filecounttrash  = ceil($filecountstrash / 10);
  $indtrash = $this->input->get('trashpage');



  if ($indtrash == ''){
    $indtrash = 1;
  }
  $ind1  = $indtrash* 10 - 10;
  $start = 0;
  $end   = 10;

if ($indtrash == 1) {
  $start = 0;
  $end   = 10;
  }
if ($indtrash > 1) {
  $start = $ind1;
  $end   = 10 * $indtrash;
  if ($end >  $filecountstrash - 1) {
    $end =  $filecountstrash - 1;
      }
  }


  for ($i = $start; $i <= $end; $i++) {
  // foreach($textonlytrashed as $top){ ?>

  <?php 
        $ax[] = $i;
        echo '<br>';	   
        $files    = $textonlytrashed[$i];
        $file_name = basename($files);
        $file_name;
        if (!empty($file_name)) {?>
		 <?php $sample    = file_get_contents($files);?>
        <div class='col-md-1 bbb'>
        <input name="checkboxes" type="checkbox" id="delcheck1" class="delcheck1" value="<?php echo $file_name ?>">
			 <span class="hidden">
   <?php
                              $custm_id = ''; //get_Contents($sample, '<userid>', '<enduserid>');
								 $a = get_Contents($sample, '<id>', '<endid>');
                              $emailUID = get_Contents($sample, '<uid>', '<enduid>');
                              echo "<br>(for debugging purposes) SID : $a"; 
                              echo " UID : $emailUID";
                              echo " EmailID : $username"; ?></span>
   
			
        </div>
       <div class="col-md-11 email_view1 xxx"  id="abd" data-id="<?php echo $file_name ?>">
   <style>
   .chk_boxes {  margin-left: 3% !important; }
   </style>
   
        <div class="em_head">
        
        
    
      <div  class   ='col-md-3 demo'>
     <?php $address   = get_Contents($sample, '<eid>', '<endeid>');
           $addresses = $address;
           echo '<b>' . iconv_mime_decode($addresses) . '</b>';?>
         <input type="hidden" class="fname" value="<?php echo $addresses ?>">
           </div> <!-- demo ends here -->
                   
       <div class='col-md-3 subject'>
   <?php $sbject = get_Contents($sample, '<subject>', '<endsubject>');
        echo iconv_mime_decode($sbject);?>
      <input type="hidden" class="sbs" value="<?php echo $sbject ?>">
      </div>
        
     <div class='col-md-3 date'>
   <?php $datee = get_Contents($sample, '<Date>', '<endDate>');
         $dt    = new DateTime('@'.substr($datee,1));
         $dt->setTimeZone(new DateTimeZone('Australia/Perth'));
         echo $dt->format('D, j F, Y, g:i a');  ?>
      <input type="hidden" class="e_date" value="<?php echo $datee ?>"/>
      </div>
       </div>
      
      <div class="col-md-1 end_btn"> <span id="close" class="close " style="display:none">X</span></div>
      <div class ='col-md-12 mess' id='message' style="height:100%;width:100%;overflow:scroll;overflow-y:hidden;overflow-x:scroll;">
      <div class="main_msg <?php echo "mail".$i; ?>">
      
               <?php $eid = get_Contents($sample, '<eid>', '<endeid>');
                    echo '<input type="hidden" class="hidden" value="' . $eid . '">';
                   $message  = get_Contents($sample, '<message>', '<endmessage>');
                   
                   $file_ids = get_Contents($sample, '<id>', '<endid>');
                   $file_id  = $file_ids;
                   $a        = $file_id;
                   $a        = trim($a);
                   if( function_exists('refine_message')){ // For mails which are trashed
							echo refine_message($message); 
							
						}
					  else{
						  echo 'no mails';
					  }
   
                        $file_ids = get_Contents($sample, '<id>', '<endid>');
                        $file_id  = $file_ids;
                        $a        = $file_id;
                        $a        = trim($a);
   
                      if ($ustypees == "businessaccountmanager") {
                        $filees = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com/attachments/" . $a;
                          }elseif ($ustypees == "businessaccount") {
                            $filees = "email_files/" . $dbname . "/" . $username . "/attachments/" . $a;
                              } else {
                                if ($dbname != "") {
                                   $filees = "email_files/" . $dbname . "/" . $username . "/" . $uid . "/attachments" . $a;
                                 }else {
                                   $filees = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com/attachments" . $a;
                                }
                            }
                
                        echo '<input type="hidden" class="hide" id="img_id_path" value="' . $filees . '">';
              
                           if ($filees) {
                               $directory  = $_SERVER['DOCUMENT_ROOT'] . '/dev/action_station/' . $filees . '/';
                        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/dev/action_station/debug.txt", "looking for attachments files at " . $directory . PHP_EOL, FILE_APPEND);
                               $images     = glob("{$directory}*.{jpg,jpeg,gif,ico,png,JPG,JPEG,GIF,ICO,PNG}", GLOB_BRACE);
                               $pdf        = glob("{$directory}*.{pdf,xml,html,PSP}", GLOB_BRACE);
                               $filo       = glob("{$directory}*.{docx,DOCX,txt,log,zip,php,js}", GLOB_BRACE);
                        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/dev/action_station/debug.txt", "content of pdf variable is : " . print_r($pdf, true) . PHP_EOL, FILE_APPEND);
                            
               if ($images || $pdf || $filo) {
                               echo "<br> <b>Attachments :</b> <br>";
                              }
   
                           if (count($images)) {
                             foreach ($images as $image) {
                               $image  = basename($image);
                               echo '<input type="hidden" class="hide" id="img_id" value="' . $image . '">';
                               $image_file_id = substr_replace($image, "", 10);
                               $url           = base_url() . $filees;
                               $path_image    = $url . '/' . $image;?>
                               <input type="hidden" class="attach" value="<?php echo $path_image ?>'"  />
                               
   <?php file_put_contents($_SERVER['DOCUMENT_ROOT'] . "email_files/" . $dbname . "/" . $username . "/attachments/" . $a . "/emailsViewDebug.txt", "textfileID imagefileID equalOrNot : " . $text_file_id . " " . $image_file_id . " " . ($text_file_id == $image_file_id) . PHP_EOL, FILE_APPEND);
         file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/dev/action_station/debug.txt", "textfileID imagefileID equalOrNot : " . $text_file_id . " " . $image_file_id . " " . ($text_file_id == $image_file_id) . PHP_EOL, FILE_APPEND);
   
                   echo "<img  src='" . $path_image . "' style=\"width: 500px; height:auto; \"/>";
                                       echo "<br>";
                                   }
                               }
                         if ($filo) {
                foreach ($filo as $filos) {
                               $filoss   = basename($filos);
                               $url      = base_url() . $filees;
                               $path_doc = $url . '/' . $filoss;?>
                              <input type="hidden" class="attach_2" value="<?php echo $path_doc ?>'"  />
                              <a target="_self" href="<?php echo $path_doc ?>"><?php echo $filoss; ?></a>
                               <br>
   <?php }
             }
  if ($pdf) {
         foreach ($pdf as $pdfs) {
                 $pdfs     = basename($pdfs);
                 $url      = base_url() . $filees;
                 $path_pdf = $url . '/' . $pdfs;?>
                 <input type="hidden" class="attach_1" value="<?php echo $path_pdf ?>'"  />
                 <!-- <embed src="" width="800px" height="2100px" /> -->
                 <a target="_self" target="_blank" href="<?php echo $path_pdf ?>"><?php echo $pdfs; ?></a>
                 <br>
   <?php }
             }
            } ?>
                          
   
          </div><!-- end of div main_msg -->       
       </div>
      <!-- end of div mess -->
           </div><!-- end of col-md-12 email view1 -->
   
               <?php }
                            ?>
                          
                         <?php } #}?>
                          </div><!-- end of email view1 -->
                          <?php 
 if ($text !== false) {
	    $filecounts = count($text);
        $filecount  = ceil($filecounts / 10);
    }  ?>
          </div><!-- end of div zxcv -->

          <div class="col-md-4">
          <div class="col-md-12 view">
          </div>
<div class="col-md-12">
<div class="autocreate hidden" id="incoming_email_modal">
		
        <div class="modal-header">
         <h3>Standard Web Form Detected</h3>
            <h4 class="modal-title" id="entry_create">
            Do you want to create an entry for this?
            </h4>
        </div>
        <div class="modal-body">
                <div class="btn btn-success" id="create_entry" data-value="entry">Yes </div>
                <div class="btn btn-danger not_created" data-dismiss="modal" aria-label="close">No </div>	
        </div>
</div> 
          </div>
      
          </div>        

<!-- start of pagination fxn-->
<?php function em_pagination($startPage,$endPage,$ind,$filecount,$filecounts){
           if($ind == ceil($filecounts/10)){  ?>
<style>
.create1sd{ float:left; }
</style>
<?php 
       }
           $startPage = $ind - 4;
           $endPage   = $ind + 4;

        if ($startPage <= 0) {
           $endPage  -= ($startPage - 1);
           $startPage = 1;
           }
        if ($endPage > $filecount) {
           $endPage   = $filecount;
           }
        echo "<a target='_self' class='btn btn-default create1sd' href='".base_url('crmmails/viewmails')."?page=1' class='button'>Newest</a>";
        echo " ";

       if ($ind > 1) {
           echo "<a target='_self' class='btn btn-default create' href='viewmails?page=" . ($ind - 1) . "' class='button'>Previous</a>";
           echo " ";
          }
       for ($i = 1; $i <= ceil($filecounts/10); $i++) {
            $class;
            if ($_GET['page'] == $i) {
               $class = 'active';
              }
                else{
                $class = '';
                 }
			
        if($i == $ind && (($i+2) <= ceil($filecounts/10))){	
        echo "<a target='_self' class='btn btn-default create " . $class . "' href='".base_url('crmmails/viewmails')."?page=" . $i . "'>$i</a>";
        echo " ";
        }
    
        elseif($i == (ceil($filecounts/10))){
          
          if( $ind  != (ceil($filecounts/10)-2)){
            echo"...";
          }
          
          // if($ind != (ceil($filecounts/10)-1) || $ind == ceil($filecounts/10)){
            $m = ceil($filecounts/10)-1;
            $n = $i-1;
            if ($_GET['page'] == $n) {
              $class11 = 'active';
             }
               else{
               $class11 = '';
                }
                if($ind > 2){
                  echo "<a target='_self' class='btn btn-default createt old $class11' href='".base_url('crmmails/viewmails')."?page=" . $m . "' class='button'>$n</a>";

                }
      
          echo "<a target='_self' class='btn btn-default createt old $class' href='".base_url('crmmails/viewmails')."?page=" . ceil($filecounts/10) . "' class='button'>$i</a>";
          echo " ";
        }
        
        }
        if ($ind < ceil($filecounts/10)) {
          echo "<a target='_self' class='btn btn-default create' href='".base_url('crmmails/viewmails')."?page=" . ($ind + 1) . "' class='button'>Next</a>";
          echo " ";
        }
          echo "<a target='_self' class='btn btn-default createt old' href='".base_url('crmmails/viewmails')."?page=" . ceil($filecounts/10) . "' class='button'>Oldest</a>";
          echo " ";
        } 
#pagination funtion end


# pagination function trash  
    
    function em_pagination_trash($startPage,$endPage,$ind,$filecount,$filecounts){
      if($ind == ceil($filecounts/10)){  ?>
<style>
.create1sd{ float:left; }
</style>
<?php 
  }
      $startPage = $ind - 4;
      $endPage   = $ind + 4;

   if ($startPage <= 0) {
      $endPage  -= ($startPage - 1);
      $startPage = 1;
      }
   if ($endPage > $filecount) {
      $endPage   = $filecount;
      }
   echo "<a target='_self' class='btn btn-default create1sd' href='".base_url('crmmails/viewmails')."?page=".$_GET['page']."&trashpage=1"."' class='button'>Newest</a>";
   echo " ";

  if ($ind > 1) {
      echo "<a target='_self' class='btn btn-default create' href='viewmails?page=" . $_GET['page'] . "&trashpage=". ($ind - 1) ."' class='button'>Previous</a>";
      echo " ";
     }
  for ($i = 1; $i <= ceil($filecounts/10); $i++) {
       $class;

       if ($_GET['trashpage'] == $i) {
          $class = 'active';
         } else {
           $class = '';
            }
 
    if($i == $ind && (($i+2) <= ceil($filecounts/10))){	
    echo "<a target='_self' class='btn btn-default create active' href='".base_url('crmmails/viewmails')."?page=" . $_GET['page'] . "&trashpage=".$i."'>$i</a>";
    echo " ";
    }
    
    elseif($i == (ceil($filecounts/10))){

      if( $ind  != (ceil($filecounts/10)-2) ){
        echo"...";
      }

      $m = ceil($filecounts/10)-1;
      $n = $i-1;

      if ($_GET['trashpage'] == $n) {
        $class11 = 'active';
        }
          else{
          $class11 = '';
          }
      if($ind > 2){
        echo "<a target='_self' class='btn btn-default create " . $class11 . "' href='".base_url('crmmails/viewmails')."?page=" . $_GET['page'] . "&trashpage=".$m."'>$n</a>";

      }

    echo "<a target='_self' class='btn btn-default createt old $class' href='".base_url('crmmails/viewmails')."?page=" . $_GET['page'] ."&trashpage=" . ceil($filecounts/10) . "' class='button'>$i</a>";
    echo " ";
    
  
  }

    }
    if ($ind < ceil($filecounts/10)) {
    echo "<a target='_self' class='btn btn-default create' href='".base_url('crmmails/viewmails')."?page=" . $_GET['page'] . "&trashpage=".($ind + 1)."' class='button'>Next</a>";
    echo " ";
    }
    echo "<a target='_self' class='btn btn-default createt old' href='".base_url('crmmails/viewmails')."?page=".  $_GET['page']  ."&trashpage=" . ceil($filecounts/10) . "' class='button'>Oldest</a>";
    echo " ";
    }
    
    ?>
<?php } ?>
<!-- end of pagination fxn -->

<!-- call to pagination fxn -->
<div class="col-md-12 simple-pagination" style="margin-top:6%; text-align:left">          
<span class="page1">
<?php
if (function_exists('em_pagination')){ 
    echo $a = em_pagination($startPage,$endPage,$ind,$filecount,$filecounts);
}
  ?>
</span>

<!-- <span class="page2"> -->
<?php
/* $filecounts_t = count($ax);
$filecount_t  = ceil($filecounts_t/10);
if (function_exists('em_pagination')){ 
 echo $a = em_pagination($startPage,$endPage,$ind,$filecount_t,$filecounts_t); 
} */?>
<!-- </span> -->
</div>
<!-- end of pagination -->

<!-- call to pagination trash fxn -->
<?php 
  // $startpage=1;
  $indtrash = $this->input->get('trashpage');
  if ($indtrash == ''){
    $indtrash = 1;
  }
 
   
?>
<div class="col-md-12 trash-pagination" style="margin-top:6%; text-align:left">          
<span class="page1">
<?php
if (function_exists('em_pagination_trash')){ 
    echo $a = em_pagination_trash($startPage,$endPage,$indtrash,$filecounttrash,$filecountstrash);
}
  ?>
</span>

<!-- <span class="page2"> -->
<?php
/* $filecounts_t = count($ax);
$filecount_t  = ceil($filecounts_t/10);
if (function_exists('em_pagination_trash')){ 
 echo $a = em_pagination_trash($startPage,$endPage,$indtrash,$filecount_t,$filecounts_t);  
} */?>
<!-- </span> -->
</div>
<!-- end of trash pagination -->

<?php imap_expunge($inbox);
     imap_close($inbox); }
?>

<style>
.active{
  background-color:#00C;
  color: white;
  font-weight:bold;
}
body{
  width:100% !important;
}
#create_entry{
  width: 16%;
  margin-left: 2px;
}
.not_created{
  width: 16%;
  margin-left: 15px;
}
.autocreate{
    position:fixed;
}

</style>
<!---  For Automatic Entry Creation   -->

<div class="row automatic_ent_create hidden" style="margin-top:10px">
    <div class="col-md-12 col-sm-12">
        <?php $attributes = array('class' => 'form-add-entry','id'=>'form'); 
        echo form_open('entry/add', $attributes); ?>
        <div class="form-group">
            <fieldset>
             <label for="entryType">Entry Type </label>
             <select class="form-control" id="entry_type_dd" name="entryType" required>
               <option value="">Select</option>
            <?php  
                if(is_array($entry_type)):
                    foreach($entry_type as $type):?>
                        <option value="<?php echo $type['entry_type_name'] ?>" <?php if($type['entry_type_name'] == 'Lead'){?> selected="selected" <?php } ?> ><?php echo $type['entry_type_name']?> </option>
                    <?php endforeach;
                   endif;?>
                </select>
            </fieldset>
            <?php
            $users  =   get_users();
            if(is_array($users)):
              $user_s = array();
              $i      = 0;
            foreach ($users as $user):
                $info = get_user_details($user->user_id);
                $type = get_user_type($user->user_type);
              
              if(isset($type)):
                  $user_s[$i]['type']     = $type->type_name;
                  $user_s[$i]['priority'] = $type->priority;
               endif;
                  $user_s[$i]['id'] = $user->user_id;
                
               if (!empty($info)) {
				  if(property_exists($info, "first_name"))
			   			$name = $info->first_name;
					 else{
					   $name  = "Not available";
					 }
				  if( property_exists($info, "last_name"))
					     $name1 = '<u>'.$info->last_name.'</u>';
					   else{  
					     $name1 = "";
						 $name  = "Not available";
					   }	  
					  }else{
					   $name  = "Not available";
					   $name1 = "";
					   $email = "Not available";
				}
                    $user_s[$i]['name'] =  $name.$name1;
                    $i++;
              endforeach;
            endif;
            // short users according to their priprity level
            usort($user_s, function($a, $b) {
              return $a['priority'] - $b['priority'];
            });
           ?>
           <input type="hidden" name="action_comm" value="email_communication">

           <fieldset class="new_fields">
              <label for="sAssign">Assign Staff </label>
              <!-- autocomplete [assign customer] -->
              <div class="form-control">
                  <ul class="tag-labels"></ul>
                  <input type="text" name="assignStaff" id="assignStaff" class="form-control tag-input" data-html="true"/>
              </div>
                  <input type="hidden" name="sAssign[]" id="staffIds" value="<?php echo $session_data['id'].','; ?>" required>
              <!-- end [assign customer] -->
              <?php
                if(is_array($user_s)): ?>
                  <script type="text/javascript">
                    var js_staff = <?php echo json_encode($user_s); ?>;
                  </script>
              <?php  endif; ?>
          </fieldset>

			<style>
			.crtss{ margin-left: 75% !important; }
			</style>

          <fieldset class="new_fields">
              <label id="test" for="assignCustomer" title="got me">Assign Customer </label> 
              
              <!-- autocomplete [assign customer] -->
              <div class="form-control assign_cus">
                  <img src="<?php echo base_url('images/'); ?>loading_spinner.gif" class="type_search_loader" style="">
                  <ul class="tag-labels"></ul>
                  <?php if(isset($_REQUEST['uid'])){
				  $id      = $_REQUEST['uid'];
				  $usrinfo = get_user_details($id); ?>
                
                 <input type="text" name="assignCustomer" id="assignCustomer" class="form-control tag-input" data-html="true" style="background:#FFF !important" value="<?php if(property_exists($usrinfo, "first_name")){ echo $usrinfo->first_name; }else{ echo "No name";} if(property_exists($usrinfo, "last_name")){ echo $usrinfo->last_name;}?>"/ disabled="disabled"><?php }else{?>
                 <input type="text" name="assignCustomer" id="assignCustomer" class="form-control tag-input" data-html="true"/>
                  <?php }?>
              </div>
              <?php if(isset($_REQUEST['uid'])){
				 $id = $_REQUEST['uid'];?>
             <input type="hidden" name="cAssign[]" id="customerIds" value="<?php echo $_REQUEST['uid'] ?>" required />
              <!-- end [assign customer] -->
              <?php } else {?>
               <input type="hidden" name="cAssign[]" id="customerIds" value="" required>
              <!-- end [assign customer] -->
              <?php } ?>     
          </fieldset>
  
          <fieldset class="new_fields">
              <label for="eHeading">Assign Filter Heading</label>
             
              <!-- autocomplete [assign customer] -->
              <div class="form-control">
                <img src="<?php echo base_url('images/'); ?>loading_spinner.gif" class="type_search_loader" style="">
                <ul class="tag-labels"></ul>
                <input type="text" name="assignHeading" id="assignHeading" value="" class="form-control tag-input" data-html="true"/>
              </div>
                <input type="hidden" name="eHeading[]" id="headings" value="3" required>
                <input type="hidden" name="addSchedule" id="headings" value="nextSchedule" required>
              <!-- end [assign customer] -->
		
              <script type="text/javascript">
                  <?php if(is_array($headings)): ?>
                  var js_headings = <?php echo json_encode($headings); ?>;
                  <?php endif; ?>
              </script>
          </fieldset>

          <fieldset>
              <label for="eNote">Add New Entry Notes</label>
              <textarea class="form-control"  placeholder="Add New Entry Notes" name="eNote"></textarea>
              <?php if(isset($_REQUEST['action'])){ ?>
			         <input type="hidden" name="action" id="action_taken" value="<?php echo $_REQUEST['action']  ?>" required> 
			<?php  }?>
          
          </fieldset>
          <input type="hidden" name="action_taken_incom_email" id="action_taken_incom_email" value="">
          <input type="text"   name="entrySource" value="<?php echo  "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']; ?>" hidden=""/>
   </div>
   <!--- Email hidden fields -->
   <input type="hidden" name="add_entry" value="entry"  />
   <input type="hidden" name="c_user" value="c_user">
   <input type="hidden" id="subject" name="subject" value="">
   <input type="hidden" id="file_name" name="file_name" value="">
   <input type="hidden" id="edate" name="edate" value="">
   <input type="hidden" id="semail" name="semail" value=""  />
    <!--- end -->
  <div id="p_prop1" class='p_prop' style="display:none">
          
          <fieldset class="hidden">
              <label for="eSource">Product source</label>
              <input type="text" data-pid="" class="form-control eSource" id="eSource" name="eSource[]">
          </fieldset>

          <fieldset>
              <label for="eProduct">Product Name</label>
              <button type="button" class="remove" >x</button>
              <input type="text" data-pid="" class="form-control eProduct" id="eProduct" name="eProduct[]" value="">
          </fieldset>
           <!--- product spacification -->
          <fieldset>
              <label for="eUrl">Product Specifications</label>
              <input type="text" class="form-control eProduct_specification" id="eProduct_specification" name="eProduct_specification[]" />
          </fieldset>
          <fieldset>
              <label for="eUrl">Product SKU</label>
              <input type="text" class="form-control eProduct_sku" id="eProduct_sku" name="eProduct_sku[]" />
          </fieldset>
            <fieldset>
              <label for="eUrl">Product Quantity</label>
              <input type="text" class="form-control eProduct_qunt" id="eProduct_qunt" name="eProduct_qunt[]" value="<?php echo trim(strip_tags($quantity)).","; ?>" />
          </fieldset>
           
         <!-- end -->
         <!--<fieldset>
            <label> Url</label>
            <input type="text" class="form-control input-lg eurlss" id="eurlss"  name="eurlss" value=""/>
            </fieldset>
          <fieldset>-->
              <label for="eUrl">Product Url</label>
              <input type="text" class="form-control eUrl" id="eUrl" name="eUrl[]" value="" />
          </fieldset>        
             
     <?php  if(get_systems_currency() != ""){
              $user_def_currency = get_systems_currency();
     }elseif(get_userinfo_currency() != ""){
              $user_def_currency = get_userinfo_currency();
     }else{
              $user_def_currency = $user_def_currency;
      }
    ?>  
          <fieldset>
            <div class="">
             <label for="eRetail">Retail </label> 
              </div>
              <div class="col-xs-1">
                  <div class="col-xs-2"></div>
                  <div class="col-xs-10 dropdown">
                      <span aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#">
                      <span class="cs_select" id="retaile" data-currency="retaile"><?php if(isset($session_currency)){echo $session_currency;}else if(isset($user_def_currency)){echo $user_def_currency;}else{echo $retaileCurrency;};  ?></span><span class="caret"></span>
                      </span>
                  </div>
              </div>
              <div class="col-xs-11">
                  <input type="number" min="0"  step="0.01" class="form-control eRetail" id="eRetail" name="eRetail[]" value="<?php echo trim(strip_tags($price_pro)); ?>"/>
              </div>
          </fieldset>

          <fieldset>
              <div class="">
                  <label for="eWholeSale">Wholesale</label>
              </div>
              <div class="col-xs-1">
                  <div class="col-xs-2"></div>
                  <div class="col-xs-10 dropdown">
                      <span aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#">
                      <span class="cs_select" id="whole" data-currency="whole"><?php if(isset($session_currency)){echo $session_currency;}else if(isset($user_def_currency)){echo $user_def_currency;}else{echo $wholeCurrency;}; ?></span><span class="caret"></span>
                      </span>
                  </div>
              </div>
              <div class="col-xs-11">
                  <input type="number" min="0"  step="0.01" class="form-control eWholeSale" id="eWholeSale" name="eWholeSale[]" value="<?php echo trim(strip_tags($price_pro)); ?>"/>
              </div>
          </fieldset>

          <fieldset>
              <div class="">
                <label for="eCost">Cost</label>
              </div>
              <div class="col-xs-1">
                  <div class="col-xs-2"></div>
                  <div class="col-xs-10 dropdown">
                      <span aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#">
                      <span class="cs_select" id="cost" data-currency="cost"><?php if(isset($session_currency)){echo $session_currency;}else if(isset($user_def_currency)){echo $user_def_currency;}else{echo $costCurrency;}; ?></span><span class="caret"></span>
                      </span>
                  </div>
              </div>
              <div class="col-xs-11">
                  <input type="number" min="0"  step="0.01" class="form-control eCost" id="eCost" name="eCost[]"  value="<?php echo trim(strip_tags($price_pro)); ?>"/>
              </div>
          </fieldset>
           <fieldset>
              <label for="eUrl">Product Discont</label>
              <input type="text" class="form-control eProduct_discont" id="eProduct_discont" name="eProduct_discont[]" readonly="readonly" />
          </fieldset>
           <div class="supp">
            <fieldset>
          <label for="Supplier">Assign Supplier</label>
           <div class="form-control">
                  <img src="<?php echo base_url('images/'); ?>loading_spinner.gif" class="type_search_loader" style="">
           <ul class="tag-labels"></ul>
          <input type="text" name="assignSupplier" id="assignSupplieree" class="form-control tag-input input-lg supname" data-html="true" readonly="readonly"/>
          </div>
             <input type="hidden" name="supAssignid[]" id="supplierIdss" class="supid" value="" required readonly="readonly"> </fieldset>
             </div>
              <fieldset>
              <label for="ePnotes">Product Notes</label>
              <input type="text" class="form-control ePnotes" id="ePnotes" name="ePnotes[]"  readonly="readonly"/>
          </fieldset>
        
          <a id="addmoree" data-target="#productListModel" data-toggle="modal" href="">Add More</a>
      </div>
     
      <input type="text" name="entrySource" value="<?php echo  "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']; ?>" hidden=""/>
      
      <!-- </fieldset> -->
      
      <!--currency -->
      <input type="text" name="retaileCurrency" value="<?php if(isset($session_currency)){echo $session_currency;}else{echo $user_def_currency;} ?>" id="retaileCurrency" style="display:none"/>
      <input type="text" name="wholeCurrency" value="<?php if(isset($session_currency)){echo $session_currency;}else{echo $user_def_currency;} ?>" id="wholeCurrency" style="display:none"/>
      <input type="text" name="costCurrency" value="<?php if(isset($session_currency)){echo $session_currency;}else{echo $user_def_currency;} ?>" id="costCurrency" style="display:none"/>
  </div>
</div>


</form>
</div>
</div>
<!---   Automatic Entry Creation End   -->

<?php
	
			function refine_message($message)
			{
			
      
                  
                     $message  = chop($message, "rel='icon'");
                     echo '<input type="hidden" class="messages" value="' . htmlentities($message) . '" />';
                     file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/dev/action_station/debug.txt", "input.messages contains :" . htmlentities($message) ."input.messages ends"  . PHP_EOL, FILE_APPEND);

                     // refine embedded css <style> to avoid bleeding or conficting with items outside email content
                    /*
                    (for debugging purposes) SID : dXUXB1SO4L UID : 39049 EmailID : scalesplus.cameron@gmail.com (font market sans)
                    (for debugging purposes) SID : v0JMYZwz2n UID : 39116 EmailID : scalesplus.cameron@gmail.com blue rectangles in every mail and buttons on the right
                    (for debugging purposes) SID : HvocOe0voN UID : 39433 EmailID : scalesplus.cameron@gmail.com
					Leaking CSS on create user dialog
                    testcase bleeding leaking css */
                    $a = '<style';
                    $uniqueClass = "mail".$i;
                    // echo "<pre> 2 $message</pre>";
                    $html = $message;
                      if (strpos($message, $a) !== false) {
                          $html      = $message;
                          $needle    = $a;
                          $lastPos   = 0;
                          $positions = array();

                     while (($lastPos = strpos($html, $needle, $lastPos))!== false) {
                              $positions[] = $lastPos;
                              $anglePos    = strpos( $html, ">",$lastPos)+1;
                              $html        = substr_replace($html,"<style type=\"text/less\">",$lastPos,$anglePos-$lastPos);
                              $anglePos    = strpos( $html, ">",$lastPos)+1;
                              $html        = substr_replace($html,".mail".$i."{",$anglePos,0);
                              $endvalue    = strpos($html,"</style",$lastPos);
                              $html        = substr_replace($html,"}",$endvalue,0);
                              $lastPos     = $lastPos + strlen($needle);
                          }
                      }
                      // echo "<pre> 3 $message</pre>";
                     //end of refining
								  
								  //refine external css
								  //https://keithclark.co.uk/articles/loading-css-without-blocking-render/
								  
//								  $html = str_replace('rel="stylesheet"','rel="stylesheet" media="none" onload="if(media!=\'all\')media=\'all\'"',$html);
								  
								  //end of refine external css
                     
                    
                     //now refine the body tag ( don't let any attribute be with body tag of email body)
                     
                     $needle    = '<body';
                     $lastPos   = 0;
                    

                     $lastPos     = strpos($html, $needle, $lastPos);
                    //  echo "lastPos $lastPos";
                     if($lastPos){
                      $anglePos    = strpos( $html, ">",$lastPos)+1;
                      $html        = substr_replace($html,"<body>",$lastPos,$anglePos-$lastPos);
                     }
                     
                     // end of body tag refining


                     $message   = $html;
                    //  echo "<pre> 1 $message</pre>";
                     $searchfor = '<a href="" style="color: #557da1; font-weight: normal; text-decoration: underline;">';
                     $pattern   = preg_quote($searchfor, '/');
                     $pattern   = "/^.*$pattern.*\$/m";
                     if(preg_match_all($pattern, $message, $matches)){
                     $Product = implode("\n", $matches[0]);
                     preg_match('~>\K[^<>]*(?=<)~', $Product, $match);
                      $Product_name = $match[0];
                     }
                    
                     $searchfor = ' <td scope="col" style="padding: 12px; text-align: left;">';
                     $pattern   = preg_quote($searchfor, '/');
                     $pattern   = "/^.*$pattern.*\$/m";
                     if(preg_match_all($pattern, $message, $matches)){
                       $quantity_price = implode("\n", $matches[0]);
                       $money          = explode('$', $quantity_price);
                       $quantity       = $money[0];
                       $price_pro      = $money[1];
                     }
                    //  echo "<pre>$message</pre>";
                
                     return $message; //for mails which are not trashed	
	
			}
	
	
	
	?>

</body>
</html>

<script type = "text/javascript" language = "javascript">


  jQuery('.close').click(function() {
   jQuery(this).parents(".email_view, .email_view1").find('.mess').slideUp();
   jQuery('.close').hide();
 });

jQuery(".email_view1").click(function () {
     cl  =  jQuery(this).find('#close');
	   cl.css("display","block");
	   cl.css("color","red");
	   cl.css("margin-top","-40px");
	   jQuery('.mess').not(jQuery(this).find('.mess')).hide(0);
	   st = jQuery(this).find('.mess').show(); 
}); 
 
var file_name;
var div;
var email;
var mailcontent;
var attachment;
var attach_pdf;
var attach_doc;
var sbj;
var scontent;
var e_date;
var edate;
var sender;
var senderemail;
var fname;

jQuery(".email_view").click(function () {
     file_name =  jQuery(this).data('id');
			 cl            =  jQuery(this).find('#close');
			 cl.css("display","block");
			 cl.css("color","red");
			 cl.css("margin-top","-40px");
			 jQuery('.mess').not(jQuery(this).find('.mess')).hide(0);
			 st = jQuery(this).find('.mess').show();

			     div          = jQuery(this).find('.mess');
			     email        = jQuery(div).find('input.hidden[type="hidden"]').val();
			     mailcontent  = jQuery(div).find('input.messages[type="hidden"]').val();
			     attachment   = jQuery(div).find('input.attach[type="hidden"]').val();
			     attach_pdf   = jQuery(div).find('input.attach_1[type="hidden"]').val();
			     attach_doc   = jQuery(div).find('input.attach_2[type="hidden"]').val();
			     sbj          = jQuery(this).find('.subject');
			     scontent     = jQuery(sbj).find('input.sbs[type = "hidden"]').val();
			     e_date       = jQuery(this).find('.date');
			     edate        = jQuery(e_date).find('input.e_date[type = "hidden"]').val();
			     sender       = jQuery(this).find('.demo');
			     senderemail  = jQuery(sender).find('input.senderemail[type="hidden"]').val();
			     fname        = jQuery(sender).find('input.fname[type="hidden"]').val();
             var emails       = email;
             var senderemails = senderemail;

          var fsrt_name    = jQuery(this).find('.lead_fname').html();
          var lst_name     = jQuery(this).find('.lead_lname').html();
          var quote_email  = jQuery(this).find('.lead_email').html(); 
          var compny_email = jQuery(this).find('.lead_company').html(); 
          var phone_email  = jQuery(this).find('.lead_phone').html(); 
          var cntry_email  = jQuery(this).find('.lead_country').html(); 
          var state_email  = jQuery(this).find('.lead_state').html(); 
          var pro_namee    = jQuery(this).find('.pro_namee').html(); 
          var pro_url      = jQuery(this).find('.pro_namee').attr('href');
           
          var v            = jQuery(this).closest("div.email_view").find("input[name='subject']").val(); 
          var skipvalid    = "skipvalid"; 
          if(v == "[Request a quote]" || v == fsrt_name+" - mypracticalrobot.com"){
            jQuery('#incoming_email_modal').removeClass("hidden"); 
             jQuery.ajax({
                         
                         url: "<?php echo base_url(); ?>crmmails/auto_create",
                         data: {'fname': fsrt_name, 'lname': lst_name, 'quote_email': quote_email},
                         type: "POST",
                         success: function(resp) {
                            var a = resp.replace(/['"]/g,'');
                             if (a == "empty") {   
                                 jQuery.ajax({
                                     type: "post",
                                     url: "<?php echo base_url(); ?>accounts/create_user",
                                     data: {'fname_user_email': fsrt_name, 'lname_user_email': lst_name, 'skipvalid': skipvalid,'quote_email': quote_email, 'compny_email': compny_email, 'phone_email': phone_email, 'cntry_email': cntry_email, 'state_email': state_email},
                                     success: function (data) {
                                         jQuery('#customerIds').val(data.replace(/['"]/g,''));
                                       }
                                 });   
                               }else{
                                 jQuery('#customerIds').val(resp.replace(/['"]/g,''));
                               }
                          },
                         error: function(xhr, textStatus, errorThrown){
                          return false;
                         }
                         });
                         
              if(v == "[Request a quote]"){
                 jQuery("#action_taken_incom_email").val('action_taken_incom_email');
              }
              jQuery('.edata').hide();
              jQuery('#incoming_email_modal').removeClass("hidden");
            
              jQuery('#subject').val(scontent);
              jQuery('#file_name').val(file_name);
              jQuery('#edate').val(edate);
              jQuery('#semail').val(fname);
              jQuery('#eProduct').val(pro_namee);
              jQuery('#eUrl').val(pro_url);

           jQuery('.not_created').click(function(){
             jQuery('#incoming_email_modal').addClass("hidden");
          var emails       = email;
          var senderemails = senderemail;
             jQuery.ajax({
             type: "POST",
             url: "<?php echo base_url('crmmails/viewdetail/') ?>",
     data:{'useremail': emails, 'senderemail': senderemails, 'message': 'no need', 'subject': scontent, 'file_name': file_name, 'e_date': edate, 'f_name': fname, 'attachments': attachment, 'attachment_pdf': attach_pdf, 'attachment_doc': attach_doc, 'cust_ids': false },
             success: function(res){
                 jQuery(".view").append(res);
                 }
             });

             jQuery('.edata').show();
             
});    
      }else{ jQuery('#incoming_email_modal').addClass("hidden");

			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url('crmmails/viewdetail/') ?>",
        data:{'useremail': emails, 'senderemail': senderemails, 'message': 'no need', 'subject': scontent, 'file_name': file_name, 'e_date': edate, 'f_name': fname, 'attachments': attachment, 'attachment_pdf': attach_pdf, 'attachment_doc': attach_doc, 'cust_ids': false },
				success: function(res){
					jQuery(".view").html(res);

					}
				});
              }
            })


jQuery(document.body).on('click', '.dd', function(){
			 var favorite     = [];
			 var div          = jQuery(this).closest('.email_view');
			 var img_file     = jQuery('#img_id').val();
			 var img_id_paths = jQuery('#img_id_path').val();
			 var file         = div.data('id');
			 
       jQuery.each(jQuery("input[name='checkboxes']:checked"), function(){
             favorite.push(jQuery(this).val());
            });
			
             var ab = favorite.join(", ");
             console . log(div);
             console . log('id '+ab+' filename '+ab+' img_files '+img_file+' img_id_pathss '+img_id_paths);

	   jQuery.ajax({
			 type: "POST",
			 url: "<?php echo base_url('crmmails/do_not_traks/') ?>",
			 data:{'id': ab, 'filename': ab, 'img_files': img_file, 'img_id_pathss':img_id_paths},
			 success: function(res){
				location.reload();
				 }
				  });
			  	 })

jQuery(document.body).on('click', '.p_e_del', function(){
			 var favorite     = [];
			 var div          = jQuery(this).closest('.email_view1');
			 var img_file     = jQuery('#img_id').val();
			 var img_id_paths = jQuery('#img_id_path').val();
			 var file         = div.data('id');
			 
   jQuery.each(jQuery("input[name='checkboxes']:checked"), function(){
             favorite.push(jQuery(this).val());
            });
			
             var ab = favorite.join(", ");
             console . log(div);
             console . log('id '+ab+' filename '+ab+' img_files '+img_file+' img_id_pathss '+img_id_paths);

	   jQuery.ajax({
			 type: "POST",
			 url: "<?php echo base_url('crmmails/deletfile/') ?>",
			 data:{'id': ab, 'filename': ab, 'img_files': img_file, 'img_id_pathss':img_id_paths},
			 success: function(res){
                jQuery( ".view11" ).load(window.location.href + " .xxx" );

        setTimeout(function(){ 
       jQuery(".email_view1").click(function () {
       cl  =  jQuery(this).find('#close');
	   cl.css("display","block");
	   cl.css("color","red");
	   cl.css("margin-top","-40px");
	   jQuery('.mess').not(jQuery(this).find('.mess')).hide(0);
	   st = jQuery(this).find('.mess').show(); 
});
}, 3000);
				 }
				  });
			  	 })

jQuery(document.body).on('click', '.Restore', function(){
         
			 var favorite     = [];
			 var div          = jQuery(this).closest('.email_view1');
			 var img_file     = jQuery('#img_id').val();
			 var img_id_paths = jQuery('#img_id_path').val();
			 var file         = div.data('id');
			 
    jQuery.each(jQuery("input[name='checkboxes']:checked"), function(){
             favorite.push(jQuery(this).val());
            });
			
             var ab = favorite.join(", ");
             console . log(div);
             console . log('id '+ab+' filename '+ab+' img_files '+img_file+' img_id_pathss '+img_id_paths);
 
	   jQuery.ajax({
			 type: "POST",
			 url: "<?php echo base_url('crmmails/restore_trash_email/') ?>",
			 data:{'id': ab, 'filename': ab, 'img_files': img_file, 'img_id_pathss':img_id_paths},
			 success: function(resp){
				location.reload();
				 }
				  });
			  	 })
			function pagerelod()
		{
		    location.reload();
		}
		
//     jQuery(window).on("load", function () {
//       jQuery('.email_viewchange').hide();
// });		

//"hide on ready is done with css instead of js"
jQuery(document).ready(function() {
	  // jQuery('.email_view1').hide(); 
    // jQuery('.email_viewchange').hide();
    // jQuery('.delcheck1').hide(); 
    //jQuery('.back_incoming').hide();
    //jQuery('.p_e_del').hide(); 
    //jQuery('.Restore').hide(); 
	  // jQuery('.descrip').show();
	  //jQuery('.descrip2').hide();
	  // jQuery('.page2').hide();
    //jQuery(".trash-pagination").hide();
    if (jQuery('#zxcv').html().trim()) {
   } else {
		jQuery('#zxcv').html("<b><p style='text-align:center;'>No Incoming Communications.</p></b>");
		jQuery('.bar').css("display","block");
		jQuery('#day').hide();
}
});
  
jQuery('#day').click(function () {
      jQuery('.forteen').toggle();
	  jQuery('#zxcv').toggle();
    });

jQuery('.back_incoming').click(function () {
      jQuery(".simple-pagination").show();
      jQuery(".trash-pagination").hide();
});

jQuery('.Trashed').click(function () { 
	    jQuery('.email_view').hide();
      jQuery('.another').hide();
      jQuery('.delcheck').hide();
      jQuery('.dd').hide();
      jQuery('.descrip').hide();
      jQuery('.Trashed').hide();
      
      jQuery(".view").hide();
      jQuery(".simple-pagination").hide();
      jQuery(".trash-pagination").show();
      jQuery('.back_incoming').show();
      jQuery('.email_view1').show();
      jQuery('.email_viewchange').show();
      jQuery('.delcheck1').show();
      jQuery('.p_e_del').show(); 
      jQuery('.Restore').show(); 
	    jQuery('.descrip2').show();
      jQuery('.page2').show();
	  jQuery('.chk_boxes').hide();
	jQuery('.chk_boxes1').show();
	
      console.log('<?php echo $_SESSION['last_page_no']; ?>');

      history.replaceState({page: 3}, "title 3", "?page=<?php echo $_SESSION['last_page_no'] ?>&trashpage=<?php echo $indtrash ?>");
	    });
	   
 jQuery('.back_incoming').click(function () { 
      jQuery('.email_view1').hide();
      jQuery('.email_viewchange').hide();
      jQuery('.delcheck1').hide();
      jQuery('.back_incoming').hide(); 
      jQuery('.p_e_del').hide(); 
      jQuery('.Restore').hide();
      jQuery('.page2').hide(); 
      //jQuery( "#zxcv" ).load(window.location.href + " .email_view" );
      jQuery('.email_view').show();
      jQuery('.another').show();
      jQuery('.delcheck').show();
      jQuery('.dd').show();
      jQuery('.descrip').show();
      jQuery('.descrip2').hide();
      jQuery('.Trashed').show();
      // jQuery('.page1').show();
	 jQuery('.chk_boxes1').hide();
	 jQuery('.chk_boxes').show();
	 
      jQuery(".view").show();
      history.replaceState({page: 3}, "title 3", "?page=<?php echo $_SESSION['last_page_no'] ?>");
            
        });	   
 </script>
 <link rel="icon" href="favicon.png" type="image/png" />
 <link rel='shortcut icon' href='<?php echo base_url(); ?>images/favicon_r4p_icon.ico' type='image/x-icon' />
 <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js" ></script> 
    <!-- for inline css inside mails -->
     <script >
        /* if(jQuery.fn.jquery != '1.9.1'){
          var whatever = jQuery.noConflict();
          whatever = $.noConflict();

        } */
        // if(jQuery.fn.jquery != '1.9.1'){
          // alert("after first nc Version : "+jQuery.fn.jquery);
          
          var jQuery = $x.noConflict();
          var $ = $x.noConflict();
          // alert("jquery again Version: "+jQuery.fn.jquery);
        
     </script> 
      <!-- for inline css inside mails -->
<!-- <style>
body{
	background-color:#FFF !important;
	background-image:none !important;
	background-image:none !important;
      }
		
element.style {background-image:none !important;}
		
h3{
	color: #202020 !important;
	font-family: Helvetica !important;
	font-size: 24px !important;
	font-style: normal !important;
	font-weight:normal !important;
	line-height: 125%;
	letter-spacing: normal !important;
	text-align: left;
	margin-top:20px !important;
		}
		
.assig{
	color: #202020 !important;
	font-family: Helvetica !important;
	font-size: 24px !important;
	font-style: normal !important;
	font-weight:normal !important;
	margin-top:20px !important;
		}
		
.mess{font-size:14px !important;}
	
div{font-size:14px !important;}
		
.email_view {
    overflow: hidden !important;
    cursor: pointer !important;
    border-top: solid 1px #ccc !important;
    float: left !important;
    border-right: 1px solid #ccc !important;
}

.email_view1 {
    overflow: hidden !important;
    cursor: pointer !important;
}

-->


<!-- .container{max-width:100% !important;} -->

<script>



jQuery('#create_entry').click(function(){
    jQuery('#form').submit();
});
</script>