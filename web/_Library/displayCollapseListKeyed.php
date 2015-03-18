<?php




  ///////////////////////////
 //  Collapse List Keyed  //
///////////////////////////



function displayCollapseListKeyed ( $id = '0', $idFull = '0', $note = null, $pageName = 'main', $key = null, $htmlXtra = null ) {

	// Find this $key object
							
	$sql    = "SELECT *, objects.id AS objectsId FROM wires, objects ";
	$sql   .= "WHERE wires.fromid = $id AND wires.toid = objects.id ";
	$sql   .= "AND objects.name1 LIKE '$key' ";
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
		
			$name = $myrow["name1"];
			if (substr($name, 0, 1) != "." && substr($name, 0, 1) != "_") {
		
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

				if ($htmlXtra) {
				
					// Hack for Objectif
				
					// $htmlXtra  = "Download the complete <a href='MEDIA/PDF/Deball.pdf' target='_new'>PDF here</a>.<br/>";
					$htmlXtra  = "Download the <a href=\"javascript: windowPop('" . $myrow['url'] . 	"','poppdf', '450', '550')\">PDF</a>.<br/>";
					$htmlXtra .= "View the <a href=\"javascript: windowPop('popimage.html?id=" . $myrow['objectsId'] . 	"','popimage', '800', '533')\">installation images</a>.<br/>";
					$htmlXtra .= "Read the <a href=\"javascript: windowPop('poptext.html?id=" . $myrow['objectsId'] . 	"','poptext', '450', '800')\">exhibition text</a>.<br/><br/>";

					//echo "\n	<br /><br />".$myrow["deck"]."<br/>" . $htmlXtra . "</li>";
					echo nl2br ("\n	<br />". $myrow["deck"] . "<br/><br />" . $htmlXtra . "</li>");

					// End hack
					
				} else {
				
					echo nl2br ("\n	<br />". $myrow["deck"] . "<br/><br /></li>");
				}

				
				$i++;
			}
		}
		echo "\n	</ul>";
	}
}
	
	


	
?>