<?php
	class Feeling {
		private $id = 0;
		private $feel = "";
		private $user = null;
		private $dtime = "";
		private $nr_comments = 0;
		
		function __construct($id, $feel, $user, $time, $nr_comments) {
			$this->id = $id;
			$this->feel = $feel;
			$this->user = $user;
			$this->dtime = $time;
			$this->nr_comments = $nr_comments;
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
	}
?>