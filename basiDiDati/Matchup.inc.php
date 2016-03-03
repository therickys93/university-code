<?php

	/**
	* Matchup.inc.php
	*
	* Riccardo Crippa
	* therickys93@gmail.com
	*
	* this file is a simple inc of all classes files 
	*/
	// include all the classes file here
	require_once 'classes/MUUsers.class.php';
	require_once 'classes/MUTournaments.class.php';
	require_once 'classes/MURole.class.php';
	require_once 'classes/MUType.class.php';
    require_once 'classes/MURegistration.class.php';
    require_once 'classes/MUMatch.class.php';
	
	// define a function for debug what its inside an array or a variable
	function MUDebug($value)
	{
		echo "<pre>";
		var_dump($value);
		echo "</pre>";
	}
	
?>