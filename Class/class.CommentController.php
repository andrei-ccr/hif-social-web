<?php
	require_once("class.Feeling.php");
	require_once("class.UserController.php");

	
	class CommentController extends UserController {
		function __construct() {
			Parent::__construct();
		}
		
		/*
			$feeling - Required; a Feeling object.
			Return : An array of Comment objects, or null if failed to retrieve information.
		*/
		
		public function GetComments($feeling) {
			$q = "
				SELECT c.comment AS comment, u.username AS username
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
						array_push($comments_array, new Comment($r['username'],  $r['comment']));
					}
				}
				else
					return null;
			} else {
				return null;
			}
			return $comments_array;
		}
		

		public function InsertComment($comment_txt, $feel_id, $user_id=1) {
			$q = "INSERT INTO comments(comment, feeling_id, user_id) VALUES (:comment, :fid, :uid)";
			$ps = $this->connection->prepare($q);
			$ps->bindValue(":comment", $comment_txt);
			$ps->bindValue(":fid" , (int)$feel_id, PDO::PARAM_INT);
			$ps->bindValue(":uid" , (int)$user_id, PDO::PARAM_INT);
			$ok = $ps->execute();
			if($ok) return true;
			else return false;
		}
	}

?>