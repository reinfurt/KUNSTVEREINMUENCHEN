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