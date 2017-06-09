<div id="header-acc-container">
		<?php
			if($fc->IsLoggedIn()) {
				echo '<img src="https://api.adorable.io/avatars/50/'.$_SESSION['user']->GetUsername().'.png" style="width: 40px; height: 40px; margin-top: 1px; vertical-align: middle; cursor: pointer; border: 1px solid gray;">';
				echo '<button class="header-btn" id="header-user-btn">'.$_SESSION['user']->GetUsername().'</button>';
				echo '<button class="header-btn" id="header-logout-btn">Logout</button>';
			}
			else {
				echo '<button class="header-btn" id="header-login-btn">Login</button>';
			}
		?>
	</div>