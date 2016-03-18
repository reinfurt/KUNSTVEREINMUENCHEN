<?
function is_valid_email($email)
{
	// return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email);
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function send_mail($from, $to, $subject, $message)
{
	// address headers
	$header = "From: $sender <$sender>\r\n";
	$header.= "X-X-Sender: $sender\r\n";
	
	// mail program headers
	$header.= "Message-ID: <".md5(uniqid(time()))."@{$_SERVER['SERVER_NAME']}>\r\n";
	
	mail($to, $subject, $message, $header, "-f$from");
}

function send_mail_long($from_name, $from_email, $to_name, $to_email, $subject, $message)
{
	$header = "From: $from_name <$from_email>\r\n";
	$header.= "Message-ID: <".md5(uniqid(time()))."@{$_SERVER['SERVER_NAME']}>\r\n";
	$to = "$to_name <$to_email>";
	mail($to, $subject, $message, $header, "-f$from_email");
}

function uri_is($uri)
{
	return $uri == $_SERVER['REQUEST_URI'];
}
?>