<?
date_default_timezone_set('Europe/Berlin');
require_once('_Library/systemDatabase.php');
require_once("_Library/systemCookie.php");
require_once("_Library/displayNavigation.php"); 
require_once("_Library/displayMedia.php"); 

// parse $id
$id = $_GET['id'];
if (!$id)
	$id = "0";
$ids = explode(",", $id);
$idFull = $id;
$id = $ids[count($ids) - 1];

// dev
$dev = $_REQUEST['dev'];
$dev = systemCookie("devCookie", $dev, 0);
if(!$dev) 
	die('Under construction . . .');
	
$sql = "SELECT 
			media.id,
			media.type
		FROM
			media
		WHERE 
			media.object = $id
			AND media.active
		ORDER BY media.rank;";
$res = MYSQL_QUERY($sql);
$html = "";

while($row = mysql_fetch_array($res))
{
	$f = "MEDIA/";
	$f.= str_pad($row["id"], 5, "0", STR_PAD_LEFT);
	$f .= "." . $row["type"];
	$s = "width: 100%";
	
	$html.= "<div class='image".$id."'>";
	$html.= displayMedia($f, "", $s);
	$html.= "</div>";
	$html.= "<div class='image".$id."'>";
	$html.= displayMedia($f, "", $s);
	$html.= "</div>";
	$html.= "<div class='image".$id."'>";
	$html.= displayMedia($f, "", $s);
	$html.= "</div>";
}
?><!DOCTYPE html>
<html>
	<head>
		<title>6 plus woes</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" media="all" href="GLOBAL/global.css">
	</head>
	<body><? echo $html; ?></body>
</html>