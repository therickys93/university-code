<?php
/**
 * create.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this class create a tournament 
 */
 
 // include all the files 
 require_once 'Matchup.inc.php';
 require_once 'session.php';
 // create an instance MUType
 $types = new MUType();
 // get all types 
 $allTypes = $types->getAllTypes();
 
 // if the submit button is pressed 
 if(isset($_POST["submit"]))
 {
     // get all the values from the $_POST array 
     $name = $_POST["name"];
     $date = $_POST["date"];
     $time = $_POST["time"];
     $min_players = $_POST["min_players"];
     $max_players = $_POST["max_players"];
     $type = $_POST["type"];
     // check if there are some empty values 
     if(!empty($name) && !empty($date) && !empty($time) && !empty($min_players) && !empty($max_players) && !empty($type))
     {
         // create an instanceof MUTournaments
         $tournament = new MUTournaments();
         // format the time 
         $time = date('H:i:s', strtotime($time));
         // create the tournament 
         if($tournament->createTournament($_SESSION["username"], $name, $date, $time, $min_players, $max_players, $type))
         {
             // redirect the user 
             header("location: tournaments.php");
             exit;
         }
     }
 }
 
 
 ?>
 <!DOCTYPE html>
 <html>
 <head>
     <title>Crea torneo</title>
     <link rel="stylesheet" type="text/css" href="css/style.css" />
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
        Crea Torneo
        <form method="POST">
            <input type="text" name="name" placeholder="Nome" /><br />
            <input type="date" name="date" placeholder="Data" /><br />
            <input type="time" name="time" placeholder="ora" /><br />
            <input type="text" name="min_players" placeholder="minimo giocatori" /><br />
            <input type="text" name="max_players" placeholder="massimo giocatori" /><br />
            <select name="type">
                <?php
                    for($i = 0; $i < count($allTypes); $i++)
                    {
                        echo "<option value='".$allTypes[$i]["tipo"]."'>".$allTypes[$i]["tipo"]."</option>";
                    }
                ?>  
            </select>
            <input type="submit" value="Crea" name="submit"/>
        </form>
    </div>
 </body>
 </html>
