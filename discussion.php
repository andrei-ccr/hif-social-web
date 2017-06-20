<?php
	require_once("Include/include.sessioninit.php");

	require_once("Class/class.FeelingController.php");
	require_once("Class/class.PageController.php");
	require_once("Class/class.CommentController.php");
	
	$fc = new FeelingController($_SESSION['user']);
	$pc = new PageController();
	$cc = new CommentController($_SESSION['user']);
	
	$feeling = null;
	
	if(isset($_GET['id'])) {
		if(is_numeric($_GET['id']) && $_GET['id']>0) {
			$feeling = $fc->GetFeeling($_GET['id']);
		}
	}
?>

<!DOCTYPE html>
<html lang="en-us">
<head>
	<?php require_once("Include/include.head.php"); ?>
	<title>Discussion - How I Feel</title>
</head>
<body>
<?php require_once("Include/include.header.php"); ?>
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
		<script type="text/javascript">
			var fid = $(".nfeel").data("id"); 
			var request = $.ajax({
				url: "sys.php",
				method: "POST",
				data: { feeling_id : fid },
				dataType: "html"
			});
			 
			request.done(function( list ) {
				$(".related-container").html(list);		
			});
			 
			request.fail(function( jqXHR, textStatus ) {
				console.log("Failed to load related feelings");
			});
		</script>
		<span>Loading...</span>
		
	</div>
</div>

</body>
</html>