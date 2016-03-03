<?php
/**
 * updateinfo.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this script update the user information and a draft of the user accept
 */

 	// require the session
 	require_once 'session.php';
	// check if the script was called from the pression of the button 
 	if(isset($_POST["update"]))
	{
		// get out the value of the port array
		$key = $_POST["key"];
		$value = $_POST["value"];
		// include the Matchup classes
		require_once 'Matchup.inc.php';
		// create an object of MUUsers
		$user = new MUUsers();
		// select which is the case here and what to do
		switch($key)
		{
			case "nome":
				// update the name
				$user->changeName($_SESSION["username"], $value);
				break;
			case "cognome":
				// update the surname
				$user->changeSurname($_SESSION["username"], $value);
				break;
			case "password":
				// update the password
				$user->changePassword($_SESSION["username"], $value);
				break;
			default:
				// redirect to home.php and exit the script
				header("location: home.php");
				exit;
				break;
		}
		// take out the updated information from the database
		$newUser = $user->getInformationAboutUser($_SESSION["username"]);
		// reset the session array
		$_SESSION = $newUser;
		// redirect to the home.php and exit the script
		header("location: home.php");
		exit;
	}
	else if(isset($_POST["enable"]))
	{
		// get out the value from the post array
		$newValue = $_POST["value"];
		$username = $_POST["username"];
		// require the file with all the classes
		require_once 'Matchup.inc.php';
		// create an object of MUUsers
		$user = new MUUsers();
		// update the enable state
		$user->changeEnableState($username, $newValue);
		// redirect the user to the home.php page and exit the script
		header("location: home.php");
		exit;
	}
	else
	{
		// redirect user to index.php file
		header("location: index.php");
		exit;
	}

?>