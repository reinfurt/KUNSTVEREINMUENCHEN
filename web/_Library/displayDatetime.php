<?php




  /////////////
 //  Range  //
/////////////

function displayDatetimeRange($begin = null, $end = null) {

	$status = false;

	if ($begin) {

		$range = (date("H:i:s", strToTime($begin)) == "00:00:00") ? date("l, d F Y", strToTime($begin)) : date("l, d F Y, g:ia", strToTime($begin));
	}
	if ($begin && $end && ($begin != $end)) {

		$range         .= "&ndash;";
		$beginTemp	=  date("Y-m-d", strToTime($begin));
		$endTemp   	=  date("Y-m-d", strToTime($end));
		if ($beginTemp ==  $endTemp) {

			$range .= date("g:ia", strToTime($end));

		} else {

			$range .= (date("H:i:s", strToTime($end)) == "00:00:00") ? date("l, d F Y", strToTime($end)) : date("l, d F Y, g:ia", strToTime($end));
		}
	}

	$status = $range;
	return $status;
}




  ////////////
 //  Span  //
////////////

function displayDatetimeSpan($begin = null, $end = null) {

	$status = false;

	if (!$begin) $begin = "now";
	if (!$end) $end = "now";
	$begin = date("U", strToTime($begin));
	$end   = date("U", strToTime($end));

	$span = $end - $begin;

	$days 		= floor($span / 86400);		// seconds in a day
	$span		= $span % 86400;
	$hours 		= floor($span / 1440);		// minutes in a day
	$span 		= $span % 1440;
	$minutes 	= floor($span / 24);		// hours in a day
	$span 		= $span % 24;
	$seconds 	= floor($span / 1);		// days in a day. HA!

	$hours 		= str_pad($hours,   2, "0", str_pad_left);
	$minutes 	= str_pad($minutes, 2, "0", str_pad_left);
	$seconds 	= str_pad($seconds, 2, "0", str_pad_left);

	// $status = "$days days, $hours:$minutes:$seconds";
	$status = "$days days, $hours hours, $minutes minutes, $seconds seconds";
	return $status;
}








?>