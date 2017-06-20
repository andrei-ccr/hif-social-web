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
<?php require_once("Include/include.header.php"); ?>

<div class="container">
	
	<input type="text" id="feeling-insert" placeholder="I am ..." autofocus/>
	<button id="feeling-submit"></button>
	
	<div id="suggestion-container">
		<div id="suggestions">
			<ul></ul>
		</div>
	</div>
	
	<div class="feelings-container">
		<div class="f-latest-container">
			<?php 
				$feelings = $fc->GetLatestFeelings(32);
				$pc->ShowLatestFeelings($feelings); 
			?>
		</div>
	</div>
</div>

</body>
</html>