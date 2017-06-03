<?php
	class Feeling {
		private $id = 0;
		private $feel = "";
		private $name = "";
		private $dtime = "";
		private $nr_comments = 0;
		
		function __construct($id, $feel, $name, $time, $nr_comments) {
			$this->id = $id;
			$this->feel = $feel;
			$this->name = $name;
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
		
		public function GetName() {
			return $this->name;
		}
		
		public function GetTime() {
			return $this->dtime;
		}
	}
?>