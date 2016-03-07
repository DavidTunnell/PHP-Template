<?php
define('dbax', true); 
include 'app/database.php';

class ic {

    function __construct() {
    	if (!defined('icx')) { exit; }
    }

	function test_return() {
		$db = new Db(); 
		$testSelectQuery = $db->db_select('SELECT * FROM test');
		$test = $testSelectQuery[0];
		FB::log($test);
	    return $test['testcol'] . " through  index controller";
	}
}
?>