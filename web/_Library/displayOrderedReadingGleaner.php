<?php




  ///////////////////////////////
 //  Ordered Reading Gleaner  //
///////////////////////////////



function displayOrderedReadingGleaner ( $id = '0', $note = null, $pageName = 'main', $write = FALSE, $quiet = null, $wordFind = null, $wordFindNumber = '1', $contextLimit = '10' ) {



 
	/////////////// 1. QUERY ///////////////
	
	
	// Harvest OPEN-RECORDS-GENERATOR
	// Avoids hidden records with % wildcard
							
	$sql    = "SELECT id AS objectsId, name1, body FROM objects ";
	$sql   .= "WHERE name1 NOT LIKE '.%' ";
	$sql   .= "AND name1 NOT LIKE '2007%' ";
	$sql   .= "AND name1 NOT LIKE '(%' ";
	$sql   .= "AND objects.active = 1 ";
	$sql   .= "ORDER BY id ASC";
	$result =  MYSQL_QUERY($sql);
	
	// These will be used for text manipulation
	// But first we have to load them up
	
	$textMass = null;
	$textMassLines = null;
	$textMassLinesSorted = null;
	$textMassLinesSortedCounted = null;

	// Build raw TextMass
	
	while ($myrow = MYSQL_FETCH_ARRAY($result)) {
		$textMass .= $myrow["name1"]." ".$myrow["body"];
	}



	
	/////////////// 2. PROCESS ///////////////	


	// Print raw textMass

	if (!$quiet) {

		echo "\n		<div class = 'rightSubContainer'>";
		echo "\n			<div class = 'Mono'>";					
		echo "\n			<!-- textMass -->";
		echo "\n\n				<ul>";
		echo "\n					<li class = 'Body collapseListOpen'>".$textMass."</li>";
		echo "\n				</ul>";
		echo "\n			</div>";
		echo "\n		</div>";
	}


	// Clean up $textMass	
	// Strip out anything that is not (^) either a-z or A-Z
	
	$pattern = '/[^a-zA-Z]/';
	$replacement = ' ';
	$textMassParsed = preg_replace( $pattern, $replacement, $textMass );
	
	
	// Split textMassParsed by any number of commas or space characters
	// And put result into an array
	// Then sort through $textMassArray manually when finding the selected word
	
	$textMassArray = preg_split ("/[\s,]+/", $textMassParsed);
			
	
	// Print unsorted $textMassArray

	if (!$quiet) {

		echo "\n		<div class = 'rightSubContainer'>";
		echo "\n			<div class = 'Mono'>";
					
		echo "\n			<!-- textMassArray -->";
		foreach ($textMassArray as $value) {
			
			if (!$quiet) echo $value." ";
		}
		echo "\n			</div>";
		echo "\n		</div>";
	}
	

	// Print sorted $textMassArray

	natcasesort($textMassArray);
	
	if (!$quiet) {

		echo "\n		<div class = 'rightSubContainer'>";
		echo "\n			<div class = 'Mono'>";
					
		echo "\n			<!-- textMassArray -->";
		foreach ($textMassArray as $value) {
			
			if (!$quiet) echo $value." ";
		}
		echo "\n			</div>";
		echo "\n		</div>";
	}
		
	
	// Count how many times each word appears
	// Do this by cycling through the sorted array
	// Every time the word changes, record Count and Word
	// In a 2d array which would be more elegant!
	
	$previousValue = null;
	$i = 0;
	$counter = 1;
	
	foreach ($textMassArray as $value) {

		$value = strtolower($value);

		if ( $value != $previousValue ) { 
					
			// Temporary hack for removing words!! would be best to use REGEX above
		
			if (  ( $previousValue == "the" | 
					$previousValue ==   "a" | 
					$previousValue ==  "an" | 
					$previousValue ==  "of" | 
					$previousValue == "for" | 
					$previousValue == "and" | 
					$previousValue ==   "i" | 
					$previousValue ==  "in" | 
					$previousValue ==  "it" | 
					$previousValue ==  "to" |
					$previousValue ==  "was" |
					$previousValue == "is" | 
					$previousValue == "href" | 
					$previousValue == "url" | 
					$previousValue == "edu" | 
					$previousValue == "target" | 
					$previousValue ==  "http" | 
					$previousValue ==  "www" |
					$previousValue ==  "what" |
					$previousValue == "at" | 
					$previousValue == "that" | 
					$previousValue == "href" | 
					$previousValue ==   "s" | 
					$previousValue ==  "on" | 
					$previousValue ==  "be" | 
					$previousValue ==  "br" | 
					$previousValue == "this" | 
					$previousValue == "html" | 
					$previousValue ==   "as" | 
					$previousValue ==  "has" | 
					$previousValue ==  "he" | 
					$previousValue ==  "by" | 
					$previousValue ==  "com" ) ) { 
				
					// Discard word, do nothing and reset $counter
					$counter = 1;
		
			} else {
						
					// Add this word to the List
			
					$word[$i] = $previousValue;
					$count[$i] = $counter;
					$wordCount[$i] = "(".$count[$i].") ".$word[$i];
					$counter = 1;	
			}
			
		} else {
			
			// Increase $counter and go to next word
			$counter++;
		}
		
		$previousValue = $value;
		$i++;
	}
	
	
	// Sort combined array and reverse order to make $wordCount
	// $wordCount is an array of all words and their frequencies developed above
	
	natcasesort($wordCount);
	$wordCount = array_reverse($wordCount);
	
	$i = 0;			
	foreach ($wordCount as $value) {
		if (!$quiet) echo $value."<br />";
		if ( $i < 4 ) $tipofthetongue[$i] = $value;
		$i++;
	}
	




	/////////////// 3. CLEAN UP ///////////////	

	// Clean up final words list 
	// Prepare master Arrays $tipofthetongueParsed and $tipofthetongueParsedCounts
	// Output final words and send $wordFind in URL to return context

	foreach ($tipofthetongue as $value) {
			
		// Strip out anything that is not (^) either a-z or A-Z
		// in order to get correct search term
			
		$pattern = '/[^a-zA-Z]/';
		$replacement = '';
		$tipofthetongueParsedStub = preg_replace( $pattern, $replacement, $value );
		$tipofthetongueParsed[] = $tipofthetongueParsedStub;
		
		// Strip out anything that is not (^) 0-9 in order to get the counts of each
			
		$pattern = '/[^0-9]/';
		$replacement = '';
		$tipofthetongueParsedCounts[] = preg_replace( $pattern, $replacement, $value );
	}
	
	
	


	/////////////// 4. WRITE ///////////////	
	
	
	// Uses Flag $write to control output to database


	if ( $write == TRUE ) { 
	
	
		//  1. Write object with date to house the words


	
		// Get .Tip of the Tongue .Outputs object 		
	
		$sql    = "SELECT id AS objectsId FROM objects ";
		$sql   .= "WHERE name1 LIKE '.Outputs' ";
		$sql   .= "AND objects.active = 1 ";
		$sql   .= "LIMIT 1";
		$result =  MYSQL_QUERY($sql);
		$myrow = MYSQL_FETCH_ARRAY($result);
		$thisObjectId = $myrow['objectsId'];


		//  Add object to database

		$sql  = "INSERT INTO objects (created, modified, name1) ";
		$sql .= "VALUES ('". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."')";
		$res  =  MYSQL_QUERY($sql);
		$insertId = MYSQL_INSERT_ID();


		//  Add wire to database

		$sql  = "INSERT INTO wires (created, modified, fromid, toid) ";
		$sql .= "VALUES('". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."', '$thisObjectId', '$insertId')";
		$res  =  MYSQL_QUERY($sql);

		$insertIdStub = $insertId;


		//  2. Write Tip of the Tongue words

		foreach ($tipofthetongue as $value) {

			//  Add object to database

			$sql  = "INSERT INTO objects (created, modified, name1) ";
			$sql .= "VALUES ('". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."', '$value')";
			$res  =  MYSQL_QUERY($sql);
			$insertId = MYSQL_INSERT_ID();


			//  Add wire to database

			$sql  = "INSERT INTO wires (created, modified, fromid, toid) ";
			$sql .= "VALUES('". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."', '$insertIdStub', '$insertId')";
			$res  =  MYSQL_QUERY($sql);
		
		}
		
		
		//	3. Redundantly Update the .Notes under .Tip of the Tongue object
		
		// Find this .Tip of the Tongue object
						
		$sql    = "SELECT id AS objectsId FROM objects ";
		$sql   .= "WHERE name1 LIKE '.Tip of the Tongue' ";
		$sql   .= "AND objects.active = 1 ";
		$sql   .= "LIMIT 1";
		$result =  MYSQL_QUERY($sql);
		$myrow = MYSQL_FETCH_ARRAY($result);
		$thisTipoftheTongueId = $myrow['objectsId'];
						
						
		// Find this .Notes object
							
		$sql    = "SELECT *, objects.id AS objectsId FROM wires, objects ";
		$sql   .= "WHERE wires.fromid = $thisTipoftheTongueId AND wires.toid = objects.id ";
		$sql   .= "AND objects.name1 LIKE '.Notes' ";
		$sql   .= "AND wires.active=1 AND objects.active=1 ";
		$sql   .= "LIMIT 1";
		$result =  MYSQL_QUERY($sql);
		$myrow = MYSQL_FETCH_ARRAY($result);
		$foundObjectId = $myrow['objectsId'];


		// Find connected objects
		
		$sql    = "SELECT *, objects.id AS objectsId FROM wires, objects ";
		$sql   .= "WHERE wires.fromid = $foundObjectId AND wires.toid = objects.id ";
		$sql   .= "AND wires.active=1 AND objects.active=1 ";
		$sql   .= "ORDER BY rank ASC";
		$result =  MYSQL_QUERY($sql);
		while ($myrow = MYSQL_FETCH_ARRAY($result)) {
		
			$foundObjectIdArray[] = $myrow['objectsId'];
		}
		

		// Update objects
				
		// Output all numbers corresponding to wordFindNumbers to make $writeWordFindNumberURLStub
		// This will be used to write in the .Notes body for each word
	
		for ($i = 0; $i < count($tipofthetongueParsedCounts); $i++) {
		
			for ($ii = 1; $ii <= $tipofthetongueParsedCounts[$i]; $ii++) {

				// to deal with Notes since it is written one time, do it manually to keep the note open using $foundObjectIdArray[]
				$noteStub = $foundObjectIdArray[$i];
				$writeWordFindNumberURLStubStub .=  "<a href = 'tipofthetongue.html?wordFind=" . $tipofthetongueParsed[$i] . "&wordFindNumber=" . $ii . "&write=false&note=" . $noteStub . "'>". $ii . "</a> ";
			}	
			$writeWordFindNumberURLStub[] = $writeWordFindNumberURLStubStub;
			$writeWordFindNumberURLStubStub = '';
		}
	
	
		$counter = 0;	
		
		foreach ($foundObjectIdArray as $value) {
			
			$thisWord = $tipofthetongue[$counter];
			$thisURL = $writeWordFindNumberURLStub[$counter];
			$thisURLescaped = addslashes($thisURL);
			//echo $thisURL;
			//echo $thisURLescaped;
			$sql    = "UPDATE objects ";
			$sql   .= "SET name1 = '$thisWord', body = '$thisURLescaped' ";
			$sql   .= "WHERE id = $value";
			$result =  MYSQL_QUERY($sql);			
			$counter++;
		}
	}
	
		
		


	
	
	
	
	
	
	
		
	
	/////////////// 5. FIND ///////////////	

	
	// Find and print Words in Context
	
	// Sorting manually through $textMassArray (sorted) looking at adjacent words
	// Using array_keys to retrieve which positions return a match and collecting indices
	// Uses $contextLimit to determine how many words either side
	
	// First -- populate an Array of indices using array_keys in order to refer to it later by number
	
	if ($wordFind) {
	
		foreach (array_keys($textMassArray, $wordFind) as $value) {
		
			$wordFindIndex[] = $value;
		}
		echo "\n	<!--  LEFT  -->";
		echo "\n	<div id='left' class='leftContainer'>";
		echo "\n		<div class = 'Body'>";
	
		echo "$wordFindNumber.<br /><br />";
		echo "... ";

	 	for ( $i = $contextLimit; $i > 0; $i-- ) {
			
			$stub = strtolower($textMassArray[$wordFindIndex[$wordFindNumber] - $i]);
			echo $stub . " ";	
		}
		
		$stub = strtoupper($textMassArray[$wordFindIndex[$wordFindNumber]]);
		echo $stub . " ";	

	 	for ( $i = 1; $i < $contextLimit; $i++ ) {
	 		
	 		$stub = strtolower($textMassArray[$wordFindIndex[$wordFindNumber] + $i]);
			echo $stub . " ";	
		}	
		
		echo " ...";
		$contextLimitNext = $contextLimit + 100;
		echo "<br /><br /><a href='" . $pageName . ".html?wordFind=$wordFind&wordFindNumber=$wordFindNumber&write=FALSE&note=$note&contextLimit=$contextLimitNext'>Read more . . .</a>	";
			
		echo "\n		</div>";
		echo "\n	</div>";	
	} 
}

?>