<?php
	class Comment {
		private $name = "";
		private $comment = "";
		private $dtime = "";
		
		function __construct($name, $comment, $time) {
			$this->name = $name;
			$this->comment = $comment;
			$this->dtime = $time;
		}
		
		public function GetName() {
			return $this->name;
		}
		
		public function GetComment() {
			return $this->comment;
		}
		
		public function GetTime() {
			return $this->dtime;
		}
	}
?>