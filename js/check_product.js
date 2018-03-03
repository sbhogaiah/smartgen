/* initialize */
$(function () {

	/* declarations */
	var body = $('body');
	var form = $('#productCheckForm');	
	var card = $('.product-check-card');

	/* help messages */
	var helpMsg = {
		'stage0' : '<b>Product not in database.</b>',
		'stage1' : '<b>Product sent from production to be received at bay for testing.</b>',
		'stage2' : '<b>Product received at bay. Waiting for HIPOT test.</b>',
		'stage3' : '<b>HIPOT test is in progress for this product.</b>',
		'stage4' : '<b>HIPOT test completed for this product. Waiting for LOW POWER test.</b>',
		'stage5' : '<b>Low Power test is in progress for this product.</b>',
		'stage6' : '<b>Low Power test completed for this product. Waiting for BURN IN test.</b>',
		'stage7' : '<b>Burn In test is in progress for this product.</b>',
		'stage8' : '<b>Burn In test completed for this product. Waiting for FCT test.</b>',
		'stage9' : '<b>FCT test is in progress for this product.</b>',
		'stage10' : '<b>All tests completed for this product. Waiting for Finishing.</b>',
		'stage11' : '<b>Product received at finishing.</b>',
		'stage12' : '<b>Product sent from finishing. Waiting for Packaging.</b>',
		'stage13' : '<b>Product received at packaging.</b>',
		'stage14' : '<b>Product shipped from packaging to the customer.</b>',
	}

	/* supporting functions */
	function stage0 () {
		console.log('Not yet sent from production');

		var helpType = 'warn';
		if (body.hasClass('production')) {
			helpType = 'success';
			helpMsg.stage0 = 'Product not in database. Press send button to create record.';
		}

		components.help('.product-stage-help', helpType, helpMsg.stage0);
		$('#prodSendBtn').prop('disabled', false);
	}

	function stage1 () {
		console.log('Sent from production');
		
		var system = $('#testSystem').val();
		var helpType = 'warn';
		if (body.hasClass('testing')) {
			if (system === 'hipot 1' || system === 'hipot 2') {
				helpType = 'success';
				helpMsg.stage1 = 'Press receive at bay button to receive and start testing.';
				testing.progress.none();
				$('#bayRecCard').removeClass('hide');
				$('#bayRecBtn').prop('disabled', false);
			} else {
				helpType = 'warn';
				helpMsg.stage2 = '<b>Please follow the test flow: HIPOT > LOW POWER > BURN IN > FCT.</b>';
			}
		}

		components.help('.product-stage-help', helpType, helpMsg.stage1);

	}

	function stage2 () {
		console.log('Received at Bay');

		var system = $('#testSystem').val();
		var helpType = 'warn';
		if (body.hasClass('testing')) {
			helpType = 'success';
			helpMsg.stage2 = '<b>Press the start button to start the testing process.</b>';

			// call testing js Hipot button check
			if (system === 'hipot 1' || system === 'hipot 2') {
				testing.progress.zero();
			} else {
				helpType = 'warn';
				helpMsg.stage2 = '<b>Please login using the correct system to do HIPOT test.</b>';
			}
		}

		// change help message
		components.help('.product-stage-help', helpType, helpMsg.stage2);
	}

	function stage3 () {
		console.log('HIPOT test started');
		components.help('.product-stage-help', 'info', helpMsg.stage3);
	}

	function stage4 () {
		console.log('HIPOT test completed');
		
		var system = $('#testSystem').val();
		var helpType = 'warn';

		if (body.hasClass('testing')) {
			helpType = 'success';
			helpMsg.stage4 = '<b>Press the start button to start the testing process.</b>';
		}

		// call testing js Hipot button check
		if (system === 'low power 1' || system === 'low power 2') {
			testing.progress.one();
		} else {
			helpType = 'warn';
			helpMsg.stage4 = '<b>HIPOT test completed for this product. Please login using the correct system to do LOW POWER test.</b>';
		}

		// change help message
		components.help('.product-stage-help', helpType, helpMsg.stage4);
	}

	function stage5 () {
		console.log('Low Power test started');
		components.help('.product-stage-help', 'info', helpMsg.stage5);
	}

	function stage6 () {
		console.log('Low Power test completed');
		
		var system = $('#testSystem').val();
		var helpType = 'warn';

		if (body.hasClass('testing')) {
			helpType = 'success';
			helpMsg.stage6 = '<b>Press the start button to start the testing process.</b>';
		}

		// call testing js Hipot button check
		if (system === 'fct 1' || system === 'fct 2') {
			testing.progress.two();
		} else {
			helpType = 'warn';
			helpMsg.stage6 = '<b>LOW POWER test completed for this product. Please login using the correct system to do BURN IN test.</b>';
		}

		// change help message
		components.help('.product-stage-help', helpType, helpMsg.stage6);
	}

	function stage7 () {
		console.log('Burn In test started');
		components.help('.product-stage-help', 'info', helpMsg.stage7);
	}

	function stage8 () {
		console.log('Burn In test completed');
		
		var system = $('#testSystem').val();
		var helpType = 'warn';

		if (body.hasClass('testing')) {
			helpType = 'success';
			helpMsg.stage8 = '<b>Press the start button to start the testing process.</b>';
		}

		// call testing js Hipot button check
		if (system === 'fct 1' || system === 'fct 2') {
			testing.progress.three();
		} else {
			helpType = 'warn';
			helpMsg.stage8 = '<b>BURN IN test completed for this product. Please login using the correct system to do FCT test.</b>';
		}

		// change help message
		components.help('.product-stage-help', helpType, helpMsg.stage8);
	}

	function stage9 () {
		console.log('FCT test started');
		components.help('.product-stage-help', 'info', helpMsg.stage9);
	}

	function stage10 () {
		console.log('FCT test completed');
		
		var helpType = 'warn';
		if (body.hasClass('finishing')) {
			helpType = 'success';
			helpMsg.stage10 = 'All tests completed. Press the receive button to receive at finishing.';
			
			$('#prodRecBtn').prop('disabled', false);
		}

		components.help('.product-stage-help', helpType, helpMsg.stage10);

	}

	function stage11 () {
		console.log('Finishing in');

		var helpType = 'warn';
		if (body.hasClass('finishing')) {
			helpType = 'success';
			helpMsg.stage11 = 'Press the send button to send to packaging.';
			
			$('#prodSendBtn').prop('disabled', false);
		}

		components.help('.product-stage-help', helpType, helpMsg.stage11);
	}

	function stage12 () {
		console.log('Finishing out');

		var helpType = 'warn';
		if (body.hasClass('packaging')) {
			helpType = 'success';
			helpMsg.stage12 = 'Press the receive button to receive at packaging.';
			
			$('#prodRecBtn').prop('disabled', false);
		}

		components.help('.product-stage-help', helpType, helpMsg.stage12);
	}

	function stage13 () {
		console.log('Packaging in');
		
		var helpType = 'warn';
		if (body.hasClass('packaging')) {
			helpType = 'success';
			helpMsg.stage13 = 'Press the send button to send to customer.';
			
			$('#prodSendBtn').prop('disabled', false);
		}

		components.help('.product-stage-help', helpType, helpMsg.stage13);
	}

	function stage14 () {
		console.log('Packaging out');
		components.help('.product-stage-help', 'info', helpMsg.stage14);
	}

	// form on submit
	form.on('submit', function (e) {
		e.preventDefault();

		var $this = $(this);
		var formData = $this.serialize();

		components.card.loading( card );

		$.ajax({
			url: base_url + 'modules/check_product.php',
			data: formData,
			method: 'POST',
			dataType: 'json'
		}).done(function ( d ) {

			// if errors 'serial model do not match' or 'model not present'
			if (d.error) {
				components.help('.product-stage-help', 'error', '<b>'+d.error+'</b>');
				return false;
			}

			// if product not found
			if (d.message && d.message === 'Product not found!') {
				stage0();
				return false;
			}

			// disable buttons if active
			$('#prodRecBtn').prop('disabled', true);
			$('#prodSendBtn').prop('disabled', true);

			// check stages
			switch(d.data.Stage) {
				case '1': stage1(); break;
				case '2': stage2(); break;
				case '3': stage3(); break;
				case '4': stage4(); break;
				case '5': stage5(); break;
				case '6': stage6(); break;
				case '7': stage7(); break;
				case '8': stage8(); break;
				case '9': stage9(); break;
				case '10': stage10(); break;
				case '11': stage11(); break;
				case '12': stage12(); break;
				case '13': stage13(); break;
				case '14': stage14(); break;
			};

		}).always(function () {
			components.card.loaded( card );
		}).fail(function ( error ) {
			components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
			components.form.reset(form);
			console.log(error);
		});
	});

});