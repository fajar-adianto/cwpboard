onconnect = function(e) {
  var port = e.ports[0];
  port.onmessage = function(ev) {
	if(ev.data[0] == "initData")
	{
		port.postMessage(ev.data);
	}
	
	if(ev.data[0] == "board")
	{
		var p0 = ev.data[1] + " ";
		var p1 = ev.data[2] + " ";
		var p2 = ev.data[3] + " ";
		var p3 = ev.data[4] + " ";
		var p4 = ev.data[5] + " ";
		var p5 = ev.data[6] + " ";
		var p6 = ev.data[7];
			
		port.postMessage(["sendData", "boardData " + p0 + p1 + p2 + p3 + p4 + p5 + p6]);
	}
	
	if(ev.data[0] == "status")
	{
		var p0 = ev.data[1] + " ";
		var p1 = ev.data[2];
			
		port.postMessage(["sendData", "statChange " + p0 + p1]);
	}
	
	if(ev.data[0] == "boardData")
	{
		port.postMessage(ev.data);
	}
	
	if(ev.data[0] == "audList")
	{
		port.postMessage(ev.data);
	}
	
	if(ev.data[0] == "statUpdate")
	{
		port.postMessage(ev.data);
	}
	
	if(ev.data == "initRequest")
	{
		port.postMessage("initRequest");
	}
	
	if(ev.data == "serverCheckIn")
	{
		port.postMessage("serverCheckIn");
	}
  }
  port.start();  // not necessary since onmessage event handler is being used
}