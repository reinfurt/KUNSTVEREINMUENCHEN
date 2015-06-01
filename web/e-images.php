<?php
require_once("_Library/systemDatabase.php");
require_once("_Library/displayMedia.php");

function transpose($array)
{
	array_unshift($array, null);
	return call_user_func_array('array_map', $array);
}

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
		$mediaStyle = "width: 100%;";
		$images[] = array(displayMedia($mediaFile, $mediaCaption, $mediaStyle), $myrow['caption']);
	}
	$i++;
}

$b_arr = explode("++", $body);
foreach($b_arr as &$b)
{
	$b = trim($b);
	$b = explode("+", $b);
	foreach($b as &$c)
		$c = trim($c);
}
//$b_arr = transpose($b_arr);

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
			@font-face {
				font-family: 'neuzeit-s-book';
				src: url('../fonts/nzs-book-webfont.eot');
				src: url('../fonts/nzs-book-webfont.eot?#iefix') format('embedded-opentype'),
					 url('../fonts/nzs-book-webfont.woff2') format('woff2'),
					 url('../fonts/nzs-book-webfont.woff') format('woff'),
					 url('../fonts/nzs-book-webfont.ttf') format('truetype'),
					 url('../fonts/nzs-book-webfont.svg#neuzeitsbook') format('svg');
				font-weight: normal;
				font-style: normal;
			}
			html {
 				font-family: neuzeit-s-book, "Helvetica Neue", Helvetica, sans-serif;
 				font-size: 17px;
				line-height: 20px;
			}
			a {
				text-decoration: none;
				color: #fff;
				background-color: #183029;
			}
			.square {
				margin-left: auto;
				margin-right: auto;
			}
			table {
				border-collapse: collapse;
			}
			td {
				vertical-align: top;
				width: 50%;
				padding: 20px;
			}
		</style>
		<center>
			<table border="0" cellspacing="0"><?
				foreach($images as &$i)
				{
				?><tr>
					<td colspan="2"><?
						echo $i[0];
					?></td>
				</tr><?
				if($i[1])
				{
				?><tr><?
					echo nl2br($i[1]);
				?></tr><?
				}
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