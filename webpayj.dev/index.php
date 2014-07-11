<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebPay Form</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="page.css">
    <style>h5{color:#FFF;}</style>
  </head>
  <body>

<!-- GOOD STUFF GOES IN HERE-->
<?php include('time.php'); //Payment Hash Form ?>
<?php include('payment_hash.php'); //Payment Hash Form ?>
<?php include('test_Get.php'); //Test Get Object ?>


<!-- Guidance Form  -->
<div class="container">
  <pre>
  <?php include('sample_form.php'); //Test Get Object ?>
</pre>
</div>
<?php include('buttons.php'); ?>
<?php //include('help_docs.php'); ?>
<?php include('footer.php'); ?>
<!-- GOOD STUFF GOES UNTIL HERE-->
    <section class="footer">
      <section class="container">
        <h5 class="text-muted">Place sticky footer content here.</h5>
      </section>

    </section>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>