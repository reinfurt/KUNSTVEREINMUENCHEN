<link rel="stylesheet" type="text/css" media="all" href="/static/css/cube.css">
<script type="text/javascript" src="/static/js/cube.js"></script>
<div id="header">
	<div id="date"><a href="javascript:show();"><?php echo $time; ?></a></div>
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
		<div class="f2">
			<li>
				<a href"/">menu-alt</a>
			</li>
			<ul class="nav-level">
				<li>
					<a href"/">menu-alt</a>
				</li>
			</ul>
		</div>
	</div>
</div>