// animateMessage.js
//
// adapted from animatePunctuation.js
//
// source : {id}
// display : {id}
// animate : {true, false}	
// delay : ## [50]

// make onresize function
// + adjust max_length

// 	globals

// timer variables
var timeout;

// dom variables
var source;
var display;

var pointer;
var max_length = 10;
var logo_text = "k.m";
// var logo_text = " . ";
var logo_delay = 1500;
var logo_div; 


function init_tweet(source_id, display_id, animate, start_delay, tweet_delay)
{
	var source = document.getElementById(source_id);
	var display = document.getElementById(display_id);
	var message = buildMessage(source);
	
	pointer = 0;
	
	if(animate)
	{
		clearTimeout(timeout);
		timeout = null;
		if(!delay)
			delay = 50;
		// flash k.m / dots
		display.innerHTML = "";
		animateMessage(source, display, message, tweet_delay);
	}
	else
		display.appendChild(message);		
}

function flash_dot(display_id)
{
	var display = document.getElementById(display_id);
	
	display.innerHTML = "";
	
	for(var i = 0; i < logo_text.length; i++)
	{
		var temp = document.createElement("span");
		temp.textContent = logo_text.charAt(i);
		if(logo_text[i] === ".")
			temp.className = "blink-fast";
		display.appendChild(temp);
	}
}

function initMessage(sourceId, displayId, animate, delay) 
{
	var source = document.getElementById(sourceId); 
	var display = document.getElementById(displayId);
	var message = buildMessage(source);

	pointer = 0;
	max_length = Math.ceil(window.innerWidth/100);                     
	// max_length = 10;
	
	// window.addEventListener("resize", calculate_max_length);
	
	if(animate)
	{
		clearTimeout(timeout);
		timeout = null;
		if(!delay) 
			delay = 50;
		flash_dot(displayId);
// 		display.innerHTML = "";
// 		logo_div = document.getElementById("logo");
		window.setTimeout(function(){
			display.innerHTML = "";
			animateMessage(source, display, message, delay)
		}, 3000);
	} 
	else
		display.appendChild(message);
	// hideShowMessage('displayWrapper','displayControl','show');
}

function calculate_max_length()
{
	max_length = Math.ceil(window.innerWidth/100);
	console.log(max_length);
}
function buildMessage(root) 
{
	var next;
	var node = root.firstChild;
	var message = document.createDocumentFragment();
	
	do
	{      
		next = node.nextSibling;
		if (node.nodeType === 1) 
		{
			message.appendChild(node.cloneNode(true));
		}
		else if (node.nodeType === 3)
		{
			var text = node.textContent;
			for (i = 0; i < text.length; i++)
			{
				var temp = document.createElement("span");
				temp.textContent = text[i];
				message.appendChild(temp);
			}
		}
	} 
	while(node = next);
	
	return message;
}

function animateMessage(source, display, message, delay) 
{
	if(pointer < message.childNodes.length) 
	{
		display.appendChild(message.childNodes[pointer].cloneNode(true));
		pointer++;
		if(pointer > max_length)
			display.childNodes[0].parentNode.removeChild(display.childNodes[0]);
		timeout = setTimeout(function(){animateMessage(source, display, message, delay);}, delay);
	}
	// this is so dangerous
	else if(max_length > 0)
	{
		display.childNodes[0].parentNode.removeChild(display.childNodes[0]);
		timeout = setTimeout(function(){animateMessage(source, display, message, delay);}, delay);
		max_length--;
	}
	else 
	{
		// repeat the process
		window.setTimeout(function(){initMessage("source", "display", true, delay);}, 1000);
	}
}

function startStopAnimateMessage() 
{
	if (timeout == null) 
	{				
		initMessage("animateMessage", "target", true, delay);			
		return true;
	} 
	else 
	{
		clearTimeout(timeout);
		timeout = null;
		return false;
	}
}


function hideShowMessage(displayId,controlId,forceAction) 
{
	var display = document.getElementById(displayId);
	var control = document.getElementById(controlId);

	if ((display.style.overflow != "hidden") || forceAction == "hide") 
	{
		display.style.overflow = "hidden";
		display.style.height = "20px";
		control.textContent = "+";
	} 
	else if ((display.style.overflow == "hidden") || forceAction == "show")
	{
		display.style.overflow = "auto";
		display.style.height = "auto";
		control.textContent = "×";
	}
}

function setCookie(name)
{
	if (getCookie(name) == "")
	{
		document.cookie=name+"=true";
		return true;
	} 
	else
		return false;
}

function expireCookie(name)
{
	if (getCookie(name) != "") 
	{
		document.cookie = name+"=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
		return true;
	} 
	else 
		return false;
}

function getCookie(name)
{
	var cname = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++)
	{
		var c = ca[i];
		while (c.charAt(0)==' ') 
			c = c.substring(1);
		if (c.indexOf(cname) != -1) 
			return c.substring(cname.length,c.length);
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
