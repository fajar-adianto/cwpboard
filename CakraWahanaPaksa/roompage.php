<?php
	$status = $_POST['status'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$gender = $_POST['gender'];
	$roomemail = $_POST['roomemail'];
	$roomname = $_POST['roomname'];
	$roomport = $_POST['roomport'];
	$wsip = 'ws://127.0.0.1:';
	$wsaddress = $wsip . $roomport;
	
	$boardname = str_replace(array('.',',','@','-'),'',$roomemail) . $roomname;
	
	$ssefile = 'roomServer/server_' . $boardname . '.php';
	
	$mpurl = "mainpage.php";
?>

<!doctype html>
<html>
<head>
    <title><?php require("name.php"); ?> - <?php echo($roomname); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="roompage/lib/jquery/jquery-1.8.2.min.js"></script>
	<script src="roompage/fancywebsocket.js"></script>
	<script src="roompage/virtualBoardWorker.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">

	<script type="text/javascript">
	var sheet = document.createElement('style');
	sheet.innerHTML = "html, body{height: 100%}";
	document.head.appendChild(sheet);
	
	if(typeof(EventSource) !== "undefined") {
		var source = new EventSource("<?php echo("$ssefile") ?>");
		source.onmessage = function(event) {
			log( "server-sent event: " + event.data );
		};
	} else {
		log( "Sorry, your browser does not support" );
	}
	//source.close();
		
	window.onerror = function(msg, url, linenumber) {
		alert('roomPAGE\nError message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
		return true;
	}
		
	var Server;
	var container;
	var invokeVBW_whiteboard = function(msg) {};
	var invokeVBW_audienceList = function(msg) {};
	
	var alReady = false;
	var connReady = false;
	
	function VBWonMessage(msg) {
		if(msg[0] == "initRequest")
		{
			var status = "<?php echo("$status") ?>";
			var fname = "<?php echo("$fname") ?>";
			var lname = "<?php echo("$lname") ?>";
			var email = "<?php echo("$email") ?>";
			var gender = "<?php echo("$gender") ?>";
			var roomemail = "<?php echo("$roomemail") ?>";
			var roomname = "<?php echo("$roomname") ?>";
			var boardname = "<?php echo("$boardname") ?>";
			if (msg[1] == "WB")
			{
				invokeVBW_whiteboard(VBWpostMessage(["initData",status,fname,lname,email,gender,roomemail,roomname,boardname]));
			}
			else if (msg[1] == "AL")
			{
				invokeVBW_audienceList(VBWpostMessage(["initData",status,fname,lname,email,gender,roomemail,roomname,boardname]));
			}
		}
		
		if(msg == "serverCheckIn")
		{
			alReady = true;
			checkIn();
		}
		
		if(msg[0] == "sendData")
		{
			send( msg[1] );
		}
		
		if(msg[0] == "canvasRequest")
		{
			send( msg[1] );
			//var reqAddress = msg[1] + " ";
			//var canvasDataBinary = msg[2];
			//send( "canvasData " + reqAddress + canvasDataString );
			//send( canvasDataBinary );
		}
	}
	
	function checkIn() {
		if (alReady && connReady)
		{
			var status = "<?php echo("$status") ?>";
			var fname = "<?php echo("$fname") ?>";
			var lname = "<?php echo("$lname") ?>";
			var email = "<?php echo("$email") ?>";
			var gender = "<?php echo("$gender") ?>";
			send( "audData " + status + ",," + fname + "," + lname + "," + email  + "," + gender);
		}
	}
	
	function log( text ) {
		$log = $('#log');
		//Add text to log
		$log.append(($log.val()?"\n":'')+text);
		//Autoscroll
		$log[0].scrollTop = $log[0].scrollHeight - $log[0].clientHeight;
	}
	
	function send( text ) {
		Server.send( 'message', text );
	}
	
	$(document).ready(function() {
		$('#message').keypress(function(e) {
			if ( e.keyCode == 13 && this.value ) {
				log( 'You: ' + this.value );
				send( "chatMessage "+"<?php echo($fname) ?> <?php echo($lname) ?> : "+this.value );

				$(this).val('');
			}
		});
		
		log('Connecting...');
		//Server = new FancyWebSocket('ws://127.0.0.1:9300');
		Server = new FancyWebSocket('<?php echo("$wsaddress") ?>');

		//Let the user know we're connected
		Server.bind('open', function() {
			log( "Connected." );
			connReady = true;
			checkIn();
		});

		//OH NOES! Disconnection occurred.
		Server.bind('close', function( data ) {
			log( "Disconnected." );
		});

		//Log any messages sent from server
		Server.bind('message', function( payload ) {
			if(payload instanceof Blob)
			{
				//alert("blob!");
				invokeVBW_whiteboard(VBWpostMessage(["boardData", "canvasInit", payload]));
			}
			else
			{
				container = payload.split(" ");
				if((container[0] == "notification")||(container[0] == "chatMessage")) {
					log( payload.replace(container[0] + ' ','') );
				}
				else {
					invokeVBW_whiteboard(VBWpostMessage(container));
					invokeVBW_audienceList(VBWpostMessage(container));
				}
			}
			
		});

		Server.connect();
	});
	
	</script>
</head>
<body class="body">
	<div class="col-md-9 col-xs-12" style="height:98%"><iframe id="whiteboard" width="100%" height="100%" src="roomBoard.html"></iframe></div>
	<div class= "col-md-3 hidden-xs hidden-sm" style="height:100%">
		<iframe id="audienceList" style="min-height:40%" src="roomAudienceList.html"></iframe>
		<input style="width:100%; margin:20px auto" class="form-control" type='text' id='message' name='message' placeholder="type message here" />
		<textarea  style="resize:none; min-height:40%; width:100%" class="form-control" id='log' name='log' readonly='readonly'></textarea>
	</div>
	<form id="personalDataForm" name="personalForm" action="<?php echo "$mpurl" ?>" method="post">
		<input type="hidden" name="email" value="<?php echo("$email") ?>">
		<input type="hidden" name="fname" value="<?php echo("$fname") ?>">
		<input type="hidden" name="lname" value="<?php echo("$lname") ?>">
		<input type="hidden" name="gender" value="<?php echo("$gender") ?>">
	</form>

</body>
</html>
