<?php 
if(empty($_GET['name'])) 
  echo "No GET variables"; 
else ?><div class="container"><?php 
    print_r($_GET); 
?>
<h6>Tests</h6>
	<pre>
		<?php
		print_r($_GET);
		if($_GET["name"] === "") echo "$name is an empty string\n";
		if($_GET["name"] === false) echo "$name is false\n";
		if($_GET["name"] === null) echo "$name is null\n";
		if(isset($_GET["name"])) echo "$name is set\n";
		if(!empty($_GET["name"])) echo "$name is not empty";
		?>
	</pre>
<?php	echo htmlspecialchars($_GET["name"]); ?>
</div>

