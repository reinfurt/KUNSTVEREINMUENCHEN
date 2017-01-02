<?
$media_arr = $oo->media($uu->id);
?><div id="main-container" class="no-gallery" style="width: 80%; margin-left: auto;">
	<div class="content">
		<div class="images"><?
			// foreach($media_arr as $media) {
				// credits
				?><div class="press-credits">
					Please include credit Kunstverein München for all uses offline and online.
				</div><?
			// }
			foreach($media_arr as $media)
			{
				$media_url = m_url($media);
			?><div class="press-image">
				<a href="<? echo $media_url; ?>">
					<div class="press-div" style="background-image: url('<? echo $media_url; ?>');"></div>
				</a>
				<div class="caption"><?
				echo $media['caption'];
				?></div>
			</div>
			<?
			}
		?></div>
	</div>
</div>
