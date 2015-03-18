<?php
  //////////////////
 //  Navigation  //
//////////////////
function displayNavigation(	$path = "0", 
							$limit = null, 
							$selection = null, 
							$pageName = "main", 
							$stub = FALSE, 
							$breadcrumbsMode = FALSE, 
							$multiColumn = null ) {

	//  Handle recursion 
	$limitNext = ($limit === null) ? null : $limit - 1;
	$obj = explode(",", $path);
	$depth = count($obj) - 1;
	$first = $obj[1];
	$final = $obj[$depth];

	// build target
	$target = null;	
	for ($i = 1; $i < count($obj); $i++) 
	{
		if ($i > 1) 
			$target .= ",";
		$target .= $obj[$i];
	}
	if (!$target) 
		$target = $path;

	// Check for selection
	$selected = false;
	$selects  = explode(",", $selection);
	for ($i = 0; $i < count($selects); $i++) 
	{
		if ($selection && $selects[$i] == $final) 
			$selected = true;
	}

	//  Pull from current node
	$sql = "SELECT * 
			FROM objects 
			WHERE 
				id = '$final' 
				AND active = 1 
			LIMIT 1";
	$res = MYSQL_QUERY($sql);
	$row = MYSQL_FETCH_ARRAY($res);

	$name = nl2br($row["name1"]);
	$rank = nl2br($row["rank"]);
	$deck = nl2br($row["deck"]);
	$body = nl2br($row["body"]);
	$bodyParts = explode("|", $body);
	
	//  Check if $obj is a root
	if ($final == 0)
		$name = "";
	if (substr($name, 0, 1) == "+") 
		$name = ""; 

	//  Display current node
	if ((substr($name, 0, 1) != "." && substr($name, 0, 1) != "_")) 
	{
		$html  = "<div class='menuItemContainer ";
		$html .= ($selected || $final == 0) ? "active" : "static";
		$html .= "'>";
		$html .= "<a href='";

		// Check if URL and whether full or partial	
		$pattern = "/html/";			
		$URL = $row["url"];
		if (preg_match($pattern, $URL)) 
			$fullURL = TRUE;
			
		$html .= ($URL) ? $URL : $pageName;	// normal
		if (!$fullURL) 
			$html .= ".php?id=$target";						

		$html .= "'>";
		$html .= $name;	
		$html .= "</a></div>";

		if (($breadcrumbsMode) && ($depth <= count($selects))) 
		{
			if ($selected) 
				echo $html;	
		} 
		else
		{ 
			// k.m-specific hack -- don't show top-level deutsch / english
			if ($depth > 0) 
				echo $html;
		}
		
		//  Find children of current node
		if ((($limit > 0 || $limit === null) || $selected) && !$stub) 
		{
			$sql = "SELECT objects.id AS objectsId 
					FROM 
						wires, 
						objects 
					WHERE 
						wires.fromid = '$final' 
						AND wires.toid = objects.id 
						AND wires.active = 1 
						AND objects.active = 1 
					ORDER BY 
						objects.rank, 
						end DESC, 
						begin DESC, 
						name1, 
						name2, 
						objects.modified DESC, 
						objects.created DESC";
			$res = MYSQL_QUERY($sql);

			
			//if ($multiColumn && $depth>1) 
			if ($multiColumn && $depth !=0)
				echo "<div style='padding-left:" . $multiColumn . "px;'>";
			
			// acquiring modernity hack -- not sure if nec for k.m?
			if ($final && $depth>0) 
				$breadcrumbsMode=TRUE;
			
			while ($row = MYSQL_FETCH_ARRAY($res)) 
			{
				$tmp = $path .",". $row["objectsId"];
				$limitTemp = ($selected) ? $limit : $limitNext;
				displayNavigation($tmp, $limitTemp , $selection, $pageName, $stub, $breadcrumbsMode, $multiColumn);
			}
			
			//if ($multiColumn && $depth>1) 
			if ($multiColumn && $depth !=0)
				echo "</div>";
		}
	}
}

?>