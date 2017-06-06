<?php
	//This file would set SESSION variables to make user logged in.
	//Note that if the username and password POST variables it receives are invalid, any current valid sessions will be overwriten by a new "not logged in" session.
	
	session_start();
	
	require_once("Class/class.User.php");
	require_once("Class/class.UserController.php");
	
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$SESSION = array(); //Reset any session
		$uc = new UserController($_POST['username'], $_POST['password']);
		
		if($u = $uc->IsLoggedIn()) {
			//This means the username and password provided are valid
			$_SESSION['user'] = $u;
			$_SESSION['loggedin'] = true;
		} else {
			$_SESSION['user'] = null;
			$_SESSION['loggedin'] = false;
		}
	}
	
	
	
	

?>