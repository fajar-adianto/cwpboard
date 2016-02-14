<?php
	//pre-declared variables in menuCreateRoom.php ==> $dbhost, $dbuser, $dbpass, $dbname, $roomtbl, $error
	if(preg_match("/\W/", $_POST['roomname']))
	{
		$error = 1; //invalid room name (room name should be alphanumeric)
	}
	elseif(preg_match("/\W/", $_POST['password']))
	{
		$error = 2; //invalid password (password should be alphanumeric)
	}
	else
	{
		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		if(! $conn )
		{
			$error = 3; // could not connect to database;
		}
		else //Connected successfully
		{
			$roomemail = $_POST['roomemail'];
			$roomname = $_POST['roomname'];
			$password = $_POST['password'];
			mysql_select_db($dbname);
			
			//find available port for new room
			$roomport = 9300;
			$sql = "SELECT * FROM $roomtbl ORDER BY room_port";
			$retval = mysql_query( $sql, $conn );
			while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) 
			{ 
				if($row['room_port'] == $roomport)
				{
					$roomport++;
				}
			} //available port found and register the new room data into the database
			
			$sql = 	"INSERT INTO $roomtbl ".
					"(room_email,room_name,room_pass,room_port) ".
					"VALUES ".
					"('$roomemail','$roomname','$password','$roomport')";
			$retval = mysql_query( $sql, $conn );
			if(! $retval )
			{
				$error = 4; //could not register room;
			}
			else //Registered room to database successfully;
			{	
				$roomid = str_replace(array('.',',','@','-'),'',$roomemail) . $roomname;
				require("rstemplate.php");
			}
		}
		mysql_close($conn);
	}
?>