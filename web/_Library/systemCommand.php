<?php




  ///////////////
 //  Command  //
///////////////

function systemCommand() {


	//  Get ".System" object
	
	$sql  = "SELECT objects.id AS objectsId FROM wires, objects ";
	$sql .= "WHERE wires.fromid = 0 AND wires.toid = objects.id AND objects.name1 LIKE '.System' ";
	$sql .= "AND wires.active = 1 AND objects.active = 1 ";
	$sql .= "ORDER BY objects.modified DESC LIMIT 1";
	$res  =  MYSQL_QUERY($sql);
	$row  =  MYSQL_FETCH_ARRAY($res);

	global $systemObjectId;
	$systemObjectId = $row["objectsId"];
	
	
	//  Get "documentBase"
	
	$sql  = "SELECT * FROM wires, objects ";
	$sql .= "WHERE wires.fromid = $systemObjectId AND wires.toid = objects.id AND objects.name1 LIKE 'documentBase' ";
	$sql .= "AND wires.active = 1 AND objects.active = 1 ";
	$sql .= "ORDER BY objects.modified DESC LIMIT 1";
	$res  =  MYSQL_QUERY($sql);
	$row  =  MYSQL_FETCH_ARRAY($res);
	
	global $documentBase;
	$documentBase = $row['body'];
	// echo $documentBase;

	//  Establish page

	global $pageName;
	$pageName = basename($PHP_SELF, ".html");
	if (!$pageName) $pageName = "index";
	

	//  Set document title
	
	global $documentTitle;
	$documentTitle = ($documentTitle) ?  $documentBase . " | ". $documentTitle : $documentBase;
	
	
	//  Parse object commands

	global $objects;
	global $object;
	global $id;
	
	$objects = explode(",", $id);
	$object  = $objects[sizeof($objects) - 1];
	if (!$object) {

		$object = 0;
	}
	if (sizeof($objects) == 1 && empty($objects[sizeof($objects) - 1])) unset($objects);
}
systemCommand();




  /////////////////
 //  Build URL  //
/////////////////

function urlBuild($limit = null, $next = null) {

	global $objects;

	if ($limit === null) $limit = sizeof($objects);
	$url = "?id=";

	for ($i = 0; $i < $limit + 1; $i++) {

		if ($i < sizeof($objects) + 1)	 $url .= $objects[$i];
		if ($i > sizeof($objects) - 1) 	 $url .= $next;
		if ($i < $limit && $objects[$i]) $url .= ",";
	}
	return $url;
}








?>