<?php
	require_once("func.php");
	if(isset($_POST['input'])) { 		//Populates the suggestion list with suggestions based on what is being typed
		$sugs = GetSuggestions($_POST['input']);
		if($sugs !=0 ) {
			foreach($sugs as $sug) {
				echo $sug;
			}
		}
	}
	else if(isset($_POST['retr'])) {		//Retrieves the latest feelers and displays them
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
	else if(isset($_POST['insert'])) {		//Creates a new feeler
		$db = new mysqli("localhost","root","","feelings");
		$insert = trim($_POST['insert']);
		$stmt = $db->prepare("SELECT id 
							FROM feels 
							WHERE feeling = ?
							LIMIT 1");
							
		$stmt->bind_param('s', $insert);
		$stmt->execute();
		
		$stmt->store_result();
		$stmt->bind_result($id);
		if($stmt->num_rows > 0) {
			$stmt->fetch();
			$stmt2 = $db->prepare("INSERT INTO feelers(feel_id) VALUES(?)");
			$stmt2->bind_param('i', $id);
			$stmt2->execute();
		} else {
			$stmt2 = $db->prepare("INSERT INTO feels(feeling) VALUES(?)");
			$stmt2->bind_param('s', $insert);
			$stmt2->execute();
			$last_id = $db->insert_id;
			$stmt2 = $db->prepare("INSERT INTO feelers(feel_id) VALUES(?)");
			$stmt2->bind_param('i', $last_id);
			$stmt2->execute();
		}
		
		
	}
?>