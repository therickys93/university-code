<?php
/**
 * index.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this script contains registration and login form
 */
 
 // require all the classes
 require_once 'Matchup.inc.php';
 // create an object of MURole
 $roles = new MURole();
 // get all the roles from the database
 $roles = $roles->getAllRoles();
 
?>
<!DOCTYPE html>
<html>
<head>
	<title>MatchUp</title>
	<link rel="stylesheet" type="text/css" href="css/index.css" />
</head>
<body>
	<h1 align="center">MatchUp</h1>
	<div class="signup_div">
		<div class="signup_div_form">
			<form align="center" method="POST" action="register.php">
				<table class="signup_form">
					<tr><td><h3>Registrati</h3></td></tr>
					<tr><td><input autocomplete="off" name="email" placeholder="Email" type="text"></td></tr>
					<tr><td><input name="username" autocomplete="off" placeholder="Username" type="text"></td></tr>
					<tr><td><input name="password" autocomplete="off" placeholder="Password" type="password"></td></tr>
					<tr><td><input name="repeat_password" autocomplete="off" placeholder="Ripeti Password" type="password"></td></tr>
					<tr><td><select name="role" id="">
								<?php
									// iterate all the values in the roles array
									for($i = 0; $i < count($roles); $i++)
									{
										echo "<option value='".$roles[$i]["ruolo"]."'>".$roles[$i]["ruolo"]."</option>";
									}
								?>
							</select></td></tr>
					<tr><td><input name="register" type="submit" value="Registrati"></td></tr>
				</table>
			</form>
			<?php
				// check if there are messages
				if(isset($_GET["register_response_success"]))
				{
					// check if the response success is equal to 1
					if($_GET["register_response_success"] === "1")
					{
						echo "<span class='success'>".$_GET["register_response_message"]."</span>";
					}
					else
					{
						echo "<span class='error'>".$_GET["register_response_message"]."</span>";	
					}
				}
			?>
		</div>
	</div>
	<div class="login_div">
		<div class="login_div_form">
			<form align="center" method="POST" action="login.php">
				<table class="login_form">
					<tr><td><h3>Accedi</h3></td></tr>
					<tr><td><input autocomplete="off" name="username" placeholder="Username" type="text"></td></tr>
					<tr><td><input autocomplete="off" name="password" placeholder="Password" type="password"></td></tr>
					<tr><td><input name="login" type="submit" value="Accedi"></td></tr>
				</table>
			</form>
			<?php
				// check if there are messages
				if(isset($_GET["login_response_success"]))
				{
					echo "<span class='error'>".$_GET["login_response_message"]."</span>";
				}
			?>
		</div>
	</div>
	<div class="clear"></div>	
</body>
</html>