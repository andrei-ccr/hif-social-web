<?php
	class User {
		protected $id = 0;
		protected $username = "";
		protected $pass = "";
		protected $email = "";
		
		function __construct($i=1, $u="", $p="", $e="") {
			if($i == 1) {
				$this->id = 1;
				$this->username = "Anonymous";
				$this->pass = "";
				$this->email = "";
			} else {
				$this->id = $i;
				$this->username = $u;
				$this->pass = $p;
				$this->email = $e;
			}
			
			
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

		public function IsAnonymous() {
			if($this->id == 1) {
				return true;
			} else {
				return false;
			}
		}
	}
?>