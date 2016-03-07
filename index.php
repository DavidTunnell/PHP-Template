<?php include 'header.php' ?>
<?php define('icx', true); include 'app/controllers/indexController.php' ?>
<link rel="stylesheet" type="text/css" href="css/home.css">
		<div id="tip">
			<div class="center">
				<div class="left">
					<h2>Today's Tip</h2>
				</div>
				<div class="right">
					<?php 
						$ic = new ic();
						echo $ic->test_return();
					?>
				</div>
			</div>	
		</div>
		<main>
		</main>
<?php include 'footer.php'; ?>