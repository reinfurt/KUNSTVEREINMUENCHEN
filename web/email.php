<?php
require_once("_Library/systemDatabase.php");
require_once("_Library/displayMedia.php");
require_once('_Library/systemEmail.php');

// Parse $id
$id = $_REQUEST['id']; // no register globals
if (!$id) 
	$id = "0";
$ids = explode(",", $id);
$idFull = $id;
$id = $ids[count($ids) - 1];
$pageName = basename($_SERVER['PHP_SELF'], ".php");

function get_children($n)
{
	$c = NULL;
	$sql = "SELECT toid
			FROM wires
			INNER JOIN objects
			ON wires.fromid = objects.id
			WHERE
				objects.id = ".$n."
				AND wires.active = 1
			ORDER BY objects.rank";
	$result = MYSQL_QUERY($sql);
	while($myrow = MYSQL_FETCH_ARRAY($result))
		$c[] = $myrow['toid'];

	return $c;
}

$children = get_children($id);
$j = 0;
foreach($children as $c)
{
	// SQL object plus media
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
				objects.id = $c 
				AND objects.active 
			ORDER BY media.rank;";
	$result = MYSQL_QUERY($sql);
	$html = "";
	$i=0;
	// collect images
	while($myrow = MYSQL_FETCH_ARRAY($result)) 
	{
		if($i == 0) 
		{
			$name[] = $myrow['name1'];
			$body[$name[$j]] = $myrow['body'];
			$deck[$name[$j]] = $myrow['deck'];
			$notes[$name[$j]] = $myrow['notes'];
		}
		if($myrow['mediaActive'] != null) 
		{
			$mediaFile = "MEDIA/".str_pad($myrow["mediaId"], 5, "0", STR_PAD_LEFT).".".$myrow["type"];
			$mediaStyle = "width: 100%;";
			$images[$name[$j]][$i] .= displayMedia($mediaFile, $mediaCaption, $mediaStyle);
		}
		$i++;
	}
	$j++;
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>k.m - email</title>
		<link rel="shortcut icon" href="http://www.kunstverein-muenchen.de/MEDIA/km.png">
	</head>
	<body>
	<div id="page-container">
		<div 
			id="page" 
			style="font-family: Helvetica, sans-serif;
					font-size: 17px;
					line-height: 20px;
					color: #183029;"
		>
			<style type="text/css">
				@import url(http://fonts.googleapis.com/css?family=Hind:600);

				td {
					vertical-align: top;
					padding: 20px;
				}
			</style>
			<center>
				<table border="0" cellspacing="0">
					<tr><?
						foreach($name as $n)
						{
						?><td><?php
							echo nl2br($deck[$n]);
						?></td><?
						} 
					?></tr>
					<tr><?
						foreach($name as $n)
						{
						?><td><?
							$html = "";
							if($images[$n]) 
							{
								for($j = 0; $j < count($images[$n]); $j++)
									$html .= $images[$n][$j];
							}
							echo $html;
						?></td><?
						}
					?></tr>
					<tr><?
						foreach($name as $n)
						{
						?><td><?php
							echo nl2br($body[$n]);
						?></td><?
						} 
					?></tr>
					<tr><?
						foreach($name as $n)
						{
						?><td><?php
							echo nl2br($notes[$n]);
						?></td><?
						} 
					?></tr>
				</table>
			</center>
		</div>
	</div><?
// 			$body = ob_get_contents(); 			
// 			$subject = "km";
// 			$sender = "lilyhealey1@gmail.com";
// 			$recipient = "lily@lilyhealey.co.uk";
// 			$body .= "</body></html>";
// 			//systemEmail($sender, $recipient, $subject, $body);
// 			ob_end_clean();
		?><script type="text/javascript">
			// build renderedHTML
			var renderedHTML;
			var find;
			var replace;
			var old;

			renderedHTML = "<html><body>";
			renderedHTML += document.getElementById("page-container").innerHTML;
			renderedHTML += "</body></html>";

			// find = /(class=[\"\']punctuation[\"\'])/g;
// 			replace = "style=\"font-family: Monaco, 'Lucida Console', monospace;\"";
// 			renderedHTML=renderedHTML.replace(find, replace);
// 
// 			find = /(<a href)/g;
// 			replace = "<a style='color:#000; text-decoration: none; border-bottom: solid 3px;' href";
// 			renderedHTML=renderedHTML.replace(find, replace);
// 
			find = /<img src=[\"\'](MEDIA\/.*)[\"\']>/g;
			replace = "<img src=\"http://www.kunstverein-muenchen.de/$1\">";
			while(old != renderedHTML)
			{
				old = renderedHTML;
				renderedHTML=renderedHTML.replace(find, replace);
			}
		</script>
		<button 
			type="button" 
			onclick="prompt('Press command-C to copy rendered html:',renderedHTML);"
		>Render html</button>
	</body>
</html>