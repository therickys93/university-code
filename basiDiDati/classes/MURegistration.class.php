<?php 
/**
 * MURegistration.class.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this class manage registration for a tournament
 */
 
 	class MURegistration
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
        
        // function that returns all the users enrolled in a specific tournament
        function getUsersEnrolledInTournament($tournament)
        {
            // prevent any type of mysql injection
            $tournament = $this->database->sqlInjection($tournament);
            // create the query 
            $query = "SELECT * FROM Iscrizione WHERE torneo_id='$tournament'";
            // run the query 
            $this->database->query($query);
            // check if the query goes well
            if($this->database->queryOk())
            {
                // return the data in array
                return $this->database->resultsInArray();
            } 
            else
            {
                return false;
            }
        }
        
        function getUserNotApproved($tournament)
        {
          	 // prevent any type of mysql injection
            $tournament = $this->database->sqlInjection($tournament);
            // create the query 
            $query = "SELECT * FROM Iscrizione WHERE torneo_id='$tournament' AND approvato='1'";
            // run the query 
            $this->database->query($query);
            // check if the query goes well
            if($this->database->queryOk())
            {
                // return the data in array
                return $this->database->resultsInArray();
            } 
            else
            {
                return false;
            }   
        }
		
		// function that register a user to a specific tournament
		function registerUserToTournament($username, $tournament)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$tournament = $this->database->sqlInjection($tournament);
			// create the query
			$query = "INSERT INTO Iscrizione (utente, torneo_id) VALUES ('$username', '$tournament')";
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
        
        // function that update all
        function updateAll($username, $tournament, $price, $award, $approved)
        {
            // update the price 
            $this->updatePrice($username, $tournament, $price);
            // update the approved 
            $this->updateApproved($username, $tournament, $approved);
            // update the award 
            $this->updateAward($username, $tournament, $award);
        }
		
		function updatePrice($username, $tournament, $price)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$tournament = $this->database->sqlInjection($tournament);
			$price = $this->database->sqlInjection($price);
			// create the query 
			$query = "UPDATE Iscrizione SET prezzo='$price' WHERE utente='$username' AND torneo_id='$tournament'";
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
		
		function updateAward($username, $tournament, $award)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$tournament = $this->database->sqlInjection($tournament);
			$award = $this->database->sqlInjection($award);
			// create the query 
			$query = "UPDATE Iscrizione SET premio='$award' WHERE utente='$username' AND torneo_id='$tournament'";
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
		
		function updateApproved($username, $tournament, $approved)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$tournament = $this->database->sqlInjection($tournament);
			$approved = $this->database->sqlInjection($approved);
			// create the query
			$query = "UPDATE Iscrizione SET approvato='$approved' WHERE utente='$username' AND torneo_id='$tournament'"; 
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
