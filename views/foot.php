<?
	if($is_mobile)
	{
	?><script type="text/javascript" src='/static/js/swipe.js'></script>
	<script type="text/javascript" src='/static/js/mobile.js'></script><?
	}
	else
	{
	?><script type="text/javascript" src="/static/js/desktop.js"></script><?
		if($run_parallax)
		{
		?><script type="text/javascript" src="/static/js/skrollr.min.js"></script>
		<script type="text/javascript">
			window.onload = function(){startP()};
			window.addEventListener("resize", refreshP);
		</script><?
		}
		else
		{
		?><script type = "text/javascript">
			// un-fix the menu if it's taller than the viewport height
			var header = document.getElementById("header");
			hh = header.scrollHeight;
			console.log(hh);
			vh = Math.max(	document.documentElement.clientHeight, 
							window.innerHeight || 0);
			console.log(vh);
			if(hh > vh) {
				header.style.position = "relative";
				document.getElementById("fixed-container").style.position = "absolute";
			}				
			window.onload = function(){startNP()};
		</script><?php
		}
		if($ds)
		{
		?><script type="text/javascript" src ="/static/js/ds.js"></script>
		<script type="text/javascript">window.onload = radioInit();</script><?
		}
	}
	?></body>
</html>