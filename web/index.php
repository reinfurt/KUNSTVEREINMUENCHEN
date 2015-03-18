<?php
$pageName = basename(__FILE__, ".php");
$showMenu = TRUE;
require_once('GLOBAL/head.php');

$rootid = $ids[0];
// SQL object plus media plus rootname
$sql = "SELECT 
			objects.id AS objectsId, 
			objects.name1, 
			objects.deck, 
			objects.body, 
			objects.notes, 
			objects.active, 
			objects.begin, 
			objects.end, 
			objects.rank as objectsRank, 
			(SELECT objects.name1 
			FROM objects 
			WHERE objects.id = $rootid) AS rootname, 
			media.id AS mediaId, 
			media.object AS mediaObject, 
			media.type, 
			media.caption, 
			media.active AS mediaActive, 
			media.rank 
		FROM objects 
		LEFT JOIN media 
		ON 
			objects.id = media.object 
			AND media.active = 1 
		WHERE 
			objects.id = $id 
			AND objects.active 
		ORDER BY media.rank;";

$result = MYSQL_QUERY($sql);
$myrow = MYSQL_FETCH_ARRAY($result);
$rootname = $myrow['rootname'];
$name = nl2br($myrow['name1']);
$body = nl2br($myrow['body']);
$notes = nl2br($myrow['notes']);
$begin = $myrow['begin'];
$end = $myrow['end'];
$images = array();
$i = 0;
$images[] = "";
$imageFiles[] ="";

// reset to row 0
mysql_data_seek($result, 0);

// collect images
while ($myrow  =  MYSQL_FETCH_ARRAY($result))
{
	if ($myrow['mediaActive'] != null)
	{
		$mediaFile = "MEDIA/";
		$mediaFile .= str_pad($myrow["mediaId"], 5, "0", STR_PAD_LEFT);
		$mediaFile .= "." . $myrow["type"];
		$mediaCaption = strip_tags($myrow["caption"]);
		$mediaStyle = "width: 100%;";
		$imageFiles[$i] = $mediaFile;

		// build random styles
		$randomPadding = rand(0, 15);
		$randomPadding *= 10;
		if ($isMobile)
			$randomWidth = rand(7, 9);
		else
			$randomWidth = rand(2, 5);
		$randomWidth *= 10;
		
		$randomFloat = (rand(0, 1) == 0) ? 'left' : 'right';
		$icStyle = 'width:'.$randomWidth.'%; ';
		$icStyle .= 'float:'.$randomFloat.'; ';
		$icStyle .= 'padding-top:'.$randomPadding.'px; ';
		$icStyle .= 'margin: 40px;'; 

		$images[$i] .= "<div ";
		$images[$i] .= "id='image".$i."' ";
		$images[$i] .= "class='imageContainer' ";
		$images[$i] .= "onclick='launch(".$i.");' ";
		if (!$isMobile)
		{
			$images[$i] .= "style='".$icStyle."' ";
			//$images[$i] .= "data-anchor-target='#image".$i."' ";
			$images[$i] .= "data-start='transform: translate3d(0px, 0%, 0px);' ";
			//$images[$i] .= "data-center-center='transform: translate3d(0px, 0%, 0px);' ";
			$images[$i] .= "data-end='transform: translate3d(0px, -100%, 0px);'";
		}
		$images[$i] .= ">";
		
		$images[$i] .= "<div class='image-hover'>";
		
		$images[$i] .= displayMedia($mediaFile, $mediaCaption, $mediaStyle);
		$images[$i] .= "</div>";
		$images[$i] .= "<div class = 'caption'>";
		$images[$i] .= ($i+1);	
		$images[$i] .= "</div>";
		$images[$i] .= "</div>";
	}
	$i++;
}

$bodyData = "data-start='transform: translate3d(0px, 0%, 0px);' ";
$bodyData .= "data-end='transform: translate3d(0px, 0%, 0px);' ";
$bodyData = ""; // clear skrollr info for body

?>


<div class="content" id="skrollr-body">
	<?php if (!$isMobile) { ?>
	<div 
		class="body"
		<?php echo $bodyData ?>
	>
		<?php echo $body; ?>
	</div>
	<?php } ?>
	<?php 
		if(count($images) > 1) {
	?>
		<?php if($isMobile) { ?>
			<div id="slider" class="swipe">
				<div class="swipe-wrap">
		<?php } ?>
		<?php
			$html = "";
			for($i = 0; $i < count($images); $i++)
				$html .= $images[$i];
			echo $html;
		?>
		<?php /* force div to have height */ ?>
		<?php if($isMobile) { ?>
				</div>
		<?php } ?>
		<div class="clearer"></div>
		<?php if($isMobile) { ?>
			</div>
		<?php } ?>
	<!--/div-->
	<?php } ?>
</div>

<?php if ($isMobile) { ?>
	<script type='text/javascript' src='GLOBAL/swipe.js'></script>
	<script type='text/javascript'>
		window.mySwipe = Swipe(document.getElementById('slider'));
	</script>
<?php } ?>

<script type="text/javascript">
var scroll;
var index;
var images = <?php echo json_encode($imageFiles); ?>;
var inGallery = false;

function launch(i) {
	scroll = document.body.scrollTop; // store scroll position
	setbg(images[i]); // display image
	index = i; // store current image index
	inGallery = true;
}

function prev() {
	if(index == 0)
		index = images.length;
	index--;
	setbg(images[index]);
}

function next() {
	if(index == images.length-1)
		index = -1;
	index++;
	setbg(images[index]);
}

document.onkeydown = function(e) {
	if(inGallery) {
		e = e || window.event;
		switch(e.which || e.keyCode) {
			case 37: // left
				prev();
			break;
			case 39: // right
				next();
			break;
			case 27: // esc
				closeGallery();
			break;
			default: return; // exit this handler for other keys
		}
		e.preventDefault();
	}
}
</script>

<?php
require_once('GLOBAL/foot.php');
?>