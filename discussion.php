<?php
	require_once("Class/class.FeelingController.php");
	require_once("Class/class.PageController.php");
	require_once("Class/class.CommentController.php");
	
	$fc = new FeelingController();
	$pc = new PageController();
	$cc = new CommentController();
	
	$feeling = null;
	
	if(isset($_GET['id'])) {
		if(is_numeric($_GET['id']) && $_GET['id']>0) {
			$feeling = $fc->GetFeeling($_GET['id']);
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php require_once("Include/include.head.php"); ?>
	<title>Discussion - How I Feel</title>
</head>
<body>
<div class="container">
	<div class="feelings-container f-c-discussion">
		<?php
			$pc->ShowDiscussionFeeling($feeling);
		?>
	</div>
	<?php 
		//If the discussion doesn't exist, show latest feelings instead of displaying textarea
		if($feeling != null) {
			include("Include/include.discussionform.php");
			$comments = $cc->GetComments($feeling);
			$pc->ShowComments($comments);
		}
	?>
</div>

</body>
</html>