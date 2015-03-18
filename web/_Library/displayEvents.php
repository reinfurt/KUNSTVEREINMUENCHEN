<?php




  //////////////
 //  Events  //
//////////////

function displayEventNext() {


	//  Get current year
	
	$sql  = "SELECT objects.id AS objectsId FROM wires, objects ";
	$sql .= "WHERE wires.fromid = '35' AND wires.toid = objects.id AND objects.name1 LIKE '". date("Y") ."' ";
	$sql .= "AND wires.active = 1 AND objects.active = 1 LIMIT 1";
	$res  =  MYSQL_QUERY($sql);
	$row  =  MYSQL_FETCH_ARRAY($res);
	$yearCurrent = $row["objectsId"];

	
	//  Get next year
	
	$sql  = "SELECT objects.id AS objectsId FROM wires, objects ";
	$sql .= "WHERE wires.fromid = '35' AND wires.toid = objects.id AND objects.name1 LIKE '". (date("Y", strtotime("now"))+1) ."'";
	$sql .= "AND wires.active = 1 AND objects.active = 1 LIMIT 1";
	$res  =  MYSQL_QUERY($sql);
	$row  =  MYSQL_FETCH_ARRAY($res);
	$yearNext = $row["objectsId"];
	
	
	//  Get current month
	
	$sql  = "SELECT objects.name1, objects.id AS objectsId FROM wires, objects ";
	$sql .= "WHERE wires.fromid = '$yearCurrent' AND wires.toid = objects.id AND objects.name1 LIKE '". date("F") ."'";
	$sql .= "AND wires.active = 1 AND objects.active = 1 LIMIT 1";
	$res  =  MYSQL_QUERY($sql);
	$row  =  MYSQL_FETCH_ARRAY($res);
	$monthCurrent     = $row["objectsId"];
	$monthCurrentName = $row["name1"];
	
	
	//  Get next month
	
	$yearTemp = date("Y", strtotime("now + 1 month"));
	$yearFrom = ($yearTemp == date("Y")) ?  $yearCurrent : $yearNext;
	$sql  = "SELECT objects.name1, objects.id AS objectsId FROM wires, objects ";
	$sql .= "WHERE wires.fromid = '$yearFrom' AND wires.toid = objects.id ";
	$sql .= "AND objects.name1 LIKE '". date("F", strtotime("now + 1 month")) ."' ";
	$sql .= "AND wires.active = 1 AND objects.active = 1 LIMIT 1";
	$res  =  MYSQL_QUERY($sql);
	$row  =  MYSQL_FETCH_ARRAY($result);
	$monthNext     = $row["objectsId"];
	$monthNextName = $row["name1"];
	
	
	//  Get next public event within the next 2 months
	
	$monthName = $monthCurrentName;
	$sql  = "SELECT *, objects.id AS objectsId FROM wires, objects ";
	$sql .= "WHERE wires.fromid = '$monthCurrent' AND wires.toid = objects.id ";
	$sql .= "AND objects.name1 > '". str_pad(date("d")-1, 2, "0", str_pad_left) ."' AND objects.deck LIKE \"Public\" ";
	$sql .= "AND wires.active = 1 AND objects.active = 1 ";
	$sql .= "ORDER BY ASCII(objects.name1), objects.name1 LIMIT 1";
	$res  =  MYSQL_QUERY($sql);
	$row  =  MYSQL_FETCH_ARRAY($res);
	
	if (!$row["objectsId"]) {
	
		$monthName = $monthNextName;
		$sql  = "SELECT *, objects.id AS objectsId FROM wires, objects ";
		$sql .= "WHERE wires.fromid = '$monthNext' AND wires.toid = objects.id AND objects.deck LIKE \"Public\" ";
		$sql .= "AND wires.active = 1 AND objects.active = 1 ";
		$sql .= "ORDER BY ASCII(objects.name1), objects.name1 LIMIT 1";
		$res  =  MYSQL_QUERY($sql);
		$row  =  MYSQL_FETCH_ARRAY($res);
	}


	//  Display final result
	
	$day = substr($row["name1"], 0, 2) + 0;
	$eventNotice = $monthName ." ". $day ." / ". substr($row["name1"], 2);
	echo ($day) $eventNotice : "Check back soon for new events";
}








?>