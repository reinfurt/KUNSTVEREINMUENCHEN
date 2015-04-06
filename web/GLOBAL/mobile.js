function launch(i) {
	open_gallery();
	// should a new object be made each time?
	window.mySwipe = new Swipe(document.getElementById('slider'), { 
			startSlide: i
		});
}

function prev() {
	mySwipe.prev();
}

function next() {
	mySwipe.next();
}