<?php
	require_once("class.FeelingController.php");
	require_once("class.PageController.php");
	$fc = new FeelingController();
	$pc = new PageController();
	
	$feeling = null;
	
	if(isset($_GET['id'])) {
		if(is_numeric($_GET['id'])) {
			$feeling = $fc->GetFeeling($_GET['id']);
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php require_once("include.head.php"); ?>
	<title>Discussion - How I Feel</title>
</head>
<body>
<div class="container">
	<div class="feelings-container f-c-discussion">
		<?php
			if($feeling == null) {
				echo "<h2>This discussion doesn't exist</h2>";
			} else {
				echo $feeling;
			}
		?>
	</div>
	<?php 
		//If the discussion doesn't exist, show latest feelings instead of displaying textarea
	?>
	<div class="discussion-container">
		<textarea id="comment-insert" placeholder="Discuss about this..."autofocus></textarea>
		<button id="comment-submit">Post</button>
		<div class="comment">
			<span class="username">Anonymous</span>
			<p>Monmouth had been a significant border settlement since the Roman occupation of Britain, when it was the site of the fort of Blestium. The River Wye may have 
			been bridged at this time but the Monnow, being easily fordable, appears not to have had a crossing until after the Norman Conquest. According to the local 
			tradition, construction of Monnow Bridge began in 1272 to replace a 12th-century Norman timber bridge. Through the medieval era, the English Civil War, and the 
			Chartist uprising, the bridge played a significant, if ineffectual, role in defending Monmouth. It also served as a gaol, a munitions store, a lodge, an advertising 
			hoarding, a focus for celebrations and, most significantly, as a toll gate. Much of the medieval development of Monmouth was funded by the taxes and tolls the 
			borough was entitled to raise through Royal Charter. The tolls were collected through control of the points of entry to the town, including the gatehouse on Monnow Bridge.
			</p>
		</div>
		<div class="comment">
			<span class="username">Anonymous</span>
			<p>It also served as a gaol, a munitions store, a lodge, an advertising 
			hoarding, a focus for celebrations and, most significantly, as a toll gate. Much of the medieval development of Monmouth was funded by the taxes and tolls the 
			borough was entitled to raise through Royal Charter. The tolls were collected through control of the points of entry to the town, including the gatehouse on Monnow Bridge.
			</p>
		</div>
	</div>
</div>

</body>
</html>