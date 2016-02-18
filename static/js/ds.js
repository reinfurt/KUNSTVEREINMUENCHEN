// radioControl

// radioOnOff
// radioVolumeUpDown
// add video position cookie to continuously stream the radio
// video preload?
	
function radioInit()
{
	var radio = document.getElementById("radio");
	var controls = document.getElementById("controls");
	var audio = getCookie("audioCookie");

	if (audio == "off") 
		radio.pause();
	else 
		radio.play();
}

function radioOnOff()
{
	var radio = document.getElementById("radio");
	var controls = document.getElementById("controls");
	var audio = getCookie("audioCookie");
 		
 	if (audio == "off") 
 	{
		radio.play();
		document.cookie="audioCookie=on";
	} 
	else 
	{
		radio.pause();	
		document.cookie="audioCookie=off";
	}	
}

function getCookie(name) 
{
	var cname = name + "=";
	var ca = document.cookie.split(';');

	for(var i = 0; i < ca.length; i++) 
	{
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1);
		if (c.indexOf(cname) != -1) return c.substring(cname.length,c.length);
	}
	return "";
}

function checkCookie(name) 
{
	if (getCookie(name) != "")
		return true;

	else
		return false;
}