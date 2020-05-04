<?php
register_shutdown_function('shutdown');
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/action_station/debug.txt", "email crm 1" . PHP_EOL, FILE_APPEND);

require_once('Encoding.php');  //https://github.com/neitanod/forceutf8
use \ForceUTF8\Encoding;  // It's namespaced now.



set_time_limit(1000);
// ob_start();
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/action_station/debug.txt", "email crm 5" . PHP_EOL, FILE_APPEND);

include 'index.php';
// ob_end_clean();
error_reporting(E_ERROR | E_PARSE);
$CI = &get_instance();
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/action_station/debug.txt", "email crm 6" . PHP_EOL, FILE_APPEND);

$CI->load->library('session'); //if it's not autoloaded in your CI setup
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/action_station/debug.txt", "email crm 7" . PHP_EOL, FILE_APPEND);

// $CI->load->helper('email_helper'); // this line was crashing and is most probably not required.
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/action_station/debug.txt", "email crm 4" . PHP_EOL, FILE_APPEND);

$db       = $CI->session->userdata('database_name');
$db_user  = $CI->session->userdata('db_name');
$db_pswd  = $CI->session->userdata('db_pass');
$aa       = $CI->session->userdata('logged_in');
$utype    = $aa['usertype'];
$utypees  = get_user_type($utype);
$ustypees = $utypees->type_name;
$ustypees = preg_replace('/\s+/', '', $ustypees);
$ustypees = strtolower($ustypees);
$uid      = $aa['id'];
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/action_station/debug.txt", "email crm 3" . PHP_EOL, FILE_APPEND);

if (!empty($db)) {
    $servername = 'localhost';
    $username   = $db_user;
    $password   = $db_pswd;
    $database   = $db;
    $con = mysqli_connect($servername, $username, $password, $database);

    if (mysqli_connect_errno($con)) {
        "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if ($utype == 8) {
        $sql = "SELECT * from system_setting WHERE setting_type = 'incoming_email'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $D_email = $row["setting_value"];
            }
        } else {
            "0 results";
        }

        $sql = "SELECT * from system_setting WHERE setting_type = 'incoming_email_password'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $D_pass = $row["setting_value"];
            }
        } else {
            "0 results";
        }
    } else {
        $sql = "SELECT * from user_information WHERE user_id = '" . $uid . "'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $D_email = $row["incoming_email"];
                $D_pass = $row["incoming_email_password"];
            }
        } else {
            "0 results";
        }
        if (empty($D_email) && empty($D_pass)) {
            $sql = "SELECT * from system_setting WHERE setting_type = 'incoming_email'";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $D_email = $row["setting_value"];
                }
            } else {
                "0 results";
            }

            $sql = "SELECT * from system_setting WHERE setting_type = 'incoming_email_password'";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $D_pass = $row["setting_value"];
                }
            } else {
                "0 results";
            }
        }
    }
    mysqli_close($con);

    echo $CI->session->set_userdata('sys_email', $D_email);
}
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/action_station/debug.txt", "email crm 2" . PHP_EOL, FILE_APPEND);

if (!empty($D_email) && !empty($D_pass)) {
    $username = $D_email;
    $password = $D_pass;
}

if (empty($D_email) && empty($D_pass)) {
    $D_email = "camsaurustest@gmail.com";
    $D_pass = "camsaurus";
    $username = $D_email;
    $password = $D_pass;
}

echo $CI->session->set_userdata('sys_email', $D_email);
//////////////////////////////creating email directory/////////////////////////////
$dirArray = setDirectory($ustypees, $db, $username);
//file_put_contents("debug.txt", "File is being written from email_crm.php and/or emails_view.php" . PHP_EOL, FILE_APPEND);

//file_put_contents("debug.txt", print_r($dirArray, true) . PHP_EOL, FILE_APPEND);

//$filees_attach = $dirArray['filees_attach'];
//$filees_attachInline = $dirArray['filees_attachInline'];
$debugFilesFolder  = $dirArray['debugFilesFolder'];
$filees            = $dirArray['filees'];
$latestExpectedUID = file_get_contents($debugFilesFolder . "lastUID.txt");;
$realTimeLastUID   = $latestExpectedUID;
//file_put_contents($debugFilesFolder . "/MainDebug.txt", date("Y-m-d h:i:sa") . "\n"); //file is created for the first time here
// file_put_contents("debug.txt", "2 User type is " . $ustypees . "db is " . $db . "username is " . $username . PHP_EOL, FILE_APPEND);

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// $s_id = generateRandomString();

// try to connect

$hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}Inbox';
// $hostname = '{pop.gmail.com:995/pop3/ssl}Inbox';
$inbox = imap_open($hostname, $username, $password);

// Report all PHP errors
// error_reporting(-1);

//report errors to file
set_error_handler('write_error', -1);

// error_log("Hello, errors!");

//to ensure that echo happens immediately without buffering, https://stackoverflow.com/questions/3133209/how-to-flush-output-after-each-echo-call/4978809#4978809
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
@ob_end_clean();
set_time_limit(0);
echo '<script>
        parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\"> checking for new mails</div>";</script>';
echo str_repeat(' ', 4096 * 64);

// file_put_contents($debugFilesFolder."MainDebug.txt","I am above getMessages call". PHP_EOL, FILE_APPEND);
getMessages($inbox, $debugFilesFolder, $filees); //array of objects of type EmailMessage
file_put_contents($debugFilesFolder . "MainDebug.txt", "Getmessages done successfully" . PHP_EOL, FILE_APPEND);



function write_error($errno, $errstr, $errfile, $errline)
{
    $message = "[Error $errno] $errstr - Error on line $errline in file $errfile";
    error_log($message); // writes the error to the log file
    // mail('you@yourdomain.com', 'I have an error', $message);
    
}
function shutdown()
{
    // This is our shutdown function, in 
    // here we can do any last operations
    // before the script is complete.
    global $latestExpectedUID;
    global $debugFilesFolder;
    global $realTimeLastUID;
    // $lastUID = file_get_contents($debugFilesFolder . "lastUID.txt");
    file_put_contents("debugshut.txt","chekcing for crash\n");
    file_put_contents("debugshut.txt",$debugFilesFolder."\n",FILE_APPEND);
    file_put_contents("debugshut.txt","LASTUID : $realTimeLastUID, latestexpecteduid : $latestExpectedUID\n",FILE_APPEND);
    if($realTimeLastUID != $latestExpectedUID){
       
        file_put_contents("debugshut.txt","crashed\n",FILE_APPEND);
        // $realTimeLastUID++; //culprit uid is next one
        file_put_contents($debugFilesFolder . "lastUID.txt", $realTimeLastUID);
        echo '<script>
        parent.document.getElementById("dateerror").innerHTML="crashed due to encrypted mail! UID : '.$realTimeLastUID.' <b>Please refresh.</b>";
        </script>';
        error_reporting(E_ALL ^ E_NOTICE);
        error_reporting(E_ERROR | E_PARSE);
        imap_expunge($inbox);

        imap_close($inbox);
        
       /*  echo '<script>
            parent.location.reload();  // it is unreliable sometimes it works, sometimes crashes.
            </script>';
          */
    }
    // close the connection
    error_reporting(E_ALL ^ E_NOTICE);
    error_reporting(E_ERROR | E_PARSE);
    imap_expunge($inbox);

    imap_close($inbox);
    
}


function setDirectory($ustypees, $db, $username)
{

    // file_put_contents("debug.txt", "User type is " . $ustypees . "db is " . $db . "username is " . $username . PHP_EOL, FILE_APPEND);

    if ($ustypees == "businessaccountmanager") {
        $filees = "email_files/ceocamer_cam_crm/camsaurustest@gmail.com/";
    } else {
        $filees = "email_files/" . $db . "/" . $username . "/";
    }

    $debugFilesFolder = $filees . "DebugFiles/";
    if (!file_exists($filees)) {
        $oldmask = umask(0);
        mkdir($filees, 0777, true);
        umask($oldmask);
    }
    // file_put_contents($debugFilesFolder. "error.log","Hi Errors!\n");
    ini_set("error_log", $debugFilesFolder . "error.log");

    if (!file_exists($debugFilesFolder)) {
        $oldmask = umask(0);
        mkdir($debugFilesFolder, 0777, true);
        umask($oldmask);
    }
    $dirArray = array(
        'filees' => $filees,
        //'filees_attach' => $filees_attach,
        //'filees_attachInline' => $filees_attachInline,
        'debugFilesFolder' => $debugFilesFolder,
    );
    return $dirArray;
    // }
}

function getMessages($inbox, $debugFilesFolder, $filees)
{
    // global $debugFilesFolder;
    file_put_contents($debugFilesFolder . "MainDebug.txt", "get Messages fired" . PHP_EOL, FILE_APPEND);
  
    
    $lastUID = file_get_contents($debugFilesFolder . "lastUID.txt") + 1; //Keep track of the last highest UID email fetched in a file and then fetch emails which have UID above lastUID
    
    $daysOfEmailsToFetch = 15;
    $lastUIDforHeader = $lastUID;
    if ($lastUID != 1) {
        file_put_contents($debugFilesFolder . "MainDebug.txt", "lastUID != 1\n", FILE_APPEND);
        $lastUIDforHeader = $lastUID - 1;
    }
    file_put_contents($debugFilesFolder . "MainDebug.txt", "lastUIDforHeader is " . $lastUIDforHeader ."\n", FILE_APPEND);
    file_put_contents($debugFilesFolder . "MainDebug.txt", "msgno for headerinfo is " . imap_msgno($inbox, $lastUIDforHeader) ."\n", FILE_APPEND);

    $header = imap_headerinfo($inbox, imap_msgno($inbox, $lastUIDforHeader));
    file_put_contents($debugFilesFolder . "MainDebug.txt", "header is " . $header , FILE_APPEND);
    if($header === false){
        file_put_contents($debugFilesFolder . "MainDebug.txt", "header is nullnull." , FILE_APPEND);

    }
    file_put_contents($debugFilesFolder . "MainDebug.txt", "date on lastUID mail is" . $header->date."." , FILE_APPEND);
    
    if(strtotime($header->date) === false  && $header !== false){ // eg. it fails for "Fri, 8 Mar 2019 03:50:23 -0700 (GMT-07:00)" acceptable example is "Fri, 08 Mar 2019 20:03:15 -0800"
       
        file_put_contents($debugFilesFolder . "MainDebug.txt", "strtotime failed\n", FILE_APPEND);
        $header->date = substr($header->date,0,strpos($header->date,"("));
        file_put_contents($debugFilesFolder . "MainDebug.txt", "refined date on lastUID mail is" . $header->date."." , FILE_APPEND);
        if(strtotime($header->date) === false){
            file_put_contents($debugFilesFolder . "MainDebug.txt", "strtotime failed again." , FILE_APPEND);
            echo '<script>
            parent.document.getElementById("dateerror").innerHTML="failed to fetch date from lastUID '.$lastUIDforHeader.' Please report this to your developer along with email id.";
            </script>';

        }
        else{
            file_put_contents($debugFilesFolder . "MainDebug.txt", "strtotime passed in 2nd attempt." , FILE_APPEND);

        }

    }
    else{
        file_put_contents($debugFilesFolder . "MainDebug.txt", "strtotime succeeded... or header does not exist.\n", FILE_APPEND);

    }
    // file_put_contents($debugFilesFolder . "MainDebug.txt", "header is " . $header ."\n", FILE_APPEND);
   
    $oldestDate = date('d M Y', strtotime($header->date));
    $date15DaysEarlier = date("d M Y", strtotime("-15 days")); //date('Y-m-d', strtotime('-15 days', strtotime($date_raw)));
    file_put_contents($debugFilesFolder . "MainDebug.txt", "lastUID in file is " . $lastUID . "\noldest date in lastUID is " . $oldestDate . "\n date 15 days earlier was " . $date15DaysEarlier, FILE_APPEND);
    if (strtotime($oldestDate) < strtotime($date15DaysEarlier)) {
        file_put_contents($debugFilesFolder . "MainDebug.txt", "oldest Date is older, switching to mails since last 15 days.", FILE_APPEND);
        $mails15 = imap_search($inbox, "SINCE \"$date15DaysEarlier\"", SE_UID);
        file_put_contents($debugFilesFolder . "MainDebug.txt", " mails15 is \n" . print_r($mails15, true), FILE_APPEND);

        $lastUID = $mails15[0];
    }

    // for testing
    // $lastUID = 10;
    // end testing
    global $latestExpectedUID;
    
    file_put_contents($debugFilesFolder . "MainDebug.txt", "content of lastUID is $lastUID ", FILE_APPEND);
    /* if($lastUID == 1){
    $latestUID = end(imap_fetch_overview($inbox, "*", FT_UID))->uid;
    if($latestUID>50){
    $lastUID = $latestUID -$numberOfEmailsToFetch;
    }
    } */
    $emailsObjects = imap_fetch_overview($inbox, "$lastUID:*", FT_UID); //This will always fetch atleast one email referring to * i.e. the latest
    file_put_contents($debugFilesFolder . "MainDebug.txt", "content of lastUID is $lastUID last Object in emailscontents has uid " . end($emailsObjects)->uid . "true or false " . (end($emailsObjects)->uid == $lastUID), FILE_APPEND);

    if (end($emailsObjects)->uid == ($lastUID - 1)) { //  removing the last mail which is repeatedly fetching if there are no new mails.
        $emailsObjects = array();
    }
    // $numberOfEmailsToFetch = 50;
    // if(count($emailsObjects)>$numberOfEmailsToFetch){
    $n = count($emailsObjects);
    file_put_contents($debugFilesFolder . "MainDebug.txt", "fetched number of mails = " . $n, FILE_APPEND);

    // $emailsObjects = array_slice($emailsObjects,$n-$numberOfEmailsToFetch); // keep only the latest mails acc. to $numberOfEmailsToFetch
    // }
    file_put_contents($debugFilesFolder . "MainDebug.txt", "content of emailsObjects is " . print_r($emailsObjects, true) . PHP_EOL, FILE_APPEND);
    
   
     if ($emailsObjects) {
        $latestExpectedUID = end($emailsObjects)->uid;
        file_put_contents($debugFilesFolder . "lastUID.txt", end($emailsObjects)->uid);

    } 
    $emails = array();

    foreach ($emailsObjects as $email) {
        $emails[] = $email->msgno;
    }
    file_put_contents($debugFilesFolder . "MainDebug.txt", "content of emails is " . print_r($emails, true) . PHP_EOL, FILE_APPEND);

    /* to fetch only one mail for testing */
        $emails=Array();
       $emailUID = 276;
  $mNO = imap_msgno($inbox,$emailUID);  
    //  $emails[]=$mNO; 
    /* $emailUID = 39121;//34614 is causing out of memory error, scalesplus@gmail.com
    $mNO = imap_msgno($inbox,$emailUID); 
    $emails[]=$mNO;   */
   
 /* end of test code */
// $emails = array_slice($emails,0,10);
    // rsort($emails);

    // *** all the special test cases ***
    //34614 is causing out of memory error, scalesplus@gmail.com
     //44062 contains <script src"...jquery ></script> another version jquery to break and select user button not working properly, scalesplus@gmail.com
    $messages = array();
    if ($emails) {
        // $emails = $emails;
        $i = 1;


        //to ensure that echo happens immediately without buffering, https://stackoverflow.com/questions/3133209/how-to-flush-output-after-each-echo-call/4978809#4978809

        @ini_set('zlib.output_compression', 0);
        @ini_set('implicit_flush', 1);
        @ob_end_clean();
        set_time_limit(0);
        $debugFilesFolderbase = $filees . "DebugFiles/";
        foreach ($emails as $email_number) {
            // $email_number = imap_msgno($inbox,$email_number);
            // $email_number=imap_msgNO($inbox,);
            $emailMessage = new EmailMessage($inbox, $email_number);
            // $attachments = array();
            // $uid = imap_uid($inbox, $email_number);
            file_put_contents($debugFilesFolderbase . "MainDebug.txt", "get Messages emails are there" . PHP_EOL, FILE_APPEND);

            $messages = $emailMessage->fetch($debugFilesFolderbase);
            file_put_contents($debugFilesFolderbase . "MainDebug.txt", "fetch completed successfully" . PHP_EOL, FILE_APPEND);
            //write files here

            // print_r($messages);

            $output = "";
            $message = "";
            $s_id = generateRandomString();
            // $debugFilesFolderbase = $filees . "DebugFiles/";
            $debugFilesFolder = $filees . "DebugFiles/" . $s_id."/";
            if (!file_exists($debugFilesFolder)) {
                $oldmask = umask(0);
                mkdir($debugFilesFolder, 0777, true);
                umask($oldmask);
            }
            // $attach = $messages->attachments;
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "Content of messages->from['address'] variable is : " . $messages->from['address'] . PHP_EOL, FILE_APPEND);
            $a = $messages->from['address']; //here
            $b = $messages->subject;
            $c = $messages->date;
            $d = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!' , '' , $messages->message ); 
           
            $e = $messages->uid;
            if(strtotime($c) === false){ // eg. it fails for "Fri, 8 Mar 2019 03:50:23 -0700 (GMT-07:00)" acceptable example is "Fri, 08 Mar 2019 20:03:15 -0800"
       
                file_put_contents($debugFilesFolder . "individualMainDebug.txt", "strtotime failed at c\n", FILE_APPEND);
                $c = substr($c,0,strpos($c,"("));
                file_put_contents($debugFilesFolder . "individualMainDebug.txt", "refined date on lastUID mail is" . $header->date."." , FILE_APPEND);
                if(strtotime($c) === false){
                    file_put_contents($debugFilesFolder . "individualMainDebug.txt", "strtotime failed again." , FILE_APPEND);
                    $strtotimec = "strtotime failed";
        
                }
                else{
                    file_put_contents($debugFilesFolder . "individualMainDebug.txt", "strtotime passed in 2nd attempt." , FILE_APPEND);
                    $strtotimec = strtotime($c);
                }
        
            }
            else{
                file_put_contents($debugFilesFolder . "individualMainDebug.txt", "strtotime succeeded... or header does not exist.\n", FILE_APPEND);
                $strtotimec = strtotime($c);
            }
            
            $output .= "<subject>" . $b . "<endsubject>";
            $output .= "<id> " . $s_id . "<endid>" . '<br/>';
            $output .= "<uid>" . $e . "<enduid>";
            $output .= "<Date> " . $strtotimec . "<endDate>" . '<br/>';
            $emailaddr = '<eid>' . $a . '<endeid>';
            $date = 'Date: ' . date('Y-m-d H:i:s');
            // $strtotimec = 


            $mailname = $s_id . $strtotimec;
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "Content of matches bodyHTML before preg_match_all is : $messages->bodyHTML". PHP_EOL, FILE_APPEND);

            // preg_match_all('/src="cid:(.*)"/Uims', $messages->bodyHTML, $matches); //stores all the inline image links in matches
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "Content of matches variable is : " . print_r($matches, true) . PHP_EOL, FILE_APPEND);
            // preg_match_all('/src=("*)cid:(?:(?!/>)[^\s>])*("*)/Uims', $messages->bodyHTML, $matches); //stores all the inline image links in matches, newer version to match cid:xyz without double quotes too
            // preg_match_all('/src/', $messages->message, $matches); //stores all the inline image links in matches

            // file_put_contents($debugFilesFolder . "individualMainDebug.txt", "before search array matches is : ". print_r($matches,true) . PHP_EOL, FILE_APPEND);
            $matches = $messages->matches;
            $matchesWithKey = Array();
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "final matches is : ". print_r($matches,true) . PHP_EOL, FILE_APPEND);

            $flagInline = false;

            // file_put_contents($debugFilesFolder."individualMainDebug.txt","Content of messages->attachments variable is : ". print_r($messages->attachments, true) . PHP_EOL, FILE_APPEND);
            $at = $messages->attachments;
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "attachments is : ". print_r($at,true) . PHP_EOL, FILE_APPEND);

            if (!empty($at)) {
                $search = array(); //for inline attachments
                $replace = array(); //for inline attachments
                
                foreach ($at as $key => $value) {

                    if ($at[$key]['inline'] == false) {

                        $dir = $filees . "attachments/" . $s_id . "/";
                        // $attch = "<attach>" . $at['name'] . "<endattach>";
                        $filename = $at[$key]['filename'];
                        if (!file_exists($dir)) {
                            $oldmask = umask(0);
                            mkdir($dir, 0777, true);
                            umask($oldmask);
                        }

                        file_put_contents($dir . $filename, $at[$key]['data'], FILE_APPEND);
                        file_put_contents($debugFilesFolder . "individualMainDebug.txt", "attachments are being saved to  : " . $dir . $s_id . "/" . $filename . PHP_EOL, FILE_APPEND);

                    } else {
                        file_put_contents($debugFilesFolder . "individualMainDebug.txt", "value of flaginline is : $flagInline " . PHP_EOL, FILE_APPEND);

                        $flagInline = true;

                        $dir = $filees . "attachments/" . $s_id . "/inline/";
                        // $attch = "<attach>" . $at[$key]['name'] . "<endattach>";
                        if (!file_exists($dir)) {
                            $oldmask = umask(0);
                            mkdir($dir, 0777, true);
                            umask($oldmask);
                        }

                        $filename = $at[$key]['filename'];
                        file_put_contents($dir . $filename, $at[$key]['data'], FILE_APPEND);
                        file_put_contents($debugFilesFolder . "individualMainDebug.txt", "attachments are being saved to  : " . $dir . $s_id . "/" . $filename . PHP_EOL, FILE_APPEND);

                        // if there are any matches, loop through them and save to filesystem, change the src property
                        // of the image to an actual URL it can be viewed at
                        file_put_contents($debugFilesFolder . "individualMainDebug.txt", "count(matches[1] is ".count($matches[1]). "\n", FILE_APPEND);

                        if (count($matches[1])) {

                            // search and replace arrays will be used in str_replace function below
                            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "count(matches[1] is true". "\n", FILE_APPEND);

                            foreach ($matches[1] as $match) { //https: //www.electrictoolbox.com/php-email-extract-inline-image-attachments/

                                file_put_contents($debugFilesFolder . "individualMainDebug.txt", "Match and Key and equal are : $match and $key and " . ($match == $key) . "\n", FILE_APPEND);
                                // file_put_contents($debugFilesFolder . "individualMainDebug.txt", "bodyhtml is : \n". $messages->bodyHTML . PHP_EOL, FILE_APPEND);
                                // file_put_contents($debugFilesFolder . "individualMainDebug.txt", "match and key are $match and $key." . PHP_EOL, FILE_APPEND);

                                if ($match == $key) {

                                    // file_put_contents("email_files/Inlineimages/".$uniqueFilename, $emailMessage->attachments[$match]['data']);
                                    $search[] = "src=\"cid:$match\"";
                                    $search[] = "src=cid:$match"; //with or without quotes
                                    // $replace[] = "src=\"../$dir$filename\"";
                                    $replace[] = "src=\"".base_url().$dir.$filename."\"";
                                    $replace[] = "src=\"".base_url().$dir.$filename."\"";
                                    $matchesWithKey[]=$match;
                                    break;
                                }

                            }

                        }
                    }
                }

            }

            $matchesWithoutKey = array_diff($matches[1],$matchesWithKey);

            /* testcase false inline attachment, sometimes the program identifies the attachemnt as not inline when it is actually inline
            (for debugging purposes) SID : TQw7Vulun0 UID : 3098 EmailID : testdemo256@gmail.com
            inline image broken */
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "matches[1] : ". print_r($matches[1],true). "matcheswithkey : ".print_r($matchesWithKey,true)."matcheswithoutkey : ".print_r($matchesWithoutKey,true) . PHP_EOL, FILE_APPEND);

            $dir = $filees . "attachments/" . $s_id . "/";
           foreach($matchesWithoutKey as $match){
            $flagInline = true;
            $search[] = "src=\"cid:$match\"";
            $search[] = "src=cid:$match"; //with or without quotes
            // $replace[] = "src=\"../$dir$filename\"";
            $replace[] = "src=\"".base_url().$dir.$match."\"";
            $replace[] = "src=\"".base_url().$dir.$match."\"";
           }
            // global $flagInline;
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "value of flaginline at last is : $flagInline " . PHP_EOL, FILE_APPEND);
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "search array is : \n" . print_r($search, true) . PHP_EOL, FILE_APPEND);
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "replace array is : \n" . print_r($replace, true) . PHP_EOL, FILE_APPEND);
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "bodyhtml is : \n" . $messages->bodyHTML . PHP_EOL, FILE_APPEND);

            if ($flagInline) {
                // now do the inline replacements
                $messages->bodyHTML = str_replace($search, $replace, $messages->bodyHTML);
                $d = $messages->bodyHTML;
                file_put_contents($debugFilesFolder . "individualMainDebug.txt", "flagline is true : $flagInline " . PHP_EOL, FILE_APPEND);

            }

            // assemble the inline attachments here
            $message = '<message>' . $d . '<endmessage>';
            $saved_mail = $emailaddr . "\n" . $output . "\n" . $message;

            $dir = $filees . '/';
            file_put_contents("debug.txt", "email files are being saved to " . $dir . PHP_EOL, FILE_APPEND);
            file_put_contents($debugFilesFolder . "individualMainDebug.txt", "Content of savedmail variable is : " . $saved_mail . PHP_EOL, FILE_APPEND);
            if($strtotimec !== "strtotime failed"){
                $myfile = file_put_contents($dir . $mailname . ".txt", $saved_mail . PHP_EOL, FILE_APPEND);
            }
            // file_put_contents($debugFilesFolderbase."lastUID.txt", imap_uid($inbox,$email_number));
            global $realTimeLastUID;
            $realTimeLastUID = imap_uid($inbox,$email_number);
            $percent = intval($i / sizeof($emails) * 100) . "%";
            // Javascript for updating the progress bar and information
            /* echo '<script language="javascript">
            document.getElementById("progressbar").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
            document.getElementById("information").innerHTML="'.$i.' row(s) processed.";
            </script>'; */

            echo '<script>
            parent.document.getElementById("progressbar").innerHTML="<div style=\"width:' . $percent . ';background:linear-gradient(to bottom, rgba(125,126,125,1) 0%,rgba(14,14,14,1) 100%); ;height:35px;\">&nbsp;</div>";
            parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\"> Loading new mails.. ' . $percent . ' is processed.</div>";</script>';

            /* flush();
            ob_flush(); */
            // This is for the buffer achieve the minimum size in order to flush data
            echo str_repeat(' ', 4096 * 64);

            // Send output to browser immediately
            //flush();

            // end of write files here
            //if($i == 50)
            //sleep(1);
            /*  if ($i == 50) {
            break;
            } */

            $i++;
        }
        echo '<script>
        parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\"> Loaded ' . sizeof($emails) . ' new mails. Refresh to view them. </div>";</script>';
        echo str_repeat(' ', 4096 * 64);

    } else {
        echo '<script>
        parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\"> No new mails to load</div>";</script>';
        echo str_repeat(' ', 4096 * 64);
    }

   
    
    // return $messages;


}

function processAddressObject($addresses)
{
    $outputAddresses = array();
    if (is_array($addresses)) {
        foreach ($addresses as $address) {
            if (property_exists($address, 'mailbox') && $address->mailbox != 'undisclosed-recipients') {
                $currentAddress = array();
                $currentAddress['address'] = $address->mailbox . '@' . $address->host;
                if (isset($address->personal)) {
                    $currentAddress['name'] = decode($address->personal);
                }
                $outputAddresses = $currentAddress;
            }
        }
    }

    return $outputAddresses;
}

function getRawHeaders($uid, $inbox)
{
    $rawHeaders = imap_fetchheader($inbox, $uid, FT_UID);
    return $rawHeaders;
}
function getHeaders($uid, $inbox)
{
    $rawHeaders = getRawHeaders($uid, $inbox);
    $headerObject = imap_rfc822_parse_headers($rawHeaders);
    if (isset($headerObject->date)) {
        $headerObject->udate = strtotime($headerObject->date);
    } else {
        $headerObject->date = null;
        $headerObject->udate = null;
    }
    $headers = $headerObject;
    return $headers;
}

function decode($text)
{
    if (null === $text) {
        return null;
    }
    $result = '';
    foreach (imap_mime_header_decode($text) as $word) {
        $ch = 'default' === $word->charset ? 'ascii' : $word->charset;
        $result .= iconv($ch, 'utf-8', $word->text);
    }
    return $result;
}
function getOverview($uid, $inbox)
{
    $results = imap_fetch_overview($inbox, $uid, FT_UID);
    $messageOverview = array_shift($results);
    if (!isset($messageOverview->date)) {
        $messageOverview->date = null;
    }
    return $messageOverview;
}

class EmailMessage
{ //https: //www.electrictoolbox.com/php-email-message-class-extracting-attachments/

    protected $connection;
    protected $messageNumber;
    // protected $dates;
    public $emails;
    public $message;
    public $from;
    public $subject;
    public $date;
    public $uid;

    public $bodyHTML = '';
    public $bodyPlain = '';
    public $attachments;

    public $getAttachments = true;
    public $matches = Array();
    public function __construct($connection, $messageNumber)
    {

        $this->connection = $connection;
        $this->messageNumber = $messageNumber;
        $this->uid = imap_uid($connection, $messageNumber);

    }

    public function fetch($debugFilesFolder)
    {
        // global $debugFilesFolder;
        file_put_contents($debugFilesFolder . "MainDebug.txt", "fetch called " . date("Y-m-d h:i:sa") . PHP_EOL, FILE_APPEND);
        file_put_contents($debugFilesFolder . "MainDebug.txt", "uid of this mail is ". $this->uid . PHP_EOL, FILE_APPEND);

        $this->loadMessageNew($this->uid, $this->connection, $this->messageNumber, $debugFilesFolder);
        file_put_contents($debugFilesFolder . "MainDebug.txt", "1" . PHP_EOL, FILE_APPEND);

        $structure = imap_fetchstructure($this->connection, $this->messageNumber);
        file_put_contents($debugFilesFolder . "MainDebug.txt", "2" . PHP_EOL, FILE_APPEND);

        file_put_contents($debugFilesFolder . "structure.txt", "Structure uid $this->uid\n" . date("Y-m-d h:i:sa") . "\n", FILE_APPEND); //

        file_put_contents($debugFilesFolder . "structure.txt", print_r($structure, true) . PHP_EOL, FILE_APPEND); //
        if (!$structure) {
            return false;
        } else {
            if (!isset($structure->parts)) {
                $part = $structure;
                $structure->parts = array($part);
            }
            if($structure->subtype == 'PKCS7-MIME'){
                
                //testcase pkcs7 34614 is causing out of memory error, scalesplus@gmail.com
                $this->message = "This message couldn't be decrypted! It is a [subtype] => PKCS7-MIME.\n";
                return $this;
            }
            $this->recurse($structure->parts, '', 1, true, $debugFilesFolder);
            file_put_contents($debugFilesFolder . "MainDebug.txt", "at function fetch Content of messages->from['address'] variable is : " . $this->from['address'] . PHP_EOL, FILE_APPEND);
            // compiler definitely reach here
            // replaceInlineAttachmentLinks();
            // preg_match_all('/src/', $this->message, $matches); //stores all the inline image links in matches

            // file_put_contents($debugFilesFolder . "MainDebug.txt", "After recurse matches is : ". print_r($matches,true) . PHP_EOL, FILE_APPEND);

            
            return $this;
        }

    }
    public function loadMessageNew($uid, $inbox, $email_number, $debugFilesFolder)
    {
        // global $debugFilesFolder;
        $overview = getOverview($uid, $inbox);
        $headerInfo = imap_headerinfo($inbox, $email_number);

        // $array = array();
        $this->uid = $overview->uid;
        $this->subject = isset($overview->subject) ? decode($overview->subject) : '';
        $this->date = $headerInfo->date;
        $headers = getHeaders($uid, $inbox);
        $this->from = isset($headers->from) ? processAddressObject($headers->from) : array('');
        file_put_contents($debugFilesFolder . "MainDebug.txt", "at function loadmessagenew Content of messages->from['address'] variable is : " . $this->from['address'] . PHP_EOL, FILE_APPEND);

    }
    public function recurse($messageParts, $prefix = '', $index = 1, $fullPrefix = true, $debugFilesFolder)
    {
        try { //beacuse if the Emails size exceeds the limits it may throw fatal error, it does not work actually.
            foreach ($messageParts as $part) {

                $partNumber = $prefix . $index;

                if ($part->type == 0) {
                    if (isset($part->ifdisposition)) {
                        if ($part->ifdisposition == 1 && $part->disposition == 'ATTACHMENT') {
                            $this->attachments[] = array(
                                'type' => $part->type,
                                'subtype' => $part->subtype,
                                'filename' => $this->getFilenameFromPart($part),
                                'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
                                'inline' => false,
                            );

                        } else {
                            $this->plainOrHTML($part, $partNumber, $debugFilesFolder);

                        }
                    } else {
                        $this->plainOrHTML($part, $partNumber, $debugFilesFolder);

                    }
                    file_put_contents($debugFilesFolder . "MainDebug.txt", "content of this dot message variable is : " . $this->message . PHP_EOL, FILE_APPEND);

                } elseif ($part->type == 2) {
                    $msg = new EmailMessage($this->connection, $this->messageNumber);
                    $msg->getAttachments = $this->getAttachments;
                    $msg->recurse($part->parts, $partNumber . '.', 0, false);
                    $this->attachments[] = array(
                        'type' => $part->type,
                        'subtype' => $part->subtype,
                        'filename' => '',
                        'data' => $msg,
                        'inline' => false,
                    );
                } elseif (isset($part->parts)) {
                    if ($fullPrefix) {
                        $this->recurse($part->parts, $prefix . $index . '.', 1, true, $debugFilesFolder);
                    } else {
                        $this->recurse($part->parts, $prefix, 1, true, $debugFilesFolder);
                    }
                } elseif ($part->type > 2) {
                    if (isset($part->disposition)) {
                        if ($part->disposition == 'INLINE' && $part->type == 5) {
                            $this->getInlineAttachments($part, $partNumber);

                        } else {
                            $this->attachments[] = array(
                                'type' => $part->type,
                                'subtype' => $part->subtype,
                                'filename' => $this->getFilenameFromPart($part),
                                'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
                                'inline' => false,
                            );
                        }
                    } else {
                        $this->getInlineAttachments($part, $partNumber);

                    }
                }

                $index++;

            }
        } catch (Exception $e) {
            //do nothing
        }
        // return $this->message;

    }

    public function getInlineAttachments($part, $partNumber)
    {
        $id = str_replace(array('<', '>'), '', $part->id);
        $this->attachments[$id] = array(
            'type' => $part->type,
            'subtype' => $part->subtype,
            'filename' => $this->getFilenameFromPart($part,$id),
            'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
            'inline' => true,
        );

    }
    public function convertToUTF8($string, $debugFilesFolder){
        if(@iconv('utf-8', 'utf-8//IGNORE', $string) != $string){
            file_put_contents($debugFilesFolder . "MainDebug.txt", "not utf8" . PHP_EOL, FILE_APPEND);

            $string = Encoding::toUTF8($string);
        }
        else{
            file_put_contents($debugFilesFolder . "MainDebug.txt", "is utf8" . PHP_EOL, FILE_APPEND);

        }
        return $string;
    }
    public function switchToUTF8($string, $encoding,$debugFilesFolder){
        if($encoding != "UTF-8"){
            if($encoding!=""){
                $string = mb_convert_encoding($string, "UTF-8", $encoding);
            }
            else{
                file_put_contents($debugFilesFolder . "MainDebug.txt", "encoding variable is blank : $encoding" . PHP_EOL, FILE_APPEND);
                if(@iconv('utf-8', 'utf-8//IGNORE', $string) != $string){
                    $string = Encoding::toUTF8($string);

                }
            }
        }
        return $string;
    }
    public function plainOrHTML($part, $partNumber, $debugFilesFolder)
    {
        if ($part->subtype == 'PLAIN') {
            $this->bodyPlain .= nl2br($this->getPart($partNumber, $part->encoding));

            $parArray = $part->parameters;
            $encoding = "";
            foreach($parArray as $par){
                if($par->attribute == 'CHARSET')
                    $encoding = $par->value;
            }
            file_put_contents($debugFilesFolder . "MainDebug.txt", "pararray : ". print_r($parArray,true) . PHP_EOL, FILE_APPEND);
            file_put_contents($debugFilesFolder . "MainDebug.txt", "encoding : ". $encoding . PHP_EOL, FILE_APPEND);
            $this->bodyPlain = $this->switchToUTF8($this->bodyPlain,$encoding, $debugFilesFolder);
            $this->message = $this->bodyPlain;
           /*  if(@iconv('utf-8', 'utf-8//IGNORE', $this->bodyPlain) != $this->bodyPlain){
                file_put_contents($debugFilesFolder . "MainDebug.txt", "not utf8" . PHP_EOL, FILE_APPEND);

                $this->bodyPlain = Encoding::toUTF8($this->bodyPlain);
            }
        else{
            file_put_contents($debugFilesFolder . "MainDebug.txt", "is utf8" . PHP_EOL, FILE_APPEND);

        } */
            file_put_contents($debugFilesFolder . "MainDebug.txt", "It is a plain" . PHP_EOL, FILE_APPEND);
        } else {
            $this->bodyHTML .= $this->getPart($partNumber, $part->encoding);
            

               $parArray = $part->parameters;
            $encoding = "";
            foreach($parArray as $par){
                if($par->attribute == 'CHARSET')
                    $encoding = $par->value;
            }
            file_put_contents($debugFilesFolder . "MainDebug.txt", "pararray : ". print_r($parArray,true) . PHP_EOL, FILE_APPEND);
            file_put_contents($debugFilesFolder . "MainDebug.txt", "encoding : ". $encoding . PHP_EOL, FILE_APPEND);
            // preg_match_all('#src#', $this->bodyHTML, $matches); //stores all the inline image links in matches
            // file_put_contents($debugFilesFolder . "MainDebug.txt", "Before switchtoutf8 matches is : ". print_r($matches,true) . PHP_EOL, FILE_APPEND);

            $this->bodyHTML = $this->switchToUTF8($this->bodyHTML,$encoding , $debugFilesFolder);
            preg_match_all('~src="*cid:([^\s>/"]*?)"*~Uims', $this->bodyHTML, $matches); //stores all the inline image links in matches
          /*   testcase broken inline buttons not showing
          (for debugging purposes) SID : aoFnmXQkp8 UID : 39121 EmailID : scalesplus.cameron@gmail.com
broken inline image, also buttons not appearing 5MkVeqt1VV1558688126.txt */
            // preg_match_all('/src="cid:(.*)"/Uims', $this->bodyHTML, $matches); //stores all the inline image links in matches

            
            file_put_contents($debugFilesFolder . "MainDebug.txt", "After switchtoutf8 matches is : ". print_r($matches,true) . PHP_EOL, FILE_APPEND);
            $this->matches = $matches;
            file_put_contents($debugFilesFolder . "MainDebug.txt", "Before domdocument html is :  $this->bodyHTML" . PHP_EOL, FILE_APPEND);
            /*       $this->bodyHTML = mb_convert_encoding($this->bodyHTML,'UTF-8','utf-16');
            $this->bodyHTML = mb_convert_encoding($this->bodyHTML,'HTML-ENTITIES','UTF-8'); */
            // $this->bodyHTML = str_replace("charset=utf-16", "charset=utf-8", $this->bodyHTML);
            // $this->bodyHTML = str_replace("charset=iso-8859-1", "charset=utf-8", $this->bodyHTML);
            $pattern = 'charset=([^"\']+)';
            $this->bodyHTML = preg_replace("/$pattern/",'charset=utf-8',$this->bodyHTML); //https://stackoverflow.com/questions/3458217/how-to-use-regular-expression-to-match-the-charset-string-in-html
            //https://stackoverflow.com/questions/20705399/warning-preg-replace-unknown-modifier
            //testcase encoding scalesplus.cameron@gmail.com uid : 39032 "charset mentioned, replace all with utf-8"

            
            // file_put_contents($debugFilesFolder . "MainDebug.txt", "It is an html" . PHP_EOL, FILE_APPEND);
            // $this->bodyHTML = str_replace("<head>", "<head><meta charset=\"UTF-8\">", $this->bodyHTML);
            $cleanMessage = new DOMDocument();
            // $cleanMessage->encoding='UTF-16';
            @$cleanMessage->loadHTML("<?xml encoding=\"utf-8\">".$this->bodyHTML); //load htm : To clean the html code for unclosed td table tags and other ,  encoding utf-8 : https://stackoverflow.com/a/10286376/2083877

            $this->message = $cleanMessage->saveHTML();//$this->bodyHTML;
            // preg_match_all('/src/', $this->message, $matches); //stores all the inline image links in matches

            // file_put_contents($debugFilesFolder . "MainDebug.txt", "After domdocument matches is : ". print_r($matches,true) . PHP_EOL, FILE_APPEND);

            file_put_contents($debugFilesFolder . "MainDebug.txt", "It is an html" . PHP_EOL, FILE_APPEND);
        }
    }

    public function getPart($partNumber, $encoding)
    {

        $data = imap_fetchbody($this->connection, $this->messageNumber, $partNumber);
        switch ($encoding) {
            case 0:return $data; // 7BIT
            case 1:return $data; // 8BIT
            case 2:return $data; // BINARY
            case 3:return base64_decode($data); // BASE64
            case 4:return quoted_printable_decode($data); // QUOTED_PRINTABLE
            case 5:return $data; // OTHER
        }

    }

    public function getFilenameFromPart($part,$id)
    {

        $filename = '';

        if ($part->ifdparameters) {
            foreach ($part->dparameters as $object) {
                if (strtolower($object->attribute) == 'filename') {
                    $filename = $object->value;
                }
            }
        }

        if (!$filename && $part->ifparameters) {
            foreach ($part->parameters as $object) {
                if (strtolower($object->attribute) == 'name') {
                    $filename = $object->value;
                }
            }
        }
        if($filename != '')
            return $filename;
        elseif($part->ifsubtype){
            // return 'noname'.$nonamecount.'.'.$part->subtype;
            if($part->ifid)
                return $id.'.'.$part->subtype;

        }
        else
            return 'namenotfound';
    }
} //End of class EmailMessage
