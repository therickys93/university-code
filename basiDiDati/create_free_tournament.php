<?php

    require_once 'Matchup.inc.php';
    
    
    if(isset($_POST["submit"]))
    {
        MUDebug($_POST);
        $match = new MUMatch();
        if($match->createMatchesForFreeTournament($_POST["tournament_id"], $_POST["day"], $_POST["players"]))
        {
            $tournament = new MUTournaments();
            $type = $tournament->getType($_POST["tournament_id"]);
            header("location: match.php?tournament=".$_POST["tournament_id"]."&tipo=".$type);
            exit;
        }
    }

?>