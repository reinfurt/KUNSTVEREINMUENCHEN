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
				document.body.onload = function(){
					start()};
				function start() {
					skrollr.init({
						smoothScrolling: true,
						forceHeight: false,
						skrollrBody: 'main-container',
					});
				}
				skrollr.refresh();
			</script><?php 
			}
		}
	?></body>
</html>