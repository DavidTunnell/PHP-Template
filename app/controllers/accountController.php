<?php
define('aux', true);
include 'app/authentication.php';

class ac {

    function __construct() {
    	if (!defined('acx')) { exit; }
    }

	function test_return_ac() {
		$db = new Db(); 
		$testSelectQuery = $db->db_select('SELECT * FROM test');
		$test = $testSelectQuery[0];
		FB::log($test);
	    return $test['testcol'] . " through account controller";
	}

	function check_if_authorized() {
		$auth = new auth();
		$authorized = $auth->checkSession();
		//$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
		if (!$authorized) {
			header('Location: login.php');
		}
	}
}
?>