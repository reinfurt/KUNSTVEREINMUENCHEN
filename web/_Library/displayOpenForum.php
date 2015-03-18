<?php 




  ///////////////////////
 //  displayOpenForum //
///////////////////////


function displayOpenForum( $pageName = 'discuss', $name1, $deck, $body, $id, $action, $submit, $idFull, $idBase, $reply, $userName ) {

	global $childObject;
	global $posts;
	global $currentId;
	global $currentIdIndex;
	$currentId = $id;
	$posts = array();


	
	// Parse $IdFull to get $idPath

	$idsFull = explode(",", $idFull);	
	if ( count($idsFull) > 1 ) {
		$idPath = $idsFull[0];
	} else {
		$idPath = '0';
	}

							
	//  Find "Forum" object

	$sql  = "SELECT objects.id AS objectsId FROM wires, objects ";
	$sql .= "WHERE wires.fromid = '$idPath' AND wires.toid = objects.id AND objects.name1 LIKE 'Discussion Forum' ";
	$sql .= "AND wires.active = 1 AND objects.active = 1 ";
	$sql .= "ORDER BY objects.created DESC LIMIT 1";
	$res  = MYSQL_QUERY($sql);
	$row  = MYSQL_FETCH_ARRAY($res);
	$obj  = $row["objectsId"];


	//  Post a message

	if ($action == "post") {
		
		//  Clean up input

		if (!get_magic_quotes_gpc()) {

			$name1  = "_".$name1;
			$name1  = addslashes($name1);
			$deck   = addslashes($deck);
			$body   = addslashes($body);
		}

		//  Process variables

		if (!$name1) $name1 = "[No Subject]";
		$name1 = textFilter($name1);
		$deck  = textFilter($deck);
		$body  = textFilter($body);

echo "new!" . $body;

		//  Add object to database

		$sql  = "INSERT INTO objects (created, modified, name1, deck, body) ";
		$sql .= "VALUES ('". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."', '$name1', '$deck', '$body')";
		$res  =  MYSQL_QUERY($sql);
		$insertId = MYSQL_INSERT_ID();
		//echo "INSERT ID".$insertId;


		//  Add wire to database
		
		// Need to convert id to just get last part for messageId
		$explodedId = explode(",", $id);
		$messageId = $explodedId[count($explodedId) - 1];
		// echo "messageId = " . $messageId;

		$sql  = "INSERT INTO wires (created, modified, fromid, toid) ";
		// $sql .= "VALUES('". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."', '$id', '$insertId')";

		$sql .= "VALUES('". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."', '$messageId', '$insertId')";
		$res  =  MYSQL_QUERY($sql);
		$id = null;

		// THIS $id?
	}


	//  Write a message

	if ($action == "write") {

		if (is_authed()) {

			$html2  = "You are posting a message to the Discussion Forum.<br />";
			$html2 .= "<a href='".$pageName.".html?action=viewall'>View All Messages</a><br /><br />";
			$html2 .= "<br />";
			$html2 .= "<table cellpadding='0' cellspacing='0' border='0'>";
			$html2 .= "<form enctype='multipart/form-data' action='".$pageName.".html?action=post' method='post' style='margin: 0; padding: 0;'>";
			$html2 .= "<tr><td width='90'>Subject&nbsp; </td>";
			$html2 .= "<td><textarea name='name1' cols='50' rows='1'>";
		
			// Add Re: if replying. Will need to get the previous object that you are replying to... ** IN PROCESS **
			if ($reply) $html2 .= "\nRe: ". $reply;

			$html2 .= "</textarea></td></tr>";
			$html2 .= "<tr><td>From&nbsp; </td>";
			$html2 .= "<td><textarea name='deck' cols='50' rows='1'>" . $userName . "</textarea></td></tr>";
			$html2 .= "<tr><td style='vertical-align: top;'>Message&nbsp; </td>";
			$html2 .= "<td><textarea name='body' cols='50' rows='40'></textarea></td></tr>";
			$html2 .= "<tr><td><!--  --></td>";
			$html2 .= "<td><br />";
			$html2 .= "<input name='id' type='hidden' value='".$id."' />";
			$html2 .= "<input name='action' type='hidden' value='post' />";
			$html2 .= "<input name='submit' type='submit' value='Post Message' />";
			$html2 .= "</form></td></tr>";
			$html2 .= "</table>";
		
			// These are all of the variables which are posted here in this form
			// These must be passed in from the page that uses this function
			// $name1, $deck, $body, $id, $action, $submit
		
			echo $html2;
		
		} else {
			echo "Please <a href='" . $pageName . ".html?user=login'>LOG IN</a> or <a href='" . $pageName . ".html?user=register'>REGISTER</a> to post messages in the Discussion Forum.";
		}

	}


	//  View message selected


	if ( $id != $obj ) {
	
		// systemForumMapper($idFull, 2, TRUE);	
		// systemForumMapper("3,386,390", 2, TRUE);	
		// systemForumMapper("0,386,390", 2, TRUE);
		$idBasePad = "0," . $idBase;
		systemForumMapper($idBasePad, 10, TRUE);
		
		$postOlderIndex = ( $currentIdIndex < 2 ) ? 1 : $currentIdIndex - 1;
		$postNewerIndex = ( $currentIdIndex > count($posts) - 2 ) ? count($posts) - 1 : $currentIdIndex + 1;

		$postNewer = $posts[$postNewerIndex];
		$postOlder = $posts[$postOlderIndex];
		
		/*
		// Debug for $ids passing
		
		echo "id = " . $id . " / " . "idFull = " . $idFull . " / " . "idBase = " . $idBase  . " / " . "idBasePad = " . $idBasePad  . "<br />";
		echo $postOlderIndex . " / " . $postNewerIndex . "<br />";
		echo $postOlder . " / " . $postNewer . "<br />";
		*/	
			
		
		//  Get selected "Forum" object

		$sql = "SELECT * FROM objects WHERE id = '$id' AND objects.active = 1 LIMIT 1";
		$res =  MYSQL_QUERY($sql);
		$row =  MYSQL_FETCH_ARRAY($res);

		if ( !$action ) {
			
			$html .= "<a href='".$pageName.".html?id=$postOlder'>Previous</a> / ";
			$html .= "\n<a href='".$pageName.".html?action=viewall'>View All Messages</a> / ";
			$html .= "<a href='".$pageName.".html?id=$postNewer'>Next</a>";
			$html .= "\n<br />";
			if (count($ids) < 2) {
				$replySubject .= substr($row['name1'],1);	
				//$idComplete = $idFull . "," . $id;
				//echo "<br />" . $id . "<br />" . $idFull . "<br />" . $idComplete;
				$html .= "\n<a href='".$pageName.".html?action=write&amp;id=$idFull&amp;reply=$replySubject'>Reply to this message</a>";
			}
			// $html .= "\n<a href='".$pageName.".html?action=write&amp;id=$idBase'>Post a new message</a> / ";
			// $html .= "\n<a href='".$pageName."Print.html?id=".$idFull."'>Print</a>";
			$html .= "<br /><br />";
	
			// Write out the body of the selected message
			
			// Account for "_" which is at the beginning of each open record
			if ( substr($row['name1'], 0, 1) == "_" ) $nameDisplay = substr($row['name1'], 1); 
	
			$html .= "\n<table cellspacing='0' cellpadding='0'>";	
			//$html .= "\n<tr><td style='padding-right: 12px;'>Subject</td><td>".$row['name1']."</td></tr>";
			$html .= "\n<tr><td style='padding-right: 12px;'>Subject</td><td>".$nameDisplay."</td></tr>";
			$html .= "\n<tr><td style='padding-right: 12px;'>Date</td><td>" .date('j F Y H:i:s', strtotime($row['created'])) ."</td></tr>";
			$html .= "\n<tr><td style='padding-right: 12px;'>From</td><td>".$row['deck']."</td></tr>";
			$html .= "\n</table><br />";
			$html .= "\n". nl2br($row['body']) ."<br /><br />";

		}
		if (!$action) {

			$html .= "<a href='".$pageName.".html?id=$postOlder'>Previous</a> / ";
			$html .= "\n<a href='".$pageName.".html?action=viewall'>View All Messages</a> / ";
			$html .= "<a href='".$pageName.".html?id=$postNewer'>Next</a>";
			$html .= "\n<br />";
			if (count($ids) < 2) {
				$replySubject .= substr($row['name1'],1);		
				$html .= "\n<a href='".$pageName.".html?action=write&amp;id=$idFull&amp;reply=$replySubject'>Reply to this message</a>";
			}
			// $html .= "\n<a href='".$pageName.".html?action=write&amp;id=$idBase'>Post a new message</a> / ";
			// $html .= "\n<a href='".$pageName."Print.html?id=".$idFull."'>Print</a>";

		}
		echo $html;
	}
		if ( $action == 'post' ) {

			$html3  = "You are posting to the Discussion Forum.<br />";
			$html3 .= "<a href='".$pageName.".html?action=viewall'>View All Messages</a><br /><br />";
			$html3 .= "Your message has been posted as of ". date('j F Y H:i:s')."<br />";
			$html3 .= "Please <a href='".$pageName.".html'>click here to continue</a>...";
			echo $html3;
		}

	//  Show all messages

	if ( ($id == $obj && !$action) || ($id == $obj && $action == 'viewall') ) {
	
		echo "You are viewing all posts in the Discussion Forum.<br /><br />";

		systemForumMapper($id, 10);
		
		echo "<br /><a href='".$pageName.".html?action=write&amp;id=$idFull'>Post a new response to the essay</a><br/><br/>";

	}
}							













function systemForumMapper($objects = "0", $limit = null, $quiet = null) {

	// Displays tree structure, Returns $id list
	
	global $pageName;
	global $idFull;
	global $childObject;
	global $posts;
	global $currentId;
	global $currentIdIndex;
 
	//  Handle recursion 

	$limitNext = ($limit === null) ? null : $limit - 1;
	$obj   = explode(",", $objects);
	$depth = count($obj) - 1;
	$first = $obj[1];
	$final = $obj[$depth];
	//$base = $objects[0];
	//$base = $objects[1];
	//echo "base = " . $base;
	
	$id = null;	
	for ($i = 1; $i < count($obj); $i++) {

		if ($i > 1) $id .= ",";
		$id .= $obj[$i];
	}


	//  Pull from current node

	$sql = "SELECT * FROM objects 
			WHERE id = '$final' 
			AND active = 1 
			LIMIT 1";
	$res = MYSQL_QUERY($sql);
	$row = MYSQL_FETCH_ARRAY($res);
	$name  = strip_tags($row["name1"]);
	$deck  = strip_tags($row["deck"]);

	if ( !$name ) $name = "Untitled";
	if ( substr($name, 0, 1) != "." ) {


	if ($quiet) {
		  
		//  Populate $posts[] array
		//  Check to see if currentId is found to locate Array index	
		
		// $posts[] = $base . "," . $id;
		$posts[] = $id;

		if ($final == $currentId) {
		
			// find a match in the array and set currentId and then can get rid of counter
			for ($i = 0; $i < count($posts); $i++) {
				
				// if ($posts[$i] == $base . "," . $id) {
				if ($posts[$i] == $id) {
					$currentIdIndex = $i;
					// echo "MATCH FOUND / currentIdIndex = " . $currentIdIndex;		
				}
			}
		}
		

		/*
		// Debug for SystemForumMapper $quiet
		echo "<pre>";
		print_r($posts);
				print($posts);
		echo "<br/>";
		echo "</pre>";
		*/
		
	} else {
		
		//  Display current node
		
		if (count($obj) > 1) {	
		
			$html  = "\n<div style='padding-left: ". (($depth - 1) * 20) ."px;'>";
			$html .= "<a href='".$pageName.".html?id=".$idFull.",".$id."'>";
			$html .=  date("j F Y", strtotime($row["created"])) . " / ";
			$html .= strip_tags(substr($name, 1)) . " / ";			
			$html .= strip_tags(substr($deck,0)) ;		
			$html .= "</a></div>";
			echo $html;
		}
	} 
		
	
		//  Find children of current node
	
		if ($limit > 0 || $limit === null) {
	
			$sql = "SELECT objects.id AS objectsId FROM wires, objects 
					WHERE wires.fromid = '$final' AND wires.toid = objects.id 
					AND wires.active = 1 AND objects.active = 1 
					ORDER BY objects.created ASC
					";
			$res = MYSQL_QUERY($sql);	
			while ($row = MYSQL_FETCH_ARRAY($res)) {
			
				$tmp = $objects .",". $row["objectsId"];
				systemForumMapper($tmp, $limitNext, $quiet);
			}
		}
	}	
}






//  Text Filter

function textFilter($text = null) {

	$filter = array ( 
		array("fuck", "---"), 
		// add others here.
	); 

	for ($i = 0; $i < sizeof($filter); $i++) {

		$text = eregi_replace($filter[$i][0], $filter[$i][1], $text);
	}
	return $text;
}



?>			