<?php
include('debug.php');
include('functions.php');

  //sha512 hash
  //txnref.productid.pay_item_id.amount.site_redirect_url.mackey
/*
 * Payment Function hash(sha512, $data, false)
 * string hash ( string $algo , string $data [, bool $raw_output = false ] )
 * 
 * $algo -> sha512 (128 bit encryption string)
 * $data -> @txnref . @productid . @pay_item_id . @amount . @site_redirect_url . @mackey
 * $raw_data ->  False -> Outputs Lowercase hexits   : True -> Outputs raw binary data
 *
 */
  $payment_gateway = 'https://stageserv.interswitchng.com/test_paydirect/pay';
  $name = "" ;

    if(isset($_GET['name']))
      $name = $_GET['name'];
    else 
      $name = "JCC" ;

  	$txnref = '123456';
  	$product_id = "4220";
	$item_id = "101";
  	$amount = '100000'; //amount in Kobo (Kobo *100) = Naira NGN or N1000
  	$site_redirect_url = 'webpayj.dev/return.php';
 	$mackey = '199F6031F20C63C18E2DC6F9CBA7689137661A05ADD4114ED10F5AFB64BE625B6A9993A634F590B64887EEB93FCFECB513EF9DE1C0B53FA33D287221D75643AB';

	
	
  //PAYMENT
  $payUrl = 'https://stageserv.interswitchng.com/test_paydirect/pay?';
  $paydata = $txnref . $product_id . $item_id . $amount . $site_redirect_url . $mackey;
  $payStr = hash('sha512',$paydata , false);
  $purl = $payUrl . $payStr;
  
  //QUERY
  $queryUrl = "https://stageserv.interswitchng.com/test_paydirect/api/v1/gettransaction.json";
  $querydata = $product_id . $txnref . $mackey;
  $queryStr = hash('sha512',$querydata , false);
  $qurl = $queryUrl ."?". $queryStr;



?>
<!DOCTYPE>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Testing WebPay J</title>
</head>
<body>
<div class="container">
	<span class="page-header"><h2>Payment Data Hash</h2></span><hr>
	<form>
		<section class="form-group">
			<label for="paydata" class="control-label">Pay Post Data:</label>
	    	<textarea  id="paydata"  class="form-control input-lg" rows="2" readonly ><?php echo $paydata; ?></textarea>
	    	<br/>
	  	</section>
		<section  class="form-group">
			<label for="paystring" class="control-label">Payment Post Hash:</label>
	        <textarea  id="paystring"  class="form-control input-lg" rows="3" readonly ><?php echo $payStr; ?></textarea><br/>
	    </section>
	    <section  class="form-group">
			<label for="purl" class="control-label">Payment Post Hash Url:</label>
	        <textarea  id="purl"  class="form-control input-lg" rows="5" readonly ><?php echo $purl; ?></textarea><br/>
	    </section>
	</form>
</div>
<div class="container"><span class="page-header"><h2>Payment Query Returned Data Hash</h2></span><hr>
	<form>
		<section class="form-group">
			<label for="querypaydata" class="control-label">Query Post Data:</label>
		    <textarea  id="querypaydata"  class="form-control input-lg" rows="2" readonly ><?php echo $querydata; ?></textarea>
		    <br/>
		  </section>
		<section  class="form-group">
			<label for="querypaystring" class="control-label">Query Payment Post Hash:</label>
		    <textarea  id="querypaystring"  class="form-control input-lg" rows="3" readonly ><?php echo $queryStr; ?></textarea><br/>
		</section>
		<section  class="form-group">
			<label for="qurl" class="control-label">Query Payment Post Hash Url:</label>
		    <textarea  id="qurl"  class="form-control input-lg" rows="5" readonly ><?php echo $qurl; ?></textarea><br/>
		</section>
	</form>    
</div>
<div class="container">
    <span class="page-header"><h2>Query POST</h2></span><hr>
	<form method="POST" action="<?php echo $purl; ?>">
		<fieldset>
		    <legend >Visible</legend><br/>
		    <label for="name">Name:</label>
		    <input type="text" id="name" placeholder="name" value="<?php echo $name; ?>"class="form-control input-lg" /><br/>
			</fieldset>
		<hr>
			<button type="submit" value="submit" class="btn btn-lg btn-danger btn-block">Submit </button>
		<hr>
		<fieldset> 
		  	<legend>Invisible</legend>
		  	<label for="txnref">TXNREF:</label>
		  	<input type="text" id="txnref" value="<?php echo $txnref; ?>" class="form-control input-lg" readonly /><br/>
		  	<label for="product_id">Product_Id:</label>
		  	<input type="text" id="product_id" value="<?php echo $product_id; ?>" class="form-control input-lg" readonly/><br/>
		  	<label for="item_id">Pay_Item_Id:</label>
		  	<input type="text" id="item_id" value="<?php echo $item_id; ?>" class="form-control input-lg" readonly/><br/>
		  	<label for="amount">Amount:</label>
		  	<input type="text" id="amount" value="<?php echo $amount; ?>" class="form-control input-lg" readonly/><br/>
		  	<label for="site_redirect_url">Site_Redirect_Url:</label>
		  	<input type="text" id="site_redirect_url" value="<?php echo $site_redirect_url; ?>" class="form-control input-lg" readonly/><br/>
		  	<label for="mackey">Mac Key:</label>
		  	<input type="text" id="mackey" value="<?php echo $mackey; ?>" class="form-control input-lg" readonly/>
		</fieldset> 

		<input type="hidden" id="name" placeholder="name" value="<?php echo $name; ?>"/>
		<input type="hidden" id="txnref" value="<?php echo $txnref; ?>" />
		<input type="hidden" id="product_id" value="<?php echo $product_id; ?>"  />
		<input type="hidden" id="item_id" value="<?php echo $item_id; ?>" />
		<input type="hidden" id="amount" value="<?php echo $amount; ?>" />
		<input type="hidden" id="site_redirect_url" value="<?php echo $site_redirect_url; ?>"  />
		<input type="hidden" id="mackey" value="<?php echo $mackey; ?>" />
		<button type="submit" value="Submit" class="btn btn-lg btn-success btn-block">WebPay Query Result</button>
	</form>
</div>


	