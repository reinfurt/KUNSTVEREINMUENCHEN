<?php
// wrapper class for http post variables
// should this be custom per view? perhaps
class Request
{
	public $dev;
	
	function __construct()
	{	
		// get variables
		$get = array('dev');

		foreach($get as $v)	
			$this->$v = $_GET[$v];
	}
}

?>