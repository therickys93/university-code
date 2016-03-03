<?php
/**
 * home.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this file show the user profile and a menu
 */
 
 	// require all the classes and the session
 	require_once 'Matchup.inc.php';
	require_once 'session.php';
    
    $user = new MUUsers();
    $credit = $user->getCredit($_SESSION["username"]);
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>HomePage</title>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>
	<div class="header">
		<div class="content_left"><b>MatchUp</b></div>
		<div class="content_right">
			<ul>
				<li><a href="home.php">Profilo</a></li>
				<li><a href="tournaments.php">Torneo</a></li>
				<li><a href="logout.php">Esci</a></li>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
	<div class="content">
		Il Mio Profilo
		<table class="table">
		<?php 
			// print out what it's inside the $_SESSION array
			foreach($_SESSION as $key => $value)
			{
				echo "<tr><form action='update.php' method='POST'>";
				echo "<td class='td'>".$key."</td>";
				// check if the key are equal to username, email, ruolo
				if($key === "username" || $key === "email" || $key === "ruolo")
				{
					echo "<td class='td'><input type='text' value='".$value."' disabled/></td>";
					echo "<td class='td'><input type='submit' value='Aggiorna' disabled/></td>";	
				}
				else
				{
					echo "<td class='td'><input autocomplete='off' type='hidden' name='key' value='".$key."'/><input name='value' type='text' value='".$value."'/></td>";
					echo "<td class='td'><input name='update' type='submit' value='Aggiorna' /></td>";
				}

				echo "</form></tr>";
			}
            echo "<tr>";
            echo "<td class='td'>Credito</td>";
            echo "<td class='td'><input type='text' value='".$credit."' disabled/></td>";
			echo "<td class='td'><input type='submit' value='Aggiorna' disabled/></td>";
            echo "</tr>";
		?>
			<tr><form action="update.php" method="POST">
				<td class="td">password</td>
				<td class="td"><input type="hidden" name="key" value="password"/><input autocomplete="off" type="password" name="value" /></td>
				<td class="td"><input name="update" type="submit" value="Aggiorna" /></td>
			</form></tr>
		</table>
		<?php
			// show this part only if the user is an administrator
			if($_SESSION["ruolo"] === "amministratore")
			{
				// create an object of MUUsers
				$user = new MUUsers();
				// get all the users from the database
				$allUsers = $user->getAllUsers();
				// print some information
				echo "Utenti non ancora accettati";
				echo "<table class='table'>";
				echo "<tr><td class='td'>";
				echo "Email";
				echo "</td><td class='td'>";
				echo "Username";
				echo "</td><td class='td'>";
				echo "Ruolo";
				echo "</td><td class='td'>";
				echo "Accetta/Rifiuta";
				echo "</td></tr>";
				// display all the users
				for($i = 0; $i < count($allUsers); $i++)
				{
					echo "<tr><form action='update.php' method='POST'><td class='td'>";
					echo $allUsers[$i]["email"];
					echo "</td><td class='td'>";
					echo $allUsers[$i]["username"];
					echo "</td><td class='td'>";
					echo $allUsers[$i]["ruolo"];
					// check if the user is enable
					if($allUsers[$i]["abilitato"] === "1")
					{
						echo "</td><td class='td'>";
						echo "<input type='hidden' value='0' name='value' />";
						echo "<input type='hidden' value='".$allUsers[$i]["username"]."' name='username' />";
						echo "<input type='submit' value='Rifiuta' name='enable' />";	
					}
					else
					{
						echo "</td><td class='td'>";
						echo "<input type='hidden' value='1' name='value' />";
						echo "<input type='hidden' value='".$allUsers[$i]["username"]."' name='username' />";
						echo "<input type='submit' value='Accetta' name='enable'/>";
					}
					echo "</td></form></tr>";
				}
				echo "</table>";
			}
		?>
	</div>
</body>
</html>
