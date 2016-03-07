<?php
//ob_start(); require 'app/FirePHPCore/fb.php';//<!-- PHP debugger remove in production -->
define('aux', true);
include 'app/authentication.php';

$auth = new auth();

if(isset($_POST['signupButton'])){
	$auth->createUser($_POST['signupEmail'], $_POST['signupPassword'], 0);
	echo 'signup: ';
	echo ($auth) ? 'true' : 'false';
}

if(isset($_POST['loginButton'])){
	$loginResponse = $auth->login($_POST['loginEmail'], $_POST['loginPassword']);
	echo 'login: ';
	echo $loginResponse;
}

?>