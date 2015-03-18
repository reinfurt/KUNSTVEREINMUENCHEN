<?php
// THIS FILE IS ALWAYS INCLUDED ON ANY PAGE THAT REQUIRES LOGIN



/*
// SET FULL PAGE PATH

// $pageName, from head.php = name only, no .html || $fullPageName = with query string
// always use only $pageName except use $fullPageName to continue if neccessary

$pageFullName = basename($PHP_SELF);
if ($id) $pageFullName .= "?id=" . $idFull;
if ($note) $pageFullName .= "&note=" . $note;
*/




// MENU

$html 	= '';

if ($user == 'login' || $user == 'continue' || !$user) {
	$html   .= "LOG IN / ";
} else {
	$html   .= "<a href='" . $pageName . ".html?user=login'>LOG IN</a> / ";
}

if ($user == 'logout') {
	$html   .= "LOG OUT / ";
} else {
	$html   .= "<a href='" . $pageName . ".html?user=logout'>LOG OUT</a> / ";
}

if ($user == 'register') {
	$html   .= "REGISTER";
} else {
	$html   .= "<a href='" . $pageName . ".html?user=register'>REGISTER</a>";
}

$html .= '<br/><br/>';
echo $html;




// need to clean up these control stuctures!

// LOGIN

if ($user == 'login' || $user == 'continue' || !$user) {

	if (!is_authed()) {
	
		/*
		// Display instruction text
			
		$html  =	"\n	If you don't have a Username and Password, please <a href='" . $pageName . ".html?user=register'>REGISTER here</a>.";
		$html .= 	"\n <br /><br />";
		echo $html;
		*/
		
		if (!isset($_POST['submit'])) {

			// Display form
	
			displayLogin($pageName);
	
		} else {
	
			// Try and login with the given username & pass
	
			$result = user_login($_POST['username'], $_POST['password']);
			if ($result != 'Correct') {

				// Display form with the error
		
				$login_error = $result;
				displayLogin($pageName);
			} else {

				echo 'Currently logged in as ';	
				echo strtoupper($_SESSION['username']) . ".<br/>";
				echo "<a href='" . $pageName . ".html?user=continue'>Click here</a> to CONTINUE</a>.";

			} 	
		}
	} else {	
	
		echo	'Currently logged in as ';	
		echo 	strtoupper($_SESSION['username']) . ".<br/>";
	}
}




// REGISTER

if ($user == 'register') {

	if (!is_authed()) {

		if (!$accept) {

			// Display terms of use
			
			echo 	"\n	Please READ and ACCEPT our terms of use below:

					<blockquote>BY REGISTERING AT WWW.WORDSWITHOUTPICTURES.ORG 
					I REPRESENT THAT MY WRITTEN CONTRIBUTIONS TO THIS WEBSITE 
					ARE ORIGINAL AND AGREE THAT MY CONTRIBUTIONS MAY, AT LACMA'S 
					DISCRETION, BE INCLUDED, IN WHOLE OR IN PART, IN A WORDS 
					WITHOUT PICTURES PUBLICATION.</blockquote>";

			echo 	"\n <a href='" . $pageName . ".html?user=register&accept=true'>
					Click here to ACCEPT and continue</a>.";
				
			echo 	"\n <br /><br />";
				
	
		} elseif ($accept == 'true') {
			
			if (!isset($_POST['submit'])) {
			
				// Display instruction text and show the form
				
				echo 	"\n	Please select a Username, Password and valid email by completing the form below. 
						This will be your LOG IN information and will remain PRIVATE -- 
						we will not share any of this with a third party.
						Please make a note of your information.
						";
				echo 	"\n <br /><br />";
				
				displayRegister($pageName);				
			} else {

				// Check if any of the fields are missing

				if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirmpass'])) {

					// Reshow the form with an error
					$reg_error = 'One or more fields missing';
					displayRegister($pageName);
				}

				// Check if the passwords match

				if ($_POST['password'] != $_POST['confirmpass']) {
	
					// Reshow the form with an error
					$reg_error = 'Your passwords do not match';
					displayRegister($pageName);
	
				}
	
				// Everything is ok, register

				user_register ($_POST['username'], $_POST['password'], $_POST['email']);
				echo "Thank you for Registering. <br />";
				echo "<a href='" . $pageName . ".html?user=login'>Click here to LOG IN</a> using this information.";
			}
		}
	} else {
		
			echo	'Currently logged in as ';	
			echo 	strtoupper($_SESSION['username']) . ".<br/>";
			echo	"Please <a href='" . $pageName . ".html?user=logout'>LOGOUT</a> before you register as a new user.";	
	}
}




// LOGOUT

if ($user == 'logout') {

	if (!is_authed()) {
		die ("You are not currently logged in. <br/><a href='" . $pageName . ".html?user=login'>Click here to LOG IN</a> or <a href='" . $pageName . ".html?user=register'>here to REGISTER</a>.");
	}

	echo "You are now logged out.<br/>";
	echo "<a href='" . $pageName . ".html?user=continue'>Click here</a> to CONTINUE</a>.";

	user_logout();
}






?>