<?php

	/**
	* MURole.class.php
	*
	* Riccardo Crippa
	* therickys93@gmail.com
	*
	* this class manage users roles
	*/
	class MURole
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

		// function that get all the roles from the database except the amministratore
		function getAllRoles()
		{
			// create the query
			$query = "SELECT * FROM Ruolo WHERE ruolo!='amministratore'";
			// run the query
			$this->database->query($query);
			// check if the query goes well
			if($this->database->queryOk())
			{
				// return all the results in an array
				return $this->database->resultsInArray();
			}
			else
			{
				return false;
			}
		}

		// function that is called when the object is going to be destroyed
		function __destruct()
		{
			// close the connection with the database
			$this->database->close();
		}
	}

?>