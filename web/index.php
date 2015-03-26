<?php
$pageName = basename(__FILE__, ".php");
$showMenu = TRUE;
require_once('GLOBAL/head.php');

$rootid = $ids[0];
// SQL object plus media plus rootname
$sql = "SELECT 
			objects.id AS objectsId, 
			objects.name1, 
			objects.deck, 
			objects.body, 
			objects.notes, 
			objects.active, 
			objects.begin, 
			objects.end, 
			objects.rank as objectsRank, 
			(SELECT objects.name1 
			FROM objects 
			WHERE objects.id = $rootid) AS rootname, 
			media.id AS mediaId, 
			media.object AS mediaObject, 
			media.type, 
			media.caption, 
			media.active AS mediaActive, 
			media.rank 
		FROM objects 
		LEFT JOIN media 
		ON 
			objects.id = media.object 
			AND media.active = 1 
		WHERE 
			objects.id = $id 
			AND objects.active 
		ORDER BY media.rank;";

$result = MYSQL_QUERY($sql);
$myrow = MYSQL_FETCH_ARRAY($result);
$rootname = $myrow['rootname'];
$name = nl2br($myrow['name1']);
$body = nl2br($myrow['body']);
$notes = nl2br($myrow['notes']);
$begin = $myrow['begin'];
$end = $myrow['end'];
$images = array();
$i = 0;
$images[] = "";
$imageFiles[] ="";

$swipes[] = "";

// reset to row 0
mysql_data_seek($result, 0);

// collect images
while ($myrow  =  MYSQL_FETCH_ARRAY($result))
{
	if ($myrow['mediaActive'] != null)
	{
		$mediaFile = "MEDIA/";
		$mediaFile .= str_pad($myrow["mediaId"], 5, "0", STR_PAD_LEFT);
		$mediaFile .= "." . $myrow["type"];
		$mediaCaption = strip_tags($myrow["caption"]);
		$mediaStyle = "width: 100%;";
		$imageFiles[$i] = $mediaFile;

		// build random styles
		$randomPadding = rand(0, 15);
		$randomPadding *= 10;
		if ($isMobile)
			$randomWidth = rand(7, 9);
		else
			$randomWidth = rand(2, 5);
		$randomWidth *= 10;
		
		$randomFloat = (rand(0, 1) == 0) ? 'left' : 'right';
		$icStyle = 'width:'.$randomWidth.'%; ';
		$icStyle .= 'float:'.$randomFloat.'; ';
		if(!$isMobile)
			$icStyle .= 'padding-top:'.$randomPadding.'px; ';
		$icStyle .= 'margin: 40px;'; 

		$images[$i] .= "<div ";
		$images[$i] .= "id='image".$i."' ";
		$images[$i] .= "class='imageContainer' ";
		$images[$i] .= "onclick='launch(".$i.");' ";
		$images[$i] .= "style='".$icStyle."' ";
		if (!$isMobile)
		{
			//$images[$i] .= "data-start='transform: translate3d(0px, 0%, 0px);' ";
			//$images[$i] .= "data-end='transform: translate3d(0px, -100%, 0px);'";
		}
		$images[$i] .= ">";
		
		$images[$i] .= "<div class='image-hover'>";
		$images[$i] .= displayMedia($mediaFile, $mediaCaption, $mediaStyle);
		$images[$i] .= "</div>";
		$images[$i] .= "<div class = 'caption'>";
		$images[$i] .= ($i+1);	
		$images[$i] .= "</div>";
		$images[$i] .= "</div>";
	}
	$i++;
}

$imageData = "data-start='transform: translate3d(0px, 0%, 0px);' ";
$imageData .= "data-100p-start='transform: translate3d(0px, -10%, 0px);' ";
$imageData .= "data-end='transform: translate3d(0px, 0%, 0px);' ";

$bodyData = "data-start='transform: translate3d(0px, 0%, 0px);' ";
$bodyData .= "data-end='transform: translate3d(0px, 20%, 0px);' ";

// $bodyData = ""; // clear skrollr info for body

?>
<?php
// swipe functionality 
if($isMobile) 
{ 
?><div id="slider" class='swipe hidden gallery'>
	<div class='swipe-wrap'><?php
		for($i = 0; $i < count($imageFiles); $i++)
		{
		?><div>
			<img 
				src='MEDIA/blank.gif' 
				width='100%' 
				height='100%'
				data-src='<?php echo $imageFiles[$i]; ?>'
				style='background-image: url(<?php echo $imageFiles[$i] ?>);
						background-repeat: no-repeat;
						background-position: center;
						background-size: cover; '
			>
		</div><?php 
		} 
	?></div>
</div><?php 
} 
?><div id="nav-container" class='hidden gallery'>
	<div id="prev" onclick='prev();'>
		<img src="../MEDIA/la.png" style="width: 15px">
	</div>
	<div id="next" onclick='next()''>
		<img src="../MEDIA/ra.png" style="width: 15px">
	</div>
	<div id="ex" onclick='close_gallery();'>
		<img src="../MEDIA/ex.png" style="width: 15px">
	</div>
</div>
<div id="mainContainer" class="no-gallery">
	<div class="content" id="skrollr-body">
		<div class="body" <?php if(!$isMobile) echo $bodyData; ?>>
			<?php echo $body; ?>
		</div>
		
		<?php 
			if(count($images) > 0) 
			{
				$html = "";
				if($id != 22) // display logos differently
				{
				?><div class="images" <?php if(!$isMobile) echo $imageData; ?>><?
					for($i = 0; $i < count($images); $i++)
						$html .= $images[$i];
				?></div><?
				}	
				else
				{
					?><div class="logos"><?
					for($i = 0; $i < count($imageFiles); $i++)
					{
					?><!--div id="image<?php echo $i; ?>" class='logo-container'-->
						<img src="<?php echo $imageFiles[$i]; ?>">
					<!--/div--><?php
					}
					?></div><?
				}
				echo $html;	
		 	/* force div to have height */ ?>
			<div class="clearer"></div>
		<?php } ?>
		</div>
	</div>
</div>
<?php
require_once('GLOBAL/foot.php');
?>