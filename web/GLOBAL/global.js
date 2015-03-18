function setbg(url) {
	// build bg style
	bs = "url('".concat(url);
	bs = bs.concat("')");
	bs = bs.concat(" no-repeat center center fixed");
	
	// set bg style
	g = document.getElementById("gallery");
	g.className = g.className.replace(/(?:^|\s)hidden(?!\S)/g , '')
	g.style.background = bs;
	g.style.backgroundSize = "cover";
	
	// hide mainContainer
	mc = document.getElementById("mainContainer");
	mc.style.display = "none";
	
	// hide logo
	l = document.getElementById("logo");
	l.style.display = "none";
}

function closeGallery() {
	g = document.getElementById("gallery");
	g.className += "hidden";
	
	// show mainContainer
	mc = document.getElementById("mainContainer");
	mc.style.display = "inherit";
	
	// show logo
	l = document.getElementById("logo");
	l.style.display = "inherit";
	
	// restore scroll position
	window.scrollTo(0, scroll);
	
	inGallery = false;
}