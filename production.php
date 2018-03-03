<?php 
	require_once 'modules/session.php';
	include_once 'includes/head.php';

	if ($session_role !== 'production') {
		header("Location:".BASE_URL);
	}
?>

<body class="production">
	
	<?php include_once 'includes/nav.php'; ?>

	<div class="production__content  mt-5">
	    <div class="row col maxw-50 mt-4 ml-4">
		<div class="col md-8-12">
					<div class="card product-check-card">
						<div class="card__header">
							<h3>Product Info 
								<span class="help-icon" data-toggle="tooltip" data-placement="top" title="Scan or enter product information"><i class="fa fa-question-circle-o"></i></span>
							</h3>
						</div>
						<form action="#" id="productCheckForm" method="POST" class="form">
							<div class="card__body">
								<div class="form__group">
									<label for="model">SKU ID</label>
									<input type="text" id="model" name="model" placeholder="SKU ID" class="form__control" data-validation="required">
								</div>
								<div class="form__group">
									<label for="serial">Serial Number</label>
									<input type="text" id="serial" name="serial" placeholder="Serial Number" class="form__control" data-validation="required">
								</div>
							</div>
							<div class="card__footer">
								<div class="btn-group">
									<button type="submit" class="btn btn-blue sm-3-4">Check Product</button>
									<button type="reset" class="btn btn-blue btn-darker sm-1-4"><i class="fa fa-repeat"></i></button>
								</div>
							</div>

							<div class="card__progress-bar progress-bar__orange">
								<span style="width: 100%"></span>
							</div>
						</form>
					</div>
		</div>

		<div class="col md-8-12 ">
					<div class="card">
						<div class="card__header">
							<h3>Product Stage Info</h3>
						</div>
						
						<div class="card__body mb-5">
							<div class="help-block product-stage-help">
								<strong>Note:</strong> Enter or Scan product details to check the status of the product.
							</div>
						</div>

						<div class="card__footer">
							<button class="btn btn-green btn-block send-btn" disabled id="prodSendBtn" data-stage="1">Send to Bay</button>
						</div>
					</div>
		</div>
		</div>
		</div>
	
	<script src="js/check_product.js"></script>
	<script src="js/send_receive.js"></script>
	
</body>
</html>