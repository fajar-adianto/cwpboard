<?php
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'cwp';
	$usertbl = 'userlist_tbl';
	
	$mpurl = "mainpage.php";
	$error = 0;
	
	$fname = "";
	$lname = "";
	$email = "";
	$gender = "";
	
	if(isset($_POST['action']))
	{
		if($_POST['action'] == 'login')
		{
			require("fplisubmission.php");
		}
		elseif($_POST['action'] == 'signup')
		{
			require("fpsusubmission.php");
		}
		
		if(!$error)
		{
			echo("<form id=\"invform\" action=\"$mpurl\" method=\"post\">");
			echo("<input type=\"hidden\" name=\"email\" value=\"$email\">");
			echo("<input type=\"hidden\" name=\"fname\" value=\"$fname\">");
			echo("<input type=\"hidden\" name=\"lname\" value=\"$lname\">");
			echo("<input type=\"hidden\" name=\"gender\" value=\"$gender\">");
			echo("<input type=\"submit\" name=\"submitbutton\" value=\"click me if not redirected\">");
			echo('</form>');
			echo("<script type=\"text/javascript\">document.getElementById('invform').submit();</script>");
		}
	}
?>
