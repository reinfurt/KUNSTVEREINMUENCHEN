<link rel="stylesheet" type="text/css" media="all" href="/static/css/cube.css">
<script type="text/javascript" src="/static/js/cube.js"></script>
<div id="header">
	<div id="date"><a href="javascript:rotate_menu();"><?php echo $time; ?></a></div>
	<div id="menu-cube">
		<div class="f1"><?
			$prevd = $nav[0]['depth'];
			foreach($nav as $n)
			{
				$d = $n['depth'];
				if($d > $prevd)
				{ ?><ul class="nav-level"><? }
				else
				{
					for($i = 0; $i < $prevd - $d; $i++)
					{ ?></ul><? }
				}
				?><li>
					<a href="<? echo $host.$lang_url[$lang].'/'.$n['url']; ?>"><?
						echo $n['o']['name1'];
					?></a>
				</li><?
				$prevd = $d;
			}
			?><div class="clearer"></div>
		</div>
		<div class="f2"><?
			$menu_alt = array();
			$menu_alt['de'] = 1517;
			$menu_alt['en'] = 1518;
			$menu_id = $menu_alt[$lang];
			$menu = $oo->get($menu_id);
			$menu_body = $menu['body'];
			$menu_items = explode(PHP_EOL, $menu_body);
			?><li><? echo trim($menu['name1'], "."); ?></li>
			<ul><?
				foreach($menu_items as $i)
				{
				?><li><? echo $i; ?></li><?
				}
			?></ul>
		</div>
	</div>
</div>