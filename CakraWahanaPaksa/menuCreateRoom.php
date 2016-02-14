<?php
	header('Cache-Control: no-cache');

	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'collaboard';
	$roomtbl = 'roomlist_tbl';
	$error = 0;
	$errormsg = "";
	
	if(isset($_POST['roomname']))
	{
		require("mainpage/createRoomDataProcess.php");
		if(!$error)
		{	
			echo("<script type=\"text/javascript\">parent.document.getElementById('roomDataForm')['status'].value = 'admin';</script>");
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
					$errormsg = "Invalid room name (room name should be alphanumeric)";
					break;
				case 2:
					$errormsg = "Invalid password (password should be alphanumeric)";
					break;
				case 3:
					$errormsg = "Could not connect to database";
					break;
				case 4:
					$errormsg = "Could not register room";
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
		parent.history.pushState(null, null, "?menu=create");
	</script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:transparent;">
	<div id="createRoomContent" style="text-align:center">
		<form class="form" id="createRoomForm" name="createForm" action="<?php $_PHP_SELF ?>" method="post">
			<input type="hidden" name="roomemail">
			<input class="form-control" type="text" name="roomname" placeholder="Room Name" required><br>
			<input class="form-control" type="password" name="password" placeholder="Password (Optional)"><br>
			<input class="btn btn-primary" id="submitCreateButton" type="submit" value="Create">
		</form>
		<script type="text/javascript">
			document.getElementById('createRoomForm')['roomemail'].value = parent.document.getElementById('roomDataForm')['email'].value;
		</script>
	</div>
</body>
</html>
