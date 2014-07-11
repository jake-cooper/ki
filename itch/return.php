<?php  // $Id: return.php,v 1.5 2006/10/23 15:17:31 moodler Exp $



    require("../../config.php");
    require_once("$CFG->dirroot/enrol/iinterswitch/enrol.php");
    require_once("$CFG->dirroot/enrol/enrol.class.php");

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

//require_once('config.php');
session_name('MoodleSession');
session_start();
//echo $_SESSION['views'];
$trans_ref = $_SESSION['views'];
$amount = $_SESSION['cost'];
//echo $_SESSION['cost'];
//echo "</Br>";
//echo "my transaction ref is:" .$trans_ref;
//echo "</Br>";
//echo "my cost is:" .$amount;
//echo "</Br>";






$mac= "$CFG->enrol_mac";
$product_id = "$CFG->enrol_product_id";
$pay_item_id = "$CFG->enrol_pay_item_id";
//echo $cost;
//echo $amount;

$redirect = "$CFG->wwwroot/enrol/iinterswitch/return.php?id=$course->id";
//$hashkey =hash("sha512","$trans_ref$product_id$pay_item_id$amount$redirect$mac");
$hash = strtoupper(hash('sha512',$product_id.$trans_ref.$mac));


$header = array("hash:$hash");

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_URL, "https://webpay.interswitchng.com/paydirect/api/v1/gettransaction.xml?productid=$product_id&transactionreference=$trans_ref&amount=$amount");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
$xmlreal = (array)simplexml_load_string($output);
//echo $output;
//echo $output;
curl_close($ch);

//if(isset($_SESSION['views']))
  //unset($_SESSION['views']);

$querystring = "https://webpay.interswitchng.com/paydirect/api/v1/gettransaction.xml?productid=$product_id&transactionreference=$trans_ref&amount=$amount";
//echo $xmlreal;
$finalresponse2 = ($xmlreal[ResponseCode] . $xmlreal[ResponseDescription] . $xmlreal[Amount] . $xmlreal[CardNumber] . $xmlreal[MerchantReference]. $xmlreal[RetrievalReferenceNumber]. $xmlreal[TransactionDate]);


//echo count($xmlreal);
print_header();
$finalresponse = ($output[139] . $output[139]);
//echo $finalresponse;
//echo $finalresponse2;


if ($xmlreal[ResponseCode]!= "00" ){
echo '<div style="text-align:left; color:red"><strong><h1>Transaction Error</h1></strong></div>';
echo "</Br>";
echo "</h1>Sorry, Your Payment was not successful. Reason :".$xmlreal[ResponseDescription]."</h1>";
echo "</Br>";
echo  "</h1>RESPONSE CODE :".$xmlreal[ResponseCode]."</h1>";
             } else {
echo '<div style="text-align:left; color:blue"><strong><h1>Transaction Successful!</h1></strong></div>';
}

echo "</Br>";
echo "<h1>";
echo "Transaction ID :".$trans_ref;
echo "</Br>";
echo "Transaction Response Description :".$xmlreal[ResponseDescription];



if ($xmlreal[RetrievalReferenceNumber]!= "" ){
echo "</Br>";
echo  "Payment Reference :".$xmlreal[PaymentReference];
             }
echo "</h1>";





////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $id = required_param('id', PARAM_INT);

    if (!$course = get_record("course", "id", $id)) {
        redirect($CFG->wwwroot);
    }

    if (! $context = get_context_instance(CONTEXT_COURSE, $course->id)) {
        redirect($CFG->wwwroot);
    }

    require_login();
    
    load_all_capabilities();

    if ($SESSION->wantsurl) {
        $destination = $SESSION->wantsurl;
        unset($SESSION->wantsurl);
    } else {
        $destination = "$CFG->wwwroot/course/view.php?id=$course->id";
    }
    
    // IPN functionality include: start
    $data = new object();

    foreach ($_POST as $key => $value) {
        $key = strtolower($key);
        $value = stripslashes($value);
        $req .= "&$key=".urlencode($value);
        $data->$key = $value;
    }
    //$tt = time();
    $today = date("Y-m-d H:i:s");
    

    $data->description  =  $xmlreal[ResponseDescription];
    $data->payment_ref  = $xmlreal[PaymentReference];
    $data->payment_status  = $xmlreal[ResponseCode];
    $data->paymentlog_id =   $trans_ref;    
    $data->courseid         = $course->id;
    $data->userid           = $USER->id;
    //$data->payment_status   = $data->success;
    $data->payment_date      	= $today ;
    $data->amount  = $amount;
    
     insert_record("paymentlog_id", addslashes_object($data));

    if (! $user = get_record("user", "id", $data->userid) ) {
        email_iinterswitch_error_to_admin("Not a valid user id", $data);
        echo "1";
        fault();
    }

    elseif (! $course = get_record("course", "id", $data->courseid) ) {
        email_iinterswitch_error_to_admin("Not a valid course id", $data);
        echo "2";
        fault();
    }

    elseif (! $context = get_context_instance(CONTEXT_COURSE, $course->id)) {
        email_iinterswitch_error_to_admin("Not a valid context id", $data);
        echo "3";
        fault();
    }
    
     if ($data->payment_status != "00") {


     if ($data->payment_status = "54") {  
        role_unassign(0, $data->userid, 0, $context->id);
        email_iinterswitch_error_to_admin("Online Payment not successful. Card Expired", $data);
        //echo "15";
        //echo "cost is :$amount ";
        fault_card_expired();
                }

      if ($data->payment_status = "Z4") {  
        role_unassign(0, $data->userid, 0, $context->id);
        email_iinterswitch_error_to_admin("Online Payment not successful.Wrong Product ID.", $data);
        //echo "Card Expired";
        //echo "cost is :$amount ";
        fault_wrong_product_id();
                }
        


  if ($data->payment_status = "51") {  
        role_unassign(0, $data->userid, 0, $context->id);
        email_iinterswitch_error_to_admin("Online Payment not successful. Insufficient Funds", $data);
        //echo "Card Expired";
        //echo "cost is :$amount ";
        fault_card_insuff();
                }
  
        role_unassign(0, $data->userid, 0, $context->id);
        email_iinterswitch_error_to_admin("Online Payment not successful. Some other reasons", $data);
        echo "4";
        //echo "cost is :$amount ";
        fault();
        }

    
        
    // At this point we only proceed with a status of completed or pending with a reason of echeck
    
    if ($existing = get_record("enrol_iinterswitch", "paymentlog_id", addslashes($data->paymentlog_id))) {   // Make sure this transaction doesn't exist already
        email_iinterswitch_error_to_admin("Payment Log $data->paymentlog_id is being repeated!", $data);
        fraud();
    }
    
    if (!$user = get_record('user', 'id', $data->userid)) {   // Check that user exists
        email_iinterswitch_error_to_admin("User $data->userid doesn't exist", $data);
        echo "5";
        fault();
    }
    
    if (!$course = get_record('course', 'id', $data->courseid)) { // Check that course exists
        email_iinterswitch_error_to_admin("Course $data->courseid doesn't exist", $data);
        echo "6";
        fault();
    }
    
    // Check that amount paid is the correct amount
    if ( (float) $course->cost < 0 ) {
        $cost = (float) $CFG->enrol_cost;
    } else {
        $cost = (float) $course->cost;
    }
    
    

if  ($data->amount < $amount) {
        $cost = format_float($cost, 2);
        email_iinterswitch_error_to_admin("Amount paid is not enough ($data->amount < $cost))", $data);
        echo "7";
        fault();


    }
    
    // ALL CLEAR !
    
    if (!insert_record("enrol_iinterswitch", addslashes_object($data))) {       // Insert a transaction record
        email_iinterswitch_error_to_admin("Error while trying to insert valid transaction", $data);
        echo "8";
        fault();
    }
    
    if (!enrol_into_course($course, $user, 'iinterswitch')) {
        email_iinterswitch_error_to_admin("Error while trying to enrol ".fullname($user)." in '$course->fullname'", $data);
        echo "9";
        fault();
    } else {
        $teacher = get_teacher($course->id);
    
        if (!empty($CFG->enrol_mailstudents)) {
            $a->coursename = $course->fullname;
            $a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id";
            email_to_user($user, $teacher, get_string("enrolmentnew", '', $course->shortname),
                          get_string('welcometocoursetext', '', $a));
        }
    
        if (!empty($CFG->enrol_mailteachers)) {
            $a->course = $course->fullname;
            $a->user = fullname($user);
            email_to_user($teacher, $user, get_string("enrolmentnew", '', $course->shortname),
                          get_string('enrolmentnewuser', '', $a));
        }
    
        if (!empty($CFG->enrol_mailadmins)) {
            $a->course = $course->fullname;
            $a->user = fullname($user);
            $admins = get_admins();
            foreach ($admins as $admin) {
                email_to_user($admin, $user, get_string("enrolmentnew", '', $course->shortname),
                              get_string('enrolmentnewuser', '', $a));
            }
        }
    }
    // stop

/// Refreshing enrolment data in the USER session
    
    if (has_capability('moodle/course:view', $context)) {
        redirect($destination, get_string('paymentthanks', '', $course->fullname));
        

    } else {   /// Somehow they aren't enrolled yet!  :-(
        print_header();
        echo "10";
        notice(get_string('paymentsorry', '', $course), $destination);
    }


    //$say = echo $finalresponse2;

function fault_card_expired() {
    global $course, $destination;
    //print_header();
    echo "$data->description";    
    //echo "1";
    echo "$xmlreal[ResponseDescription]";
    //notice(get_string('paymentsorry', '', $course), $destination);
      notice( "", $destination);
      print_r(get_headers($finalresponse2));
      
    exit;
}
function fault_card_insuff() {
    global $course, $destination;
    //print_header();
    //echo "11";
    //notice(get_string('paymentsorry', '', $course), $destination);
      notice("", $destination);
    exit;
}
function fault_wrong_product_id() {
    global $course, $destination;
    //print_header();
    //echo "11";
    //notice(get_string('paymentsorry', '', $course), $destination);
      notice("", $destination);
    exit;
}

function fault() {
    global $course, $destination;
    //print_header();
    //echo "11";
      notice("", $destination);    
    exit;
}

function fraud() {
    global $course, $destination;
    print_header();
    //echo "12";
    //echo "$xmlreal[ResponseCode]";    
    //echo "</Br>";
    //echo "$xmlreal[ResponseDescription]";
    //echo "</Br>";
    notice(get_string('paymentfraud', 'enrol_iinterswitch', $course), $destination);
    exit;
}

function email_iinterswitch_error_to_admin($subject, $data) {
    $admin = get_admin();
    $site = get_site();

    $message = "$site->fullname:  Transaction failed.\n\n$subject\n\n";

    foreach ($data as $key => $value) {
        $message .= "$key => $value\n";
    }

    email_to_user($admin, $admin, "INTERSWITCH PAYMENT ERROR: ".$subject, $message);

}

//echo $finalresponse2;



?>