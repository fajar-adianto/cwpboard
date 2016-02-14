<?php
	header('Cache-Control: no-cache');

	$dbhost = '127.10.68.2';
	$dbuser = 'adminquc4uCW';
	$dbpass = 'HS5iXilyCy_t';
	$dbname = 'cwp';
	$roomtbl = 'roomlist_tbl';
	$error = 0;
	$errormsg = "";
	
	if(isset($_POST['roomname']))
	{
		require("mainpage/joinRoomDataProcess.php");
		if(!$error)
		{	
			echo("<script type=\"text/javascript\">parent.document.getElementById('roomDataForm')['status'].value = 'passive';</script>");
			echo("<script type=\"text/javascript\">parent.document.getElementById('roomDataForm')['roomemail'].value = '$roomemail';</script>");
			echo("<script type=\"text/javascript\">parent.document.getElementById('roomDataForm')['roomname'].value = '$roomname';</script>");
			echo("<script type=\"text/javascript\">parent.document.getElementById('roomDataForm')['roomport'].value = $roomport;</script>");
			echo("<script type=\"text/javascript\">parent.document.getElementById('roomDataForm').submit();</script>");
		}
		else
		{
			switch($error)
			{
				case 1:
					$errormsg = "Could not connect to database";
					break;
				case 2:
					$errormsg = "Search failed";
					break;
				case 3:
					$errormsg = "Invalid password or room doesn't exist";
					break;
				default :
					$errormsg = "";
			}
			echo("<script type=\"text/javascript\">parent.document.getElementById('errorMessage').innerHTML = \"$errormsg\";</script>");
		}
	}
?>

<!doctype html>
<html>
<head> 
	<script type="text/javascript">
		parent.history.pushState(null, null, "?menu=join");
	</script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:transparent;">
	<div id="joinRoomContent" style="text-align:center">
		<form class="form" id="joinRoomForm" name="joinForm" action="<?php $_PHP_SELF ?>" method="post">
			<input class="form-control" type="text" name="roomemail" placeholder="Room Email" required><br>
			<input class="form-control" type="text" name="roomname" placeholder="Room Name" required><br>
			<input class="form-control" type="password" name="password" placeholder="Password (if set)"><br>
			<input class="btn btn-primary" id="submitJoinButton" type="submit" value="Join">
		</form>
		</script>
	</div>
</body>
</html>
