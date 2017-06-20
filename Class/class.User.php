<?php
	class User {
		protected $id = 0;
		protected $username = "";
		protected $pass = "";
		protected $email = "";
		protected $profile_pic = "";
		
		function __construct($i=1, $u="", $p="", $e="", $pp="") {
			if($i == 1) {
				$this->id = 1;
				$this->username = "Anonymous";
				$this->pass = "";
				$this->email = "";
				$this->profile_pic = "https://api.adorable.io/avatars/50/" . mt_rand(1000, time()) . ".png";
			} else {
				$this->id = $i;
				$this->username = $u;
				$this->pass = $p;
				$this->email = $e;
				if(empty($pp)) {
					$this->profile_pic = "https://api.adorable.io/avatars/50/" . mt_rand(1000, time()) . ".png";
				} else {
					$this->profile_pic = $pp;
				}
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

		public function GetProfilePic() {
			return $this->profile_pic;
		}
	}
?>