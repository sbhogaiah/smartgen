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
				<h1>Dashboard</h1>
			</div>

			<div class="page-header__right">
				<a href="<?= BASE_URL; ?>bay_utilization.php" class="btn btn-blue btn-sm">Bay Utilization Chart</a>
			</div>
		</div>
	
		<div class="container">
			<div class="row">
				<div class="col md-9-12">
					<div class="row compress">
						<div class="col sm-3-12">
							<div class="card card-info card-darkCyan">
								<div class="card__body">
									<p>Produced</p>
									<ul class="produced-info">
										<li>
											<span>Today</span>
											<strong id="prodToday">0</strong>
										</li>
										<li>
											<span>Month</span>
											<strong id="prodMonth">0</strong>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col sm-3-12">
							<div class="card card-info card-orange">
								<div class="card__body">
									<p>Tested</p>
									<ul class="tested-info">
										<li>
											<span>Today</span>
											<strong id="testedToday">0</strong>
										</li>
										<li>
											<span>Month</span>
											<strong id="testedMonth">0</strong>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col sm-3-12">
							<div class="card card-info card-green">
								<div class="card__body">
									<p>Finished</p>
									<ul class="finished-info">
										<li>
											<span>Today</span>
											<strong id="finishedToday">0</strong>
										</li>
										<li>
											<span>Month</span>
											<strong id="finishedMonth">0</strong>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col sm-3-12">
							<div class="card card-info card-blue">
								<div class="card__body">
									<p>Packaged</p>
									<ul class="packaging-info">
										<li>
											<span>Today</span>
											<strong id="packagedToday">0</strong>
										</li>
										<li>
											<span>Month</span>
											<strong id="packagedMonth">0</strong>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>

					<div class="row compress chart-cards">
						<div class="card__header">
							<h3>People Performance Index <span class="pill pill-blue ml-3">Bay 1</span></h3>
						</div>
						<div class="col sm-3-12">

							<div class="card__header">
								<div class="testing__progress-bar progress-bar__idle" id="barhipot1">
									<span style="width: 100%"> Idle</span> 
								</div>
							</div>							

							<div class="card">								
								<div class="card__body">
									<canvas id="cvs1" width="275" height="275"></canvas>
								</div>
							</div>
						</div>
						<div class="col sm-3-12">

							<div class="card__header">
								<div class="testing__progress-bar" id="barlp1">
									<span style="width: 100%"> Idle</span> 
								</div>
							</div>							

							<div class="card">								
								<div class="card__body">
									<canvas id="cvs2" width="275" height="275"></canvas>
								</div>
							</div>
						</div>						
						<div class="col sm-3-12">

							<div class="card__header">
								<div class="testing__progress-bar" id="barburnin1">
									<span style="width: 100%"> Idle</span> 
								</div>
							</div>	

							<div class="card">								
								<div class="card__body">
									<canvas id="cvs3" width="275" height="275"></canvas>
								</div>
							</div>
						</div>
						<div class="col sm-3-12">

							<div class="card__header">
								<div class="testing__progress-bar" id="barfct1">
									<span style="width: 100%"> Idle</span> 
								</div>
							</div>	

							<div class="card">								
								<div class="card__body">
									<canvas id="cvs4" width="275" height="275"></canvas>
								</div>
							</div>
						</div>				

					</div>

					<div class="row compress chart-cards">
						
						<div class="card__header">
							<h3>People Performance Index <span class="pill pill-blue ml-3">Bay 2</span></h3>
						</div>
					
						<div class="col sm-3-12">

							<div class="card__header">
								<div class="testing__progress-bar" id="barhipot2">
									<span style="width: 100%"> Idle</span> 
								</div>
							</div>	

							<div class="card">								
								<div class="card__body">
									<canvas id="cvs5" width="275" height="275"></canvas>
								</div>
							</div>
						</div>
						<div class="col sm-3-12">

							<div class="card__header">
								<div class="testing__progress-bar" id="barlp2">
									<span style="width: 100%"> Idle</span> 
								</div>
							</div>	

							<div class="card">								
								<div class="card__body">
									<canvas id="cvs6" width="275" height="275"></canvas>
								</div>
							</div>
						</div>						
						<div class="col sm-3-12">


							<div class="card__header">
								<div class="testing__progress-bar" id="barburnin2">
									<span style="width: 100%"> Idle</span> 
								</div>
							</div>	

							<div class="card">								
								<div class="card__body">
									<canvas id="cvs7" width="275" height="275"></canvas>
								</div>
							</div>
						</div>
						<div class="col sm-3-12">

							<div class="card__header">
								<div class="testing__progress-bar" id="barfct2">
									<span style="width: 100%"> Idle</span> 
								</div>
							</div>	

							<div class="card">								
								<div class="card__body">
									<canvas id="cvs8" width="275" height="275"></canvas>
								</div>
							</div>
						</div>				
						<!--
						<div class="col sm-6-12">
							<div class="card">
								<div class="card__header">
									<h3>People Performance Index <span class="pill pill-blue ml-3">Bay 1</span></h3>
								</div>
								<div class="card__body">
									<canvas id="ppiChart1" width="100%" height="250"></canvas>
								</div>
							</div>
						</div>
						<div class="col sm-6-12">
							<div class="card">
								<div class="card__header">
									<h3>People Performance Index <span class="pill pill-blue ml-3">Bay 2</span></h3>
								</div>
								<div class="card__body">
									<canvas id="ppiChart2" width="100%" height="250"></canvas>
								</div>
							</div>
						</div>
					-->
					</div>
					<div class="row compress">
						<div class="col sm-12-12">
							<div class="card legend-card">
								<div class="card">
									<div class="legend-container">
										<h3>Legend</h3>
										<ul class="legend">
											<li class="id"><span></span>Idle</li>
											<li class="rem"><span></span>Remaining</li>
											<li class="pr"><span></span>Progress</li>
											<li class="dt"><span></span>Downtime</li>
											<li class="ex"><span></span>Excess</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
			
				</div>

				<div class="col md-3-12">
					<div class="card stats-card">
						<div class="card__header">
							<h3>Today's Testing Statistics</h3>
						</div>

						<div class="card card-info card-darkCyan">
								<div class="card__body">
									<p>HIPOT</p>
									<ul class="produced-info">
										<li>
											<span>Today</span>
										</li>
										<li>
											
											<strong  data-toggle="tooltip" title="Completed" id="hipotToday">0</strong>
										</li>
									</ul>
								</div>
						</div>

						<div class="card card-info card-orange">
								<div class="card__body">
									<p>Low Power</p>
									<ul class="produced-info">
										<li>
											<span>Today</span>
										</li>
										<li>
											
											<strong  data-toggle="tooltip" title="Completed" id="lowPowerToday">0</strong>
										</li>
									</ul>
								</div>
						</div>

						<div class="card card-info card-green">
								<div class="card__body">
									<p>Burn In</p>
									<ul class="produced-info">
										<li>
											<span>Today</span>
										</li>
										<li>											
											<strong  data-toggle="tooltip" title="Completed" id="burnInToday">0</strong>
										</li>
									</ul>
								</div>
						</div>

					<div class="card card-info card-blue">
								<div class="card__body">
									<p>FCT</p>
									<ul class="produced-info">
										<li>
											<span>Today</span>
										</li>
										<li>											
											<strong  data-toggle="tooltip" title="Completed" id="fctToday">0</strong>
										</li>
									</ul>
								</div>
						</div>
<!--
						<div class="card__body">
							<table class="test-stats">
								<tr>
									<th></th>
									<th>Today</th>									
								</tr>
								<tr>
									<td>HIPOT</td>
									<td>
										<span class="pill pill-green" data-toggle="tooltip" title="Completed" id="hipotToday">0</span>
									</td>	

								</tr>
								<tr>
									<td>Low Power</td>
									<td>
										<span class="pill pill-green" data-toggle="tooltip" title="Completed" id="lowPowerToday">0</span>
									</td>
	
								</tr>
								<tr>
									<td>Burn In</td>
									<td>
										<span class="pill pill-green" data-toggle="tooltip" title="Completed" id="burnInToday">0</span>
									</td>

								</tr>
								<tr>
									<td>FCT</td>
									<td>
										<span class="pill pill-green" data-toggle="tooltip" title="Completed" id="fctToday">0</span>
									</td>

								</tr>
							</table>
						</div>
					-->
					</div>
				</div>
					
			</div>
		</div>
	</div>

	<div class="modal" id="test-details">
		<div class="modal-contents">
			<button class="modal-close" data-modal-close="#test-details">&times;</button>
			<div class="modal-header">
				<h3>Test Details</h3>
			</div>
			<div class="modal-body">
				<p><strong><span class="active-system"></span></strong></p>
				<table class="bordered">
					<thead>
						<tr>
							<th>Model ID</th>
							<th>Serial Number</th>
							<th>Tester</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><span class="active-model"></span></td>
							<td><span class="active-serial"></span></td>
							<td><span class="active-user"></span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<script src="js/chart-options.js"></script>
	<script src="js/ppi.js"></script>
	<script src="js/dashboard.js"></script>
	<script>
		
		$(function () {
			components.sidebar.init();
		});

	</script>
	
</body>
</html>