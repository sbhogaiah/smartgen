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
				<h1>Yield Reports</h1>
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
							<h3>Search Yield</h3>
						</div>
						<div class="card__body">
							<form action="#" id="report_search_form" class="form">
								<div class="form__group">
									<label for="report-model">Model ID</label>
									<input type="text" id="report-model" name="report-model" placeholder="Model ID" class="form__control" data-validation="required">
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
					<strong>Note:</strong> Search by Model ID or select date range.
				</div>
	
				<div class="report-contents yield-report report-records hidden">
					<div class="report-contents-header report-header">
						<h4><strong>Search Results</strong> </h4>
					</div>
				 <div class="report-data-container">
					<div class="report-summary">
						<span class="report-cell">
							<span class="cell-content">
								<small>Model ID</small>
								<strong class="modelid"></strong>
							</span>
						</span>
						<span class="report-cell">
							<span class="cell-content">
								<small>From</small>
								<strong class="from-date"></strong>
							</span>
						</span>
						<span class="report-cell">
							<span class="cell-content">
								<small>To</small>
								<strong class="to-date"></strong>
							</span>
						</span>
						<span class="report-cell">
							<span class="cell-content">
								<small>Total Products Tested</small>
								<strong class="total-count">0</strong>
							</span>
						</span>
					</div>
					<div class="report-table">
						<table class="table">
							<thead>
								<tr>
									<th><span>#</span></th>
									<th class="table-col-full"><span>Model ID</span></th>
									<th class=""><span class="pass">Passed</span></th>
									<th class=""><span class="fail">Failed</span></th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" class="text-right"><span>Total</span></td>
									<td><span class="total-passed">0</span></td>
									<td><span class="total-failed">0</span></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="download-table" id="downloadData" style="display: none;">
						<table>
							<thead>
								<tr>
									<th>#</th>
									<th >Model ID</th>
									<th>Passed</th>
									<th>Failed</th>
								</tr>										
							</thead>
							<tbody>
								
							</tbody>
							<tfoot>
								
							</tfoot>									
						</table>
					</div>							
				</div>			
			</div>
		</div>
	</div>
	
<!--
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
-->

	<script src="js/reports_yield.js"></script>
	<script>
		
		$(function () {
			components.sidebar.init();
		});

	</script>

	
</body>
</html>