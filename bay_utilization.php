<?php 
	require_once 'modules/session.php';
	include_once 'includes/head.php';

	if ($session_role !== 'admin' && $session_role !== 'guest') {
		header("Location:".BASE_URL);
	}

	$page = "admin_charts";
?>

<body class="admin admin__sidebar-collapse">
	
	<?php include_once 'includes/nav.php'; ?>
	
	<?php include_once 'includes/admin_sidebar.php'; ?>

	<div class="admin__content">
		
		<div class="page-header">
			<div class="page-header__left">
				<h1>Bay Utilization</h1>
			</div>

			<div class="page-header__right">
	
				<span class="date-control">
					<span class="date-control__label">Select Date:</span>
					<span class="date-picker">
						<input type="text" id="date-selector" placeholder="yyyy-mm-dd">
					</span>
					<button type="button" id="date-selector-btn" class="btn btn-green btn-outline"><i class="fa fa-check"></i></button>
				</span>

				<a href="<?= BASE_URL; ?>admin_charts.php" class="btn btn-blue btn-sm">People Performance Index</a>
			</div>
		</div>
	
		<div class="container">
			<div class="row">
				<div class="col sm-2-5">
					<div class="card">
						<div class="card__header">
							<h3>Bay Utilization: Bay 1</h3>
						</div>
						<div class="card__body">
							<canvas id="bayChart1" width="400" height="400"></canvas>
						</div>
					</div>
				</div>
				<div class="col sm-2-5">
					<div class="card">
						<div class="card__header">
							<h3>Bay Utilization: Bay 2</h3>
						</div>
						<div class="card__body">
							<canvas id="bayChart2" width="400" height="400"></canvas>
						</div>
					</div>
				</div>
				<div class="col sm-1-5">
					<div class="card">
						<div class="card__header">
							<h3>Legend</h3>
						</div>
						<div class="card__body">
							<div class="legend-container">
									
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="js/chart-options.js"></script>
	<script src="js/bayutil.js"></script>
	<script>
		
		$(function () {
			components.sidebar.init();
		});

	</script>
	
</body>
</html>