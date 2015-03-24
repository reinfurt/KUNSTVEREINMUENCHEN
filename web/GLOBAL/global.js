var scroll;
var index;
var inGallery = false;

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
	
	inslider = false;
}

/* utilities */

function hide(e) {
	e.className += " hidden";	
}

function show(e) {
	e.className = e.className.replace(/(?:^|\s)hidden(?!\S)/g , '');
}