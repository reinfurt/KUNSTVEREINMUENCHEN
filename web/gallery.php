<?php
$pageName = basename(__FILE__, ".php");
$showMenu = FALSE;
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

// reset to row 0
mysql_data_seek($result, 0);

// collect images
while ($myrow  =  MYSQL_FETCH_ARRAY($result)) 
{
	if ($myrow['mediaActive'] != null) 
	{
		$mediaFile = "MEDIA/". str_pad($myrow["mediaId"], 5, "0", STR_PAD_LEFT) .".". $myrow["type"];
		$mediaCaption = strip_tags($myrow["caption"]);
		$mediaStyle = "width: 100%;";

		// build random styles
		$randomPadding = rand(0, 15);
		$randomPadding *= 10;
		$randomWidth = rand(30, 50);
		$randomFloat = (rand(0, 1) == 0) ? 'left' : 'right';
		$icwStyle = 'width:'.$randomWidth.'%; float:'.$randomFloat.';';
		$icStyle = 'padding-top:'.$randomPadding.'px; margin: 40px;'; 
	
		$images[$i] .= "<div class='imageContainerWrapper' style='".$icwStyle."'>";
		$images[$i] .= "<div id='image".$i."' class='imageContainer' style='".$icStyle."'>";
		$images[$i] .= displayMedia($mediaFile, $mediaCaption, $mediaStyle);
		//$images[$i] .= "<div class = 'caption'>";
		//$images[$i] .= $mediaCaption . "<br /><br />";
		//$images[$i] .= "</div>";
		$images[$i] .= "</div>";
		$images[$i] .= "</div>";
	}
	$i++;
}
?>

<?php
require_once('GLOBAL/foot.php');
?>