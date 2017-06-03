<?php
	require_once("class.Feeling.php");
	require_once("class.Comment.php");
	
	class PageController {
		
		public function ShowFeeling($feeling) {
			echo '
				<div class="feel" data-id="'.$feeling->GetId().'">
					<div class="feel-body">
						<img src="https://api.adorable.io/avatars/50/'.$feeling->GetTime().'.png">
						<b>'.$feeling->GetName().'</b> is feeling <span>'.$feeling->GetFeel().'</span>
						<span style="font-size: 12px; display: block; text-align:right; color: gray;">Comments: '.$feeling->CountComments().'</span>
					</div>
				</div>';
		}
		
		public function ShowFeelings($feelings) {
			if($feelings && is_array($feelings)) {
				foreach($feelings as $feeling) {
					$this->ShowFeeling($feeling);
				}
			}
		}
		
		public function ShowLatestFeelings($feelings) {
			if(count($feelings) > 0 && $feelings!=null) {
				echo '<h2>Latest Feelings</h2>';
				$this->ShowFeelings($feelings);
			} else {
				echo 'No feelings at this time :|';
			}
		}
		
		public function ShowSuggestions($suggestions) {
			foreach($suggestions as $s) {
				echo '<li>'.$s.'</li>';
			}
		}
		
		public function ShowDiscussionFeeling($feeling) {
			if($feeling == null) {
				echo "<h2>This discussion doesn't exist :(</h2>";
			} else {
				echo $this->ShowFeeling($feeling);
			}
		}
		
		public function ShowComments($comments) {
			if(count($comments) > 0 && $comments!=null) {
				foreach($comments as $c) {
					echo '
						<div class="comment">
							<span class="username">'.$c->GetName().'</span>
							<p>'.$c->GetComment().'</p>
						</div>';
				}
			} else {
				echo '<span style="display: block">No comments here so far :(</span>';
			}
		}
	}
?>