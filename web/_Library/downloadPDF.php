<?php



  //////////////////
 //  downloadPDF //
//////////////////

function downloadPDF($sourceFile = null, $downloadFile = null, $timeStamp = null) {


	//  Writes document headers to force a PDF download 
	//  Rename the file, add date stamp optional
	//  Must be sent before * any * output to browser
	//  Checks to see if file exists before trying to send it
	//  And dies if not 

	$sourceFile = 'MEDIA/' . $sourceFile . ".pdf";
	if ( !$downloadFile ) $downloadFile = "download";		
	// if ( $timeStamp ) $downloadFile .= " -- " . $timeStamp;
	$downloadFile .= ".pdf";
		
	$contentType = "Content-type: application/pdf";
	$contentDispositionFilename = "Content-Disposition: attachment; filename=" . $downloadFile;

	if(file_exists($sourceFile) && is_file($sourceFile)) {

		header($contentType);
		header($contentDispositionFilename);		
		readfile($sourceFile);
		
	} else {		
		
		echo "I am sorry, there is no valid PDF available to download.";
		exit(0);
	}

	return $true;	
}



function downloadPDFfromStream($sourceFileStream = null, $downloadFile = null, $timeStamp = null) {


	//  Writes document headers to force a PDF download 
	//  and works from a raw rendered PDF stream
	
	if ( !$downloadFile ) $downloadFile = "download";		
	// if ( $timeStamp ) $downloadFile .= " -- " . $timeStamp;
	$downloadFile .= ".pdf";
		
	$contentType = "Content-type: application/pdf";
	$contentDispositionFilename = "Content-Disposition: attachment; filename=" . $downloadFile;

	if($sourceFileStream) {

		header($contentType);
		header($contentDispositionFilename);		
		echo $sourceFileStream;
		// readfile($sourceFile);
		
	} else {		
		
		echo "I am sorry, there is no valid PDF stream available.";
		exit(0);
	}

	return $true;	
}

?>