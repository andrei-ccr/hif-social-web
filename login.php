
	<!DOCTYPE html>
	<html lang="en-us">
	<head>
		<?php require_once("Include/include.head.php"); ?>
		<title>Login - How I Feel</title>
	</head>
	<body>
		<?php
			if(isset($_GET['result'])) {
				if(!$_GET['result']) {
					if(isset($_GET['action'])) {
						if($_GET['action'] == "login") {
							echo '<b style="color: red;">Invalid username and password combination!</b>';
						} else if($_GET['action'] == "reg") {
							echo '<b style="color: red;">Could not create account! Invalid or duplicate information provided!</b>';
						}
					}
				}
			}
			
		?>
		<h3>Login</h3>
		<form action="account_action.php" method="POST">
			<input type="hidden" name="action_login" value="1"/>
			<input type="text" name="username" placeholder="Username"/>
			<input type="password" name="password" placeholder="Password"/>
			<input type="submit" name="submit" value="Log in"/>
		</form>
		<br>
		<h3>Create a new account</h3>
		<form action="account_action.php" method="POST">
			<input type="hidden" name="action_register" value="1"/>
			<input type="text" name="username" placeholder="Username"/>
			<input type="password" name="password" placeholder="Password"/>
			<input type="email" name="email" placeholder="Email"/>
			<input type="submit" name="submit" value="Register"/>
		</form>
	</body>
	</html>

