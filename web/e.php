<?php
require_once("_Library/systemDatabase.php");
require_once("_Library/displayMedia.php");

// Parse $id
$id = $_REQUEST['id']; // no register globals
if (!$id) 
	$id = "0";
$ids = explode(",", $id);
$idFull = $id;
$id = $ids[count($ids) - 1];
$pageName = basename($_SERVER['PHP_SELF'], ".php");

$sql = "SELECT 
			objects.id AS objectsId, 
			objects.name1, 
			objects.deck, 
			objects.body, 
			objects.notes, 
			objects.active, 
			objects.rank as objectsRank, 
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

// collect images
$i = 0;
$images = array();
$captions = array();
while($myrow = MYSQL_FETCH_ARRAY($result)) 
{
	if($i == 0) 
	{
		$name = $myrow['name1'];
		$deck = $myrow['deck'];
		$body = $myrow['body'];
		$notes = $myrow['notes'];
	}
	if($myrow['mediaActive'] != null) 
	{
		$mediaFile = "MEDIA/".str_pad($myrow["mediaId"], 5, "0", STR_PAD_LEFT).".".$myrow["type"];
		//$mediaCaption = $myrow['caption'];
		//$mediaStyle = "width: 100%;";
		$images[] = array(displayMedia($mediaFile, $mediaCaption, $mediaStyle), $myrow['caption']);
	}
	$i++;
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<title>k.m - email</title>
		<link rel="shortcut icon" href="http://www.kunstverein-muenchen.de/MEDIA/km.png">
	</head>
	<body>
		<style>
			html {
 				font-family: "Lucida Console", "Menlo-Regular", Monaco, monospace;
			}
			img {
				max-width: 100%;
			}
			.square {
				width: 420px;
				height: 420px;
				margin-left: auto;
				margin-right: auto;
			}
			td {
				padding: 10px;
			}
		</style>
		<center>
			<table border="0" cellspacing="0"><?
				foreach($images as $im)
				{
				?><tr>
					<td class="square"><? 
					if($im[1])
					{
						$url = trim($im[1]);
						?><a href="<? echo $url; ?>"><?
						echo $im[0];
						?></a><?
					}
					else
						echo $im[0];
					?></td>
				</tr><?
				}
				?><tr>
					<td>
						<a href="http://ethreemail.net/subscribe?g=3a46e076">subscribe</a> / 
						<a href="http://ethreemail.net/unsubscribe?g=3a46e076">unsubscribe</a>
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>