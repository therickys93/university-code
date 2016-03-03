<?php
	
	/**
	* MYSQL.class.php
	*
	* Riccardo Crippa
	* therickys93@gmail.com
	*
	* this class talks directly with the mysql API
	*/
	class MYSQL{
		
		// declare some global fields
		private $connection;
		private $host;
		private $username;
		private $password;
		private $database;
		private $result;

		// create the construct method
		function __construct($host, $username, $password, $database){
			// pass all the value to the global fields
			$this->host = $host;
			$this->username = $username;
			$this->password = $password;
			$this->database = $database;
		}

		// function that connect the php with the database
		function connect(){
			// try to connect with the database
			$this->connection = @mysqli_connect($this->host, $this->username, $this->password, $this->database);
			// check if there is no connection
			if(!$this->connection){
				echo "<h1>Error connecting to the database!</h1>";
			}
		}

		// function that check if the query goes well
		function queryOk(){
			// check if there is something in the result global field
			if($this->result){
				return true; 
			} else {
				return false;
			}
		}
		
		// function that takes out the last insert id
		function lastInsertInQuery($query){
			// perform the query
			$this->result = @mysqli_query($this->connection, $query);
			// get the last insert id
			$this->result = @mysqli_insert_id($this->connection);
			// check if there is no a last insert id
			if(!$this->result){
				echo "<h1>Error in the last insert id</h1>";
			}
			return $this->result;
		}

		// function that execute the query
		function query($query){
			// perform the query
			$this->result = @mysqli_query($this->connection, $query);
			// check if there is no the result
			if(!$this->result){
				echo "<h1>Error in the query!!!</h1>";
			}
			return $this->result;
		}
		
		// function that returned the last insert id
		function lastInsert(){
			// get the last insert id
			$item = @mysqli_insert_id($this->connection);
			return $item;
		}

		// function that prevent a sql injection
		function sqlInjection($item){
			// return the item without any type of sql injection
			$item = @mysqli_real_escape_string($this->connection, $item);
			return $item;
		}

		// function that returned the assoc array 
		function fetchAssoc(){
			$row = @mysqli_fetch_assoc($this->result);
			return $row;
		}

		// function that returned the array
		function fetchArray(){
			$row = @mysqli_fetch_array($this->result);
			return $row;
		}

		// function that returned the number of rows
		function numRows(){
			$rows = @mysqli_num_rows($this->result);
			return $rows;
		}

		// function that put the > 1 results in an array
		function resultsInArray(){
			// create an empty array
			$array = array();
			// get all the value out
			while($row = @mysqli_fetch_assoc($this->result)){
				// put the value in the array
				$array[] = $row;
			}
			return $array;
		}

		// function that close the connection
		function close(){
			@mysqli_close($this->connection);
		}
		
		// function that is called when the object is going to be destroyed
		function __destruct(){
			// close the connection
			$this->close();
		}

	}

?>
