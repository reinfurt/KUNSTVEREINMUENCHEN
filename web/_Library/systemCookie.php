<?php



  ///////////////
 //  Cookie   //
///////////////

function systemCookie($name = null, $value = null, $expires = null) {


	//  Check $trigger against existing cookies
	//  Must happen before * any * output to browser
	
	if ($value) {
	
		if ( !((isset($_COOKIE[$name])) && ($_COOKIE[$name] == $value)) ) {	
		
			setcookie ( $name, $value, $expires );
			
		} 
		
	} else {

		$value = $_COOKIE[$name];
	}

	//  Return value and set cookie

	return $value;	
}



?>