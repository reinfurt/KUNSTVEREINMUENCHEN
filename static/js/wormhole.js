function wormhole_ajax()
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if(xhttp.readyState == 4 && xhttp.status == 200) {
			document.getElementById("wormhole-container").innerHTML = xhttp.responseText;
		}
	};
	xhttp.open("GET", "views/wormhole-ajax.php", true);
	xhttp.send();
}