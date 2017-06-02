<?php
	require_once("func.php");
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="func.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
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
		<?php ShowLatestFeelers(); ?>
	</div>
</div>

</body>
</html>