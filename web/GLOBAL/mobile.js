//window.mySwipe = Swipe(document.getElementById('slider'));

function launch(i) {
	open_gallery();
	if(typeof mySwipe == 'undefined')
	{
		window.mySwipe = Swipe(document.getElementById('slider'));
		// if(i > 0)
// 			mySwipe.slide(i, 0);
		console.log(mySwipe);
	}
// 	window.mySwipe = Swipe(document.getElementById('slider'), {
// 	startSlide: i,});
//	mySwipe.slide(i, 0);
}

function prev() {
	mySwipe.prev();
}

function next() {
	mySwipe.next();
}