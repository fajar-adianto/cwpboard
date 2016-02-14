<?php
	//pre-declared variables in fpsubmission.php ==> $dbhost, $dbuser, $dbpass, $dbname, $usertbl, $error, $fname, $lname, $email, $gender
	$em = $_POST['email'];
	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	if(! $conn )
	{
		$error = 1;
	}
	else   //else Connected to database successfully
	{
		mysql_select_db($dbname);
		
		$sql =	'SELECT * ' . "FROM $usertbl " . "WHERE user_email='$em'";
				
		$retval = mysql_query( $sql, $conn );
		$row = mysql_fetch_array($retval, MYSQL_ASSOC);
		
		if(! $row['user_password'] )
		{
			$error = 2;
		}
		else
		{
			if($_POST['pw'] == $row['user_password'])
			{
				$fname = $row['user_fname'];
				$lname = $row['user_lname'];
				$email = $row['user_email'];
				$gender = $row['user_gender'];
			}
			else
			{
				$error = 3;
			}
		}
	}
	mysql_free_result($retval);
	mysql_close($conn);
?>