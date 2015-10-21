<?php
$pageName = basename(__FILE__, ".php");
$showMenu = TRUE;
require_once('GLOBAL/head.php');

$rootid = $ids[0];
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
$captions[] = "";

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
		$captions[$i] = $mediaCaption;

		$images[$i] .= "<div ";
		$images[$i] .= "id='image".$i."' ";
		$images[$i] .= "class='press-image' ";
// 		$images[$i] .= "onclick='launch(".$i.");' ";
// 		$images[$i] .= "style='".$icStyle."' ";
		// individual image styles
		if (!$isMobile)
		{
			//$images[$i] .= "data-start='transform: translateY(0%);' ";
			//$images[$i] .= "data-end='transform: translateY(".(100 - $randomWidth)."%);' ";
		}
		$images[$i] .= ">";
		
		$images[$i] .= "<a href='".$mediaFile."' target='_blank'>";
		
		// make exceptions for pdfs and tifs
		if($myrow['type'] == "pdf")
			$images[$i] .= "<div class='press-div' style='background-image: url(\"../GLOBAL/pdf.png\");'>";
		elseif($myrow['type'] == "tif" || $myrow['type'] == "tiff")
			$images[$i] .= "<div class='press-div' style='background-image: url(\"../GLOBAL/tif.png\");'>";
		else
			$images[$i] .= "<div class='press-div' style='background-image: url(\"../".$mediaFile."\");'>";
		// $images[$i] .= displayMedia($mediaFile, $mediaCaption, $mediaStyle);
		$images[$i] .= "</div></a>";
		$images[$i] .= "<div class = 'caption'>";
		$images[$i] .= $mediaCaption;	
		$images[$i] .= "</div>";
		$images[$i] .= "</div>";
	}
	$i++;
}

?><div id="main-container" class="no-gallery" style="width: 80%; margin-left: auto;">
	<div class="content"><?php 
				if(count($images) > 0) 
				{
					// display logos differently
					if($id != 22)
					{
					?><div class="images" <?php if(!$isMobile) echo $imageData; ?>><?
						$html = "";
						for($i = 0; $i < count($images); $i++)
							$html .= $images[$i];
						echo $html;
						// force div to have height
						?><div class="clearer"></div><?
					?></div><?
					}	
					else
					{
						// logos
						?><div class="logos"><?
						for($i = 0; $i < count($imageFiles); $i++)
						{
						?><div><? echo $captions[$i]; ?></div>
						<img src="<?php echo $imageFiles[$i]; ?>"><?php
						}
						?></div><?
					}
				} 
	?></div>
</div><?php
require_once('GLOBAL/foot.php');
?>