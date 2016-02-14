<?php
	//pre-declared variables in menuJoinRoom.php ==> $dbhost, $dbuser, $dbpass, $dbname, $roomtbl, $error
	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	if(! $conn )
	{
		$error = 1; //Could not connect to database
	}
	else   //Connected to database successfully
	{
		$roomemail = $_POST['roomemail'];
		$roomname = $_POST['roomname'];
		mysql_select_db($dbname);
		
		$sql =	'SELECT * ' . "FROM $roomtbl " . "WHERE room_email='$roomemail' AND room_name='$roomname'";		
		$retval = mysql_query( $sql, $conn );
		$row = mysql_fetch_array($retval, MYSQL_ASSOC);
		
		if(! $retval )
		{
			$error = 2; //Search failed
		}
		else // Room found
		{
			if($_POST['password'] == $row['room_pass']) //room exist and valid password entered
			{	
				$roomport = $row['room_port'];
				$status = 'passive';
			}
			else
			{
				$error = 3; //Invalid password for selected room or room does not exist
			}
		}
		mysql_free_result($retval);
	}
	mysql_close($conn);
?>