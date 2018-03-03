var testing = {};

/* selectors */
testing.sel = {
	'serial': '#serial',
	'model': '#model',
	'testSystem': '#testSystem',
	'card' : '.test-buttons-card',
	'startBtn' : '#startTestBtn',
	'pauseBtn' : '#pauseTestBtn',
	'resumeBtn' : '#resumeTestBtn',
	'reasonSelect': '#failReason',
	'stopBtn' : '#stopTestBtn',
	'continueBtn' : '#continueTestBtn',
	'majorFailureBox': '#majorFailureBox',
	'majorFailReason': '#majorFailReason',
	'majorFailBtn': '#majorFailBtn',
	'completeBtn' : '#completeTestBtn',
	'productCheckForm': '#productCheckForm',
	'testerNamesBox': '#testerNamesBox',
	'addTesterBtn': '#addTesterBtn',
	'testStatusPill': '#testStatusPill',
	'testingProgressBar': '#testingProgressBar'
}

/* before testing start */
testing.buttons = {
	'start' : function () {
		var cur = testing;

		$(cur.sel.startBtn).off('click');
		$(cur.sel.startBtn).on('click', function (e) {
			e.preventDefault();

			// data
			var tester = $('#tester-name').val();

			// check if tester name input
			if (!tester) {
				components.nag.show('Enter your name before starting the test', 'error');
				return false;
			}

			// product
			var serial = $(cur.sel.serial).val();
			var model = $(cur.sel.model).val();
			var system = $(cur.sel.testSystem).val();

			// form data
			var formData = 'system='+system+'&serial='+serial+'&model='+model+'&tester='+tester;

			// loading
			components.card.loading( cur.sel.card );

			$.ajax({
				url: base_url+'modules/tests/start.php',
				data: formData,
				method: 'POST',
				dataType: 'json'
			}).done(function (d) {
				console.log(d);
				if (d.status == 'ok') {
					testing.stages.inProgress();
					testing.names.append(tester);
					components.nag.show('Test Started.', 'success');
				}
			}).always(function () {
				components.card.loaded( cur.sel.card );
			}).fail(function ( error ) {
				components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
				console.log(error);
			});

		});
	},

	'stop' : function () {
		var cur = testing;

		$(cur.sel.reasonSelect).off('click');
		$(cur.sel.reasonSelect).on('change', function (e) {
			if ($(this).val()) {
				$(cur.sel.stopBtn).prop('disabled', false);
			}
		});

		$(cur.sel.stopBtn).off('click');
		$(cur.sel.stopBtn).on('click', function (e) {
			e.preventDefault();

			// loading
			components.card.loading( cur.sel.card );

			// data
			var tester = $(cur.sel.testerNamesBox).find('.tester-name:last').text();
			var serial = $(cur.sel.serial).val();
			var model = $(cur.sel.model).val();
			var system = $(cur.sel.testSystem).val();
			var reason = $(cur.sel.reasonSelect).val();

			// form data
			var formData = 'system='+system+'&serial='+serial+'&model='+model+'&tester='+tester+'&failReason='+reason;

			$.ajax({
				url: base_url+'modules/tests/stop.php',
				data: formData,
				method: 'POST',
				dataType: 'json'
			}).done(function (d) {
				console.log(d);
				if (d.status == 'ok') {
					testing.stages.failed();
					components.nag.show('Test failed.', 'error');
				}
			}).always(function () {
				components.card.loaded( cur.sel.card );
			}).fail(function ( error ) {
				components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
				console.log(error);
			});
			
			//Send SMS
			$.ajax({
				url: base_url+'modules/tests/sendsms.php',
				data: formData,
				method: 'POST',
				dataType: 'json'
			}).done(function (d) {
				console.log(d);
				if (d.status == 'ok') {
					components.nag.show('SMS sent successfully.', 'error');
				}
			}).fail(function ( error ) {
				//components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
				console.log(error);
			});

		});
	},

	'resume' : function () {
		var cur = testing;

		$(cur.sel.resumeBtn).off('click');
		$(cur.sel.resumeBtn).on('click', function (e) {
			e.preventDefault();

			// loading
			components.card.loading( cur.sel.card );

			// data
			var tester = $(cur.sel.testerNamesBox).find('.tester-name:last').text();
			var serial = $(cur.sel.serial).val();
			var model = $(cur.sel.model).val();
			var system = $(cur.sel.testSystem).val();

			// form data
			var formData = 'system='+system+'&serial='+serial+'&model='+model+'&tester='+tester;

			$.ajax({
				url: base_url+'modules/tests/resume.php',
				data: formData,
				method: 'POST',
				dataType: 'json'
			}).done(function (d) {

				if (d.status == 'ok') {
					testing.stages.inProgress();
					components.nag.show('Test resume.', 'success');
				}
			}).always(function () {
				components.card.loaded( cur.sel.card );
			}).fail(function ( error ) {
				components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
				console.log(error);
			});

		});
	},

	'pause' : function () {
		var cur = testing;

		$(cur.sel.pauseBtn).off('click');
		$(cur.sel.pauseBtn).on('click', function (e) {
			e.preventDefault();
			console.log('paused');

			// loading
			components.card.loading( cur.sel.card );

			// data
			var tester = $(cur.sel.testerNamesBox).find('.tester-name:last').text();
			var serial = $(cur.sel.serial).val();
			var model = $(cur.sel.model).val();
			var system = $(cur.sel.testSystem).val();

			// form data
			var formData = 'system='+system+'&serial='+serial+'&model='+model+'&tester='+tester;

			$.ajax({
				url: base_url+'modules/tests/pause.php',
				data: formData,
				method: 'POST',
				dataType: 'json'
			}).done(function (d) {
				console.log(d);
				if (d.status == 'ok') {
					testing.stages.paused();
					components.nag.show('Test paused.', 'success');
				}
			}).always(function () {
				components.card.loaded( cur.sel.card );
			}).fail(function ( error ) {
				components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
				console.log(error);
			});

		});
	},

	'continue' : function () {
		var cur = testing;

		$(cur.sel.continueBtn).off('click');
		$(cur.sel.continueBtn).on('click', function (e) {
			e.preventDefault();

			// loading
			components.card.loading( cur.sel.card );

			// data
			var tester = $(cur.sel.testerNamesBox).find('.tester-name:last').text();
			var serial = $(cur.sel.serial).val();
			var model = $(cur.sel.model).val();
			var system = $(cur.sel.testSystem).val();

			// form data
			var formData = 'system='+system+'&serial='+serial+'&model='+model+'&tester='+tester;

			$.ajax({
				url: base_url+'modules/tests/continue.php',
				data: formData,
				method: 'POST',
				dataType: 'json'
			}).done(function (d) {
				console.log(d);
				if (d.stage == 'inprogress') {
					testing.stages.inProgress();
					components.nag.show('Test in progress.', 'success');
				} else if (d.stage == 'fail') {
					testing.stages.failed();
					components.nag.show('Test failed.', 'error');
				}
			}).always(function () {
				components.card.loaded( cur.sel.card );
			}).fail(function ( error ) {
				components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
				console.log(error);
			});

		});
	},

	'remove' : function () {
		var cur = testing;

		$(cur.sel.majorFailBtn).off('click');
		$(cur.sel.majorFailBtn).on('click', function (e) {
			e.preventDefault();

			if (!$(cur.sel.majorFailReason).val()) {
				components.nag.show('Please enter reason for major failure.', 'error');
				return false;
			}

			// loading
			components.card.loading( cur.sel.card );

			// data
			var tester = $(cur.sel.testerNamesBox).find('.tester-name:last').text();
			var serial = $(cur.sel.serial).val();
			var model = $(cur.sel.model).val();
			var system = $(cur.sel.testSystem).val();
			var reason = $(cur.sel.majorFailReason).val();

			// form data
			var formData = 'system='+system+'&serial='+serial+'&model='+model+'&tester='+tester+'&reason='+reason;

			$.ajax({
				url: base_url+'modules/tests/remove.php',
				data: formData,
				method: 'POST',
				dataType: 'json'
			}).done(function (d) {
				console.log(d)
				if (d.message) {
					testing.stages.idle();
					components.nag.show('Product removed from test system correct problem and restart.', 'success');
				}
			}).always(function () {
				components.card.loaded( cur.sel.card );
			}).fail(function ( error ) {
				components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
				console.log(error);
			});

		});
	},

	'complete' : function () {
		var cur = testing;

		$(cur.sel.completeBtn).off('click');
		$(cur.sel.completeBtn).on('click', function (e) {
			e.preventDefault();

			// loading
			components.card.loading( cur.sel.card );

			// data
			var tester = $(cur.sel.testerNamesBox).find('.tester-name:last').text();
			var serial = $(cur.sel.serial).val();
			var model = $(cur.sel.model).val();
			var system = $(cur.sel.testSystem).val();

			// form data
			var formData = 'system='+system+'&serial='+serial+'&model='+model+'&tester='+tester;

			$.ajax({
				url: base_url+'modules/tests/complete.php',
				data: formData,
				method: 'POST',
				dataType: 'json'
			}).done(function (d) {
				console.log(d);
				if (d.status == 'ok') {
					testing.stages.idle();
					components.nag.show('Test completed.', 'success');
				}
			}).always(function () {
				components.card.loaded( cur.sel.card );
			}).fail(function ( error ) {
				components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
				console.log(error);
			});

		});
	}
}

/* test progress */
testing.progress = {
	'none' : function () {
		$('.test-type').removeClass('progress-00 progress-25 progress-50 progress-75 progress-100');
		$(testing.sel.startBtn).prop('disabled', true);
	},
	'zero' : function () {
		$('.test-type').removeClass('progress-25 progress-50 progress-75 progress-100')
			.addClass('progress-00');
		$(testing.sel.startBtn).prop('disabled', false);
	},
	'one' : function () {
		$('.test-type').removeClass('progress-00 progress-50 progress-75 progress-100')
			.addClass('progress-25');
		$(testing.sel.startBtn).prop('disabled', false);
	},
	'two' : function () {
		$('.test-type').removeClass('progress-25 progress-00 progress-75 progress-100')
			.addClass('progress-50');
		$(testing.sel.startBtn).prop('disabled', false);
	},
	'three' : function () {
		$('.test-type').removeClass('progress-25 progress-50 progress-00 progress-100')
			.addClass('progress-75');
		$(testing.sel.startBtn).prop('disabled', false);
	},
	'four' : function () {
		$('.test-type').removeClass('progress-25 progress-50 progress-75 progress-00')
			.addClass('progress-100');
		$(testing.sel.startBtn).prop('disabled', false);	
	},
}

/* test stages */
testing.stages = {
	'idle' : function () {
		var cur = testing;

		// testing progress
		cur.progress.none();

		// testing names remove
		cur.names.empty();
		$(cur.sel.addTesterBtn).prop('disabled', true);

		// enable the product form
		$(cur.sel.productCheckForm).find('input, button').prop('disabled', false);
		components.form.reset(cur.sel.productCheckForm);

		// activate pause control, hide start
		$(cur.sel.startBtn).removeClass('hide').prop('disabled', true);
		$(cur.sel.pauseBtn).addClass('hide').prop('disabled', true);
		$(cur.sel.continueBtn).addClass('hide').prop('disabled', true);

		// activate fail controls
		$(cur.sel.reasonSelect).prop('disabled', true)
				.find('option:first').prop('selected', true);
		$(cur.sel.stopBtn).prop('disabled', true);

		$(cur.sel.resumeBtn).prop('disabled', true).addClass('hide');
		$(cur.sel.majorFailureBox).addClass('hide');
		$(cur.sel.majorFailReason).prop('disabled', true);
		$(cur.sel.majorFailBtn).prop('disabled', true);

		// activate complete button
		$(cur.sel.completeBtn).prop('disabled', true);

		// status
		$(cur.sel.testStatusPill).text('No test started');
		$(cur.sel.testingProgressBar).addClass('hide').find('span').text('');

		// help
		components.help('.product-stage-help', 'info', 'Scan or enter the product details in the form on the right, then check the status of the product. Based on the product stage you will be able to select one of the options above.');
	},

	'inProgress' : function ( data ) {
		var cur = testing;

		// tester names
		$(cur.sel.addTesterBtn).prop('disabled', false);

		// disable the product form
		$(cur.sel.productCheckForm).find('input, button').prop('disabled', true);

		// activate pause control, hide start
		$(cur.sel.startBtn).addClass('hide').prop('disabled', true);
		$(cur.sel.pauseBtn).removeClass('hide').prop('disabled', false);
		$(cur.sel.continueBtn).addClass('hide').prop('disabled', true);

		// activate fail controls
		$(cur.sel.reasonSelect).prop('disabled', false).removeClass('hide')
				.find('option:first').prop('selected', true);
		$(cur.sel.stopBtn).prop('disabled', true).removeClass('hide');

		$(cur.sel.resumeBtn).prop('disabled', true).addClass('hide');
		$(cur.sel.majorFailureBox).addClass('hide');
		$(cur.sel.majorFailReason).prop('disabled', true);
		$(cur.sel.majorFailBtn).prop('disabled', true);

		// activate complete button
		$(cur.sel.completeBtn).prop('disabled', false);

		// if data
		if (data) {
			$(cur.sel.serial).val(data.SerialNumber);
			$(cur.sel.model).val(data.ModelId);

			/*tester names*/
			var names = data.TesterNames.replace(' ','').split(',');

			names.forEach(function (e, i) {
				testing.names.append(e);
			});
		}

		// status
		$(cur.sel.testStatusPill).text('Test in progress');
		$(cur.sel.testingProgressBar).addClass('progress-bar__green').removeClass('hide progress-bar__paused progress-bar__red').find('span').text($(cur.sel.serial).val() + '/' + $(cur.sel.model).val());

		// help
		components.help('.product-stage-help', 'success', 'Test in progress! <span class="tt-u fw-h">'+$(cur.sel.serial).val()+'</span> is being tested.');
	},

	'failed' : function ( data ) {
		var cur = testing;

		// tester names
		$(cur.sel.addTesterBtn).prop('disabled', false);

		// disable the product form
		$(cur.sel.productCheckForm).find('input, button').prop('disabled', true);

		// activate pause control, hide start
		$(cur.sel.startBtn).addClass('hide').prop('disabled', true);
		$(cur.sel.pauseBtn).removeClass('hide').prop('disabled', false);
		$(cur.sel.continueBtn).addClass('hide').prop('disabled', true);

		// activate fail controls
		$(cur.sel.reasonSelect).prop('disabled', true).addClass('hide');
		$(cur.sel.stopBtn).prop('disabled', true).addClass('hide');

		$(cur.sel.resumeBtn).prop('disabled', false).removeClass('hide');
		$(cur.sel.majorFailureBox).removeClass('hide');
		$(cur.sel.majorFailReason).prop('disabled', false);
		$(cur.sel.majorFailBtn).prop('disabled', false);

		// activate complete button
		$(cur.sel.completeBtn).prop('disabled', true);

		// if data
		if (data) {
			$(cur.sel.serial).val(data.SerialNumber);
			$(cur.sel.model).val(data.ModelId);

			/*tester names*/
			var names = data.TesterNames.replace(' ','').split(',');

			names.forEach(function (e, i) {
				testing.names.append(e);
			});
		}

		// status
		$(cur.sel.testStatusPill).text('Test Failed');
		$(cur.sel.testingProgressBar)
				.addClass('progress-bar__red')
				.removeClass('hide progress-bar__green progress-bar__paused')
				.find('span').text($(cur.sel.serial).val() + '/' + $(cur.sel.model).val());

		// help
		components.help('.product-stage-help', 'error', 'Test failed! <span class="tt-u fw-h">'+$(cur.sel.serial).val()+'</span> is being tested.');
	},

	'paused' : function ( data ) {
		var cur = testing;

		// tester names
		$(cur.sel.addTesterBtn).prop('disabled', false);

		// disable the product form
		$(cur.sel.productCheckForm).find('input, button').prop('disabled', true);

		// activate pause control, hide start
		$(cur.sel.startBtn).addClass('hide').prop('disabled', true);
		$(cur.sel.pauseBtn).addClass('hide').prop('disabled', true);
		$(cur.sel.continueBtn).removeClass('hide').prop('disabled', false);

		// activate fail controls
		$(cur.sel.reasonSelect).prop('disabled', true);
		$(cur.sel.stopBtn).prop('disabled', true);

		$(cur.sel.resumeBtn).prop('disabled', true);
		$(cur.sel.majorFailureBox);
		$(cur.sel.majorFailReason).prop('disabled', true);
		$(cur.sel.majorFailBtn).prop('disabled', true);

		// activate complete button
		$(cur.sel.completeBtn).prop('disabled', true);

		// if data
		if (data) {
			$(cur.sel.serial).val(data.SerialNumber);
			$(cur.sel.model).val(data.ModelId);

			/*tester names*/
			var names = data.TesterNames.replace(' ','').split(',');

			names.forEach(function (e, i) {
				testing.names.append(e);
			});
		}

		// status
		$(cur.sel.testStatusPill).text('Test Paused');
		$(cur.sel.testingProgressBar)
				.addClass('progress-bar__paused')
				.find('span').text($(cur.sel.serial).val() + '/' + $(cur.sel.model).val());

		// help
		components.help('.product-stage-help', 'error', 'Test paused! <span class="tt-u fw-h">'+$(cur.sel.serial).val()+'</span> is being tested.');
	},

}

/* tester names */
testing.names = {
	'append' : function ( content ) {
		$(testing.sel.testerNamesBox).append('<span class="tester-name">'+content+'</span>');
	},
	'empty': function () {
		$(testing.sel.testerNamesBox).empty();	
	},
	'add' : function () {
		$(testing.sel.addTesterBtn).off('click');
		$(testing.sel.addTesterBtn).on('click', function (e) {
			e.preventDefault();

			// check if tester name input
			if (!$('#tester-name').val()) {
				components.nag.show('Enter your name, then press the button', 'error');
				return false;
			}

			components.card.loading('.test-buttons-card');

			var latest = $('#tester-name').val();
			var system = $(testing.sel.testSystem).val();
			var serial = $(testing.sel.serial).val();
			var tester = $(testing.sel.testerNamesBox).find('.tester-name:first').text();

			$.each($(testing.sel.testerNamesBox).find('.tester-name:gt(0)'), function (i, e) {
				tester = tester + ', ' + $(e).text();
			});

			tester = tester + ', ' + latest;
	

			var formData = "tester="+tester+"&latest="+latest+"&system="+system+"&serial="+serial;

			$.ajax({
				url: base_url+'modules/tests/add_tester.php',
				data: formData,
				method: 'POST',
				dataType: 'json'
			}).done(function (d) {
				testing.names.append(latest);
				$('#tester-name').val('');
				components.nag.show('Tester added successfully!', 'success');
			}).always(function () {
				components.card.loaded('.test-buttons-card');
			}).fail(function (error) {
				components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
					console.log(error);
			});
		});
	}
}

/* check tester activity */
testing.activity = {
	'check' : function () {
		$.ajax({
			url: base_url+'modules/tests/check_activity.php',
			method: 'post',
			dataType: 'json'
		}).done(function (d) {
			if(d.data) {
				switch(d.data.Status) {
					case 'inprogress': testing.stages.inProgress(d.data); break;
					case 'fail': testing.stages.failed(d.data); break;
					case 'pause': testing.stages.paused(d.data); break;
				}
				switch(d.data.Stage) {
					case 'hipot': testing.progress.zero(); break;
					case 'lowpower': testing.progress.one(); break;
					case 'burnin': testing.progress.two(); break;
					case 'fct': testing.progress.three(); break;
				}
			}
		}).always(function () {
			components.loader.off('.testing__content');
		}).fail(function (error) {
			components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
				console.log(error);
		});
	}
}

/* initialize */
testing.init = function () {
	testing.buttons.start();
	testing.buttons.stop();
	testing.buttons.resume();
	testing.buttons.pause();
	testing.buttons.continue();
	testing.buttons.remove();
	testing.buttons.complete();
	testing.names.add();

	// activate loader
	components.loader.on('.testing__content');

	// tester activity
	testing.activity.check();
}

$(function () {
	testing.init();
});
