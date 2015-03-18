<?php



  ///////////////////
 //  Color Blend  //
///////////////////

function systemColorBlend($first, $final, $blend) {

	$r0 = hexdec(substr($first, 0, 2));
	$g0 = hexdec(substr($first, 2, 2));
	$b0 = hexdec(substr($first, 4, 2));

	$r1 = hexdec(substr($final, 0, 2));
	$g1 = hexdec(substr($final, 2, 2));
	$b1 = hexdec(substr($final, 4, 2));

	$rd = abs($r1 - $r0) * $blend;
	$gd = abs($g1 - $g0) * $blend;
	$bd = abs($b1 - $b0) * $blend;

	$r2 = $r0 + $rd;
	$g2 = $g0 + $gd;
	$b2 = $b0 + $bd;

	$color = systemColorBuild($r2, $g2, $b2);

	return $color;
}




  ///////////////////
 //  Color Build  //
///////////////////

function systemColorBuild($red, $green, $blue) {

	if ($red   <   0) $red   =   0;
	if ($green <   0) $green =   0;
	if ($blue  <   0) $blue  =   0;

	if ($red   > 255) $red   = 255;
	if ($green > 255) $green = 255;
	if ($blue  > 255) $blue  = 255;

	$red   = STR_PAD(dechex($red  ), 2, "0", STR_PAD_LEFT);
	$green = STR_PAD(dechex($green), 2, "0", STR_PAD_LEFT);
	$blue  = STR_PAD(dechex($blue ), 2, "0", STR_PAD_LEFT);

	$color = strtoupper($red . $green . $blue);
	// $color = strtoupper($red . $red . $red);
	
	return $color;
}




  /////////////////////
 //  System Mapper  //
/////////////////////

function systemMapper($objects = "0", $limit = null, $x = 50, $y = 50, $step = 0, $color = "999999", $thisPageName = "stub" ) {


	//  Handle recursion 

	$limitNext = ($limit === null) ? null : $limit - 1;
	$obj   = explode(",", $objects);
	$depth = count($obj) - 1;
	$first = $obj[0];
	$final = $obj[$depth];


	//  Calulate position

	$radius  = ($depth) ? 48 / pow(2, $depth) : 0;
	$radians = (-1 * 6.28318 * $step);
	$degrees = round(abs($radians * (-180 / 3.14159)));
	$left    = round($x + ($radius * cos($radians)));
	$top     = round($y + ($radius * sin($radians)));
	$opacity = round(100 / pow(2, $depth));
	
	
	if ($degrees >=   0 && $degrees <  60) {

		$red   = 255;
		$green =   0;
		$blue  = 255 - (round(abs( 60 - $degrees) * 4.25));
	}
	if ($degrees >=  60 && $degrees < 120) {

		$red   = 255 - (round(abs( 60 - $degrees) * 4.25));
		$green =   0;
		$blue  = 255;
	}	
	if ($degrees >= 120 && $degrees < 180) {

		$red   =   0;
		$green = 255 - (round(abs(180 - $degrees) * 4.25));
		$blue  = 255;
	}	
	if ($degrees >= 180 && $degrees < 240) {

		$red   =   0;
		$green = 255;
		$blue  = 255 - (round(abs(180 - $degrees) * 4.25));
	}	
	if ($degrees >= 240 && $degrees < 300) {

		$red   = 255 - (round(abs(300 - $degrees) * 4.25));
		$green = 255;
		$blue  =   0;
	}	
	if ($degrees >= 300 && $degrees < 360) {

		$red   = 255;
		$green = 255 - (round(abs(300 - $degrees) * 4.25));
		$blue  =   0;
	}

	$colorTemp = systemColorBuild($red, $green, $blue);	 // child's initial color
	if ($depth > 1) $colorTemp = systemColorBlend($color, $colorTemp, .7); // blend color with parent
	if ($depth > 1) $colorTemp = systemColorBlend($colorTemp, "FFFFFF", ($depth * .2)); // blend color with parent

	$color = $colorTemp;




	//  Here is where the radius should blend the color!!
	//  need to add that, here's a hack instead	
	if ($radius == 0) $color = "FF0000";


		
	//  Pull from current node

	$sql = "
		SELECT * FROM objects 
		WHERE id = '$final' 
		AND active = 1 
		LIMIT 1
		";
	$res = MYSQL_QUERY($sql);
	$row = MYSQL_FETCH_ARRAY($res);

	$name  = strip_tags($row["name1"]);
	if (!$final) $name = "&times;&nbsp;Object-Relation-Grouper";
	if (!$name ) $name = "Untitled";


	//  Translate $objects trail to page.html?id=objects
	
	if ($first ==   1) $page = "object";
	if ($first ==  13) $page = "read";
	if ($first ==  14) $page = "read";
	if ($first ==  15) $page = "read";
	if ($first ==  16) $page = "read";
	if ($first ==  17) $page = "read";
	if ($first ==  18) $page = "read";
	if (!$page)		   $page = "object";

	$id = null;	
	for ($i = 2; $i < count($obj); $i++) {

		if ($i > 2) $id .= ",";
		$id .= $obj[$i];
	}


	//  Update Array

	global $flat;
	$node = $final;
	if (!isset($flat[$depth])) $flat[$depth] = array();
	if (!isset($flat[$depth][$node])) {
		
		$flat[$depth][$node] 			= array();
		$flat[$depth][$node]["node"] 	= $final;
		$flat[$depth][$node]["name"] 	= nl2br($name);
		$flat[$depth][$node]["top"] 	= $top;
		$flat[$depth][$node]["left"] 	= $left;
		$flat[$depth][$node]["opacity"] = $opacity;
		$flat[$depth][$node]["color"] 	= $color;
		$flat[$depth][$node]["url"] 	= $thisPageName . ".html?node=". $node;

		if ($depth == 0) {

			if ( $flat[$depth][$node]["name"] == '&times;&nbsp;Object-Relation-Grouper') {

				$flat[$depth][$node]["name"]   .= "<br /><br /><br />Click any box to navigate . . .";
			
			} else {

				$flat[$depth][$node]["name"]   .= "<br /><br /><br />Click here to visit page.";
			}

			$flat[$depth][$node]["url"] 	= $page .".html?id=". $node;
		}
	}


	//  Find children of current node

	if ($limit > 0 || $limit === null) {

		$sql = "
			SELECT objects.id AS objectsId FROM wires, objects 
			WHERE wires.fromid = '$final' AND wires.toid = objects.id 
			AND wires.active = 1 AND objects.active = 1 
			ORDER BY weight DESC, rank, end DESC, begin DESC, name1, name2, objects.modified DESC, objects.created DESC
			";
		$res = MYSQL_QUERY($sql);
		$num = MYSQL_NUM_ROWS($res);
		
		$i = 0;
		while ($row = MYSQL_FETCH_ARRAY($res)) {

			$nodeNext = $objects .",". $row["objectsId"];
			$stepNext = $i / $num;
			$i++;
			systemMapper($nodeNext, $limitNext, $left, $top, $stepNext, $color, $thisPageName);
		}
	}
}








  //////////////////////
 //  Display Mapper  //
//////////////////////

function displayMapper($flat) {


	//  Sort flat diagram by depth, descending

	krsort($flat);
	$html = "";


	// Display each node

	foreach ($flat as $depth => $val) {

		foreach ($flat[$depth] as $node => $content) {

			$node 		= $flat[$depth][$node]["node"];
			$name 		= $flat[$depth][$node]["name"];
			$top 		= $flat[$depth][$node]["top"];
			$left 		= $flat[$depth][$node]["left"];
			$opacity 	= $flat[$depth][$node]["opacity"];
			$color 		= $flat[$depth][$node]["color"];
			$url 		= $flat[$depth][$node]["url"];

			// $width  = round(120 / ($depth + 1));
			// $height = round(120 / ($depth + 1));
			$width  = round(240 / ($depth + 1));
			$height = round(240 / ($depth + 1));
			$mt = round($width  / 2);
			$ml = round($height / 2);
			
			$html .= "\n\n<div class='node' style='top: ". $top ."%; left: ". $left ."%; ";
			$html .= "width: ". $width ."px; height: ". $height ."px; margin-top: -". $mt ."px; margin-left: -". $ml ."px; ";
			
			// Hack for root level object opacity and to make all less transparent -- should *fix*
			
			if ( $opacity == 100 ) $opacity = 50;	
			$opacityLimit = 80;
			
			$html .= "opacity: ". ($opacity / $opacityLimit) ."; filter: alpha(opacity=". $opacity ."); ";
			$html .= "background-color: #". $color . "; ";
			$html .= "'><a href='". $url ."' style='display: block; height: 100%;' title='". $name ."'><div style='padding: 2px;'>". $name ."</div></a></div>";
		}
	}
	return $html;
}








//  Go for it

$flat = array();
if (!$node) $node = "0";
//systemMapper("264", 1);
$limit = null;
$x = 50; 
$y = 50; 
$step = 0; 
$color = "999999";
systemMapper($node, $limit , $x , $y , $step, $color, $pageName);
echo    displayMapper($flat);




?>