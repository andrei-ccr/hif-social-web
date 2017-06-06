<?php
	require_once("Include/include.sessioninit.php");
	
	require_once("Class/class.PageController.php");
	require_once("Class/class.FeelingController.php");
	require_once("Class/class.CommentController.php");

	
	$pc = new PageController();
	$fc = new FeelingController($_SESSION['user']);
	$cc = new CommentController($_SESSION['user']);
	
	if(isset($_POST['input'])) { 		//Populates the suggestion list with suggestions based on what is being typed
		$s = $fc->GetSearchSuggestions($_POST['input']);
		$pc->ShowSuggestions($s);
	}
	else if(isset($_POST['retr'])) {		//Retrieves the latest feelers and displays them
		$f = $fc->GetLatestFeelings(6);
		$pc->ShowLatestFeelings($f);
	} 
	else if(isset($_POST['insert'])) {		//Adds a new feeling and echoes all related feelings
		$res = $fc->InsertFeeling($_POST['insert']);
		$fels = $fc->GetRelatedFeelings($fc->GetFeelId($_POST['insert']));
		$pc->ShowFeelings($fels);
	}
	else if(isset($_POST['insert_com']) && isset($_POST['feel_id']) ) {		//Adds a new comment to the feeling
		$res = $cc->InsertComment($_POST['insert_com'], $_POST['feel_id']);
		if(!$res) echo "Com Ins Err";
	}
	else if(isset($_POST['feel'])) {    //Retrieves feelings related to the specified feel
		$fels = $fc->GetRelatedFeelings($fc->GetFeelId($_POST['feel']));
		$pc->ShowFeelings($fels);
	}
?>