<?
use \Michelf\Markdown; 
$object = $oo->get($uu->id);
$body = Markdown::defaultTransform(nl2br($object['body']));
$media_arr = $oo->media($uu->id);
$image_files = array();
for($i = 0; $i < count($media_arr); $i++)
	$image_files[] = m_url($media_arr[$i]);

if($is_mobile) 
{ 
?><div id="slider" class='swipe hidden gallery'>
	<div class='swipe-wrap'><?php
		for($i = 0; $i < count($image_files); $i++)
		{
		?><div>
			<img 
				src='/media/gif/blank.gif' 
				width='100%' 
				height='100%'
				data-src='<?php echo $image_files[$i]; ?>'
				style='background-image: url(<?php echo $image_files[$i] ?>);
						background-repeat: no-repeat;
						background-position: center;
						background-size: cover; '
			>
		</div><?
		} 
	?></div>
</div><?
}
// $image_files = array();
?><div id="nav-container" class='hidden gallery'>
	<div id="prev" onclick='prev();'>
		<img class="clear" src="/media/png/la.png" style="width: 15px">
	</div>
	<div id="next" onclick='next()''>
		<img class="clear" src="/media/png/ra.png" style="width: 15px">
	</div>
	<div id="ex" onclick='close_gallery();'>
		<img class="clear" src="/media/png/ex.png" style="width: 15px">
	</div>
	<div id="counter"></div>
</div>
<div id="main-container" class="no-gallery">
	<div class="content">
		<div class="text-container">
			<div class="text"><?
				echo $body;
			?></div>
		</div>
		<div class="images"><?
		for($i = 0; $i < count($image_files); $i++)
		{
			$img_url = $image_files[$i];
			$padding = rand(0, 150)*10;
			$width = rand(2, 5)*10;
			$float = (rand(0, 1) == 0) ? 'left' : 'right';
			$margin = "40px";
			$style = "width: $width%; float: $float; padding-top: $padding; margin: $margin";
			?><div 
				id="image<? echo $i; ?>" 
				class="imageContainer"
				onclick="launch(<? echo $i; ?>);"
				style="<? echo $style; ?>"
			><?
				// need to add exception 
				?><div class="image-hover">
					<img src="<? echo $img_url; ?>">
				</div>
			<div class="caption"><?
				echo $i+1;
			?></div>
			</div><?
		}
		?></div>
		<script type="text/javascript">
			var images = <? echo json_encode($image_files); ?>;
		</script>
		<script type='text/javascript' src='/static/js/desktop.js'></script>
	</div>
</div><?

?>