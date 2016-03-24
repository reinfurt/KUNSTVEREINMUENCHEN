<?
if(empty($wormhole))
	$wormhole = 1;

if($wormhole == 1 || $wormhole == 3)
{
?>
<script type="text/javascript" src="/static/js/wormhole.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="/static/css/cube.css">
<div id="wormhole-container"></div>
<script type="text/javascript">
	var delay = 20000;
	wormhole_ajax();
	var w = setInterval(function(){ wormhole_ajax(); }, delay);
</script><?
}