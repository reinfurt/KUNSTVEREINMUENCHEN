var scroll;
var index;
var inGallery = false;

function logohover() {
// 	l = document.getElementById("logo");
// 	l.style.background = "red";

// 	var img = document.createElement("img");
// 	img.src = "http://dev.kunstverein-muenchen.de/MEDIA/00673.jpg";
// 	l = document.getElementById("fenster");
// 	img.id = "fenster-img";
// 	l.appendChild(img);
}

function logoreturn() {
// 	document.getElementById("fenster-img").remove();
}

// desktop + mobile
function open_gallery() {
	// store scroll position
	scroll = document.body.scrollTop;
	
	h = document.getElementsByClassName("no-gallery");
	for (i = 0; i < h.length; i++)
		hide(h[i]);
	s = document.getElementsByClassName("gallery");
	for (i = 0; i < s.length; i++)
		show(s[i]);
}

// desktop + mobile
function close_gallery() {
	h = document.getElementsByClassName("gallery");
	for (i = 0; i < h.length; i++)
		hide(h[i]);
	s = document.getElementsByClassName("no-gallery");
	for (i = 0; i < s.length; i++)
		show(s[i]);
		
	// restore scroll position
	window.scrollTo(0, scroll);
	
	if(typeof mySwipe !== 'undefined')
	{
		mySwipe.kill();
		console.log(mySwipe);
	}
	
	inslider = false;
}

/* utilities */

function hide(e) {
	e.className += " hidden";	
}

function show(e) {
	e.className = e.className.replace(/(?:^|\s)hidden(?!\S)/g , '');
}