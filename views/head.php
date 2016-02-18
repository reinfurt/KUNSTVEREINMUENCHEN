<?
// path to config file
$config = $_SERVER["DOCUMENT_ROOT"];
$config = $config."/open-records-generator/config/config.php";
require_once($config);

// stores the latest @k_dot_m tweet in the variable $tweet_text
require_once("tweets.php");

// specific to this 'app'
$config_dir = $root."/config/";
require_once($config_dir."url.php");

$db = db_connect("guest");

$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();

$title = "k.m";
$time = date("H:i");

// deal with language stuff
// search uri for lang
// if found, set cookie
// else, look for lang cookie
// else, default to german
$uri = explode("/", $_SERVER["REQUEST_URI"]);
if(count($uri) > 1 and ($uri[1] == "de" or $uri[1] == "en"))
{
	$lang = $uri[1];
	$one_week = mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"));
	if($_COOKIE["lang"] != $lang)
		setcookie("lang", $lang, $one_week, "/");
}
else if(isset($_COOKIE["lang"]))
	$lang = $_COOKIE["lang"];
else
{
	$lang = "de";
}
$lang_roots = array();
$lang_roots["de"] = 1;
$lang_roots["en"] = 2;
$lang_url["de"] = "de";
$lang_url["en"] = "en";

$nav = $oo->nav($uu->ids, $lang_roots[$lang]);

// detect mobile
$is_mobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'.
				'|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
				'|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT']);

$uri = $_SERVER["REQUEST_URI"];
$is_home = ($uu->url == "en" || $uu->url == "de" || !$uu->id);
$is_member_page = ($uri == "/de/mitgliedschaft/mitglied-werden") || ($uri == "/en/membership/apply");
$is_press_page = ($uri == "/en/contact/presse") || ($uri == "/de/kontakt/presse");
$is_ds = $uu->id == 1329 || $uu->id == 1370 || $uu->id == 1394;
?>
<!DOCTYPE html>
<html class="no-skrollr">
	<head>
		<title><? echo $title; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<link rel="shortcut icon" href="/media/png/km.png">
		<link rel="stylesheet" type="text/css" media="all" href="/static/css/fonts.css">
		<link rel="stylesheet" type="text/css" media="all" href="/static/css/global.css">
		<script type="text/javascript" src="/static/js/global.js"></script>
		<script type="text/javascript" src="/static/js/twitter.js"></script>
	</head>
	<body>
		<div id="fixed-container" class="no-gallery">
			<div id="header">
				<div id="date"><a href="/"><?php echo $time; ?></a></div>
				<div id="menu"><?
					$prevd = $nav[0]['depth'];
					foreach($nav as $n)
					{
						$d = $n['depth'];
						if($d > $prevd)
						{ ?><ul class="nav-level"><? }
						else
						{
							for($i = 0; $i < $prevd - $d; $i++)
							{ ?></ul><? }
						}
						?><li>
							<a href="<? echo $host.$lang_url[$lang].'/'.$n['url']; ?>"><?
								echo $n['o']['name1'];
							?></a>
						</li><?
						$prevd = $d;
					}
					?><div class="clearer"></div>
				</div>
			</div><?
			// hide on mobile pages that aren't the homepage
			$show_logo = !$is_mobile || $is_home;
			// hide on membership application
			$show_logo = $show_logo && !($is_member_page);
			// hide on press page
			$show_logo = $show_logo && !($is_press_page);
			// and ds page
			$show_logo = $show_logo && !$is_ds;
			if($show_logo)
				require_once("logo.php");
			elseif($is_ds)
				require_once("ds.php");
			?><div id="lang">
				<span class="<? if($lang=="de") echo "selected";?>">
					<a href="/de">de</a></span> /
				<span class="<? if($lang=="en") echo "selected";?>"> 
					<a href="/en">en</a>
				</span>
			</div>
		</div>