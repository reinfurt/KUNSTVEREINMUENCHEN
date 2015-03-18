<?php
$pageFullName = basename($PHP_SELF);
if ($id) $pageFullName .= "?id=" . $idFull;
if ($note) $pageFullName .= "&note=" . $note;





  /////////////
 //  Init   //
/////////////

// Start the session, seed the random number generator

session_start();
srand();




  //////////////////////
 //  Salt Generator  //
//////////////////////

function generate_salt () { 

	$salt = '';
	for ($i = 0; $i < 3; $i++) { 
	
		$salt .= chr(rand(35, 126)); 
	} 
	
	return $salt;
}




  //////////////////////
 //  User Register   //
//////////////////////

function user_register($username, $password, $email) {

	$salt = generate_salt();
	$encrypted = md5(md5($password).$salt);
//	$query = "INSERT into users (username, password, salt) values ('$username', '$encrypted', '$salt')";
	$query = "INSERT into users (username, password, salt, email) values ('$username', '$encrypted', '$salt', '$email')";
	mysql_query ($query) or die ('Could not create user.');
}




  //////////////////////
 //  User Login      //
//////////////////////

function user_login($username, $password) {

	// Try and get the salt from the database using the username

	$query = "SELECT salt FROM users WHERE username='$username' LIMIT 1";
	$result = mysql_query($query);
	$user = mysql_fetch_array($result);

	// Using the salt, encrypt the given password to see if it matches the one in the database

	$encrypted_pass = md5(md5($password).$user['salt']);

	// Try and get the user using the username & encrypted pass
	
	$query = "SELECT userid, username FROM users WHERE username='$username' AND password='$encrypted_pass'";
	$result = mysql_query($query);
	$user = mysql_fetch_array($result);
	$numrows = mysql_num_rows($result);
	
	// Now encrypt the data to be stored in the session
	
	$encrypted_id = md5($user['userid']);
	$encrypted_name = md5($user['username']);
	
	// Store the data in the session
	
	$_SESSION['userid'] = $userid;
	$_SESSION['username'] = $username;
	$_SESSION['encrypted_id'] = $encrypted_id;
	$_SESSION['encrypted_name'] = $encrypted_name;
	
	if ($numrows == 1) { 
	
		return "Correct";
	} else {
	
		return false;
	}
}




  //////////////////////
 //  User Logout     //
//////////////////////

function user_logout() {
	
	// End the session and unset all vars
	
	session_unset ();
	session_destroy ();
}




  //////////////////////
 //  Authorized?     //
//////////////////////

function is_authed() {

	// Check if the encrypted username is the same
	// as the unencrypted one, if it is, it hasn't been changed
	
	if (isset($_SESSION['username']) && (md5($_SESSION['username']) == $_SESSION['encrypted_name'])) {
	
		return true;
	} else {
	
		return false;
	}
}




  ///////////////////////
 //  Display Register //
///////////////////////

function displayRegister($pageNameLocal) {

	if (isset($reg_error)) { 

		echo "There was an error: ".  $reg_error . ", please try again.";
	} 

	//$html =  "\n -- <br/><br/>PLEASE REGISTER BELOW:<br/><br/>";
	// $html =  "\n -- <br/><br/>";
	$html  = "\n	<form action='" . $pageNameLocal . ".html?user=register&accept=true' method='post'>";
	$html .= "\n	Username: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' size='20' maxlength='20' name='username'";
	if (isset($_POST['username'])) $html .= "value='" . $_POST['username'] ."'";
	$html .= "><br /> ";
	$html .= "\n	Password: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='password' size='20' maxlength='10' name='password' /><br />";
	$html .= "\n	Confirm Password: <input type='password' size='20' maxlength='10' name='confirmpass' /><br />";
	$html .= "\n	Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' size='20' maxlength='50' name='email' />&nbsp;";
	$html .= "\n	<input type='submit' name='submit' value='Register' /></form>";
	
	echo $html;
}




  ///////////////////////
 //  Display Login    //
///////////////////////

function displayLogin($pageNameLocal) {

	if (isset($login_error)) { 

		echo "There was an error: ".  $login_error . ", please try again.";
	} 

	// Display form
	
	$html = "\n	<form action='" . $pageNameLocal . ".html?user=login' method='post'>";
	$html .= "\n	Username: <input type='text' size='20' maxlength='20' name='username'";
	if (isset($_POST['username'])) $html .= "value='" . $_POST['username'] ."'";
	$html .= "/><br /> ";
	$html .= "\n	Password: <input type='password' size='20' maxlength='10' name='password' />&nbsp;";
	$html .= "\n	<input type='submit' name='submit' value='Login' /></form>";
		
	echo $html;
		
}




?>