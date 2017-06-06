<?php 
	require_once("Class/class.FeelingController.php"); 
	require_once("Class/class.PageController.php");
	
	$pc = new PageController();
	$fc = new FeelingController();
?>


<!DOCTYPE html>
<html lang="en-us">
<head>
	<?php require_once("Include/include.head.php"); ?>
	<title>How I Feel</title>
</head>
<style>
#header-login-container {
	position: absolute; 
	top: 10px; 
	right: 10px; 
	width: 70px; 
	height: 35px;
}

#header-login-container #header-login-btn {
	background-color: rgba(230,230,230,0); 
	width: 100%; 
	height: 100%; 
	border: 1px solid rgba(0,0,0,0.5);
	border-left: 0; border-right: 0;
	cursor: pointer; 
	font-weight: bold; 
	font-size: 12px;
}

#header-login-container #header-login-btn:focus {
	outline: 0;
}

#header-login-container #header-login-btn:hover {
	background-color: rgba(230,230,230,0.3);
}

#header-login-container #header-login-btn:active {
	background-color: rgba(230,230,230,0.7);
}
</style>
<body>
<div class="container">
	<div id="header-login-container">
		<button id="header-login-btn">Login</button>
	</div>
	<input type="text" id="feeling-insert" placeholder="I feel ..." autofocus/>
	<button id="feeling-submit"></button>
	
	<div id="suggestion-container">
		<div id="suggestions">
			<ul></ul>
		</div>
	</div>
	
	<div class="feelings-container">
		<?php 
			$feelings = $fc->GetLatestFeelings(6);
			$pc->ShowLatestFeelings($feelings); 
		?>
	</div>
</div>

</body>
</html>