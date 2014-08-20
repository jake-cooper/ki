
<form name="form2" action="https://testwebpay.interswitchng.com/test_paydirect/pay" method="post">
    product_id
    <input name="product_id" type="text" value="4220" class="form-control input-lg"/>
    amount
    <input name="amount" type="text" value="100000" class="form-control input-lg"/>
    currency
    <input name="currency" type="text" value="566" class="form-control input-lg"/>
    site_redirect_url
    <input name="site_redirect_url" type="text" value="http://webpayj.dev/getresponse.php?echo=" class="form-control input-lg"/>
    site_name
    <input name="site_name" type="text" value="http://webpayj.dev/" class="form-control input-lg"/>
    cust_id
    <input name="cust_id" type="text" value="a" class="form-control input-lg"/>
    cust_id_desc
    <input name="cust_id_desc" type="text" value="Value Name" class="form-control input-lg"/>
    cust_name
    <input name="cust_name" type="text" value="Name <B>Surname</B>" class="form-control input-lg"/>
    cust_name_desc
    <input name="cust_name_desc" type="text" value="Full name" class="form-control input-lg"/>
    txn_ref
    <input name="txn_ref" type="text" value="JCC123456" class="form-control input-lg"/>
    pay_item_id
    <input name="pay_item_id" type="text" value="XXXXX" class="form-control input-lg"/>
    pay_item_name
    <input name="pay_item_name" type="text" value="Name of Fees/Payment" class="form-control input-lg"/>
    local_date_time
    <input name="local_date_time" type="text" value="<?php echo $timestamp; ?>" class="form-control input-lg"/>
    hash
    <input name="hash" type="text" value="BB292DF9268F05CB9CBBC5E0C13CC1B13ACA34DC" class="form-control input-lg"/>
    payment_params
    <input name="payment_params" type="text" value="payment_split" class="form-control input-lg"/>
    xml_data
    <textarea name="xml_data"  class="form-control input-lg" rows="10">
<payment_item_detail>
    <item_details detail_ref=“XXXAFTXXX” institution=“ABC” sub_location=““ location=““>
        <item_detail item_id=“1” item_name=“Butter” item_amt=“0000” bank_id=“0” acct_num=“435678” />
        <item_detail item_id=“2” item_name=“Juice” item_amt=“0000” bank_id=“9” acct_num=“6010153866” />
    </item_details>
</payment_item_detail>
    </textarea> 
    json_data
    <textarea name="json_data"  class="form-control input-lg" rows="10">
https://stageserv.interswitchng.com/test_paydirect/api/v1/gettransaction.json
    Example Object
    {"Amount":0,"CardNumber":null,"MerchantReference":null,"PaymentReference":null,"RetrievalReferenceNumber":null,"LeadBankCbnCode":null,"LeadBankName":null,"SplitAccounts":null,"TransactionDate":"0001-01-01T00:00:00","ResponseCode":"20031","ResponseDescription":"20031 - Invalid value for ProductId"}
    </textarea> 
</form>
    
<form name="form1" action="<?php echo $purl ?>" method="post">
    <input name="product_id" type="hidden" value="4220" />
    <input name="amount" type="hidden" value="100000" />
    <input name="currency" type="hidden" value="566" />
    <input name="site_redirect_url" type="hidden" value="http://webpayj.dev/getresponse.php?echo=" />
    <input name="site_name" type="hidden" value="http://webpayj.dev/" />
    <input name="cust_id" type="hidden" value="a" />
    <input name="cust_id_desc" type="hidden" value="Value Name" />
    <input name="cust_name" type="hidden" value="Name <B>Surname</B>" />
    <input name="cust_name_desc" type="hidden" value="Full name" />
    <input name="txn_ref" type="hidden" value=" JCC123456" />
    <input name="pay_item_id" type="hidden" value="XXXXX" />
    <input name="pay_item_name" type="hidden" value="Name of Fees/Payment" />
    <input name="local_date_time" type="hidden" value="" />
    <input name="hash" type="hidden" value="BB292DF9268F05CB9CBBC5E0C13CC1B13ACA34DC" />
    <input name="payment_params" type="hidden" value="payment_split" />
    <input name="xml_data" type="hidden" value='<payment_item_detail>
    <item_details detail_ref=“XXXAFTXXX” institution=“ABC” sub_location=““location=““>
    <item_detail item_id=“1” item_name=“Butter” item_amt=“0000” bank_id=“0” acct_num=“435678” />
    <item_detail item_id=“2” item_name=“Juice” item_amt=“0000” bank_id=“9” acct_num=“6010153866” />
    </item_details>
    </payment_item_detail>' />
</form><?php //SAMPLE FORM ?>