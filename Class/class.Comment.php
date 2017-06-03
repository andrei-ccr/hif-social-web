<?php
	class Comment {
		private $name = "";
		private $comment = "";
		
		function __construct($name, $comment) {
			$this->name = $name;
			$this->comment = $comment;
		}
		
		public function GetName() {
			return $this->name;
		}
		
		public function GetComment() {
			return $this->comment;
		}
	}
?>