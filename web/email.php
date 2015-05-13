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

function sanitize_output($buffer) 
{
    $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
    );

    $replace = array(
        '>',
        '<',
        '\\1'
    );

    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}
// start buffering (for mini-fying output)
ob_start("sanitize_output");

// transpose a 2-d array
function transpose($array)
{
	array_unshift($array, null);
	return call_user_func_array('array_map', $array);
}

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
			objects.id = $id 
			AND objects.active 
		ORDER BY media.rank;";
$result = MYSQL_QUERY($sql);
$html = "";

// collect images
$i = 0;
$images = array();
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
		$mediaStyle = "width: 100%;";
		$images[] = displayMedia($mediaFile, $mediaCaption, $mediaStyle);
	}
	$i++;
}

// process $deck, $body, $notes
$d_arr = explode("[++]", $deck);
$b_arr = explode("[++]", $body);
for($i = 0; $i < count($b_arr); $i++)
{
	$b_arr[$i] = explode("[+]", $b_arr[$i]);
	for($j = $i; $j < count($images); $j+=count($b_arr))
		$b_arr[$i][] = $images[$j];
	shuffle($b_arr[$i]);
}
$b_arr = transpose($b_arr); // hack, hack, hack
$n_arr = explode("[++]", $notes);

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
		<div id="left"></div>
		<div id="right"></div>
			<style type="text/css">
				#page-container {
					max-width: 420px;
					margin-left: auto;
					margin-right: auto;
				}
				table {
					table-layout: fixed;
					width: 100%;
				}
				td {
					vertical-align: top;
					padding: 20px;
				}
				/* first column */
				table tr > td:first-child {
				}
				/* second column */
				table tr > td:first-child  + td {
					/*color: white;*/
					/*background-color: #183029;*/
				}
				#right {
/* 
					position: fixed;
					height: 100vh;
					width: 50%;
					right: 0px;
					top: 0px;
					background-color: #183029;
					z-index: -100;
 */
				}
			</style>
			<center>
				<table border="0" cellspacing="0">
					<tr><?
						foreach($d_arr as $d)
						{
						?><td><?php
							echo nl2br(trim($d));
						?></td><?
						} 
					?></tr><?
					foreach($b_arr as $c)
					{
					?><tr><?
						foreach($c as $r)
						{
						?><td><?
							echo nl2br(trim($r));
						?></td><?
						}
					?></tr><?
					}
					?><tr><?
						foreach($n_arr as $n)
						{
						?><td><?php
							echo nl2br(trim($n));
						?></td><?
						} 
					?></tr>
				</table>
			</center>
		</div>
	</div><?
	// output buffering breaks javascript 
	ob_end_flush(); 
	?>
		<script type="text/javascript">
			// build html
			var html;
			var f; // find
			var r; // replace
			var o; // old

			html = "<html><body>";
			html += document.getElementById("page-container").innerHTML;
			html += "</body></html>";
 
			f = /<img src=[\"\'](MEDIA\/.*)[\"\']>/g;
			r = "<img src=\"http://www.kunstverein-muenchen.de/$1\">";
			o = '';
			while(o != html) {
				o = html;
				html = html.replace(f, r);
			}
			
			f = />\s+</g;
			r = '><';
			o = '';
			while(o != html) {
				o = html; 
				html = html.replace(f, r);
			}
		</script>
		<button 
			type="button" 
			onclick="prompt('Press command-C to copy rendered html:',html);"
		>Render html</button>
	</body>
</html>