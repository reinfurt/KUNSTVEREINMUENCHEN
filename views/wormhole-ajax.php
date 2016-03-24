<?
$config = $_SERVER["DOCUMENT_ROOT"];
$config = $config."/open-records-generator/config/config.php";
require_once($config);

$db = db_connect("guest");
$sql = "SELECT *
		FROM media
		WHERE active = 1
		ORDER BY RAND()
		LIMIT 1";

$res = $db->query($sql);
$m = $res->fetch_assoc();
$url = m_url($m);

$w = rand(20, 40);
$lr_anchor = rand(0, 1) ? "left" : "right";
$lr_d = rand(0, 60);

$tb_anchor = rand(0, 1) ? "top" : "bottom";
$tb_d = rand(0, 30);
$style = "width: {$w}vw;";
$style.= "$lr_anchor: {$lr_d}vw;";
$style.= "$tb_anchor: {$tb_d}vh;";

?><div id="wormhole" style="<? echo $style; ?>" >
	<img src="<? echo $url; ?>">
</div>