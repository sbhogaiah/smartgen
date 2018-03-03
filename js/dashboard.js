$(function() {

	var prodToday = $('#prodToday'), prodMonth = $('#prodMonth'), testedToday = $('#testedToday'), testedMonth = $('#testedMonth'), finishedToday = $('#finishedToday'), finishedMonth = $('#finishedMonth'), packagedToday = $('#packagedToday'), packagedMonth = $('#packagedMonth'),
		hipotToday = $('#hipotToday'), hipotFailToday = $('#hipotFailToday'), hipotMonth = $('#hipotMonth'), hipotFailMonth = $('#hipotFailMonth'), lowPowerToday = $('#lowPowerToday'), lowPowerFailToday = $('#lowPowerFailToday'), lowPowerMonth = $('#lowPowerMonth'), lowPowerFailMonth = $('#lowPowerFailMonth'), burnInToday = $('#burnInToday'), burnInFailToday = $('#burnInFailToday'), burnInMonth = $('#burnInMonth'), burnInFailMonth = $('#burnInFailMonth'), fctToday = $('#fctToday'), fctFailToday = $('#fctFailToday'), fctMonth = $('#fctMonth'), fctFailMonth = $('#fctFailMonth');

	function dashboard() {
		$.ajax({
			url: 'modules/dashboard.php',
			method: 'GET',
			dataType: 'json',
			success: function(d) {

				prodToday.text(d[0]);
				prodMonth.text(d[1]);

				testedToday.text(d[2]);
				testedMonth.text(d[3]);

				finishedToday.text(d[4]);
				finishedMonth.text(d[5]);

				packagedToday.text(d[6]);
				packagedMonth.text(d[7]);

				hipotToday.text(d[8]);
				hipotFailToday.text(d[9]);
				hipotMonth.text(d[10]);
				hipotFailMonth.text(d[11]);

				lowPowerToday.text(d[12]);
				lowPowerFailToday.text(d[13]);
				lowPowerMonth.text(d[14]);
				lowPowerFailMonth.text(d[15]);

				burnInToday.text(d[16]);
				burnInFailToday.text(d[17]);
				burnInMonth.text(d[18]);
				burnInFailMonth.text(d[19]);

				fctToday.text(d[20]);
				fctFailToday.text(d[21]);
				fctMonth.text(d[22]);
				fctFailMonth.text(d[23]);
				
			}
		}).fail(function ( error ) {
			components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
			components.form.reset(form);
			console.log(error);
		});
	}

	dashboard();

	function dashboardInterval() {
		setTimeout(dashboardInterval, 3600000);
	}

	setTimeout(dashboardInterval, 3600000);

});