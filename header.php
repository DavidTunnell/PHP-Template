<?php ob_start(); require 'app/FirePHPCore/fb.php'; ?><!-- PHP debugger remove in production -->
<!--
USE:
		require 'app/FirePHPCore/fb.php';
		ob_start();
		$firephp = FirePHP::getInstance(true);
		$firephp->log('test');
-->
<?php require 'app/FirePHPCore/php_error.php'; ?><!-- PHP debugger remove in production  \php_error\reportErrors(); where needed -->
<!-- PHP debugger remove in production - http://phpcodechecker.com/ -->
<html>
<head>
	
	<link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,700' rel='stylesheet' type='text/css'>

	<!-- global site css -->
	<link rel="stylesheet" type="text/css" href="css/style.css">

	<script type="text/javascript">
	</script>

</head>
<body>


		<header> 
			<div class="center">
				<a href="index.php" title="Home"> 
					<div class="logo"></div> 
				</a>
				<div class="menu">
					<ul>
						<li class="header_menu_current"><a href="index.php" title="Dashboard">Dashboard</a></li>
					</ul>
				</div>
			</div>
		</header>