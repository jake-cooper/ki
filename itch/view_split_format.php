<?php


$viewer = "&lt;payment_item_detail&gt;
&lt;item_details detail_ref=&quot;<?php echo $trans_ref ?>&quot; college=&quot;OAUCDL&quot; department=&quot;Comp. Science&quot; faculty=&quot;Science&quot;&gt;
&lt;item_detail item_id=&quot;1&quot; item_name=&quot;OAUCDL&quot; item_amt=&quot;700000&quot; bank_id=&quot;128&quot; acct_num=&quot;3759416282&quot;/&gt;
&lt;item_detail item_id=&quot;2&quot; item_name=&quot;Portal Provider&quot; item_amt=&quot;70000&quot; bank_id=&quot;128&quot; acct_num=&quot;3000009209&quot;/&gt;
&lt;/item_details&gt;
&lt;/payment_item_detail&gt;";

echo $viewer;
echo $CFG->enrol_xml;
echo $CFG->enrol_payment_params;
?>