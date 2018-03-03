<?php 
	require_once 'modules/session.php';
	include_once 'includes/head.php';

	if ($session_role !== 'testing') {
		header("Location:".BASE_URL);
	}
?>

<body class="testing">
	
	<?php include_once 'includes/nav.php'; ?>

	<div class="testing__content">
		
		<div class="page-header">
			<div class="page-header__left">
				<h1>SmartGen Testing 
					<span class="pill pill-blue ml-5">System: <?= $session_bay; ?></span>
					<span class="pill pill-blue pill-outline ml-5"><?= $session_system; ?></span>
				</h1>
			</div>

			<div class="page-header__right">
				<div class="testing__progress-bar progress-bar__green hide" id="testingProgressBar">
					<span style="width: 100%"></span>
				</div>
				<span class="pill pill-orange pill-outline" id="testStatusPill">No test running</span>
			</div>
		</div>
	
		<div class="container">

			<div class="row compress">
				<!-- product check -->
				<div class="col sm-1-4">
					<div class="card product-check-card mb-5">
						<div class="card__header">
							<h3>Product Info 
								<span class="help-icon" data-toggle="tooltip" data-placement="top" title="Scan or enter product information"><i class="fa fa-question-circle"></i></span>
							</h3>
						</div>
						<form action="#" id="productCheckForm" method="POST" class="form">
							<div class="card__body">
								<div class="form__group">
									<label for="model">Model ID</label>
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

					<div class="card hide" id="bayRecCard">
						<div class="card__body mt-2 mb-2">
							<button class="btn btn-green btn-block rec-btn" id="bayRecBtn" data-stage="2" disabled>Receive at bay</button>
						</div>
					</div>

					<div class="help-block mb-5 product-stage-help">
						<strong>Note:</strong> Scan or enter the product details in the form on the right, then check the status of the product. Based on the product stage you will be able to select one of the options above.
					</div>
				</div>
				<!-- product check -->
				<div class="col sm-3-4">
					<!-- test buttons -->
					<div class="card test-buttons-card">
						<div class="card__header">
							<h3>Test Stage
								<span class="help-icon" data-toggle="tooltip" data-placement="top" title="This shows products current test stage"><i class="fa fa-question-circle"></i></span>
							</h3>
						</div>

						<!-- add classes to this tag to simulate progress styles - progress-00/25/50/75/100 -->
						<div class="card__body test-type">
	
							<!-- test progress -->
							<div class="test-progress">
								<div class="test-progress__bg">
									<span></span>
								</div>
								<div class="test-progress__steps-bg">
									<div class="row collapse">
										<div class="col xs-1-4 ta-c test-progress__steps-bg-one">
											<span></span>
										</div>
										<div class="col xs-1-4 ta-c test-progress__steps-bg-two">
											<span></span>
										</div>
										<div class="col xs-1-4 ta-c test-progress__steps-bg-three">
											<span></span>
										</div>
										<div class="col xs-1-4 ta-c test-progress__steps-bg-four">
											<span></span>
										</div>
									</div>
								</div>
								<div class="test-progress__steps">
									<div class="row collapse">
										<div class="col xs-1-4 ta-c">
											<span class="test-progress__num test-progress__num-one">Hipot</span>
										</div>
										<div class="col xs-1-4 ta-c">
											<span class="test-progress__num test-progress__num-two">Low Power</span>
										</div>
										<div class="col xs-1-4 ta-c">
											<span class="test-progress__num test-progress__num-three">Burn In</span>
										</div>
										<div class="col xs-1-4 ta-c">
											<span class="test-progress__num test-progress__num-four">FCT</span>
										</div>
									</div>
								</div>
							</div>
							<!-- test progress -->
							
							<!-- test operations -->
							<h3 class="mb-5">Test Controls</h3>

							<div class="tester-names form">
								<h4 class="mb-2">Set tester name</h4>
								
								<div class="row compress">
									<div class="col sm-1-5 p-r">
										<input type="text" id="tester-name" class="show-ib" placeholder="Your name">
										<button id="addTesterBtn" class="btn btn-green btn-outline" disabled><i class="fa fa-plus"></i></button>
									</div>
									<div class="col sm-4-5 mt-1" id="testerNamesBox">
										
									</div>
								</div>
							</div>

							<div class="row test-ops p-r">
								<div class="col sm-1-4 test-buttons">
									<div class="main-test-buttons mb-2">
										<h4 class="mb-2">General Test Controls</h4>
										<!-- system name -->
										<input type="hidden" name="system" id="testSystem" value="<?= $session_system; ?>">
										<button class="btn btn-blue btn-icon btn-outline btn-block mb-2" id="startTestBtn" disabled><i class="fa fa-play"></i> Start Test</button>
										<button class="btn btn-orange btn-icon btn-outline btn-block mb-2 hide" id="pauseTestBtn" disabled><i class="fa fa-pause"></i> Pause Test</button>
										<button class="btn btn-green btn-icon btn-outline btn-block mb-2 hide" id="continueTestBtn" disabled><i class="fa fa-play"></i> Continue Test</button>
									</div>
								</div>
								
								<div class="col sm-2-4 border-right border-left test-buttons">
									<div class="fail-test-controls form">
										<h4 class="mb-2">Fail Test Controls</h4>
										
										<div class="select-list mb-2">
											<select name="fail-reason" id="failReason" disabled>
												<option selected disabled>Select reason for failure</option>
												<option value="Power Failure">Power Failure</option>
												<option value="Bay Down">Bay Down</option>
												<option value="Unit Failure">Unit Failure</option>
											</select>
										</div>

										<button class="btn btn-red btn-icon btn-outline btn-block mb-2" id="stopTestBtn" disabled><i class="fa fa-stop"></i> Stop Test</button>
										<button class="btn btn-green btn-icon btn-outline btn-block mb-4 hide" id="resumeTestBtn" disabled><i class="fa fa-play-circle"></i> Resume Test</button>

										<div class="major-failure hide" id="majorFailureBox">
											<textarea id="majorFailReason" placeholder="In case of major failure, describe the failure here and send product for rework" disabled></textarea>
											<button class="btn btn-red btn-icon btn-block mb-2" id="majorFailBtn" disabled><i class="fa fa-play-circle-o"></i> Major Failure! Send to rework</button>
										</div>
									</div>
								</div>

								<div class="col sm-1-4 test-buttons">
									<h4 class="mb-2">Complete Test</h4>

									<button class="btn btn-green btn-icon btn-block mb-2" id="completeTestBtn" disabled><i class="fa fa-check"></i> Complete Test</button>
								</div>
							</div>
							<!-- test operations -->
								
							<div class="tests-done-msg">
								<span><i class="fa fa-check-circle-o"></i> All tests done</span>
							</div>

						</div>

						<div class="card__footer">
							<div class="test-instructions">
								<h4>Instructions</h4>
								<ol>
									<li>Scan or enter product details. Then, check product details.</li>
									<li>Receive product.</li>
									<li>Select bay, start testing.</li>
									<li>Stop testing, if test fails.</li>
									<li>Resume testing, after stopping test.</li>
									<li>If there is another failure during testing, stop and resume testing again.</li>
									<li>Complete testing.</li>
									<li>Send product.</li>
								</ol>
							</div>
						</div>

						<div class="card__progress-bar progress-bar__orange">
							<span style="width: 100%"></span>
						</div>
					</div>				
				</div>
			</div>
		</div>
	</div>

	<script src="js/check_product.js"></script>
	<script src="js/send_receive.js"></script>
	<script src="js/testing.js"></script>
	<script type="text/javascript">
		
		setTimeout(function () {
			window.location.href = "<?php echo AUTO_BASE_URL; ?>";
		}, 300000);

	</script>
	
</body>
</html>