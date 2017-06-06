<?php
	require_once("class.Feeling.php");
	require_once("class.UserController.php");

	
	class FeelingController extends UserController {
		private $user = null;
		private $loggedin = false;
		
		function __construct() {
			Parent::__construct($user_obj = null);
			if($user_obj != null) {
				$res = $this->ValidateLogin($user_obj->GetUsername(), $user_obj->GetPassword());
				if(is_object($res)) {
					//Login is valid
					$user = $res;
					$loggedin = true;
				}
			}
		}
		
		
		
		public function GetFeelId($feel_text) {
			$ps = $this->connection->prepare("SELECT id FROM feels WHERE feeling = :feeltxt LIMIT 1");
			$ok = $ps->execute(array(":feeltxt" => $feel_text));
			if($ok) {
				$res = $ps->fetch(PDO::FETCH_ASSOC);
				if($res) {
					return $res['id'];
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		}
		
		
		
		/*
			InsertFeel($feel_text)
			
			Description: Inserts a new feel into the database.
			Parameters:
				$feel_text - Required; Contains the text of the feel.
			Returns : The id of the inserted feel.
		*/
		
		public function InsertFeel($feel_text) {
			$ps = $this->connection->prepare("INSERT INTO feels(feeling) VALUES(:feeltxt)");
			$ok = $ps->execute(array(":feeltxt" => $feel_text));
			if(!$ok) return -1;
			return $this->connection->lastInsertId();
		}
		
		/*
		
			$feeling_text - Required; The text of the feel that will be placed in the feeling.
		*/
		
		public function InsertFeeling($feeling_text) {
			//Check if the Feel exists already
			$feeling_text = trim($feeling_text);
			if(empty($feeling_text)) return false;
			$ps = $this->connection->prepare("SELECT id AS fid FROM feels WHERE feeling = :feeling LIMIT 1");
			$ok = $ps->execute(array(":feeling" => $feeling_text));
			
			$feel_id = 0; // This value will change
			
			if(!$ok) {
				return false; //Query Failed
			} else {
				$res = $ps->fetch(PDO::FETCH_ASSOC);
				if($res) {
					//The feel exists in the database. Just point the feeling to its id.
					$feel_id = $res['fid'];
				} else {
					//The feel is new. Insert it in the database, then point the feeling to it.
					$feel_id = $this->InsertFeel($feeling_text);
				}
			}
				
			$ps = $this->connection->prepare("INSERT INTO feelings(feel_id) VALUES(:fid)");
			$ps->bindValue(":fid", (int)$feel_id,  PDO::PARAM_INT);
			$ok = $ps->execute();
			
			if($ok) {
				return $this->connection->lastInsertId(); //Success; Everything went well
			} else {
				return false; //Query Failed;
			}
		}

		
		
		/* 
			$id - Required; The id of the feeling.
			Returns: A Feeling object containing information about the specified feeling, or null if it fails to find the feeling.
		*/
		
		public function GetFeeling($id) {
			$q = "
				SELECT COUNT(c.comment) AS numcom, fl.feeling_id AS id, f.feeling AS feeling, u.username AS username, fl.time AS time
				FROM feelings fl 
				JOIN feels f ON fl.feel_id=f.id 
				JOIN users u ON fl.user_id=u.user_id
				LEFT JOIN comments c ON fl.feeling_id = c.feeling_id
				WHERE fl.feeling_id = :feeler_id
                GROUP BY fl.feeling_id
				";
			$ps = $this->connection->prepare($q);
			$ok = $ps->execute(array(":feeler_id" => $id));
			
			if($ok) {
				$res = $ps->fetch(PDO::FETCH_ASSOC);
				if($res) 
					return new Feeling($res['id'], $res['feeling'], $res['username'], $res['time'], $res['numcom']);
				else
					return null;
			} else {
				return null;
			}
		}
		
		public function GetRelatedFeelings($feel_id) {
			$q = "
				SELECT COUNT(c.comment) AS numcom, fl.feeling_id AS id, f.feeling AS feeling, u.username AS username, fl.time AS time
				FROM feelings fl 
				JOIN feels f ON fl.feel_id=f.id 
				JOIN users u ON fl.user_id=u.user_id
				LEFT JOIN comments c ON fl.feeling_id = c.feeling_id
				WHERE fl.feel_id = :feel_id
                GROUP BY fl.feeling_id
				ORDER BY fl.time DESC LIMIT 8
				";
			$ps = $this->connection->prepare($q);
			$ps->bindValue(":feel_id", (int)$feel_id,  PDO::PARAM_INT);
			$ok = $ps->execute();
			
			$feeling_array = array();
			
			if($ok) {
				$res = $ps->fetchAll(PDO::FETCH_ASSOC);
				if($res) 
					foreach($res as $r) {
						array_push($feeling_array, new Feeling($r['id'], $r['feeling'], $r['username'], $r['time'], $r['numcom']));
					}
				else
					return null;
			} else {
				return null;
			}
			return $feeling_array;
		}
		
		/*
			$limit - Optional; Specified the maximum number of latest feelings to retrieve. Default is 8. 
			Return : An array of Feeling objects, or null if failed to retrieve information.
		*/
		
		public function GetLatestFeelings($limit = 8) {
			$q = "
				SELECT COUNT(c.comment) AS numcom, fl.feeling_id AS id, f.feeling AS feeling, u.username AS username, fl.time AS time
				FROM feelings fl 
				JOIN feels f ON fl.feel_id=f.id 
				JOIN users u ON fl.user_id=u.user_id
				LEFT JOIN comments c ON fl.feeling_id = c.feeling_id
				GROUP BY fl.feeling_id
				ORDER BY fl.time DESC LIMIT :limit
				";
				
			$ps = $this->connection->prepare($q);
			$ps->bindValue(":limit" , (int)$limit, PDO::PARAM_INT);
			$ok = $ps->execute();
			$feeling_array = array();
			
			if($ok) {
				$res = $ps->fetchAll(PDO::FETCH_ASSOC);
				if($res) {
					foreach($res as $r) {
						array_push($feeling_array, new Feeling($r['id'], $r['feeling'], $r['username'], $r['time'], $r['numcom']));
					}
				}
				else
					return null;
			} else {
				return null;
			}
			return $feeling_array;
		}
	}

?>