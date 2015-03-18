<?php




  //////////////////
 //  Parse TEXT  //
//////////////////

function orgTextParse($url) {

	//  Requires "php_flag allow_url_fopen on" in .htaccess

	$file = @fopen($url, "r");
	if ($file) {
		$parsedText = null;
		while (!feof($file)) {

			$line = @fgets($file, 4096);
			//$parsedText .= "\n". strip_tags($line);
			$parsedText .= "\n". $line;
		}
	}
	@fclose($file);
	return $parsedText;
}








?>