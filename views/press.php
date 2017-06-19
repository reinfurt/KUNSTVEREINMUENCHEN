<?
$media_arr = $oo->media($uu->id);
?><div id="main-container" class="no-gallery" style="width: 80%; margin-left: auto;">
	<div class="content">
		<div class="images"><?
			// foreach($media_arr as $media) {
				// credits
				?><div class="press-credits"><? 
					if($lang == "en") {
						?>
For more information and images please contact us via <a href="mailto:presse@kunstverein-muenchen.de">email</a> or call +49 (0)89 22 11 52
<br /><br />
Please credit Künstverein Munchen e.V. in all reproductions offline and online and use the captions as they 
appear underneath the images.
						<?
					} else {
						?>
Für weiteres Informations- und Bildmaterial wenden Sie sich bitte <a 
href="mailto:presse@kunstverein-muenchen.de">per Mail</a> an unsere Pressestelle oder rufen Sie uns an unter +49 
(0)89 22 11 52
<br /><br />
Die Verwendung des vorliegenden Bildmaterials ist nur im Zusammenhang mit einer Berichterstattung (print/online) 
und nur unter Verwendung der entsprechenden Bildnachweise gestattet.
						<?
					}
				?></div><?
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
