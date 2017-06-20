<?php
	require_once("class.User.php");
	require_once("class.AnonymousUser.php");
	require_once("Emoticons.php");
	
	define("ERR_INVALID_USERNAME", -1);
	define("ERR_INVALID_PASSWORD", -2);
	define("ERR_INVALID_EMAIL", -5);
	define("ERR_USERNOTFOUND", -3);
	define("ERR_QUERY", -4);
	define("SUCCESS_ACT", 0);
	
	class UserController {
		protected $connection = null;
		protected $loggedin = false;
		protected $user = null; //User Object
		
		function __construct($u="", $p="") {
			$this->connection = new PDO('mysql:dbname=feelingsdb;host=localhost', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
			$res = $this->ValidateLogin($u, $p);
			if(is_object($res)) {
				//If an object is returned, the login is successful
				$this->loggedin = true;
				$this->user = $res;
			}
		}
	
		function __destruct() {
			$this->connection = null;
		}
		
		protected function startsWith($haystack, $needle)
		{
		     $length = strlen($needle);
		     return (substr($haystack, 0, $length) === $needle);
		}

		//An emoticon is of form [(emoticon)]
		protected function stripEmoticons($text) {
			global $emoticon_list;
			foreach($emoticon_list as $e) {
				$text = str_replace($e, "" , $text);
			}
			return $text;
		}

		protected function hasEmoticons($text) {
			global $emoticon_list;
			foreach($emoticon_list as $e) {
				if(strpos($text, $e) !== false) return true;
			}

			return false;
		}

		protected function getEmoticons($text) {
			global $emoticon_list;
			$emoticons_order = array();

			//This should be optimized. Current complexity is O(n^2)
			$ok = false;
			while(!$ok) {
				$ok = true;
				foreach($emoticon_list as $e) {
					if($pos = strpos($text, $e) !== false) {
						array_push($emoticons_order, $e); //Place the emoticon in an array
						$text = substr_replace($text, "", $pos, strlen($e)); //Remove the emoticon so it won't be tested again
						$ok = false; //Restart testing
						break;
					}
				}
			}
			$text = implode(" ", $emoticons_order);
			return $text;
		}

		public function IsLoggedIn() {
			if($this->loggedin) {
				return $this->user;
			} else {
				return false;
			}
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
		
		protected function ValidateLogin($username, $password) {
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
						return new User($res['user_id'], $res['username'], $password, $res['email']);
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
		
		public function Register($username, $password, $email) {
			$username = trim($username);
			$password = trim($password);
			$email = trim($email);

			if(!$this->ValidUsername($username)) {
				return ERR_INVALID_USERNAME;
			}

			if(!$this->ValidEmail($email)) {
				return ERR_INVALID_EMAIL;
			}

			$p_hash = password_hash($password, PASSWORD_BCRYPT);
			$ppic = "https://api.adorable.io/avatars/50/".md5(uniqid($your_user_login, true)).".png";

			$ps = $this->connection->prepare("INSERT INTO users(username, pass, email, profile_pic) VALUES(:username, :password, :email, :ppic)");
			$ok = $ps->execute(array(":username"=>$username, ":password"=>$p_hash, ":email"=>$email, ":ppic"=>$ppic));
			if($ok) {
				return new User($this->connection->lastInsertId(), $username, $password, $email);
			} else {
				return ERR_QUERY;
			}
		}

		private function ValidUsername($username) {
			if(strlen($username) >= 30) {
				return false;
			} else {
				return true;
			}

			//TODO: Write validation rules
		}

		private function ValidEmail($email) {
			//According to RFC 5321
			if(strlen($email) > 254) {
				return false;
			} else {
				return true;
			}

			//TODO: Write validation rules
		}
		
	}

?>