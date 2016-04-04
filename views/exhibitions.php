<?			
$e_root = array();
$e_root['de'] = array();
$e_root['en'] = array();
$e_root['de']['id'] = 421;
$e_root['en']['id'] = 412;
$e_root['de']['url'] = "/de/programm/ausstellungen/2016-1823";
$e_root['en']['url'] = "/en/program/exhibitions/2016-1823";

$id = $e_root[$lang]['id'];;
$url = $e_root[$lang]['url'];

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