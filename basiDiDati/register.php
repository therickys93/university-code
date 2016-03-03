<?php
/**
 * login.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this script register the user
 */

 	// check if the script was called after the click of the button of the register form in index.php
 	if(isset($_POST["register"]))
	{
		// check if the value are not empty
		if(!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["repeat_password"]) && !empty($_POST["email"]) && !empty($_POST["role"]))
		{
			// take out the value from the $_POST array
			$username = $_POST["username"];
			$password = $_POST["password"];
			$repeat_password = $_POST["repeat_password"];
			$role = $_POST["role"];
			$email = $_POST["email"];
			// check if the two password match
			if($password === $repeat_password)
			{
				// include a global file with all the classes
				require_once 'Matchup.inc.php';
				// create an object of MUUsers
				$user = new MUUsers();
				// check if the username is already in use
				if(!$user->isUserAlreadyRegistered($username))
				{
					// check if the registration was successful 
					if($user->registerUser($username, $password, $email, $role))
					{
						// user is correctly registered
						$register_response_success = "1";
						$register_response_message = "Utente%20registrato%20con%20successo";
					}
					else
					{
						// something wrong happen
						$register_response_success = "0";
						$register_response_message = "Qualcosa%20di%20inaspettato%20e'%20accaduto";
					}
				}
				else
				{
					// user already register
					$register_response_success = "0";
					$register_response_message = "Utente%20gia'%20registrato";
				}
			}
			else
			{
				// the passwords do not match
				$register_response_success = "0";
				$register_response_message = "Le%20password%20inserite%20non%20sono%20uguali";
			}
		} 
		else
		{
			// some fields are empty
			$register_response_success = "0";
			$register_response_message = "Alcuni%20campi%20sono%20vuoti";
		}
		// go back with the message and the response
		header("location: index.php?register_response_success=".$register_response_success."&register_response_message=".$register_response_message);
		exit;
	}
	else
	{
		// redirect the user to the index page
		header("location: index.php");
		exit;
	}

?>