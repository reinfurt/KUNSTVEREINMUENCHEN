<?php
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
// if(!$dev) 
// 	die('Under construction . . .');

$ds = true;

// language
$lang = $_REQUEST['lang'];
$lang = systemCookie("langCookie", $lang, time()+60*60*24*30*12);
if(!$lang)
	$lang = "de";

// displayNavigation variables
if($lang == "de")
	$path = "1";
if($lang == "en")
	$path = "2";
$limit = 1;
$selection = $idFull;
$linkPageName = "index";
$stub = FALSE;
$breadcrumbsMode = FALSE;
$multiColumn = 20;

// detect mobile
$isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'.
				'|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
				'|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT']);

// document header
$documentTitle = 'k.m';

// subscribe data
$subscribe["de"]["url"] = "http://www.kunstverein-muenchen.de/index.php?id=14,26";
$subscribe["en"]["url"] = "http://www.kunstverein-muenchen.de/index.php?id=8,25";
$subscribe["de"]["text"] = "abonnieren";
$subscribe["en"]["text"] = "subscribe";

// date format
if($lang == "de")
	$d = date("H:i");
else
	$d = date("H:i");

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; 
?>

<!DOCTYPE html PUBLIC "-//W3C//Dtd XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/Dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" class="no-skrollr">
	<head>
		<title><?php echo $documentTitle; ?></title>
		<meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8"> 
		<meta http-equiv="Title" content="<?php echo $documentTitle; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!--meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"-->
		
		<link rel="shortcut icon" href="MEDIA/km.png">
		<link rel="stylesheet" type="text/css" media="all" href="GLOBAL/normalise.css">
		<link rel="stylesheet" type="text/css" media="all" href="GLOBAL/global.css">
		<script type="text/javascript" src="GLOBAL/global.js"></script>
	</head>
	<body><?
		// dexter sinister asterisk
		if($ds)
		{
		?>
		<div class="no-gallery"><?
			if(!$isMobile)
			{
			?><a id="controls" href="javascript:radioOnOff();">
				<img src="MEDIA/blank.gif" width="480" height="360">
			</a>
			<video id="radio" width="480" height="360" poster="MEDIA/blank.gif" autoplay=1 loop=1>
				<source src="MEDIA/MP4/README-web.mp4" type="video/mp4">
			</video><?
			}
			else
			{
			?><div id="radio">
				<img src="MEDIA/asterisk.gif">
			</div><?
			}
		?></div><?
		}
		?><div id="fixed-container" class="no-gallery"><?
			?><div id="header">
				<div id="date"><a href="index.php"><?php echo $d;?></a></div>
				<div id="menu">
				<?php
					if($showMenu)
						displayNavigation($path, $limit, $selection, $linkPageName, $stub, $breadcrumbsMode, $multiColumn);
				?>
					<div class="clearer"></div>
				</div>
			</div><?
			if(!$ds)
			{
			?><div id="logo">k.m</div><?
			}
			if($pageName != "press")
			{
			?><div id="lang">
				<span class="<?php if($lang=="de") echo "selected";?>">
					<a href="<?php echo $pageName;?>.php?lang=de">de</a></span> /
				<span class="<?php if($lang=="en") echo "selected";?>"> 
					<a href="<?php echo $pageName;?>.php?lang=en">en</a>
				</span>
			</div><?
			} 
			if((count($ids) == 1) && $id != 0 && $pageName != "press")
			{
			?><div id="subscribe">
				<span class="blink">
				<a href="<?php echo $subscribe[$lang]['url']; ?>"><? echo $subscribe[$lang]["text"]; ?></a>
				</span>
			</div><?
			}
		?></div>	
