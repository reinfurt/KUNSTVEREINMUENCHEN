		<?php if ($isMobile) { ?>
			<script type='text/javascript' src='GLOBAL/mobile.js'></script>
			<script type='text/javascript' src='GLOBAL/swipe.js'></script>
		<?php } else { ?>
			<script type='text/javascript' src='GLOBAL/desktop.js'></script>
			<script type="text/javascript" src="GLOBAL/skrollr.min.js"></script>
			<script type="text/javascript">
			skrollr.init({
				smoothScrolling: true,
				forceHeight: false
			});
			</script>
		<?php } ?>
		<script type="text/javascript">
		var images = <?php echo json_encode($imageFiles); ?>;
		</script>
	</body>
</html>