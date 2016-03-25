/* image gallery */
var scroll;
var index;
var inGallery = false;

function open_gallery() {
	// store scroll position
	scroll = document.body.scrollTop;
	
	h = document.getElementsByClassName("no-gallery");
	for (i = 0; i < h.length; i++)
		hide(h[i]);
	s = document.getElementsByClassName("gallery");
	for (i = 0; i < s.length; i++)
		show(s[i]);
	
	/*document.ontouchmove = function(event){
    	event.preventDefault();
	}*/
}

function close_gallery() {
	h = document.getElementsByClassName("gallery");
	for (i = 0; i < h.length; i++)
		hide(h[i]);
	
	s = document.getElementsByClassName("no-gallery");
	for (i = 0; i < s.length; i++)
		show(s[i]);
		
	// restore scroll position
	window.scrollTo(0, scroll);
	
	if(typeof mySwipe !== 'undefined') {
		mySwipe.kill();
		console.log(mySwipe);
		// document.ontouchmove = function(e){ return true; }
	}
	inslider = false;
}

/* utilities */

function hide(e) {
	// e.className += " hidden";	
	e.classList.add("hidden");
}

function show(e) {
	// e.className = e.className.replace(/(?:^|\s)hidden(?!\S)/g , '');
	e.classList.remove("hidden");
}

/* can i get rid of these functions? */
function logohover() {
}

function logoreturn() {
}