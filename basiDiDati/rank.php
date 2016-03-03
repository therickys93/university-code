<?php
/**
 * rank.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * this file display the rank for a tournament
 */
 
 // require all the files 
 require_once 'Matchup.inc.php';
 require_once 'session.php';
 
 // create an instanceof MUMatch
 $match = new MUMatch();
 // get the rank for a tournament
 $rank = $match->getRank($_GET["tournament"]);
 
 ?>
 <!DOCTYPE html>
 <html>
 <head>
     <title>Classifica</title>
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
       Classifica
       <table class="table">
           <tr>
               <td class="td">Utente</td>
               <td class="td">Punteggio</td>
           </tr>
           <?php
           	$tournament = new MUTournaments();
            $type = $tournament->getType($_GET["tournament"]);
            if($type === "libero")
            {
                $allMatches = $match->getRankForFreeTournament($_GET["tournament"]);
                for($i = 0; $i < count($allMatches); $i++)
                {
                    echo "<tr>";
                    echo "<td class='td'>".$allMatches[$i]["utente"]."</td>";
                    echo "<td class='td'>".$allMatches[$i]["punti"]."</td>";
                    echo "</tr>";
                }
            }
            else 
            {
                for($i = 0; $i < count($rank); $i++)
                {
                    echo "<tr>";
                    echo "<td class='td'>".$rank[$i]["utente"]."</td>";
                    echo "<td class='td'>".$rank[$i]["punteggio"]."</td>";
                    echo "</tr>";
                }
            }
           ?>
       </table>
    </div>
 </body>
 </html>
