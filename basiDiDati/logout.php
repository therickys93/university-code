<?php
/**
 * logout.php
 *
 * Riccardo Crippa
 * therickys93@gmail.com
 *
 * destroy the session
 */
 	
	// require the session.php
 	require_once 'session.php';
	// unset the $_SESSION array
	session_unset();
	// destroy the session
 	session_destroy();
	// redirect the user to the index.php
	header("location: index.php");
	exit;

?>