<?
if(!$uu->id || $uu->id == 2 || $uu->id == 1)
{
?><div id="logo" class="centre marquee">
	<div id="display"onclick="location.href='https://twitter.com/k_dot_m';"></div>
	<div id="source" style="display: none"><? echo $tweet_text; ?></div>
	<script type='text/javascript'>
		animate = true;
		if(animate)
		{	
			start_delay = 0;
			tweet_delay = 100;
			document.onload = window.setTimeout
							(
								function()
								{
									initMessage("source", "display", true, tweet_delay);
								}, 
								start_delay
							);
		}
		else
			document.getElementById("display").innerHTML = "k.m";
	</script>
</div><?
}
else
{
?><div id="logo" class="centre">k.m</div><?
};
?>