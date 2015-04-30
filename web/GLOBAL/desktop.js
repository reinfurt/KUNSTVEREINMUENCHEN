function launch(i) {
	open_gallery();
	
	setbg(images[i]); // display image
	index = i; // store current image index
	inGallery = true;
}

function prev() {
	if(index == 0)
		index = images.length;
	index--;
	setbg(images[index]);
}

function next() {
	if(index == images.length-1)
		index = -1;
	index++;
	setbg(images[index]);
}

function setbg(url) {
	// build bg style
	bs = "url('/".concat(url);
	bs = bs.concat("')");
	bs = bs.concat(" no-repeat center center fixed");
	
	// set bg style
	g = document.getElementById("nav-container");
	g.style.background = bs;
	g.style.backgroundSize = "cover";
}

// use arrow keys for navigation within the gallery
document.onkeydown = function(e) {
	if(inGallery) {
		e = e || window.event;
		switch(e.which || e.keyCode) {
			case 37: // left
				prev();
			break;
			case 39: // right
				next();
			break;
			case 27: // esc
				close_gallery();
			break;
			default: return; // exit this handler for other keys
		}
		e.preventDefault();
	}
}

// start function for pages with parallax
function startP() {
	var body = document.body, html = document.documentElement;
// 	var ph = Math.max(	body.scrollHeight, 
// 						body.offsetHeight, 
//                      html.clientHeight, 
//                      html.scrollHeight; 
//                      html.offsetHeight );
	var text = document.getElementsByClassName("text")[0];
	var vh = Math.max(	document.documentElement.clientHeight, 
						window.innerHeight || 0);
	var th = text.scrollHeight;
	if(th > vh/2)
		o = Math.abs(th-vh).toString();
	else
		o = (th).toString();
	
	
	text.setAttribute("data-start", "transform: translateY(0px);");
	text.setAttribute("data-end", "transform: translateY(-"+o+"px);");
	
	text.style.position = "fixed";
	text.style.top = "0px";
	text.style.right = "0px";
	
	skrollr.init({
		smoothScrolling: true,
		forceHeight: false,
		skrollrBody: 'main-container',
	});
	body.style.overflow = "inherit";
}

// start function for no parallax
function startNP() {
	// var text = document.getElementsByClassName("text")[0];
	var body = document.body;
	body.style.overflow = "inherit";
	setTimeout(startBlink(), 10000);
// 	text.style.position = "relative";
// 	text.style.float = "right";
}

function startBlink() {
	return function(){
	var logo = document.getElementById("logo");
	logo.className = logo.className + " blink-slow";
	}
}