<?php 
	require_once("Include/include.sessioninit.php");
	
	require_once("Class/class.FeelingController.php"); 
	require_once("Class/class.PageController.php");
	
	$pc = new PageController();
	$fc = new FeelingController($_SESSION['user']);
?>


<!DOCTYPE html>
<html lang="en-us">
<head>
	<?php require_once("Include/include.head.php"); ?>
	<title>How I Feel</title>
</head>
<body>
<div class="container">
	<div id="header-login-container">
		<?php
			if($_SESSION['loggedin']) {
				echo $_SESSION['user']->GetUsername();
			}
			else {
				echo '<button id="header-login-btn">Login</button>';
			}
		?>
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