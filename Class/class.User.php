<?php
	class User {
		private $id = 0;
		private $username = "";
		private $pass = "";
		private $email = "";
		
		function __construct($i, $u, $p, $e) {
			$this->id = $i;
			$this->username = $u;
			$this->pass = $p;
			$this->email = $e;
		}
		
		public function GetId() {
			return $this->id;
		}
		
		public function GetUsername() {
			return $this->username;
		}
		
		public function GetPassword() {
			return $this->pass;
		}
		
		public function GetEmail() {
			return $this->email;
		}
	}
?>