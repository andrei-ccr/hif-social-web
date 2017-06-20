<?php
	
	require_once("class.UserController.php");
	require_once("class.Feeling.php");

	class FeelingController extends UserController {

		function __construct(User $user_obj = null) {
			if($user_obj === null) $user_obj = new AnonymousUser();
			Parent::__construct($user_obj->GetUsername(), $user_obj->GetPassword());
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
			if($this->startsWith(strtolower($feeling_text), "i feel")) {
				$feeling_text = trim(substr($feeling_text, 6)); //Remove the 'I feel' part of the string
			}

			if(empty($feeling_text)) return false;

			$n_feeling_text = $feeling_text;
			$emoticons_part = NULL;

			$hasEmoticons = $this->hasEmoticons($feeling_text);
			//If the text has emoticons, additional operations must be performed before inserting into db
			if($hasEmoticons) {

				//Strip the emoticons from the text temporary so it can be identified with an existing feel in the db or inserted as a new feel
				$n_feeling_text = $this->stripEmoticons($feeling_text);

				//Get only the emoticons in the text
				$emoticons_part = $this->getEmoticons($feeling_text);

			} 
			

			$ps = $this->connection->prepare("SELECT id AS fid FROM feels WHERE feeling = :feeling LIMIT 1");
			$ok = $ps->execute(array(":feeling" => $n_feeling_text));
			
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
					$feel_id = $this->InsertFeel($n_feeling_text);
				}
			}
				
			if($this->loggedin) {
				$ps = $this->connection->prepare("INSERT INTO feelings(feel_id, user_id, emoticons) VALUES(:fid, :uid, COALESCE(:emotic, NULL))");
				$ps->bindValue(":uid", (int)$this->user->GetId(), PDO::PARAM_INT);
			} else {
				$ps = $this->connection->prepare("INSERT INTO feelings(feel_id, emoticons) VALUES(:fid, COALESCE(:emotic, NULL))");
			}
			
			$ps->bindValue(":fid", (int)$feel_id,  PDO::PARAM_INT);
			$ps->bindValue(":emotic", $emoticons_part);
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
				SELECT COUNT(c.comment) AS numcom, fl.feeling_id AS id, f.feeling AS feeling, 
						u.user_id AS user_id, 
						u.username AS user_name,
						u.pass AS user_pass,
						u.email AS user_email, u.profile_pic AS ppic
						fl.time AS time,
						fl.emoticons AS emoticons
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
					return new Feeling($res['id'], $res['feeling'], new User($res['user_id'], $res['user_name'], $res['user_pass'], $res['user_email'], $res['ppic']), $res['time'], $res['numcom'], $res['emoticons']);
				else
					return null;
			} else {
				return null;
			}
		}
		
		public function GetRelatedFeelings($feeling_id) {
			$q = "
					SELECT feel_id 
					FROM feelings
					WHERE feeling_id = :feeling_id
				";

			$ps = $this->connection->prepare($q);
			$ps->bindValue(":feeling_id", (int)$feeling_id,  PDO::PARAM_INT);
			$ok = $ps->execute();

			$feel_id = 0;
			if($ok) {
				$res = $ps->fetch(PDO::FETCH_ASSOC);
				if($res) {
					$feel_id = $res['feel_id'];
				}
			}

			if($feel_id == 0) return null;

			$q = "
				SELECT COUNT(c.comment) AS numcom, fl.feeling_id AS id, f.feeling AS feeling, u.user_id AS user_id, 
						u.username AS user_name,
						u.pass AS user_pass,
						u.email AS user_email, fl.time AS time, fl.emoticons AS emoticons, u.profile_pic AS ppic
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
						if($r['id'] != $feeling_id) //Don't include the feeling used as a landmark
							array_push($feeling_array, new Feeling($r['id'], $r['feeling'], new User($r['user_id'], $r['user_name'], $r['user_pass'], $r['user_email'], $r['ppic']), $r['time'], $r['numcom'], $r['emoticons']));
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
				SELECT COUNT(c.comment) AS numcom, fl.feeling_id AS id, f.feeling AS feeling, u.user_id AS user_id, 
						u.username AS user_name,
						u.pass AS user_pass,
						u.email AS user_email, fl.time AS time, fl.emoticons AS emoticons, u.profile_pic AS ppic
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
						array_push($feeling_array, new Feeling($r['id'], $r['feeling'], new User($r['user_id'], $r['user_name'], $r['user_pass'], $r['user_email'], $r['ppic']), $r['time'], $r['numcom'], $r['emoticons']));
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