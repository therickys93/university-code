<?php
/**
 * enroll.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this script register user to a tournament 
 */
 
    // require all the files 
    require_once 'Matchup.inc.php';
    require_once 'session.php';
    // create an instanceof MUTournaments
    $tournaments = new MUTournaments();
    // get all the tournament user is not register 
    $allTournaments = $tournaments->getTournamentsUserIsNotEnrolledIn($_SESSION["username"]);
    
    // check if the submit button is pressed 
    if(isset($_POST["submit"]))
    {
        // get the tournament id 
        $tournament_id = $_POST["tournament_id"];
        // create an instanceof MURegistration
        $registration = new MURegistration();
        // register the user to the tournament 
        if($registration->registerUserToTournament($_SESSION["username"], $tournament_id))
        {
            // redirect the user to the tournament 
            header("location: tournaments.php");
            exit;
        } 
    }
 
 ?>
 <!DOCTYPE html>
 <html>
 <head>
     <title>Iscriviti al torneo</title>
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
        Tutti i Tornei
        <br />
        <table class='table'>
            <tr><td class='td'>
                Nome
            </td><td class='td'>
                Edizione
            </td><td class='td'>
                Data
            </td><td class='td'>
                Ora
            </td><td class='td'>
                Tipo
            </td><td class='td'>
                Organizzatore
            </td><td class='td'>
                Iscriviti
            </td></tr>
        <?php
        for($i = 0; $i < count($allTournaments); $i++)
            {
                echo "<tr><td class='td'>";
                echo $allTournaments[$i]["nome"];
                echo "</td><td class='td'>";
                echo $allTournaments[$i]["edizione"];
                echo "</td><td class='td'>";
                echo $allTournaments[$i]["data"];
                echo "</td><td class='td'>";
                echo $allTournaments[$i]["ora"];
                echo "</td><td class='td'>";
                echo $allTournaments[$i]["tipo"];
                echo "</td><td class='td'>";
                echo $allTournaments[$i]["organizzatore"];
                echo "</td><td class='td'>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='tournament_id' value='".$allTournaments[$i]["ID"]."' />";
                echo "<input type='submit' name='submit' value='Iscriviti' />";
                echo "</form>";
                echo "</td></tr>";
                
            }   
        ?>
        </table>
    </div>
 </body>
 </html>