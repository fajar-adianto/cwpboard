<?php
	//pre-declared variables in fpsubmission.php ==> $dbhost, $dbuser, $dbpass, $dbname, $usertbl, $error, $fname, $lname, $email, $gender
	
	if(preg_match("/\s/", $_POST['email']))
	{
		$error = 4;
	}
	elseif(preg_match("/\W/", $_POST['pw']))
	{
		$error = 5;
	}
	elseif((preg_match("/[^a-zA-Z'-]/", $_POST['fname'])) || (preg_match("/[^a-zA-Z'-]/", $_POST['lname'])))
	{
		$error = 6;
	}
	else
	{
		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		if(! $conn )
		{
			$error = 1;
		}
		else   //else Connected to database successfully
		{
			$em = $_POST['email'];
			$pw = $_POST['pw'];
			$fn = $_POST['fname'];
			$ln = $_POST['lname'];
			$gen = $_POST['gender'];
			mysql_select_db($dbname);
			$sql = 	"INSERT INTO $usertbl ".
					"(user_email,user_password,user_fname,user_lname,user_gender) ".
					"VALUES ".
					"('$em','$pw','$fn','$ln','$gen')";
			$retval = mysql_query( $sql, $conn );
			if(! $retval )
			{
				$error = 7;
			}
			else
			{
				$sql =	'SELECT * ' . "FROM $usertbl " . "WHERE user_email='$em'";
				
				$retval = mysql_query( $sql, $conn );
				$row = mysql_fetch_array($retval, MYSQL_ASSOC);
				
				$fname = $row['user_fname'];
				$lname = $row['user_lname'];
				$email = $row['user_email'];
				$gender = $row['user_gender'];
			}

		}
		mysql_free_result($retval);
		mysql_close($conn);
	}
	
?>