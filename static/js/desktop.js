
// image gallery

function launch(i) {
	open_gallery();
	setbg(images[i]); // display image
	index = i; // store current image index
	inGallery = true;
	updateCounter();
}

function prev() {
	if(index == 0)
		index = images.length;
	index--;
	setbg(images[index]);
	updateCounter();
}

function next() {
	if(index == images.length-1)
		index = -1;
	index++;
	setbg(images[index]);
	updateCounter();
}

function updateCounter() {
	var h = (index+1).toString();
	h = h.concat("/");
	h = h.concat(images.length);
	document.getElementById("counter").innerHTML = h;
}

// image gallery helpers
function setbg(url) {
	// build bg style
	// bi = "url('".concat(url).concat("')");
	
	// template string -- this is probs totally unsafe in old browsers, BUT
	bi = `url('${url}')`;
	bi = "url('"+ url + "')";
	
	// set bg style
	g = document.getElementById("nav-container");
	g.style.backgroundImage = bi;
	g.style.backgroundRepeat = "no-repeat";
	g.style.backgroundPostion = "center center";
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

// parallax
function parallaxSetup() {
	var html = document.documentElement;
	var text = document.getElementsByClassName("text")[0];
	var vh = html.offsetHeight; // viewport height
	var th = text.scrollHeight; // text height
	if(th > vh)
		o = Math.abs(th-vh).toString();
	else
		o = (th).toString();
	
	text.setAttribute("data-start", "transform: translateY(0px);");
	text.setAttribute("data-end", "transform: translateY(-"+o+"px);");
	text.style.position = "fixed";
	text.style.top = "0px";
	text.style.right = "0px";
}

// start function for pages with parallax
function startP() {
	parallaxSetup();
	s = skrollr.init({
		smoothScrolling: true,
		forceHeight: false,
		skrollrBody: 'main-container',
	});
	document.body.style.overflow = "inherit"; // unlock scrolling
}

function refreshP() {
	parallaxSetup();
	if(!inGallery) {
		s.refresh();
	}
}

// start function for no parallax
function startNP() {
	var body = document.body;
	body.style.overflow = "inherit";
}