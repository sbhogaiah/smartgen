<?php
	
	require_once '../includes/bootstrap.php';
	
	session_start();
	$a = session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Schneider Electric Test Monitor App</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style type="text/css">
		
		* {
			box-sizing: border-box;
		}

		body {
			background-color: #f6f6f9;
			font-family: sans-serif;
		}

		#page {
			position: absolute;
			top: 50%;
			left: 50%;
			width: 500px;
			height: 100px;
			background-color: #fff;
			margin-top: -50px;
			margin-left: -250px;
			padding: 1rem;
			text-align: center;
		}

		#page h2 {
			margin: 5px 0;
		}

		#page p {
			margin: 0;
		}

	</style>
</head>
<body>
	
	<!-- IMPORTANT: DO NOT REMOVE -->
	<div id="page">

		<div class="fixed-center">
			<h2>You have been auto logged out for no activity for 5 minutes.</h2>
			<p>Redirecting you to homepage.</p>
		</div>

	</div>

	<script type="text/javascript">
		
		setTimeout(function () {
			window.location.href = "<?php echo BASE_URL; ?>";
		}, 5000);

	</script>

</body>
</html>