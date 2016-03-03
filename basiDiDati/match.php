<?php
/**
 * match.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this file display the matches 
 */
 
 // require all the files 
 require_once 'Matchup.inc.php';
 require_once 'session.php';
 
 // create an instanceof MUMatch
 $match = new MUMatch();
 // check if the submit button is pressed 
 if(isset($_POST["submit"]))
 {
     // create the maches for the tournament
    $match->createMatches($_POST["tournament_id"], $_POST["tournament_type"]);
    // get all the matches from the tournament 
    $allMatches = $match->getAllMatchesFromTournament($_POST["tournament_id"]);
 }
 else if(isset($_POST["aggiorna"])) // check if the aggiorna button is pressed 
 {
     if($_GET["tipo"] === "eliminazione diretta")
     {
         $match->updateScoreForEliminazioneDiretta($_POST["match_id"], $_POST["score"], $_POST["torneo_id"], $_POST["turno"]);
         $allMatches = $match->getAllMatchesFromTournament($_GET["tournament"]);
     }
     else
     {
        // update the score
        $match->updateScore($_POST["match_id"], $_POST["score"]);
        // get all the matches from the tournament
        $allMatches = $match->getAllMatchesFromTournament($_GET["tournament"]); 
     }
     
 }
 else if(isset($_POST["free"]))
 {
     $score = $_POST["score"];
     $points = $_POST["points"];
     $id = $_POST["id"];
     $match->updateFreeTournament($_GET["tournament"], $score, $points, $id);
 }
 else
 {
     // get all the matches from a tournament from GET 
    $allMatches = $match->getAllMatchesFromTournament($_GET["tournament"]); 
 }
 

 
 ?>
 <!DOCTYPE html>
 <html>
 <head>
     <title>Gestisci Gare</title>
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
       Calendario Gare
       <?php 
       
       if($_SESSION["ruolo"] === "organizzatore" && $_GET["tipo"] === "libero")
       {
           $allMatches = $match->getAllMatchesFromFreeTournament($_GET["tournament"]);
            echo "<br /><br />";
            ?>
                <table class="table">
                 <tr><td class='td'>
                        Giornata
                    </td><td class='td'>
                        Utente
                    </td><td class='td'>
                        Risultato
                    </td><td class='td'>
                        Punti
                    </td><td class='td'>
                        Aggiorna
                    </td></tr>
            <?php 
            for($i = 0; $i < count($allMatches); $i++)
            {
                echo "<tr>";
                echo "<td class='td'>";
                echo $allMatches[$i]["giornata"];
                echo "</td>";
                echo "<td class='td'>";
                echo $allMatches[$i]["utente"];
                echo "</td>";
                echo "<td class='td'>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='id' value='".$allMatches[$i]["ID"]."' />";
                echo "<input type='text' placeholder='inserisci risultato per giocatore' name='score' value='".$allMatches[$i]["risultato"]."' />";
                echo "</td>";
                echo "<td class='td'>";
                echo "<input type='text' placeholder='inserisci punti' name='points' value='".$allMatches[$i]["punti"]."' />";
                echo "</td>";
                echo "<td class='td'>";
                echo "<input type='submit' name='free' value='Aggiorna' />";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            ?>
            <table class="table">
                <tr>
                    <td class="td">Iscritti</td>
                    <td class="td">Giornata</td>
                    <td class="td">Azione</td>
                </tr>
            <?php
            $registration = new MURegistration();
            $users = $registration->getUserNotApproved($_GET["tournament"]);
            echo "<form action='create_free_tournament.php' method='POST'>";
            for($i = 0; $i < count($users); $i++)
            {
                echo "<tr><td class='td'>";
                echo "<input type='checkbox' name='players[]' value='".$users[$i]["utente"]."'/>".$users[$i]["utente"];
                if($i == 0)
                {
                    echo "</td>";
                    echo "<td class='td'>";
                    echo "<input type='hidden' name='tournament_id' value='".$_GET["tournament"]."' />";
                    echo "<input type='text' name='day' placeholder='giornata' />";
                    echo "</td><td class='td'>";
                    echo "<input type='submit' name='submit' value='Crea Gara' />";
                    echo "</td>";
                }
                echo "</td></tr>";
            }
            echo "</form>";
            echo "</table>";
       } 
       else if($_SESSION["ruolo"] === "organizzatore")
       {
            if(!$allMatches)
            {
                echo "<br /><br />";
                ?>
                <form method="POST">
                    <input type="hidden" name="tournament_id" value="<?php echo $_GET['tournament']; ?>" />
                    <input type="hidden" name="tournament_type" value="<?php echo $_GET['tipo']; ?>" />
                    <input type="submit" name="submit" value="Genera Gare" />
                </form>
                <?php    
            }
            else
            {
            ?>
                <table class="table">
                 <tr><td class='td'>
                        Giornata
                    </td><td class='td'>
                        Casa
                    </td><td class='td'>
                        Trasferta
                    </td><td class='td'>
                        Risultato
                    </td><td class='td'>
                        Aggiorna
                    </td><td class='td'>
                        Vincitore
                    </td></tr>
            <?php 
                for($i = 0; $i < count($allMatches); $i++)
                {
                    echo "<tr><td class='td'>".$allMatches[$i]["giornata"]."</td>";
                    echo "<td class='td'>".$allMatches[$i]["casa"]."</td>";
                    echo "<td class='td'>".$allMatches[$i]["trasferta"]."</td>";
                    echo "<td class='td'><form method='POST'>";
                    echo "<input type='hidden' name='turno' value='".$allMatches[$i]["giornata"]."'/><input type='hidden' name='torneo_id' value='".$allMatches[$i]["torneo"]."'/><input type='hidden' name='match_id' value='".$allMatches[$i]["ID"]."'/><input type='text' placeholder='formato: 0-0' name='score' value='".$allMatches[$i]["risultato"]."'/></td>";
                    echo "<td class='td'><input type='submit' name='aggiorna' value='Aggiorna' /></form></td>";
                    echo "<td class='td'>".$allMatches[$i]["vincitore"]."</td></tr>";
                }
                echo "</table>";
            }
       }
       else
       {
           ?>
          <table class="table">
                 
            <?php 
                $tournament = new MUTournaments();
                $type = $tournament->getType($_GET["tournament"]);
                if($type === "libero")
                {
                ?>
                <tr><td class='td'>
                        Giornata
                    </td><td class='td'>
                        Casa
                    </td><td class='td'>
                        Risultato
                    </td><td class='td'>
                        Punti
                    </td></tr>
                <?php
                    $allMatches = $match->getAllMatchesFromFreeTournament($_GET["tournament"]);
                    for($i = 0; $i < count($allMatches); $i++)
                    {
                        echo "<tr><td class='td'>".$allMatches[$i]["giornata"]."</td>";
                        echo "<td class='td'>".$allMatches[$i]["utente"]."</td>";
                        echo "<td class='td'>".$allMatches[$i]["risultato"]."</td>";
                        echo "<td class='td'>".$allMatches[$i]["punti"]."</td>";
                    }
                }
                else
                {
                    ?>
                    <tr><td class='td'>
                        Giornata
                    </td><td class='td'>
                        Casa
                    </td><td class='td'>
                        Trasferta
                    </td><td class='td'>
                        Risultato
                    </td><td class='td'>
                        Vincitore
                    </td></tr>
                    <?php
                    for($i = 0; $i < count($allMatches); $i++)
                    {
                        echo "<tr><td class='td'>".$allMatches[$i]["giornata"]."</td>";
                        echo "<td class='td'>".$allMatches[$i]["casa"]."</td>";
                        echo "<td class='td'>".$allMatches[$i]["trasferta"]."</td>";
                        echo "<td class='td'>".$allMatches[$i]["risultato"]."</td>";
                        echo "<td class='td'>".$allMatches[$i]["vincitore"]."</td></tr>";
                    } 
                }
                
                echo "</table>";
       } 
       ?>
    </div>
 </body>
 </html>
