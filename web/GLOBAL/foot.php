	<script type="text/javascript">
			var images = <?php echo json_encode($imageFiles); ?>;
	</script>
	<?php 
		if ($isMobile) 
		{ 
		?><script type='text/javascript' src='GLOBAL/js/swipe.js'></script>
		<script type='text/javascript' src='GLOBAL/js/mobile.js'></script><?php 
		} 
		else 
		{ 
		?><script type='text/javascript' src='GLOBAL/js/desktop.js'></script><?php
			if($runParallax)
			{
			?><script type="text/javascript" src="GLOBAL/js/skrollr.min.js"></script>
			<script type="text/javascript">
				window.onload = function(){startP()};
				window.addEventListener("resize", refreshP);
			</script><?php 
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
					document.getElementById("fixed-container").style.position = "relative";
				}				
				window.onload = function(){startNP()};
			</script><?php
			}
			if($ds) 
			{
			?><script type="text/javascript" src = "GLOBAL/js/ds.js"></script>
			<script type="text/javascript">window.onload = radioInit();</script><? 
			}
		}
		if($pageName == "member" || $id == "412" || $id == "421" || ($isMobile && $id != "0") || $pageName == "press")
		{
		// hide the logo on various pages
		?><script type='text/javascript'>
			logo = document.getElementById("logo");
			logo.innerHTML = "";
			// window.setTimeout(function(){logo.className = "blink-fade";}, 5000);
		</script><?
		}
		?><script type='text/javascript'>
			animate = true;
			if(animate)
			{	
				start_delay = 0;
				tweet_delay = 100;
				document.onload = window.setTimeout(function(){initMessage("source", "display", true, tweet_delay);}, start_delay);
			}
			else
			{
				document.getElementById("display").innerHTML = "k.m";
			}
		</script><?
	?></body>
</html>