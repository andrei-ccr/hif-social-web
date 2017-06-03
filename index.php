<?php 
	require_once("Class/class.FeelingController.php"); 
	require_once("Class/class.PageController.php");
	
	$pc = new PageController();
	$fc = new FeelingController();
?>

<!DOCTYPE html>
<html>
<head>
	<?php require_once("Include/include.head.php"); ?>
	<title>How I Feel</title>
</head>
<body>
<div class="container">
	<input type="text" id="feeling-insert" placeholder="I feel ..." autofocus/>
	<button id="feeling-submit"></button>
	
	<div id="suggestion-container">
		<div id="suggestions">
			<ul></ul>
		</div>
	</div>
	
	<div class="feelings-container">
		<?php 
			$feelings = $fc->GetLatestFeelings();
			$pc->ShowLatestFeelings($feelings); 
		?>
	</div>
</div>

</body>
</html>