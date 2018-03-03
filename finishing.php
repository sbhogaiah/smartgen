<?php 
	require_once 'modules/session.php';
	include_once 'includes/head.php';

	if ($session_role !== 'finishing') {
		header("Location:".BASE_URL);
	}
?>

<body class="finishing">
	
	<?php include_once 'includes/nav.php'; ?>

	<div class="finishing__content mt-5">
		
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

				<div class="col md-8-12">
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
							<div class="btn-group">
								<button class="btn btn-green rec-btn sm-1-2" disabled id="prodRecBtn" data-stage="11">Receive at Finishing</button>
								<button class="btn btn-green btn-darker send-btn sm-1-2" disabled id="prodSendBtn" data-stage="12">Send to Packaging</button>
							</div>
						</div>
					</div>
				</div>

			</div>		
	</div>

	<script src="js/check_product.js"></script>
	<script src="js/send_receive.js"></script>
	
</body>
</html>