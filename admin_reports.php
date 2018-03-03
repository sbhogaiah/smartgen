<?php 
	require_once 'modules/session.php';
	include_once 'includes/head.php';

	if ($session_role !== 'admin' && $session_role !== 'guest') {
		header("Location:".BASE_URL);
	}

	$page = "admin_reports";
?>

<body class="admin admin__sidebar-collapse">
	
	<?php include_once 'includes/nav.php'; ?>
	
	<?php include_once 'includes/admin_sidebar.php'; ?>

	<div class="admin__content">
		
		<div class="page-header">
			<div class="page-header__left">
				<h1>Admin Reports</h1>
			</div>

			<div class="page-header__right">
				<a href="<?= BASE_URL; ?>admin_reports.php" class="btn btn-blue btn-sm">Product Reports</a>
				<a href="<?= BASE_URL; ?>yield_reports.php" class="btn btn-blue btn-sm">Yield Report</a>
			</div>
		</div>
	
		<div class="container">
			<div class="row">
				<div class="col md-3-12">
					<div class="card">
						<div class="card__header">
							<h3>Search Product</h3>
						</div>
						<div class="card__body">
							<form action="#" id="report_search_form" class="form">
								<div class="form__group">
									<label for="serial">Serial Number</label>
									<input type="text" id="serial" name="serial" placeholder="Serial Number" class="form__control" data-validation="required">
								</div>
								<div class="form__group">
									<button type="submit" class="btn btn-blue sm-3-4">Check Product</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col md-9-12">
					<div class="card">
						<div class="card__header">
							<h3>Search by Range</h3>
						</div>
						<div class="card__body">
								
							<div class="range-buttons row">
								<div class="col xs-3-12">
									<input type="button" class="btn btn-light btn-block" value="1 day reports" id="report_oneday">
								</div>
								<div class="col xs-3-12">
									<input type="button" class="btn btn-light btn-block" value="7 day reports" id="report_oneweek">
								</div>
								<div class="col xs-3-12">
									<input type="button" class="btn btn-light btn-block" value="15 day reports" id="report_halfmonth">
								</div>
								<div class="col xs-3-12">
									<input type="button" class="btn btn-light btn-block" value="30 day reports" id="report_month">
								</div>
							</div>

							<form action="#" id="report_range_form" class="form">
								<div class="form__group">
									<div class="date-range row">
										<div class="col xs-6-12 lg-4-12">
											<label for="start_date">From</label>
											<span class="date-picker">
												<input type="text" name="start_date" id="start_date" placeholder="yyyy-mm-dd" data-validation="required" class="error" style="border-color: rgb(185, 74, 72);"> 
											</span>
										</div>
										<div class="col xs-6-12 lg-4-12">
											<label for="end_date">To</label>
											<span class="date-picker">
												<input type="text" name="end_date" id="end_date" placeholder="yyyy-mm-dd" data-validation="required" disabled>
											</span>
										</div>
										<div class="col md-4-12 btn-group">
											<button type="submit" class="btn btn-green btn-sm" id="search_range">Generate Report</button>
											<button type="button" class="btn btn-green btn-darker btn-sm" id="download_range">Download Report</button>
										</div>
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>

			<div class="report-contents">
				<div class="help-block report-no-records">
					<strong>Note:</strong> Search by serial number or select date range.
				</div>
	
				<div class="report-records hide">
					<div class="report-header">
						<h4><strong>Search Results</strong> | <span class="total-records">0</span> records found!</h4>
					</div>
					<div class="report-data-container">
						<ul class="report-data">
						</ul>
					</div>
					<div class="download-table" id="downloadData" style="display: none;">
						<table></table>
					</div>
				</div>			
			</div>
		</div>
	</div>
	

	<div class="modal" id="testLogs">
		<div class="modal-contents">
			<button class="modal-close" data-modal-close="#testLogs">&times;</button>
			<div class="modal-header">
				<h3>Test Logs</h3>
			</div>
			<div class="modal-body">
				
			</div>
		</div>
	</div>

	<script src="js/reports.js"></script>
	<script>
		
		$(function () {
			components.sidebar.init();
		});

	</script>

	
</body>
</html>