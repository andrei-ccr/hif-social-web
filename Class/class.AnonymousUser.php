<?php
	require_once("class.User.php");

	class AnonymousUser extends User {
		function __construct() {
			$this->id = 1;
			$this->username = "Anonymous";
			$this->password = "";
			$this->email = "";
		}
	}

?>