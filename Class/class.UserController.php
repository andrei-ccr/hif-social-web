<?php
	class UserController {
		protected $connection = null;
		
		function __construct() {
			$this->connection = new PDO('mysql:dbname=feelingsdb;host=localhost', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
		}
	
		function __destruct() {
			$this->connection = null;
		}
		
		/*
			$current_text - Required; The current string inputed by the user.
			Returns: An array containing maximum 6 suggestions based on the current input text.
		*/
		
		public function GetSearchSuggestions($current_text) {
			$ps = $this->connection->prepare("SELECT TRIM(feeling) AS feel FROM feels WHERE TRIM(feeling) LIKE :current_text ORDER BY usageval DESC LIMIT 6");
			$ok = $ps->execute(array(":current_text" => $current_text."%"));

			$suggestions = array();
			if($ok) {
				$res = $ps->fetchAll(PDO::FETCH_ASSOC);
				foreach($res as $r) {
					array_push($suggestions, $r['feel']);
				}
			} else {
				return null;
			}
			
			return $suggestions;
		}
	}

?>