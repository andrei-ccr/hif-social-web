<?php
	require_once("class.Feeling.php");
	require_once("class.Comment.php");
	require_once("Emoticons.php");
	
	class PageController {
		function parseEmoticons($text) {
			global $emoticon_list;
			$ok = false;
			while(!$ok) {
				$ok = true;
				foreach($emoticon_list as $e) {
					if($pos = strpos($text, $e) !== false) {
						$text = substr_replace($text, "<img src=\"/git/feels/emoticons/angry.png\" class=\"emoticon\">", $pos-1, strlen($e)); //Replace the emoticon.
						$ok = false; //Restart testing
						break;
					}
				}
			}
			return $text;
		}

		function GetTime($timestamp) {
			$sec_elapsed = time() - strtotime($timestamp) + 3600; //Important: Time is reported by PHP with -1 hour on this server, so I add 1 hour to the elapsed time to fix this.
			if($sec_elapsed >= (3600*24*25)) {
				return "On " . date("Y-M-d", strtotime($timestamp));
			}
			else if($sec_elapsed >= (3600*24)) {
				return round($sec_elapsed/(3600*24)) . " day(s) ago";
			}
			else if($sec_elapsed >= 3600) {
				return round($sec_elapsed/3600) . " hour(s) ago";
			}
			else if($sec_elapsed >= 60) {
				return round($sec_elapsed/60) . " minute(s) ago";
			} 
			else if($sec_elapsed >=5) {
				return $sec_elapsed . " second(s) ago";
			} 
			else {
				return "Few moments ago"; 
			}
		}
		
		public function ShowFeeling($feeling, $non_clickable=false) {
			$c = ($non_clickable)?"nfeel":"feel";
			echo '
				<div class="'.$c.'" data-id="'.$feeling->GetId().'">
					<div class="feel-body">
						<img src="'.$feeling->GetUser()->GetProfilePic().'" class="pic">
						<b>'.$feeling->GetUser()->GetUsername().'</b><span> is </span><span class="feel-txt">'.$feeling->GetFeel().'</span>
						
						<div class="meta-info">
							<div class="meta-info-time">'. $this->GetTime($feeling->GetTime()) .'</div>'. $feeling->CountComments().' comment(s)
						</div>
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
				echo '<h3>Most Recent</h3>';
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
				echo $this->ShowFeeling($feeling, true);
			}
		}
		
		public function ShowComments($comments) {
			if($comments instanceof Countable) {
				if(count($comments) > 0 && $comments!=null) {
					foreach($comments as $c) {
						echo '
							<div class="comment">
								<img src="https://api.adorable.io/avatars/40/'. $c->GetTime() .'.png" class="pic">
								<span class="username">'.$c->GetUser()->GetUsername().'</span> <span class="timep">'.$this->GetTime($c->GetTime()).'</span>
								<p>'.$c->GetComment().'</p>
							</div>';
					}
				} else {
					echo '<span style="display: block">No comments here so far :(</span>';
				}
			}
		}
	}
?>