<?php
	require_once 'includes/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
	<meta charset="utf-8">
	<title>Schneider Electric Smart Gen App</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- STYLES -->
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.min.css">

	<!-- SCRIPTS -->
	<script src="<?php echo BASE_URL; ?>js/vendor/jquery.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/js.cookie.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/form-validator/jquery.form-validator.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/Chart.bundle.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/jquery.datetimepicker.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/components.js"></script>
	<script src="<?php echo BASE_URL; ?>js/app.js"></script>

	<script src="<?php echo BASE_URL; ?>js/vendor/RGraph.common.core.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/RGraph.common.dynamic.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/RGraph.common.tooltips.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/RGraph.gauge.js"></script>
	<script>
		
		/* Global URL variable for JS files */
		var base_url = '<?= BASE_URL; ?>';

	</script>
</head>