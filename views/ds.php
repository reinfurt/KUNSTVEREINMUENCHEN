<script type="text/javascript" src="/static/js/ds.js"></script>
<div class="no-gallery"><?
	if(!$is_mobile)
	{
	?><a id="controls" class="centre" href="javascript:radioOnOff();">
		<img class="clear" src="/media/gif/blank.gif" width="480" height="360">
	</a>
	<video 
		id="radio" class="centre" 
		width="480" height="360" 
		poster="/media/gif/blank.gif" autoplay=1 loop=1
	>
		<source src="/media/mp4/README-web-<? echo $lang; ?>.mp4" type="video/mp4">
	</video><?
	}
	else
	{
	?><div id="radio">
		<img src="/media/gif/asterisk.gif">
	</div><?
	}
?></div>