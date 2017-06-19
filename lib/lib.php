<?
function is_valid_email($email)
{
	// return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email);
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function send_mail($from, $to, $subject, $message)
{
	// address headers
	$header = "From: $from <$from>\r\n";
	$header.= "X-X-Sender: $from\r\n";

	// mail program headers
	$header.= "Message-ID: <".md5(uniqid(time()))."@{$_SERVER['SERVER_NAME']}>\r\n";
	
	// character encoding
	$header.= "Content-Type: text/html; charset='UTF-8'";
	$header.= "Content-Transfer-Encoding: 8bit";

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

function set_cookie($name=null, $value=null, $expires=null, $path="/")
{
	if(!empty($value))
	{
		setcookie($name, $value, $expires, $path);
	}
}

function get_cookie($name)
{
	if(isset($_COOKIE[$name]))
		return $_COOKIE[$name];
	else
		return null;
}

?>
