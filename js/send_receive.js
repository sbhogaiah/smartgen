/* for namespacing */
$(function () {
	var sendBtn = $('.send-btn');
	var recBtn = $('.rec-btn');
	var form = $('#productCheckForm');

	/* supporting functions */

	// send from production
	function stage1() {

		// create the product record in db
		var formData = form.serialize();

		// start loader
		components.loader.on('.production__content');

		$.ajax({
			url: base_url + 'modules/create_product.php',
			data: formData,
			method: 'post',
			dataType: 'json'
		}).done(function (d) {
			if (d.message) {
				components.nag.show(d.message, 'success');
				sendBtn.prop('disabled', true);
				recBtn.prop('disabled', true);
				components.help('.product-stage-help', 'info', '<b>Note:</b> Enter or Scan product details to check the status of the product. ');
			}
		}).always(function (d) {
			components.loader.off('.production__content');
			components.form.reset(form);
		}).fail(function ( error ) {
			components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
			sendBtn.prop('disabled', true);
			recBtn.prop('disabled', true);
			console.log(error);
		});

	}

	// receive at bay
	function stages( stage ) {

		// create the product record in db
		var formData = form.serialize() + '&stage=' + stage;
		
		// start loader
		components.loader.on('.testing__content');

		$.ajax({
			url: base_url + 'modules/product_stages.php',
			data: formData,
			method: 'post',
			dataType: 'json'
		}).done(function (d) {
			console.log(d)
			if (d.message) {
				
				components.nag.show(d.message, 'success');
				
				sendBtn.prop('disabled', true);
				recBtn.prop('disabled', true);

				if(stage === 2) {
					$('#bayRecCard').addClass('hide');
					testing.progress.zero();
					components.help('.product-stage-help', 'info', '<b>Note:</b> Select the available test option to begin testing product.');
				} else {
					components.help('.product-stage-help', 'info', '<b>Note:</b> Enter or Scan product details to check the status of the product.');
				}
			}
		}).always(function (d) {
			components.loader.off('.testing__content');
		}).fail(function ( error ) {
			components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
			sendBtn.prop('disabled', true);
			recBtn.prop('disabled', true);
			console.log(error);
		});

	}

	/* send button */
	sendBtn.on('click', function (e) {
		e.preventDefault();

		// button has data-stage attribute containing info of which stage is next
		var stage = $(this).data('stage');

		if(stage === 1) {
			stage1();
		} else {
			stages(stage);
		}

	});

	/* send button */
	recBtn.on('click', function (e) {
		e.preventDefault();

		// button has data-stage attribute containing info of which stage is next
		var stage = $(this).data('stage');

		stages(stage);

	});

});