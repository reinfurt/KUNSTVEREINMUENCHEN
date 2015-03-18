<?php




  /////////////////////
 //  Collapse List  //
/////////////////////



function displayCollapseList ( $id = '0', $idFull = '0', $note = null, $pageName = 'main' ) {

	// Find this .Notes object
							
	$sql    = "SELECT *, objects.id AS objectsId FROM wires, objects ";
	$sql   .= "WHERE wires.fromid = $id AND wires.toid = objects.id ";
	$sql   .= "AND objects.name1 LIKE '.Notes' ";
	$sql   .= "AND wires.active=1 AND objects.active=1 ";
	$sql   .= "LIMIT 1";
	$result =  MYSQL_QUERY($sql);
	
	
	// Find connected objects
	
	if ($myrow = MYSQL_FETCH_ARRAY($result)) {
				
		$foundObjectId = $myrow['objectsId'];
		$sql    = "SELECT *, objects.id AS objectsId FROM wires, objects ";
		$sql   .= "WHERE wires.fromid = $foundObjectId AND wires.toid = objects.id ";
		$sql   .= "AND wires.active=1 AND objects.active=1 ";
		$sql   .= "ORDER BY rank ASC";
		$result =  MYSQL_QUERY($sql);

		
		// Output collapseList
		
		$i = 0;
		$notes = explode(",", $note);

		echo "\n	<!-- collapseList -->";
		echo "\n\n	<ul class = 'ieFix'>";
		
		while ($myrow = MYSQL_FETCH_ARRAY($result)) {
									
			for ( $ii = 0; $ii < count($notes); $ii++ ) {
			
				if ( $notes[$ii] == $myrow['objectsId'] ) $notesOut[$ii] = null;
				else $notesOut[$ii] = $notes[$ii]; 
			}						
			$notesOutFiltered = array_filter($notesOut);
			$noteOut = implode(",", $notesOutFiltered);
			$noteOutStub = ( $noteOut ) ? $noteOut."," : "";
			
			echo ( in_array($myrow['objectsId'], $notes) ) ? 
				"\n		<li class = 'collapseListOpen'>
						- <a href='".$pageName.".html?id=".$idFull."&amp;note=".$noteOut."'>".$myrow["name1"]."</a>" : 
				"\n		<li class = 'collapseListClosed'>
						+ <a href='".$pageName.".html?id=".$idFull."&amp;note=".$noteOutStub.$myrow['objectsId']."'>".$myrow["name1"]."</a>";			
			echo "\n	<br /><br />".$myrow["body"]."<br/></li>";
			$i++;
		}
		echo "\n	</ul>";
	}
}
	
	


	
?>