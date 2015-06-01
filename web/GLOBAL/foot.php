	<script type="text/javascript">
			var images = <?php echo json_encode($imageFiles); ?>;
	</script>
	<?php 
		if ($isMobile) 
		{ 
		?><script type='text/javascript' src='GLOBAL/swipe.js'></script>
		<script type='text/javascript' src='GLOBAL/mobile.js'></script><?php 
		} 
		else 
		{ 
		?><script type='text/javascript' src='GLOBAL/desktop.js'></script><?php
			if($runParallax)
			{
			?><script type="text/javascript" src="GLOBAL/skrollr.min.js"></script>
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
		}
		if($pageName == "member" || $id == "412" || $id == "421" || $isMobile || $pageName == "press")
		{
		?><script type='text/javascript'>
			logo = document.getElementById("logo");
			logo.className = "blink-fade";
		</script><?
		}
	?></body>
</html>