<?php
	require("frontpage/fpsubmission.php");
	$errormsg = "";
	
	switch($error)
	{
		case "1":
			$errormsg = "Could not connect to database";
			break;
		case "2":
			$errormsg = "Email address not registered";
			break;
		case "3":
			$errormsg = "Invalid password";
			break;
		case "4":
			$errormsg = "Invalid email address (contain spaces)";
			break;
		case "5":
			$errormsg = "Invalid password (password should be alphanumeric)";
			break;
		case "6":
			$errormsg = "Invalid name (name should be alphabetic)";
			break;
		case "7":
			$errormsg = "Email address has been registered, please use another address";
			break;
		default :
			$errormsg = "";
	}
?>

<!doctype html>
<html>
<head>
    <title><?php require("name.php"); ?> - Sign In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
</head>
<body id="frontpage" class="body">
	<section class="header">
		<div class="title"><?php require("name.php"); ?></div>
	</section>
	<div class="row">
		<div class="col-md-4 col-md-offset-3">
			<img class="poster" src="img/poster3.png">
		</div>	
	</div>
	<div id="page" class="clearfix">
		<div class="col-md-4">
			<img src="img/logo1_alll_2px_rev.png">
		</div>
	    <div id="content" class="col-md-4" style="text-align:center">
			<p> <?php echo $errormsg ?> </p>
			<br>
			<h3>Log In</h3>
			<form class="form" action="<?php $_PHP_SELF ?>" method="post">
				<?php require("frontpage/fpliform.php") ?>
			</form>
			<h3>Or sign up</h3>
			<form class="form" action="<?php $_PHP_SELF ?>" method="post">
				<?php require("frontpage/fpsuform.php") ?>
			</form>
		</div>
		<div class="col-md-4">
			<img src="img/logo1_alll_2px.png">
		</div>
	</div> 
</body>
</html>
