<?php
 echo "<h5><strong> FULL time + date : </strong></h5><h4><strong>".date('D, d M Y h:i:s O')."</strong></h4>";
echo '<h5>DATE :<time datetime="'.date('c').'">'.date('Y - m - d').'</time></h5>';
echo '<h5>TIME :<time datetime="'.date('c').'">'.date('H:i :s A').'</time></h5>';

?>
<?php
$timestamp = date('c');
$timestamp = strtotime($timestamp);
 
  echo "<h5><strong> TIMESTAMP :".$timestamp."</strong></h5>";
?>