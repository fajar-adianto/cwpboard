<?php
// prevent the server from timing out
set_time_limit(0);

// include the web sockets server script (the server is started at the far bottom of this file)
require 'class.PHPWebSocket.php';

function wsUpdateAudList($Server) {
	//forms the list in defined format
	$audienceList = 'audList';
	foreach( $Server->wsAudienceList as $audience )
		$audienceList = $audienceList.' '.$audience['status'].','.$audience['data'];
	
	return $audienceList;
}

// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	// check if message length is 0
	if ($messageLength == 0) {
		$Server->wsClose($clientID);
		return;
	}

	// process message according to its "tag"
	$msg = explode(" ", $message);
	switch($msg[0]) {
		case "audData" :
			//update audience list
			$Server->wsAudienceList[$clientID]['status'] = explode(",,", $msg[1])[0];
			$Server->wsAudienceList[$clientID]['data'] = $clientID . "," . explode(",,", $msg[1])[1];
			//forms the list in defined format
			$audienceList = wsUpdateAudList($Server);
			//send the new list to all clients
			foreach ( $Server->wsClients as $id => $client )
				$Server->wsSend($id, $audienceList);
			//Send a join notice to everyone but the person who joined
			$audData = explode(",",$Server->wsAudienceList[$clientID]['data']);
			foreach ( $Server->wsClients as $id => $client )
				if ( $id != $clientID )
					$Server->wsSend($id, "notification $audData[1] $audData[2] has joined the room.");
			break;
		case "boardData" :
			foreach ( $Server->wsClients as $id => $client )
				$Server->wsSend($id, $message);
			break;
		case "statChange" :
			//for security reason, check client status that request the status change
			if(($Server->wsAudienceList[$clientID]['status'] == 'admin')||($Server->wsAudienceList[$clientID]['status'] == 'coadmin')) {
				//update audience list
				$Server->wsAudienceList[$msg[1]]['status'] = $msg[2];
				//send specific message about status update to the client
				$Server->wsSend($msg[1], "statUpdate ".$msg[2]);
				//forms the list in defined format
				$audienceList = wsUpdateAudList($Server);
				//send the new list to all clients
				foreach ( $Server->wsClients as $id => $client )
					$Server->wsSend($id, $audienceList);
			}
			break;
		case "chatMessage" :
			//send chat mesage to all clients but the one who send it
			foreach ( $Server->wsClients as $id => $client )
				if ( $id != $clientID )
					$Server->wsSend($id, $message);
			break;
		case "canvasInit" :
			$imageData = $Server->wsCanvasData[0];
			$Server->wsSend($clientID, 'boardData canvasInit ' . $imageData);
			break;
		case "updateCanvasData" :
			foreach ( $Server->wsClients as $id => $client )
			{
				$Server->wsSend($id, "canvasRequest");
				break;
			}
			break;
		case "canvasImage" :
			$Server->wsCanvasData[0] = $msg[1];
	}
	
}

// when a client connects
function wsOnOpen($clientID)
{
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	
	$Server->log( "$ip ($clientID) has connected." );
	
	//foreach ( $Server->wsClients as $id => $client )
		//if ( $id != $clientID )
			//$Server->wsSend($id, "room size : ".sizeof($Server->wsClients));
}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
	global $Server;
	global $dbhost;
	global $dbuser;
	global $dbpass;
	global $dbname;
	global $roomtbl;
	global $roomport;
	global $roomid;
	
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	$audData = explode(",",$Server->wsAudienceList[$clientID]['data']);
	
	$Server->log( "$ip ($clientID) has disconnected." );
	
	//remove that client from audience list and tell everyone
	unset($Server->wsAudienceList[$clientID]);
	//forms the list in defined format
		$audienceList = wsUpdateAudList($Server);
	//send the new list to all clients
	foreach ( $Server->wsClients as $id => $client )
		$Server->wsSend($id, $audienceList);
		
	//Send a user left notice to everyone in the room
	if ($audData[1] && $audData[2])
	{
		foreach ( $Server->wsClients as $id => $client )
			$Server->wsSend($id, "notification $audData[1] $audData[2] has left the room." );
	}
	
	// if that was the last client in the room, then close the server
	if ( sizeof($Server->wsClients) == 1 ) {
		unlink('server_'.$roomid.'.php');
		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db($dbname);
		$sql = "DELETE FROM $roomtbl WHERE room_port='$roomport'";
		$retval = mysql_query( $sql, $conn );
		mysql_free_result($retval);
		mysql_close($conn);
		
		$Server->unbind();
		$Server->wsStopServer();
		exit();
	}
}

// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
// for other computers to connect, you will probably need to change this to your LAN IP or external IP,
// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))
// pre-declared variables in file which this file is required ==> $roomport
$Server->wsStartServer('127.0.0.1', $roomport);
?>