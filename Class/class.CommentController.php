<?php
	require_once("class.Feeling.php");
	require_once("class.UserController.php");

	
	class CommentController extends UserController {
		private $user = null;
		private $loggedin = false;
		
		function __construct($user_obj = null) {
			Parent::__construct();
			if($user_obj != null) {
				$res = $this->ValidateLogin($user_obj->GetUsername(), $user_obj->GetPassword());
				if(is_object($res)) {
					//Login is valid
					$user = $res;
					$loggedin = true;
				}
			}
		}
		
		/*
			$feeling - Required; a Feeling object.
			Return : An array of Comment objects, or null if failed to retrieve information.
		*/
		
		public function GetComments($feeling) {
			$q = "
				SELECT c.comment AS comment, u.user_id AS user_id, 
						u.username AS user_name,
						u.pass AS user_pass,
						u.email AS user_email, c.time AS dtime
				FROM comments c 
				JOIN feelings f ON f.feeling_id=c.feeling_id
				JOIN users u ON u.user_id=c.user_id
				WHERE c.feeling_id = :fid
				ORDER BY c.time DESC LIMIT 12
				";
				
			$ps = $this->connection->prepare($q);
			$ps->bindValue(":fid" , (int)$feeling->GetId(), PDO::PARAM_INT);
			$ok = $ps->execute();
			$comments_array = array();
			
			if($ok) {
				$res = $ps->fetchAll(PDO::FETCH_ASSOC);
				if($res) {
					foreach($res as $r) {
						array_push($comments_array, new Comment(new User($r['user_id'], $r['user_name'], $r['user_pass'], $r['user_email']),  $r['comment'], $r['dtime']));
					}
				}
				else
					return null;
			} else {
				return null;
			}
			return $comments_array;
		}
		

		public function InsertComment($comment_txt, $feel_id) {
			if($loggedin) {
				$ps = $this->connection->prepare("INSERT INTO comments(comment, feeling_id, user_id) VALUES (:comment, :fid, :uid)");
				$ps->bindValue(":uid" , (int)$user_id, PDO::PARAM_INT);
			} else {
				$ps = $this->connection->prepare("INSERT INTO comments(comment, feeling_id) VALUES (:comment, :fid)");
			}
			
			$ps->bindValue(":comment", $comment_txt);
			$ps->bindValue(":fid" , (int)$feel_id, PDO::PARAM_INT);
			
			$ok = $ps->execute();
			if($ok) return true;
			else return false;
		}
	}

?>