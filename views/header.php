<div id="header">
	<div id="date"><a href="/"><?php echo $time; ?></a></div>
	<div id="menu"><?
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
</div>