<?php
/**
 * users.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this file display all the user enrolled in a tournament 
 */
 
 // require all the files 
 require_once 'Matchup.inc.php';
 require_once 'session.php';
 
 // create an instanceof MURegistration
 $registration = new MURegistration();
 
 // check if the submit button is pressed
 if(isset($_POST["submit"]))
 {
     // get all the values 
     $username = $_POST["username"];
     $tournament = $_POST["tournament"];
     $price = $_POST["price"];
     $award = $_POST["award"];
     $approved = isset($_POST['approved']) && $_POST['approved']  ? "1" : "0";
     // update all the values 
     $registration->updateAll($username, $tournament, $price, $award, $approved);
 }
 
 // get all the users that are enrolled in the tournament 
 $allUsers = $registration->getUsersEnrolledInTournament($_GET["tournament"]);
 
 ?>
 <!DOCTYPE html>
 <html>
 <head>
     <title>Utenti del torneo</title>
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
        Utenti iscritti al torneo
        <table class='table'>
            <tr><td class='td'>
                utente
            </td><td class='td'>
                punteggio
            </td><td class='td'>
                posizione
            </td><td class='td'>
                prezzo
            </td><td class='td'>
                premio
            </td><td class='td'>
                approvato
            </td><td class='td'>
                azione
            </td></tr>
        <?php
            for($i = 0; $i < count($allUsers); $i++)
            {
                echo "<tr><td class='td'>";
                echo $allUsers[$i]["utente"];
                echo "</td><td class='td'>";
                echo $allUsers[$i]["punteggio"];
                echo "</td><td class='td'>";
                echo $allUsers[$i]["posizione"];
                echo "</td><td class='td'>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='username' value='".$allUsers[$i]["utente"]."' />";
                echo "<input type='hidden' name='tournament' value='".$allUsers[$i]["torneo_id"]."' />";
                echo "<input type='text' name='price' value='".$allUsers[$i]["prezzo"]."'/>";
                echo "</td><td class='td'>";
                echo "<input type='text' name='award' value='".$allUsers[$i]["premio"]."'/>";
                echo "</td><td class='td'>";
                if($allUsers[$i]["approvato"] === "1")
                {
                    echo "<input type='checkbox' name='approved' checked>";
                }
                else
                {
                    echo "<input type='checkbox' name='approved'>";
                }
                echo "</td><td class='td'>";
                echo "<input type='submit' name='submit' value='Aggiorna'/>";
                echo "</form>";
                echo "</td></tr>"; 
            }
        ?>
        </table>
    </div>
 </body>
 </html>
<?php
?>