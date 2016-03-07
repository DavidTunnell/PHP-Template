<?php include 'header.php' ?>
<?php define('lgx', true); include 'app/controllers/loginController.php'; $lc = new lc();?>
<link rel="stylesheet" type="text/css" href="css/login.css">
<script type="text/javascript" src="js/login.js"></script>
		<div id="tip">
			<div class="center">
				<div class="left">
					<h2>Today's Tip</h2>
				</div>
				<div class="right">
					<!-- Bibendum ut velit rhoncus leo etiam diam etiam praesent platea sit cubilia ac ad, ante cubilia ad dapibus potenti phasellus rhoncus dui eros. Vel pharetra donec suscipit justo dolor. Bibendum ut velit rhoncus leo etiam diam etiam praesent platea sit cubilia ac ad, ante cubilia ad dapibus. -->
					<?php
					echo $lc->test_return_lc();

					?>
				</div>
			</div>	
		</div>
		<main>
			<div>
				<h2>Login</h2>
				<form action="actions.php" method="post">
				  Email:<br>
				  <input type="text" name="loginEmail" value="">
				  <br>
				  Password:<br>
				  <input type="text" name="loginPassword" value="">
				  <br>
				  <input type="submit" name="loginButton" value="Submit">
				</form> 
				<h2>Signup</h2>
				<form action="actions.php" method="post">
				  Email:<br>
				  <input type="text" name="signupEmail">
				  <br>
				  Password:<br>
				  <input type="text" name="signupPassword">
				  <br>
				  <br>
				  <input type="submit" name="signupButton" value="Submit">
				</form> 
			</div>
		</main>
<?php include 'footer.php'; ?>