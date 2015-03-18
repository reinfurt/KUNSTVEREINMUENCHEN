<?php




  /////////////////////
 //  Email Builder  //
/////////////////////

function orgEmail($sender, $recipient, $subject, $message) {

	$sender .= "";


	//  Address Headers

	$header  = "From: $sender <$sender>\r\n";
	$header .= "X-X-Sender: $sender\r\n";
	//$header .= "To: $recipient <$recipient>\r\n";
	//$header .= "Subject: $subject\r\n";


	//  Additional Headers
	
	//$header .= "Reply-to: $sender <$sender>\r\n";
	//$header .= "Cc: cc@example.com\r\n";
	//$header .= "Bcc: bcc@example.com\r\n";


	//  Mail Program Headers

	$header .= "Message-ID: <".md5(uniqid(time()))."@{$_SERVER['SERVER_NAME']}>\r\n";
	//$header .= "MIME-Version 1.0\r\n";
	//$header .= "Content-Type: TEXT/PLAIN; charset=US-ASCII\r\n";

	mail($recipient, $subject, $message, $header, "-f$sender");
}








?>