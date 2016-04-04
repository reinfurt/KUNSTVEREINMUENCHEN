<?php
$form["de"] = "/de/mitgliedschaft/mitglied-werden";
$form['en'] = "/en/membership/apply";

$ex['en'] = "/en/program/exhibitions/2016-1823";
$ex['de'] = "/de/programm/ausstellungen/2016-1823";

require_once('views/head.php');

if($uu->url == "press" or $uu->url == "presse")
	require_once('views/press.php');
elseif(in_array($_SERVER['REQUEST_URI'], $form)) 
	require_once('views/member.php');
elseif(in_array($_SERVER['REQUEST_URI'], $ex) && $wormhole)
{
	require_once('views/exhibitions.php');
	if($wormhole == 2)
	{
		require_once('views/wormhole.php');
	}
}
else
	require_once('views/body.php');
require_once('views/foot.php');
?>