/* image gallery */

function launch(i) {
	open_gallery();
	index = i; // store current image index
	// should a new object be made each time?
	window.mySwipe = new Swipe(document.getElementById('slider'), { 
			startSlide: i,
			callback: function(index, elem) {
				var h = (index+1).toString();
				h = h.concat("/");
				h = h.concat(images.length);
				document.getElementById("counter").innerHTML = h; 
			}
		});
	updateCounter();
}

function prev() {
	if(index == 0)
		index = images.length;
	index--;
	mySwipe.prev();
}

function next() {
	if(index == images.length-1)
		index = -1;
	index++;
	mySwipe.next();
}

function updateCounter() {
	var h = (index+1).toString();
	h = h.concat("/");
	h = h.concat(images.length);
	document.getElementById("counter").innerHTML = h;
}