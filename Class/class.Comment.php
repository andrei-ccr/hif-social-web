<?php
	class Comment {
		private $user = null;
		private $comment = "";
		private $dtime = "";
		
		function __construct($user, $comment, $time) {
			$this->user = $user;
			$this->comment = $comment;
			$this->dtime = $time;
		}
		
		public function GetUser() {
			return $this->user;
		}
		
		public function GetComment() {
			return $this->comment;
		}
		
		public function GetTime() {
			return $this->dtime;
		}
	}
?>