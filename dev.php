<?php
$form["de"] = "/de/mitgliedschaft/mitglied-werden";
$form['en'] = "/en/membership/apply";

$archive = array();
$types = array();
// exhibitions
$archive[] = "/en/program/exhibitions/2016-1823";
$archive[] = "/de/programm/ausstellungen/2016-1823";
$types[] = 'exh';
$types[] = 'exh';
// publications
$archive[] = "/en/program/publications";
$archive[] = "/de/programm/publikationen";
$types[] = 'pub';
$types[] = 'pub';
// kino
// schaufenster
$archive[] = "/de/programm/schaufenster";
$archive[] = "/en/program/schaufenster";
$types[] = "sch";
$types[] = "sch";
// events
$archive[] = "/en/program/events";
$archive[] = "/de/programm/veranstaltungen";
$types[] = "evt";
$types[] = "evt";

$uri = $_SERVER['REQUEST_URI'];

require_once('views/head.php');
require_once('views/member-dev.php');
require_once('views/foot.php');
?>
