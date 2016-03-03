<?php

	/**
	* MUUsers.class.php
	*
	* Riccardo Crippa
	* therickys93@gmail.com
	*
	* this class manage users things
	*/
	class MUUsers
	{
		// declare an instance of the database
		private $database;

		// create the construct method
		function __construct()
		{
			// require the database settings and the MYSQL class
			require_once 'include/connect.inc.php';
			require_once 'include/MYSQL.class.php';
			// create an instance of MYSQL 
			$this->database = new MYSQL(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			// connect the php to the database
			$this->database->connect();
		}

		// function that check if the user is already registered
		function isUserAlreadyRegistered($username)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			// create the query
			$query = "SELECT username FROM Utenti WHERE username='$username'";
			// run the query
			$this->database->query($query);
			// check the number of rows returned by the query
			$rows = $this->database->numRows();
			// if the rows are > 0 means that the user is already registered
			if($rows > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		// function that check if the user is accepted
		function isUserAccepted($username)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			// create the query
			$query = "SELECT abilitato FROM Utenti WHERE username='$username'";
			// run the query
			$this->database->query($query);
			// check if the abilitato value is equal to 1
			if($this->database->fetchAssoc()['abilitato'] === "1")
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		// function that get all users from the database
		function getAllUsers()
		{
			// create the query
			$query = "SELECT email, username, ruolo, abilitato FROM Utenti WHERE ruolo!='amministratore'";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				return $this->database->resultsInArray();
			}
			else
			{
				return false;
			}
		}

		// function that loggin a user
		function loginUser($username, $password)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$password = md5($this->database->sqlInjection($password));
			// create the query
			$query = "SELECT nome, cognome, email, username, ruolo FROM Utenti WHERE username='$username' AND password='$password'";
			// run the query
			$this->database->query($query);
			// check the number of rows returned by the query
			$rows = $this->database->numRows();
			// if the rows are > 0
			if($rows > 0)
			{
				// return the entire result
				return $this->database->fetchAssoc();
			}
			else
			{
				return false;
			}
		}
		
		// function that return all the information about the user
		function getInformationAboutUser($username)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			// create the query
			$query = "SELECT nome, cognome, email, username, ruolo FROM Utenti WHERE username='$username'";
			// run the query
			$this->database->query($query);
			// check the number of rows returned by the query
			$rows = $this->database->numRows();
			// if the rows are > 0
			if($rows > 0)
			{
				// return the entire result
				return $this->database->fetchAssoc();
			}
			else
			{
				return false;
			}
		}
		
		// function that change the abilitato field
		function changeEnableState($username, $enable)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$enable = $this->database->sqlInjection($enable);
			// create the query
			$query = "UPDATE Utenti SET abilitato='$enable' WHERE username='$username'";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
        
        // function that return the credit of the user 
        function getCredit($username)
        {
            // prevent any type of sql injection
            $username = $this->database->sqlInjection($username); 
            // create the query 
            $query = "SELECT credito FROM Utenti WHERE username='$username'";
            // run the query 
            $this->database->query($query);
            // check if the query goes well
            if($this->database->queryOk())
            {
                // return the credit 
                return $this->database->fetchAssoc()["credito"];
            } 
            else
            {
                return false;
            }
        }

		// function that change the password
		function changePassword($username, $newPassword)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$newPassword = md5($this->database->sqlInjection($newPassword));
			// create the query
			$query = "UPDATE Utenti SET password='$newPassword' WHERE username='$username'";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		// function that chage the name 
		function changeName($username, $newName)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$newName = $this->database->sqlInjection($newName);
			// create the query
			$query = "UPDATE Utenti SET nome='$newName' WHERE username='$username'";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		// function that change the surname 
		function changeSurname($username, $newSurname)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$newSurname = $this->database->sqlInjection($newSurname);
			// create the query
			$query = "UPDATE Utenti SET cognome='$newSurname' WHERE username='$username'";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		// function that register user
		function registerUser($username, $password, $email, $role)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$password = md5($this->database->sqlInjection($password));
			$email = $this->database->sqlInjection($email);
			$role = $this->database->sqlInjection($role);
			// create the query
			$query = "INSERT INTO Utenti (email, username, password, ruolo) VALUES ('$email', '$username', '$password', '$role')";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		// function that is called when the object is going to be destroyed
		function __destruct()
		{
			// close the connection with the database
			$this->database->close();
		}
	}

?>