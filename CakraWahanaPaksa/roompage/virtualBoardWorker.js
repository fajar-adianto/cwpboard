function VBWpostMessage(msg)
{
	var outputMessage = "null";
	
	if(msg == "serverCheckIn")
	{
		outputMessage = "serverCheckIn";
	}
	else
	{
		switch(msg[0])
		{
			case "board" :
				var p0 = msg[1] + " ";
				var p1 = msg[2] + " ";
				var p2 = msg[3] + " ";
				var p3 = msg[4] + " ";
				var p4 = msg[5] + " ";
				var p5 = msg[6] + " ";
				var p6 = msg[7];
					
				outputMessage = ["sendData", "boardData " + p0 + p1 + p2 + p3 + p4 + p5 + p6];
			break;
			case "status" :
				var p0 = msg[1] + " ";
				var p1 = msg[2];
					
				outputMessage = ["sendData", "statChange " + p0 + p1];
			break;
			case "canvasData" :
				var p0 = msg[1] + " ";
				var p1 = msg[2];
				outputMessage == ["canvasInit", "canvasData " + p0 + p1];
			break;
			default :
				outputMessage = msg;
		}
	}
	
	return outputMessage;
}