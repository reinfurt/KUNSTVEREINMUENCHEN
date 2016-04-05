<?
// archive pages
// + exhibitions
// + kino
// + publications

$e = array();
$e['exh'] = array();
$e['kin'] = array();
$e['pub'] = array();

$e['exh']['de'] = array();
$e['exh']['en'] = array();
$e['exh']['de']['id'] = 421;
$e['exh']['en']['id'] = 412;
$e['exh']['de']['url'] = "/de/programm/ausstellungen/2016-1823";
$e['exh']['en']['url'] = "/en/program/exhibitions/2016-1823";

$e['pub']['de'] = array();
$e['pub']['en'] = array();
$e['pub']['de']['id'] = 12;
$e['pub']['en']['id'] = 6;
$e['pub']['de']['url'] = "/de/programm/publikationen";
$e['pub']['en']['url'] = "/en/program/publications";

$e['evt']['de'] = array();
$e['evt']['en'] = array();
$e['evt']['de']['id'] = 39;
$e['evt']['en']['id'] = 31;
$e['evt']['de']['url'] = "/de/programm/veranstaltungen";
$e['evt']['en']['url'] = "/en/program/events";

$e['sch']['de'] = array();
$e['sch']['en'] = array();
$e['sch']['de']['id'] = 1434;
$e['sch']['en']['id'] = 1413;
$e['sch']['de']['url'] = "/de/programm/schaufenster";
$e['sch']['en']['url'] = "/en/program/schaufenster";

$id = $e[$a_type][$lang]['id'];;
$url = $e[$a_type][$lang]['url'];

$exhibitions = array();

$sql = "select 
			objects.id, url 
		from 
			objects, wires 
		where 
			wires.fromid = $id 
			and objects.id = wires.toid 
			and wires.active = 1
			and objects.name1 not like '.%'";
$res = $db->query($sql);

while($r = $res->fetch_assoc())
{
	$id = $r['id'];
	$sql = "select 
				objects.id, name1, url 
			from 
				objects, wires 
			where 
				wires.fromid = $id 
				and objects.id = wires.toid 
				and wires.active = 1
				and objects.name1 not like '.%'";
	$res2 = $db->query($sql);
	$u = $url."/".$r['url'];
	while($r2 = $res2->fetch_assoc())
	{
		$n = $r2['name1'];
		$m = strip_tags($n);
		$exhibitions[$m] = array();
		$exhibitions[$m]['name'] = $n;
		$exhibitions[$m]['url'] = $u."/".$r2['url'];
	}
}
?>
<div id="main-container" class="no-gallery">
	<div class="content">
		<div class="text-container">
			<div class="text">
				<ul class="exhibitions"><?
				// for whatever reason, 
				// need to bitwise OR SORT_FLAG_CASE and SORT_STRING
				// to get case-insensitive sorting here
				// http://php.net/manual/en/function.sort.php
				ksort($exhibitions, SORT_FLAG_CASE | SORT_STRING);
				foreach($exhibitions as $k => $v)
				{
				?><li><a href="<? echo $v['url']; ?>"><? echo $v['name']; ?></a></li><?
				}
				?></ul>
			</div>
		</div>
	</div>
</div>