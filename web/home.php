<?php
require_once('GLOBAL/head.php');

// get objects plus media
$sql = "SELECT 
			*, 
			objects.id AS objectsId 
		FROM 
			objects, wires 
		WHERE 
			wires.fromid = '". $id ."' 
			AND wires.toid = objects.id 
			AND wires.active = '1' 
			AND objects.active = '1' 
		ORDER BY 
			weight DESC, 
			objects.rank, 
			end DESC, 
			begin DESC, 
			name1, 
			name2, 
			objects.modified DESC, 
			objects.created DESC";

$result = mysql_query($sql) or die("<p>sorry</p>");

require_once('GLOBAL/foot.php');
?>