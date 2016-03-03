<?php
/**
 * tournaments.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this file show the tournaments
 */
 
    // require all the files 
    require_once 'Matchup.inc.php';
    require_once 'session.php';
 
 ?>
 <!DOCTYPE html>
 <html>
 <head>
     <title>Tornei</title>
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
        I miei tornei
        <?php
        
            $tournaments = new MUTournaments();
            if($_SESSION["ruolo"] === "amministratore")
            {
                // show all the tournaments
                $results = $tournaments->getAllTournaments();
                ?>
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
                    </td></tr>
                <?php
                for($i = 0; $i < count($results); $i++)
                {
                    echo "<tr><td class='td'>";
                    echo $results[$i]["nome"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["edizione"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["data"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["ora"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["tipo"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["organizzatore"];
                    echo "</td></tr>";
                }
                ?>
                </table>
                <?php
            }
            else if($_SESSION["ruolo"] === "organizzatore")
            {
                // show tournaments that I create
                $results = $tournaments->getAllTournamentsByOrganizator($_SESSION["username"]);
                ?>
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
                        Azione
                    </td></tr>
                <?php
                for($i = 0; $i < count($results); $i++)
                {
                    echo "<tr><td class='td'>";
                    echo $results[$i]["nome"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["edizione"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["data"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["ora"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["tipo"];
                    echo "</td><td class='td'>";
                    echo "<a href='users.php?tournament=".$results[$i]["ID"]."'>Visualizza iscritti</a>";
                    echo " / ";
                    echo "<a href='match.php?tournament=".$results[$i]["ID"]."&tipo=".$results[$i]["tipo"]."'>Visualizza gare</a>";
                    echo " / ";
                    echo "<a href='rank.php?tournament=".$results[$i]["ID"]."'>Visualizza classifica</a>";
                    echo "</td></tr>";
                    
                }
                ?>
                </table>
                <br />
                <a href="create.php">Crea Torneo</a>
                <br /><br /><br />
                Tornei a cui risulto iscritto
                <?php
                $results = $tournaments->getTournamentsUserIsEnrolledIn($_SESSION["username"]);
                ?>
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
                        Punteggio
                    </td><td class='td'>
                        Posizione
                    </td><td class='td'>
                        Prezzo
                    </td><td class='td'>
                        Premio
                    </td><td class='td'>
                        Approvato
                    </td></tr>
                <?php
                for($i = 0; $i < count($results); $i++)
                {
                    echo "<tr><td class='td'>";
                    echo $results[$i]["nome"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["edizione"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["data"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["ora"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["tipo"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["organizzatore"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["punteggio"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["posizione"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["prezzo"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["premio"];
                    echo "</td><td class='td'>";
                    if($results[$i]["approvato"] === "1")
                    {
                        echo "<input type='checkbox' name='approved' checked disabled>";
                    }
                    else
                    {
                        echo "<input type='checkbox' name='approved' disabled>";
                    }
                    echo "</td></tr>";
                }
                ?>
                </table>
                <?php
            }
            else
            {
                // show the tournaments that I can enroll in
                $results = $tournaments->getTournamentsUserIsEnrolledIn($_SESSION["username"]);
                ?>
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
                        Organizzatore
                    </td><td class='td'>
                        Punteggio
                    </td><td class='td'>
                        Posizione
                    </td><td class='td'>
                        Prezzo
                    </td><td class='td'>
                        Premio
                    </td><td class='td'>
                        Approvato
                    </td><td class='td'>
                        Azione
                    </td></tr>
                <?php
                for($i = 0; $i < count($results); $i++)
                {
                    echo "<tr><td class='td'>";
                    echo $results[$i]["nome"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["edizione"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["data"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["ora"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["organizzatore"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["punteggio"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["posizione"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["prezzo"];
                    echo "</td><td class='td'>";
                    echo $results[$i]["premio"];
                    echo "</td><td class='td'>";
                    if($results[$i]["approvato"] === "1")
                    {
                        echo "<input type='checkbox' name='approved' checked disabled>";
                    }
                    else
                    {
                        echo "<input type='checkbox' name='approved' disabled>";
                    }
                    echo "</td>";
                    echo "<td class='td'><a href='match.php?tournament=".$results[$i]["torneo_id"]."'>Visualizza calendario</a>";
                    echo "/";
                    echo "<a href='rank.php?tournament=".$results[$i]["torneo_id"]."'>Visualizza classifica</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
                </table>
                <br />
                <a href="enroll.php">Iscriviti ad un torneo</a>
                <br />
                <?php
            }
        ?>
    </div>
 </body>
 </html>
