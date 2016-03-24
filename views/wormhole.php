<?
if(empty($wormhole))
	$wormhole = 1;

if($wormhole == 1 || $wormhole == 3)
{
?>
<script type="text/javascript" src="/static/js/wormhole.js"></script>
<div id="wormhole-container"></div>
<script type="text/javascript">
	wormhole_ajax();
	var w = setInterval(function(){ wormhole_ajax(); }, 15000);
</script><?
}