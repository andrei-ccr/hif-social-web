<?php
	require_once("./Class/class.User.php");

	session_start();
	
	//If any of the user session variables are not set, set them properly.
	if(!isset($_SESSION['user']) || !isset($_SESSION['loggedin']) ) {
		$_SESSION['user'] = null;
		$_SESSION['loggedin'] = false;
	}
	
?>