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
			echo '
			<div class="discussion-container">
			<textarea id="comment-insert" placeholder="Discuss about this..."autofocus></textarea>
			<button id="comment-submit">Post</button>';
			
			$comments = $cc->GetComments($feeling);
			$pc->ShowComments($comments);
			
			echo '</div>';
		}
	?>
	<div class="related-container">
		<h3>Related Feelings</h3>
		<div class="feel feel-related" data-id="36">
			<div class="feel-body">
				<img src="https://api.adorable.io/avatars/50/2017-06-08 16:55:27.png">
				<b>Anonymous</b> is feeling <span class="feel-txt" style="cursor: pointer;">good</span>
				<div class="meta-info">
					<div class="meta-info-time">14 minute(s) ago</div>0 comment(s)
				</div>
			</div>
		</div>
		<div class="feel" data-id="37">
			<div class="feel-body">
				<img src="https://api.adorable.io/avatars/50/2017-06-08 16:55:27.png">
				<b>Anonymous</b> is feeling <span class="feel-txt" style="cursor: pointer;">good</span>
				<div class="meta-info">
					<div class="meta-info-time">14 minute(s) ago</div>0 comment(s)
				</div>
			</div>
		</div>
		<div class="feel" data-id="38">
			<div class="feel-body">
				<img src="https://api.adorable.io/avatars/50/2017-06-08 16:55:27.png">
				<b>Anonymous</b> is feeling <span class="feel-txt" style="cursor: pointer;">good</span>
				<div class="meta-info">
					<div class="meta-info-time">14 minute(s) ago</div>0 comment(s)
				</div>
			</div>
		</div>
		<div class="feel" data-id="39">
			<div class="feel-body">
				<img src="https://api.adorable.io/avatars/50/2017-06-08 16:55:27.png">
				<b>Anonymous</b> is feeling <span class="feel-txt" style="cursor: pointer;">good</span>
				<div class="meta-info">
					<div class="meta-info-time">14 minute(s) ago</div>0 comment(s)
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html>