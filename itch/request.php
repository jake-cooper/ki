<?php


require("../../config.php");
require_once("$CFG->dirroot/enrol/iinterswitch/enrol.php");
print_header();

$context = get_context_instance(CONTEXT_SYSTEM, $id);
require_capability('moodle/course:create', $context);

require_login();               //make sure user is logged in

//require("../../config.php");

  if (!empty($_GET['txn_ref'])) {

$amount = $_GET['amount'] ;
$trans_ref = $_GET['txn_ref'];    
echo "<h1>Interswitch Payment Query</h1>"; //Your code here



$mac= "$CFG->enrol_mac";
$product_id = "$CFG->enrol_product_id";
$pay_item_id = "$CFG->enrol_pay_item_id";
$redirect = "$CFG->wwwroot/enrol/iinterswitch/request.php";
$hash = strtoupper(hash('sha512',$product_id.$trans_ref.$mac));
/////////delectations finished
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

$finalresponse2 = ($xmlreal[ResponseCode] . $xmlreal[ResponseDescription] . $xmlreal[Amount] . $xmlreal[CardNumber] . $xmlreal[MerchantReference]. $xmlreal[RetrievalReferenceNumber]. $xmlreal[TransactionDate]);

echo "<h1>";
echo "Transaction ID :".$trans_ref;
echo "</Br>";
echo "Transaction Response Description :".$xmlreal[ResponseDescription];
echo "</Br>";

if ($xmlreal[RetrievalReferenceNumber]!= "" ){
echo "Payment reference :".$xmlreal[PaymentReference];
             }
echo "</h1>";


  } else {

?>
<h1>Interswitch Payment Query</h1>

<form action="request.php" method="get">
  <input type="hidden" name="act" value="run">  
  <p><label for="amount">Amount:</label><input name="amount" type="text" value="" ></p>
   <p><label for="txn_ref">Transaction Ref:</label><input name="txn_ref" type="text" value=""></p>
   <input type="submit" value="Check this transaction">  
</form>
<?php
  }
print_footer();
?>