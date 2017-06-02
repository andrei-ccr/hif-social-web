<?php
	require_once("class.PageController.php");
	require_once("class.FeelingController.php");
	
	$pc = new PageController();
	$fc = new FeelingController();
	
	if(isset($_POST['input'])) { 		//Populates the suggestion list with suggestions based on what is being typed
		$s = $fc->GetSearchSuggestions($_POST['input']);
		$pc->ShowSuggestions($s);
	}
	else if(isset($_POST['retr'])) {		//Retrieves the latest feelers and displays them
		$f = $fc->GetLatestFeelings(6);
		$pc->ShowLatestFeelings($f);
	} 
	else if(isset($_POST['insert'])) {		//Adds a new feeling to the list
		$res = $fc->InsertFeeling($_POST['insert']);
		
	}
?>