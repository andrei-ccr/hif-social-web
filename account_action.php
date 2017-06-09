<?php
	//This file would set SESSION variables to make user logged in.
	//Note that if the username and password POST variables it receives are invalid, any current valid sessions will be overwriten by a new "not logged in" session.
	
	session_start();
	
	require_once("Class/class.User.php");
	require_once("Class/class.UserController.php");

	if(isset($_POST['submit'])) {
		//Data was submitted from a form
		if(isset($_POST['action_login'])) {
			//Action is to login
			if(isset($_POST['username']) && isset($_POST['password'])) {
				$_SESSION = array(); //Reset any session
				$uc = new UserController($_POST['username'], $_POST['password']);
				
				if($u = $uc->IsLoggedIn()) {
					//This means the username and password provided are valid
					$_SESSION['user'] = $u;
					$_SESSION['loggedin'] = true;
					header("Location: /");
					exit;
				} else {
					$_SESSION['user'] = null;
					$_SESSION['loggedin'] = false;
					header("Location: login.php?result=0&action=login");
					exit;
				}

			}
		} else if (isset($_POST['action_register'])) {
			//Action is to register
			if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
				$uc = new UserController();
				$res = $uc->Register($_POST['username'], $_POST['password'], $_POST['email']);
				if(is_object($res)) {
					header("Location: /");
					exit;
				} else {
					header("Location: login.php?result=0&action=reg");
					exit;
				}
				
			}
		}
	}/* else 
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$_SESSION = array(); //Reset any session
		$uc = new UserController($_POST['username'], $_POST['password']);
		
		if($u = $uc->IsLoggedIn()) {
			//This means the username and password provided are valid
			$_SESSION['user'] = $u;
			$_SESSION['loggedin'] = true;
		} else {
			$_SESSION['user'] = null;
			$_SESSION['loggedin'] = false;
		}
	}*/ else if(isset($_POST['logout']) ) {
		unset($_SESSION['user']);
		unset($_SESSION['loggedin']);
		$_SESSION = array();
	} else if(isset($_GET['logout'])) {
		unset($_SESSION['user']);
		unset($_SESSION['loggedin']);
		$_SESSION = array();
		header("Location: /");
		exit;
	}

	
?>