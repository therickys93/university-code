<?php
/**
 * login.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * script to login user
 */

 	// require the session
 	require_once 'session.php';
	// check if the script was called after the button pressed in the login form 
 	if(isset($_POST["login"]))
	{
		// check if the value are empty
		 if(!empty($_POST["username"]) && !empty($_POST["password"]))
		 {
			 // extract all the values from $_POST array
			 $username = $_POST["username"];
			 $password = $_POST["password"];
			 // include all the classes
			 require_once 'Matchup.inc.php';
			 // create an object of MUUsers
			 $user = new MUUsers();
			 // check if the user is accepted
			 if($user->isUserAccepted($username))
			 {
				 // login the user
				 $loggedUser = $user->loginUser($username, $password);
				 // check if the user was successfully logged in
				 if($loggedUser)
				 {
					 // start session and redirect to home.php
					 $_SESSION = $loggedUser;
					 header("location: home.php");
					 exit;
				 }
				 else
				 {
					 // something wrong happen
					 $login_response_success = "0";
                     $login_response_message = "Username%20o%20password%20non%20non%20corrispondono";
				 }
			 }
			 else
			 {
				 // user not already accepted
				 $login_response_success = "0";
				 $login_response_message = "Utente%20non%20ancora%20accettato";
			 }
		 }
		 else
		 {
			 // some fields are empty
			 $login_response_success = "0";
			 $login_response_message = "Alcuni%20campi%20sono%20vuoti";
		 }
		 // redirect the user to the index.php with the message
		 header("location: index.php?login_response_success=".$login_response_success."&login_response_message=".$login_response_message);
		 exit;
	}
	else
	{
		// redirect user to the index page
		header("location: index.php");
		exit;
	}

?>