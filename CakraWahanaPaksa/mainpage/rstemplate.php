<?php
//pre-declared variables in mpcrsubmission.php ==> $roomid, $roomport 

	$filename = "roomServer/server_" . $roomid . ".php";
	$file = fopen( $filename, "w" );
   
	if( $file == false )
	{
		echo ( "Error in opening new file" );
		exit();
	}
	
	$content =
	"<?php \n\n".
	
	"\$dbhost = 'localhost';\n".
	"\$dbuser = 'root';\n".
	"\$dbpass = '';\n".
	"\$dbname = 'collaboard';\n".
	"\$roomtbl = 'roomlist_tbl';\n\n".
	
	"\$roomid = '$roomid';\n".
	"\$roomport = $roomport; \n".
	"require(\"server.php\"); \n\n".
	
	'?>';
	
	fwrite( $file, $content );
	fclose( $file );
?>
	