<?php
	class Feeling {
		private $id = 0;
		private $feel = "";
		private $name = "";
		private $dtime = "";
		
		function __construct($id, $feel, $name, $time) {
			$this->feel = $feel;
			$this->name = $name;
			$this->dtime = $time;
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