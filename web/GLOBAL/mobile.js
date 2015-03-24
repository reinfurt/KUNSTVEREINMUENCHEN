function launch(i) {
	open_gallery();
	
	window.mySwipe = Swipe(document.getElementById('slider'));
	mySwipe.slide(i, 0);
}

function prev() {
	mySwipe.prev();
}

function next() {
	mySwipe.next();
}