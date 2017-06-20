<?php
	class Feeling {
		private $id = 0;
		private $feel = "";
		private $user = null;
		private $dtime = "";
		private $nr_comments = 0;
		private $emoticons = "";
		
		function __construct($id, $feel, $user, $time, $nr_comments, $emoticons="") {
			$this->id = $id;
			$this->feel = $feel;
			$this->user = $user;
			$this->dtime = $time;
			$this->nr_comments = $nr_comments;
			if($emoticons == NULL) {
				$this->emoticons = "";
			} else 
				$this->emoticons = $emoticons;
		}
		
		public function CountComments() {
			return $this->nr_comments;
		}
		
		public function GetId() {
			return $this->id;
		}
		
		public function GetFeel() {
			return $this->feel;
		}
		
		public function GetUser() {
			return $this->user;
		}
		
		public function GetTime() {
			return $this->dtime;
		}

		public function GetEmoticons() {
			return $this->emoticons;
		}
	}
?>