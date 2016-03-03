<?php

	/**
	* MUTournaments.class.php
	*
	* Riccardo Crippa
	* therickys93@gmail.com
	*
	* this class manage tournaments
	*/
	class MUTournaments
	{
		// declare an instance of the database
		private $database;

		// create the construct method
		function __construct()
		{
			// require the database settings and the MSQL class
			require_once 'include/connect.inc.php';
			require_once 'include/MYSQL.class.php';
			// create an instance of MYSQL
			$this->database = new MYSQL(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			// connect the php to the database
			$this->database->connect();
		}

		// function that create a tournament
		function createTournament($username, $name, $date, $time, $min_players, $max_players, $type)
		{
			//prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			$name = $this->database->sqlInjection($name);
			$date = $this->database->sqlInjection($date);
			$time = $this->database->sqlInjection($time);
			$min_players = $this->database->sqlInjection($min_players);
			$max_players = $this->database->sqlInjection($max_players);
			$type = $this->database->sqlInjection($type);
			// create the query that automatically execute the trigger in the database
			$query = "INSERT INTO Torneo (nome, data, ora, min_giocatori, max_giocatori, tipo, organizzatore) VALUES ('$name', '$date', '$time', '$min_players', '$max_players', '$type', '$username')";
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
		
		// function that return all the tournaments in the database
		function getAllTournaments()
		{
			// create the query
			$query = "SELECT nome, edizione, data, ora, tipo, organizzatore FROM Torneo";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				// return the results in array
				return $this->database->resultsInArray();
			}
			else
			{
				return false;
			}
			
		}
		
		// function that return all the tournaments the user is enrolled in
		function getTournamentsUserIsEnrolledIn($username)
		{
			// prevent any type of mysql injection
			$username = $this->database->sqlInjection($username);
			// create the query
            // nome, edizione, data, ora, tipo, organizzatore, punteggio, posizione, premio, approvato
			$query = "SELECT torneo_id, nome, edizione, data, ora, tipo, organizzatore, punteggio, posizione, prezzo, premio, approvato FROM Torneo, Iscrizione WHERE Iscrizione.utente='$username' AND Iscrizione.torneo_id=Torneo.ID";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				// return the results in array
				return $this->database->resultsInArray();
			}
			else
			{
				return false;
			}
		}
        
        // function that return the tournaments that the user is not enrolled in
        function getTournamentsUserIsNotEnrolledIn($username)
        {
            // prevent any type of mysql injection
            $username = $this->database->sqlInjection($username);
            // create the query
			$query = "SELECT * FROM Torneo WHERE ID NOT IN ( SELECT torneo_id FROM Iscrizione WHERE utente='$username')";
            // run the query
            $this->database->query($query);
            // check if rhe query goes well
            if($this->database->queryOk())
            {
                return $this->database->resultsInArray();
            } 
            else
            {
                // return the results in array 
                return false;
            }

        }
        
        function getType($tournament)
        {
            // prevent any type of sql injection
            $tournament = $this->database->sqlInjection($tournament);
            // create the query
            $query = "SELECT tipo FROM Torneo WHERE ID='$tournament'";
            // run the query
            $this->database->query($query);
            // check if the query goes well
            if($this->database->queryOk())
            {
                return $this->database->fetchAssoc()["tipo"];
            }
            else
            {
                return false;
            }
        }
		
		// function that return all the tournaments the organizator organize
		function getAllTournamentsByOrganizator($username)
		{
			// prevent any type of sql injection
			$username = $this->database->sqlInjection($username);
			// create the query
			$query = "SELECT ID, nome, edizione, data, ora, tipo FROM Torneo WHERE organizzatore='$username'";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				// return the results in array
				return $this->database->resultsInArray();	
			}
			else
			{
				return false;
			}
		}
		
		function updateDateOfATournament($id, $newDate)
		{
			// prevent any type of sql injection
			$id = $this->database->sqlInjection($id);
			$newDate = $this->database->sqlInjection($newDate);
			// create the query
			$query = "UPDATE Torneo SET data='$newDate' WHERE ID='$id'";
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
		
		function updateHourOfATournament($id, $newHour)
		{
			// prevent any type of sql injection
			$id = $this->database->sqlInjection($id);
			$newHour = $this->database->sqlInjection($newHour);
			// create the query
			$query = "UPDATE Torneo SET ora='$newHour' WHERE ID='$id'";
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
		
		function updateMinPlayersOfATournament($id, $new_min_players)
		{
			// prevent any type of sql injection
			$id = $this->database->sqlInjection($id);
			$new_min_players = $this->database->sqlInjection($new_min_players);
			// create ethe query 
			$query = "UPDATE Torneo SET min_giocatori='$new_min_players' WHERE ID='$id'";
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
		
		function updateMaxPlayersOfATournament($id, $new_max_players)
		{
			// prevent any type of sql injection
			$id = $this->database->sqlInjection($id);
			$new_max_players = $this->database->sqlInjection($new_max_players);
			// create the query 
			$query = "UPDATE Torneo SET max_giocatori='$new_max_players' WHERE ID='$id'";
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