<?
$config = $_SERVER["DOCUMENT_ROOT"];
$config = $config."/open-records-generator/config/config.php";
require_once($config);
$db = db_connect("guest");
$oo = new Objects();
function get_cookie($name)
{
	if(isset($_COOKIE[$name]))
		return $_COOKIE[$name];
	else
		return null;
}
$lang = get_cookie("lang");
$root = $lang == "de" ? 1 : 2;
$sql = "SELECT *
		FROM media
		WHERE active = 1
		ORDER BY RAND()
		LIMIT 1";
// this is really inefficient!
do 
{
	$res = $db->query($sql);
	$m = $res->fetch_assoc();
	$m_url = m_url($m);
	$ancestors = $oo->ancestors_single($root, $m['object']);
}
while(empty($ancestors));
array_unshift($ancestors, $root);
$ancestors[] = $m['object'];
$o_url = $oo->ids_to_urls($ancestors);
$o_url = "/".implode("/", $o_url);
// style stuff
$w = rand(20, 40);
$lr_anchor = rand(0, 1) ? "left" : "right";
$lr_d = rand(0, 60);
$tb_anchor = rand(0, 1) ? "top" : "bottom";
$tb_d = rand(0, 30);
$style = "width: {$w}vw;";
$style.= "$lr_anchor: {$lr_d}vw;";
$style.= "$tb_anchor: {$tb_d}vh;";
?><div id="wormhole" style="<? echo $style; ?>" >
	<a href="<? echo $o_url; ?>"><img src="<? echo $m_url; ?>"></a>
</div>