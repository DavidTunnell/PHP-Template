<?php
class Db {
    // The database connection
    protected static $connection;
    private $server;
	private $username;
	private $password;
	private $dbname;
	private $port;

    function __construct() {
    	if (!defined('dbax')) { exit; }
    	//also use .htaccess to block app folder on apache
    	$config = parse_ini_file('config.ini'); 
	    $this->server = $config['server'];
		$this->username = $config['username'];
		$this->password = $config['password'];
		$this->dbname = $config['dbname'];
		$this->port = $config['port'];
    }

	function db_connect() {

	    // Define connection as a static variable, to avoid connecting more than once 
	    static $connection;

	    // Try and connect to the database, if a connection has not been established yet
	    if(!isset($connection)) {
	    	$connection = mysqli_connect($this->server, $this->username, $this->password, 
	    		$this->dbname, $this->port);
	    }

	    // If connection was not successful, handle the error
	    if(!$connection) {
	        return mysqli_connect_error();
	    }
	    return $connection;
	}

	function db_query($query) {
	    // Connect to the database
	    $connection = $this->db_connect();

	    // Query the database
	    $result = mysqli_query($connection, $query);
	    if($result === false) {
		    // Handle failure
		    $error = db_error();
		    //report $error
		    return null;
		} else {
		    // Success
		    return $result;
		}
	}

	function db_select($query) {
	    $rows = array();
	    $result = $this->db_query($query);

	    // If query failed
	    if($result === false) {
		    $error = db_error();
		    //report $error
	    	return null;
	    }

	    // If query was successful, retrieve all the rows into an array
	    while ($row = mysqli_fetch_assoc($result)) {
	        $rows[] = $row;
	    }
	    return $rows;	
	}

	function db_quote($value) {
	    $connection = $this->db_connect();
	    return "'" . mysqli_real_escape_string($connection, $value) . "'";
	}

	function db_error() {
	    $connection = $this->db_connect();
	    return mysqli_error($connection);
	}
}
?>