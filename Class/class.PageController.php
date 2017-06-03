<?php
	require_once("class.Feeling.php");
	
	class PageController {
		
		public function ShowFeeling($feeling) {
			echo '<div class="feel" data-id="'.$feeling->GetId().'"><div class="feel-body"><img src="https://api.adorable.io/avatars/50/'.$feeling->GetTime().'.png"><b>'.$feeling->GetName().'</b> is feeling <span>'.$feeling->GetFeel().'</span></div></div>';
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
	}
?>