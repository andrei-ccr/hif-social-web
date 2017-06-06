<?php
	require_once("class.User.php");
	
	define("ERR_INVALID_USERNAME", -1);
	define("ERR_INVALID_PASSWORD", -2);
	define("ERR_USERNOTFOUND", -3);
	define("ERR_QUERY", -4);
	define("SUCCESS_ACT", 0);
	
	class UserController {
		protected $connection = null;
		protected $logedin = false;
		protected $user = null; //User Object
		
		function __construct($u="", $p="") {
			$this->connection = new PDO('mysql:dbname=feelingsdb;host=localhost', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
			$res = $this->Login($u, $p);
			if(is_object($res)) {
				//If an object is returned, the login is successful
				$logedin = true;
				$user = $res;
			}
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
		
		public function Login($username, $password) {
			$username = trim($username);
			$password = trim($password);
			if(empty($username)) {
				return ERR_INVALID_USERNAME;
			}
			
			$ps = $this->connection->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
			$ok = $ps->execute(array(":username"=>$username));
			
			if($ok) {
				$res = $ps->fetch(PDO::FETCH_ASSOC);
				if($res) {
					if(password_verify($password, $res['pass'])) {
						//Password is valid. Login valid
						return new User($res['user_id'], $res['username'], $res['pass'], $res['email']);
					} 
					else {
						//Invalid password
						return ERR_INVALID_PASSWORD;
					}
				} else {
					//Username doesn't exist
					return ERR_USERNOTFOUND;
				}
			} else {
				//Error occured
				return ERR_QUERY;
			}
		}
		
		public function Logout() {
			
		}
		
	}

?>