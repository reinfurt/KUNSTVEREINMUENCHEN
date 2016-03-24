<?php
$form["de"] = "/de/mitgliedschaft/mitglied-werden";
$form['en'] = "/en/membership/apply";

require_once('views/head.php');

if($uu->url == "press" or $uu->url == "presse")
	require_once('views/press.php');
elseif(in_array($_SERVER['REQUEST_URI'], $form)) 
	require_once('views/member.php');
else
	require_once('views/body.php');

// wormhole is set in views/head.php
if($wormhole)
	require_once('views/wormhole.php');

require_once('views/foot.php');
?>