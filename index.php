<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!empty($_POST["D_value"])){

		if (!preg_match("/^[0-9]*$/",$D_value)) {
	       $D_value = "Only numbers (0 to 9)"; 
	     }else{
	     	$D_value = test_input($_POST["D_value"]);
	     	$B_value = decbin($D_value); 
	     	$O_value = decoct($D_value);
	     	$H_value = dechex($D_value);
	     }

	}else if (!empty($_POST["B_value"])){
		if (!preg_match("/^[01]*$/",$B_value)) {
	       $B_value = "Only numbers (0 or 1)"; 
	     }else{
	     	$B_value = test_input($_POST["B_value"]);
	     	$D_value = bindec($B_value); 
	     	$O_value = decoct(bindec($B_value));
	     	$H_value = dechex(bindec($B_value));
	     }
	}else if (!empty($_POST["O_value"])){
		if (!preg_match("/^[0-7]*$/",$O_value)) {
	       $O_value = "Only numbers (0 to 7)"; 
	     }else{
	     	$O_value = test_input($_POST["O_value"]);
	     	$B_value = decbin(octdec($O_value));
	     	$D_value = octdec($O_value); 
	     	$H_value = dechex(octdec($O_value));

	     }
	}else if (!empty($_POST["H_value"])){
		if (!preg_match("/^[a-fA-F0-9]*$/",$H_value)) {
	       $H_value = "Only numbers (0 to 9) or letters a - z. "; 
	     }else{$H_value = test_input($_POST["H_value"]);
	          	$O_value = decoct(hexdec($H_value));
	     		$B_value = decbin(hexdec($H_value));
	     		$D_value = hexdec($H_value); 
	     }
	}else{
		$H_value = $O_value = $B_value = $D_value = "";
	}
}else{die;}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Dec to Bin, Hex, Oct</title>

<style>
	*{margin:0px 0px;padding:10px 10px;color:#333;font-size: 16px;font-family: Arial, sans-serif;border-radius:6px;}
	body{width:1024px;min-width:1px;background-color:#333;}
	section{display:inline-block;}
	header{display:block;height:45px;border-bottom:1px solid #999;background-color:#888;}
	h1{color:#000;font-size: 1.4em;line-height:1.6em;text-align:center;vertical-align: middle;}
	h6{color:#000;font-size: 0.8em;line-height:1.2em;text-align:center;vertical-align: middle;}
	label{color:#333;font-size: 1em;}
	#main{ background-color:#efefef;margin:10px 50%;width:720px;}
	.box75{ width:65%;min-width:1px;float:right;background-color:#f9f9f9;}
		.box25{ width:25%;min-width:1px;float:left;background-color:#f9f9f9;height:200px;}
</style>

</head>
<body>
	<section id="main">
		<header>
			<h1>Calculate the Decimal , Binary , Hexadecimal and Octal values.</h1>
		</header>
		<section class="box25"><h6>Decimal , Binary , Hexadecimal and Octal Calculated by using any of the above fields. </h6></section>
		<section class="box75">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

				<label for="D_value">Decimal :   
				<input type="text" value="<?php echo $D_value; ?>" name="D_value" /> </label><br><br>
				<label for="B_value">Binary :    
				<input type="text" value="<?php echo $B_value; ?>" name="B_value" /> </label><br><br>
				<label for="H_value">Hexadecimal :
				<input type="text" value="<?php echo $H_value; ?>" name="H_value" /></label><br><br>
				<label for="O_value">Octal :      
				<input type="text" value="<?php echo $O_value; ?>" name="O_value" /></label><br><br>

				<input type="submit" value="Calculate" name="Button" />

			</form>
		</section>
			
	</section>
</body>
</html>