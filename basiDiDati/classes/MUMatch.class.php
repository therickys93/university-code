<?php
/**
 * MUMatch.class.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this class manage match objects
 */
 
 class MUMatch
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
     
     // create the matches
     function createMatches($tournament_id, $tournament_type)
     {
         // prevent any type of sql injection
         $tournament_id = $this->database->sqlInjection($tournament_id);
         $tournament_type = $this->database->sqlInjection($tournament_type);
         // check the type of the tournament
         if($tournament_type === "italiana")
         {
             // crea_torneo_italiana(torneo_id);
             $query = "CALL crea_torneo_italiana('".$tournament_id."')";
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
         else if($tournament_type === "eliminazione diretta")
         {
             // crea_torneo_eliminazione_diretta(torneo_id);
             $query = "CALL crea_torneo_eliminazione_diretta('".$tournament_id."')";
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
         else if($tournament_type === "misto")
         {
             // crea_torneo_misto(torneo_id)
             $query = "CALL crea_torneo_misto('".$tournament_id."')";
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
         else
         {
             return false;
         }
     }
     
     // create the matches for the free tournament $players is an array 
     function createMatchesForFreeTournament($tournament_id, $day, $players)
     {
         // prevent any type of sql injection
         $tournament_id = $this->database->sqlInjection($tournament_id);
         $day = $this->database->sqlInjection($day);
         foreach ($players as $user) 
         {
            // create the query
            $query = "INSERT INTO Gara_torneo_libero (torneo, giornata, utente) VALUES ('$tournament_id', '$day', '$user')";
            // run the query  
            $this->database->query($query);
         }
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
     
     function updateFreeTournament($tournament_id, $score, $points, $id)
     {
         // prevent any type of sql injection
         $tournament_id = $this->database->sqlInjection($tournament_id);
         $score = $this->database->sqlInjection($score);
         $points = $this->database->sqlInjection($points);
         $id = $this->database->sqlInjection($id);
         // create the query
         $query = "UPDATE Gara_torneo_libero SET risultato='$score', punti='$points' WHERE torneo='$tournament_id' AND ID='$id'";
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
     
     function getRankForFreeTournament($tournament_id)
     {
         // prevent any type of sql injection
         $tournament_id = $this->database->sqlInjection($tournament_id);
         // create the query
         $query = "SELECT SUM(punti) AS punti, utente FROM Gara_torneo_libero WHERE torneo = '$tournament_id' GROUP BY utente ORDER BY punti DESC";
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
     
     // get all the matches for the free tournament 
     function getAllMatchesFromFreeTournament($tournament_id)
     {
         // prevent any type of sql injection
         $tournament_id = $this->database->sqlInjection($tournament_id);
         // create the query 
         $query = "SELECT * FROM Gara_torneo_libero WHERE torneo = $tournament_id ORDER BY giornata ASC";
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
     
     // get all the matches from the tournament
     function getAllMatchesFromTournament($tournament_id)
     {
         // prevent any type of sql injection
         $tournament_id = $this->database->sqlInjection($tournament_id);
         // create the query 
         $query = "SELECT * FROM Gara WHERE torneo = $tournament_id ORDER BY giornata ASC";
         // run the query 
         $this->database->query($query);
         // check if the query goes well
         if($this->database->queryOk())
         {
             // return the value in array 
             return $this->database->resultsInArray(); 
         } 
         else
         {
             return false;
         }
     }
     
     //function that return the rank for a given tournament 
     function getRank($tournament_id)
     {
         // prevent any type of sql injection
         $tournament_id = $this->database->sqlInjection($tournament_id);
         // create the query 
         $query = "SELECT punteggio, utente FROM Iscrizione WHERE torneo_id = $tournament_id AND approvato = 1 ORDER BY punteggio DESC";
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
     
     function updateScoreForEliminazioneDiretta($match_id, $score, $torneo_id, $turno)
     {
         // update the score normally
         $this->updateScore($match_id, $score);
         // create the next game
         $query = "CALL crea_prossime_partite(".$torneo_id.",".$turno.")";
         $this->database->query($query);
         if($this->database->queryOk())
         {
             return true;
         }
         else 
         {
             return false;    
         }
     }
     
     //function that update the score
     function updateScore($match_id, $score)
     {
        // prevent any type of sql injection
        $match_id = $this->database->sqlInjection($match_id);
        $score = $this->database->sqlInjection($score);
        // get all the two point from the $score
        $minusSign = stripos($score, "-");
        // if the minus sign we can go on
        if($minusSign)
        {
            // get the home score 
            $home = substr($score, 0, $minusSign);
            // get the away score 
            $away = substr($score, ($minusSign+1));
            // create the query 
            // TODO: this function inserisci_risultato(match_id, home, away) in the database 
            $query = "CALL inserisci_risultato(".$match_id.",".$home.",".$away.")";
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
        else
        {
            return false;
        }
     }
     
     // function that is called when the object is going to be destroy
     function __destruct()
     {
         // close the connection with the database 
         $this->database->close();
     }
 }
 
 ?>
