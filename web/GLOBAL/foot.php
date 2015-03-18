		</div> <? /* end mainContainer */ ?>
		<script type="text/javascript" src="GLOBAL/skrollr.min.js"></script>
		<?php if (!$isMobile) { ?>
			<script type="text/javascript">
			skrollr.init({
				smoothScrolling: true,
				forceHeight: false,
				mobileDeceleration: 1
			});
			</script>
		<?php } ?>
	</body>
</html>