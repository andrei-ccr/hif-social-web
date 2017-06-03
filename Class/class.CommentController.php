<?php
	require_once("class.Feeling.php");
	require_once("class.UserController.php");

	
	class CommentController extends UserController {
		function __construct() {
			Parent::__construct();
		}
		
		/*
			$limit - Optional; Specified the maximum number of latest feelings to retrieve. Default is 8. 
			Return : An array of Feeling objects, or null if failed to retrieve information.
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
						array_push($comment_array, new Comment($r['comment'], $r['username']));
					}
				}
				else
					return null;
			} else {
				return null;
			}
			return $comment_array;
		}
	}

?>