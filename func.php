<?php
	include("class.Feeling.php");
	
	class UserController {
		protected $connection = null;
		$anonymous = false;
		
		function __construct() {
			$connection = new PDO('mysql:dbname="feelings";host=localhost', 'root', '');
		}
	
		function __destruct() {
			$connection->close();
		}
	}
	
	class FeelingController extends User {
		
		/* 
			$id - The id of the feeling.
			Returns: A Feel object containing information about the specified feeling, or null if it fails to find the feeling.
		*/
		
		public function GetFeeling($id) {
			$q = "
				SELECT f.feeling AS feeling, u.username AS username, fl.time AS time
				FROM feeling fl 
				JOIN feels f ON fl.feel_id=f.id 
				JOIN users u ON fl.user_id=u.user_id
				WHERE fl.feeler_id = :feeler_id
				";
			$connection->prepare();
			$connection->execute(array(":feeler_id" => $id));
			
			if($connection->rowCount() > 0) {
				$res = $connection->fetchAll(PDO::FETCH_ASSOC);
				return new Feel($res['feeling'], $res['username'], $res['time']);
			} else {
				return null;
			}
		}
	}

	class PageController {
		
		public function ShowFeeling($feeling) {
			echo '<div class="feel"><div class="feel-body"><img src="https://api.adorable.io/avatars/50/'.$feeling->GetTime().'.png"><b>'.$feeling->GetName().'</b> is feeling <span>'.$feeling->GetFeel().'</span></div></div>';
		}
		
		public function ShowFeelings($feelings) {
			foreach($feelings as $feeling) {
				ShowFeeling($feeling);
			}
		}
	}
	
	function GetFeeler($id) {
		$db = new mysqli("localhost","root","","feelings");
		$stmt = $db->prepare("SELECT f.feeling, u.name , u.time
							FROM feelers u
							JOIN feels f ON u.feel_id=f.id
							WHERE u.feeler_id = ? 
							");
		
		$stmt->bind_param('i', $id);
		$stmt->execute();

		$stmt->store_result();
		$stmt->bind_result($feel, $name, $time);
		if($stmt->num_rows > 0) {
			$stmt->fetch();
			$stmt->close();
			return '<div class="feel"><div class="feel-body"><img src="https://api.adorable.io/avatars/50/'.$time.'.png"><b>'.$name.'</b> is feeling <span>'.$feel.'</span></div></div>';
		} else {
			$stmt->close();
			return 0;
		}
		
	}
	
	function GetFeelers($limit = 10) {
		$db = new mysqli("localhost","root","","feelings");
		$stmt = $db->prepare("SELECT f.feeling, u.feeler_id, u.name , u.time
							FROM feelers u
							JOIN feels f ON u.feel_id=f.id
							ORDER BY u.time DESC LIMIT ?");
							
		$stmt->bind_param('i', $limit);					
		$stmt->execute();

		$stmt->store_result();
		$stmt->bind_result($feel, $id, $name, $time);
		$feelers = array();
		if($stmt->num_rows > 0) {
			while($stmt->fetch()) {
				array_push($feelers, '<div class="feel" data-id="'.$id.'"><div class="feel-body"><img src="https://api.adorable.io/avatars/50/'.$time.'.png"><b>'.$name.'</b> is feeling <span>'.$feel.'</span></div></div>');
			}
		} else {
			$stmt->close();
			return 0;
		}
		
		$stmt->close();

		return $feelers;
		
	}
	
	function GetSuggestions($text) {
		$text = $text."%";
		$db = new mysqli("localhost","root","","feelings");
		$stmt = $db->prepare("SELECT DISTINCT TRIM(feeling) FROM feels 
							WHERE TRIM(feeling) LIKE ?
							ORDER BY usageval DESC LIMIT 6");
							
		$stmt->bind_param('s', $text);
		$stmt->execute();

		$stmt->store_result();
		$stmt->bind_result($suggestion);
		$sug = array();
		if($stmt->num_rows > 0) {
			while($stmt->fetch())
			{
				array_push($sug, "<li>{$suggestion}</li>\n");
			}
		} else {
			return 0;
		}
		
		$stmt->close();
		return $sug;
	}
	
	function ShowLatestFeelers() {
		$feelers = GetFeelers(8);
		if($feelers != 0) {
			echo "<h2>Latest Feelings</h2>";
			foreach($feelers as $feeler) {
				echo $feeler;
			}
		} else {
			echo "No feelings at this time :|";
		}
	}
	
	
	function GetDiscussion() {
		
	}

?>