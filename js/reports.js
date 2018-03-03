$(function() {

	$('#start_date').datetimepicker({
	  	timepicker:false,
	  	format:'Y-m-d',
	  	minDate:'-1970/01/30',//yesterday is minimum date(for today use 0 or -1970/01/01)
		maxDate:'0'//today is maximum date calendar
	});

	$('#start_date').on('change', function () {
		var minDate = $(this).val();

		$('#end_date').datetimepicker({
		  	timepicker:false,
		  	format:'Y-m-d',
		  	minDate: minDate,//yesterday is minimum date(for today use 0 or -1970/01/01)
			maxDate: 0 //today is maximum date calendar
		}).prop('disabled', false);
	});

	// reports
	var searchType = "serial";
  	$("#report_search_form").submit(function(e){
  		e.preventDefault();
	  	searchType = "serial";
	  	$('#myData').empty();
	  	generateReport(searchType);
 	});
  
  	$("#report_range_form").submit(function(e){
  		e.preventDefault();
	 	searchType = "datereport";
	  	$('#myData').empty();
	  	generateReport(searchType);
  	});
  	$("#report_oneday").click(function(){
	  	searchType = "oneday";
	  	$('#myData').empty();
	  	generateReport(searchType);
  	});	  
  	$("#report_oneweek").click(function(){
	  	searchType = "oneweek";
	  	$('#myData').empty();
	  	generateReport(searchType);
  	});
  	$("#report_halfmonth").click(function(){
	  	searchType = "halfmonth";
	  	$('#myData').empty();
	  	generateReport(searchType);
  	});
  	$("#report_month").click(function(){
	  	searchType = "month";
	  	$('#myData').empty();
	  	generateReport(searchType);
  	});


  	$("#download_range").click(function(e){
  		//var html = $('#downloadData').html();
  		// 	var html = $('.download-table').html();
	  	// window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));		  
	  	// e.preventDefault();

	   	var dt = new Date();
        var day = dt.getDate();
        var month = dt.getMonth() + 1;
        var year = dt.getFullYear();
        var hour = dt.getHours();
        var mins = dt.getMinutes();
        var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
        //creating a temporary HTML link element (they support setting file names)
        var a = document.createElement('a');
        //getting data from our div that contains the HTML table
        var data_type = 'data:application/vnd.ms-excel';
        var table_div = document.querySelector('.download-table');
        var table_html = table_div.outerHTML.replace(/ /g, '%20');
        a.href = data_type + ', ' + table_html;
        //setting the file name
        a.download = 'reports_table_' + postfix + '.xls';
        //triggering the function
        a.click();
        //just in case, prevent default behaviour
        e.preventDefault();
  	});


  	function generateReport(searchType) {		
		var data = {	
			reportType : searchType,
			sn : $('#serial').val(),
			fromDate : $('#start_date').val(),
			toDate : $('#end_date').val()
		};

		jQuery.ajax({	
			type: "POST",
			url: base_url + "modules/reports/get_products.php",
			data: data,
			success: function(d){
				var jsonD = JSON.parse(d);
				console.log(jsonD);
				if(jsonD.length === 0) {
					components.nag.show('No records found for the selected serial number or date!', 'error');
					$('.report-records').addClass('hide');
					$('.report-no-records').html('<p>No records found!</p>').removeClass('hide');
				} else {
					$('.total-records').text(jsonD.length);
					$('.report-data').empty();

					// add only once the heading row
					var headingrow = "<tr align='center'><th>#</th>";
					headingrow += "<th>Serial No.</th>";
					headingrow += "<th>SKU ID</th>";
					headingrow += "<th>Start Date</th>";
					headingrow += "<th>End Date</th>";
					headingrow += "<th>Current Status</th>";
					headingrow += "<th>Production Out</th>";
					headingrow += "<th>Production Out (OpId)</th>";
					headingrow += "<th>Bay In</th>";
					headingrow += "<th>Bay In (OpId)</th>";
					headingrow += "<th>HIPOT Start</th>";
					headingrow += "<th>HIPOT End</th>";
					headingrow += "<th>HIPOT Testers</th>";
					headingrow += "<th>HIPOT UT</th>";
					headingrow += "<th>HIPOT DT</th>";
					headingrow += "<th>HIPOT TT</th>";
					headingrow += "<th>HIPOT Fail Count</th>";
					headingrow += "<th>HIPOT Status</th>";
					headingrow += "<th>LOWPOWER Start</th>";
					headingrow += "<th>LOWPOWER End</th>";
					headingrow += "<th>LOWPOWER Testers</th>";
					headingrow += "<th>LOWPOWER UT</th>";
					headingrow += "<th>LOWPOWER DT</th>";
					headingrow += "<th>LOWPOWER TT</th>";
					headingrow += "<th>LOWPOWER Fail Count</th>";
					headingrow += "<th>LOWPOWER Status</th>";
					headingrow += "<th>BURNIN Start</th>";
					headingrow += "<th>BURNIN End</th>";
					headingrow += "<th>BURNIN Testers</th>";
					headingrow += "<th>BURNIN UT</th>";
					headingrow += "<th>BURNIN DT</th>";
					headingrow += "<th>BURNIN TT</th>";
					headingrow += "<th>BURNIN Fail Count</th>";
					headingrow += "<th>BURNIN Status</th>";
					headingrow += "<th>FCT Start</th>";
					headingrow += "<th>FCT End</th>";
					headingrow += "<th>FCT Testers</th>";
					headingrow += "<th>FCT UT</th>";
					headingrow += "<th>FCT DT</th>";
					headingrow += "<th>FCT TT</th>";
					headingrow += "<th>FCT Fail Count</th>";
					headingrow += "<th>FCT Status</th>";
					headingrow += "<th>Finishing In</th>";
					headingrow += "<th>Finishing In (OpId)</th>";
					headingrow += "<th>Finishing Out</th>";
					headingrow += "<th>Finishing Out (OpId)</th>";
					headingrow += "<th>Packaging In</th>";
					headingrow += "<th>Packaging In (OpId)</th>";
					headingrow += "<th>Packaging Out</th>";
					headingrow += "<th>Packaging Out (OpId)</th>";
					headingrow += "<th>TAT</th>";
					headingrow += "</tr>";

					$('.download-table table').append(headingrow);

					$.each(jsonD, function(i, cur) {
						$('.report-data').append(dataTemplate(i+1, cur));

						$('.download-table table').append(dataTemplateTable(i+1, cur));
					});

					$('.report-item-table').find('td:contains("NA")').addClass('na');

					$('.report-records').removeClass('hide');
					$('.report-no-records').addClass('hide');
				}
			}
		});
	}

	function dataTemplate(number, stampObj) {
		// set defaults
		stampObj.timestamp.BayIn = (!stampObj.timestamp.BayIn) ? 'NA' : stampObj.timestamp.BayIn;
		stampObj.timestamp.HipotIn = (!stampObj.timestamp.HipotIn) ? 'NA' : stampObj.timestamp.HipotIn;
		stampObj.timestamp.HipotOut = (!stampObj.timestamp.HipotOut) ? 'NA' : stampObj.timestamp.HipotOut;
		stampObj.timestamp.LowPowerIn = (!stampObj.timestamp.LowPowerIn) ? 'NA' : stampObj.timestamp.LowPowerIn;
		stampObj.timestamp.LowPowerOut = (!stampObj.timestamp.LowPowerOut) ? 'NA' : stampObj.timestamp.LowPowerOut;
		stampObj.timestamp.BurnInIn = (!stampObj.timestamp.BurnInIn) ? 'NA' : stampObj.timestamp.BurnInIn;
		stampObj.timestamp.BurnInOut = (!stampObj.timestamp.BurnInOut) ? 'NA' : stampObj.timestamp.BurnInOut;
		stampObj.timestamp.FctIn = (!stampObj.timestamp.FctIn) ? 'NA' : stampObj.timestamp.FctIn;
		stampObj.timestamp.FctOut = (!stampObj.timestamp.FctOut) ? 'NA' : stampObj.timestamp.FctOut;
		stampObj.timestamp.FinishIn = (!stampObj.timestamp.FinishIn) ? 'NA' : stampObj.timestamp.FinishIn;
		stampObj.timestamp.FinishOut = (!stampObj.timestamp.FinishOut) ? 'NA' : stampObj.timestamp.FinishOut;
		stampObj.timestamp.PackIn = (!stampObj.timestamp.PackIn) ? 'NA' : stampObj.timestamp.PackIn;
		stampObj.timestamp.PackOut = (!stampObj.timestamp.PackOut) ? 'NA' : stampObj.timestamp.PackOut;

		stampObj.timestamp.ProdOutOpId = (stampObj.timestamp.ProdOutOpId === null) ? 'NA' : stampObj.timestamp.ProdOutOpId;
		stampObj.timestamp.BayInOpId = (stampObj.timestamp.BayInOpId === null) ? 'NA' : stampObj.timestamp.BayInOpId;
		stampObj.timestamp.FinishInOpId = (stampObj.timestamp.FinishInOpId === null) ? 'NA' : stampObj.timestamp.FinishInOpId;
		stampObj.timestamp.FinishOutOpId = (stampObj.timestamp.FinishOutOpId === null) ? 'NA' : stampObj.timestamp.FinishOutOpId;
		stampObj.timestamp.PackInOpId = (stampObj.timestamp.PackInOpId === null) ? 'NA' : stampObj.timestamp.PackInOpId;
		stampObj.timestamp.PackOutOpId = (stampObj.timestamp.PackOutOpId === null) ? 'NA' : stampObj.timestamp.PackOutOpId;

		var data = '<li class="report-item">';
		var tat='Test not Complete';

		data += '<div class="report-item-summary">';
		data += '<span class="report-item-cell report-item-number"><span class="cell-content"><strong class="number">'+number+'</strong></span></span>';
		data += '<span class="report-item-cell"><span class="cell-content"><small>Serial No.</small><strong class="serial-number">'+stampObj.timestamp.SerialNumber+'</strong></span></span>';
		data += '<span class="report-item-cell"><span class="cell-content"><small>Model ID</small><strong class="serial-number">'+stampObj.timestamp.ModelId+'</strong></span></span>';
		data += '<span class="report-item-cell"><span class="cell-content"><small>Start Date</small><strong class="start-date">'+stampObj.timestamp.ProdOut+'</strong></span></span>';
		data += '<span class="report-item-cell"><span class="cell-content"><small>End Date</small><strong class="end-date">'+stampObj.timestamp.PackOut+'</strong></span></span>';
		var stageName = '';		

		
		switch(stampObj.timestamp.Stage) {
			case '1': stageName = 'Sent from production!'; break;
			case '2': stageName = 'Received at bay!'; break;
			case '3': stageName = 'HIPOT test! '+stampObj.hipot.Status; break;
			case '4': stageName = 'HIPOT test completed!'; break;
			case '5': stageName = 'LOW POWER test! '+stampObj.lowpower.Status; break;
			case '6': stageName = 'LOW POWER test completed!'; break;
			case '7': stageName = 'BURN IN test! '+stampObj.burnin.Status; break;
			case '8': stageName = 'BURN IN test completed!'; break;
			case '9': stageName = 'FCt test! '+stampObj.fct.Status; break;
			case '10': stageName = 'FCT test completed!'; break;
			case '11': stageName = 'Product received at Finishing!'; break;
			case '12': stageName = 'Product sent from Finishing!'; break;
			case '13': stageName = 'Product received at Packaging!'; break;
			case '13': stageName = 'Product sent at Packaging!'; break;
			default:
			break;
		}
        
		if( stampObj.timestamp.PackOut != 'NA') {
			//tat=Date.diff(stampObj.timestamp.PackOut,stampObj.timestamp.ProdOut);
			var difftime1=new Date(stampObj.timestamp.ProdOut).getTime();
			var difftime2=new Date(stampObj.timestamp.PackOut).getTime();
			var tat=difftime2-difftime1;
			tat=convertMS(tat);
			stageName = 'Test Completed';
			data += '<span class="report-item-cell"><span class="cell-content"><small>TAT</small><strong class="end-date">'+tat.d+' days :'+tat.h+' hrs :'+tat.m+'mins :'+tat.s+' secs</strong></span></span>';
			data += '<span class="report-item-cell"><span class="cell-content"><small>Current Status</small><strong class="end-date">'+stageName+'</strong></span></span>';
		} else {
			data += '<span class="report-item-cell"><span class="cell-content"><small>TAT</small><strong class="end-date">'+tat+'</strong></span></span>';
			data += '<span class="report-item-cell"><span class="cell-content"><small>Current Status</small><strong class="end-date">'+stageName+'</strong></span></span>';
		}
		

		data += '</div>';

		if(stampObj.FailReasons) {
			var reasons = stampObj.FailReasons.split(',');
			data += '<div class="fail-reasons"><strong>Reasons for failure of this products are:</strong>';
			reasons.forEach(function(e, i) {
				if(e) { 
					data += '<span class="reason">' + e + '</span>';
				}
			});
			data += '</div>';
		}

		// test data

		// table
		data += '<div class="report-item-table"><table class="table">';
		data += '<thead><tr><th><span>Production</span></th><th><span>Bay</span></th><th><span>HIPOT</span></th><th><span>LOW POWER</span></th><th><span>BURN IN</span></th><th><span>FCT</span></th><th><span>Finishing</span></th><th><span>Packaging</span></th></tr></thead>';
		data += '<tbody>';

		data += '<tr><td><span class="cell-data">Sent:</span>'+stampObj.timestamp.ProdOut+'<span class="cell-data">Operator:</span>'+stampObj.timestamp.ProdOutOpId+'</span></td>';
		data += '<td><span class="cell-data">Received:</span>'+stampObj.timestamp.BayIn+'<span class="cell-data">Operator:</span>'+stampObj.timestamp.BayInOpId+'</td>';
		
		if (stampObj.hipot) {
			var usageTime = 0;
			if (stampObj.hipot.UsageTime) {
				var amount2 = parseFloat(stampObj.hipot.UsageTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				usageTime = hourval2 + ':' + minval2;
			}

			var downTime = 0;
			if (stampObj.hipot.DownTime) {
				var amount2 = parseFloat(stampObj.hipot.DownTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				downTime = hourval2 + ':' + minval2;
			} 

			var totalAmt = parseFloat(stampObj.hipot.DownTime) + parseFloat(stampObj.hipot.UsageTime);
			var totalHour = Math.floor(totalAmt/60);
			var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
			var totalTime = totalHour + ':' + totalMin;
			
			data += '<td><span class="cell-data">Started:</span>'+stampObj.timestamp.HipotIn+'<span class="cell-data">Ended:</span>'+stampObj.timestamp.HipotOut+'<span class="cell-data">Tester(s):</span>'+stampObj.hipot.TesterNames+'<span class="report-item-cell"><strong>UT: </strong>'+usageTime+'</span><span class="report-item-cell"><strong>DT: </strong>'+downTime+'</span><span class="report-item-cell"><strong>TT: </strong>'+totalTime+'</span><span class="report-item-cell"><button class="log-btn btn btn-sm btn-orange" data-id="'+stampObj.hipot.TestId+'">Logs</button></span></td>';
		} else {
			data += '<td><span class="cell-data">Started:</span>NA<span class="cell-data">Ended:</span>NA<span class="cell-data">Tester(s):</span>NA<span class="report-item-cell"><strong>UT: </strong>NA</span><span class="report-item-cell"><strong>DT: </strong>NA</span><span class="report-item-cell"><strong>TT: </strong>NA</span></td>';
		}

		if (stampObj.lowpower) {
			var usageTime = 0;
			if (stampObj.lowpower.UsageTime) {
				var amount2 = parseFloat(stampObj.lowpower.UsageTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				usageTime = hourval2 + ':' + minval2;
			}

			var downTime = 0;
			if (stampObj.lowpower.DownTime) {
				var amount2 = parseFloat(stampObj.lowpower.DownTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				downTime = hourval2 + ':' + minval2;
			} 

			var totalAmt = parseFloat(stampObj.lowpower.DownTime) + parseFloat(stampObj.lowpower.UsageTime);
			var totalHour = Math.floor(totalAmt/60);
			var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
			var totalTime = totalHour + ':' + totalMin;
			
			data += '<td><span class="cell-data">Started:</span>'+stampObj.timestamp.LowPowerIn+'<span class="cell-data">Ended:</span>'+stampObj.timestamp.LowPowerOut+'<span class="cell-data">Tester(s):</span>'+stampObj.lowpower.TesterNames+'<span class="report-item-cell"><strong>UT: </strong>'+usageTime+'</span><span class="report-item-cell"><strong>DT: </strong>'+downTime+'</span><span class="report-item-cell"><strong>TT: </strong>'+totalTime+'</span><span class="report-item-cell"><button class="log-btn btn btn-sm btn-orange" data-id="'+stampObj.lowpower.TestId+'">Logs</button></span></td>';
		} else {
			data += '<td><span class="cell-data">Started:</span>NA<span class="cell-data">Ended:</span>NA<span class="cell-data">Tester(s):</span>NA<span class="report-item-cell"><strong>UT: </strong>NA</span><span class="report-item-cell"><strong>DT: </strong>NA</span><span class="report-item-cell"><strong>TT: </strong>NA</span></td>';
		}

		if (stampObj.burnin) {
			var usageTime = 0;
			if (stampObj.burnin.UsageTime) {
				var amount2 = parseFloat(stampObj.burnin.UsageTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				usageTime = hourval2 + ':' + minval2;
			}

			var downTime = 0;
			if (stampObj.burnin.DownTime) {
				var amount2 = parseFloat(stampObj.burnin.DownTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				downTime = hourval2 + ':' + minval2;
			} 

			var totalAmt = parseFloat(stampObj.burnin.DownTime) + parseFloat(stampObj.burnin.UsageTime);
			var totalHour = Math.floor(totalAmt/60);
			var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
			var totalTime = totalHour + ':' + totalMin;
			
			data += '<td><span class="cell-data">Started:</span>'+stampObj.timestamp.BurnInIn+'<span class="cell-data">Ended:</span>'+stampObj.timestamp.BurnInOut+'<span class="cell-data">Tester(s):</span>'+stampObj.burnin.TesterNames+'<span class="report-item-cell"><strong>UT: </strong>'+usageTime+'</span><span class="report-item-cell"><strong>DT: </strong>'+downTime+'</span><span class="report-item-cell"><strong>TT: </strong>'+totalTime+'</span><span class="report-item-cell"><button class="log-btn btn btn-sm btn-orange" data-id="'+stampObj.burnin.TestId+'">Logs</button></span></td>';
		} else {
			data += '<td><span class="cell-data">Started:</span>NA<span class="cell-data">Ended:</span>NA<span class="cell-data">Tester(s):</span>NA<span class="report-item-cell"><strong>UT: </strong>NA</span><span class="report-item-cell"><strong>DT: </strong>NA</span><span class="report-item-cell"><strong>TT: </strong>NA</span></td>';
		}

		if (stampObj.fct) {
			var usageTime = 0;
			if (stampObj.fct.UsageTime) {
				var amount2 = parseFloat(stampObj.fct.UsageTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				usageTime = hourval2 + ':' + minval2;
			}

			var downTime = 0;
			if (stampObj.fct.DownTime) {
				var amount2 = parseFloat(stampObj.fct.DownTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				downTime = hourval2 + ':' + minval2;
			} 

			var totalAmt = parseFloat(stampObj.fct.DownTime) + parseFloat(stampObj.fct.UsageTime);
			var totalHour = Math.floor(totalAmt/60);
			var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
			var totalTime = totalHour + ':' + totalMin;
			
			data += '<td><span class="cell-data">Started:</span>'+stampObj.timestamp.FctIn+'<span class="cell-data">Ended:</span>'+stampObj.timestamp.FctOut+'<span class="cell-data">Tester(s):</span>'+stampObj.fct.TesterNames+'<span class="report-item-cell"><strong>UT: </strong>'+usageTime+'</span><span class="report-item-cell"><strong>DT: </strong>'+downTime+'</span><span class="report-item-cell"><strong>TT: </strong>'+totalTime+'</span><span class="report-item-cell"><button class="log-btn btn btn-sm btn-orange" data-id="'+stampObj.hipot.TestId+'">Logs</button></span></td>';
		} else {
			data += '<td><span class="cell-data">Started:</span>NA<span class="cell-data">Ended:</span>NA<span class="cell-data">Tester(s):</span>NA<span class="report-item-cell"><strong>UT: </strong>NA</span><span class="report-item-cell"><strong>DT: </strong>NA</span><span class="report-item-cell"><strong>TT: </strong>NA</span></td>';
		}

		data += '<td><span class="cell-data">Received:</span>'+stampObj.timestamp.FinishIn+'<span class="cell-data">Operator:</span>'+stampObj.timestamp.FinishInOpId+'<span class="cell-data">Sent:</span>'+stampObj.timestamp.FinishOut+'<span class="cell-data">Operator:</span>'+stampObj.timestamp.FinishOutOpId+'</td>';
		data += '<td><span class="cell-data">Received:</span>'+stampObj.timestamp.PackIn+'<span class="cell-data">Operator:</span>'+stampObj.timestamp.PackInOpId+'<span class="cell-data">Sent:</span>'+stampObj.timestamp.PackOut+'<span class="cell-data">Operator:</span>'+stampObj.timestamp.PackOutOpId+'</td>';
		
		data += '</tbody></table></div></li>';

		return data;
	}

	function dataTemplateTable(number, stampObj) {

		// set defaults
		stampObj.BayIn = (!stampObj.BayIn) ? 'NA' : stampObj.BayIn;
		stampObj.HipotIn = (!stampObj.HipotIn) ? 'NA' : stampObj.HipotIn;
		stampObj.HipotOut = (!stampObj.HipotOut) ? 'NA' : stampObj.HipotOut;
		stampObj.LowPowerIn = (!stampObj.LowPowerIn) ? 'NA' : stampObj.LowPowerIn;
		stampObj.LowPowerOut = (!stampObj.LowPowerOut) ? 'NA' : stampObj.LowPowerOut;
		stampObj.BurnInIn = (!stampObj.BurnInIn) ? 'NA' : stampObj.BurnInIn;
		stampObj.BurnInOut = (!stampObj.BurnInOut) ? 'NA' : stampObj.BurnInOut;
		stampObj.FctIn = (!stampObj.FctIn) ? 'NA' : stampObj.FctIn;
		stampObj.FctOut = (!stampObj.FctOut) ? 'NA' : stampObj.FctOut;
		stampObj.FinishIn = (!stampObj.FinishIn) ? 'NA' : stampObj.FinishIn;
		stampObj.FinishOut = (!stampObj.FinishOut) ? 'NA' : stampObj.FinishOut;
		stampObj.PackIn = (!stampObj.PackIn) ? 'NA' : stampObj.PackIn;
		stampObj.PackOut = (!stampObj.PackOut) ? 'NA' : stampObj.PackOut;

		stampObj.ProdOutOpId = (!stampObj.ProdOutOpId) ? 'NA' : stampObj.ProdOutOpId;
		stampObj.BayInOpId = (!stampObj.BayInOpId) ? 'NA' : stampObj.BayInOpId;
		stampObj.FinishInOpId = (!stampObj.FinishInOpId) ? 'NA' : stampObj.FinishInOpId;
		stampObj.FinishOutOpId = (!stampObj.FinishOutOpId) ? 'NA' : stampObj.FinishOutOpId;
		stampObj.PackInOpId = (!stampObj.PackInOpId) ? 'NA' : stampObj.PackInOpId;
		stampObj.PackOutOpId = (!stampObj.PackOutOpId) ? 'NA' : stampObj.PackOutOpId;

		var data_table = '';
        
		data_table += "<tr align='center'>";
		data_table += "<td>" + number + "</td>";
		data_table += "<td>" + stampObj.timestamp.SerialNumber + "</td>";
		data_table += "<td>" + stampObj.timestamp.ModelId + "</td>";
		data_table += "<td>" + stampObj.timestamp.ProdOut + "</td>";
		data_table += "<td>" + stampObj.timestamp.PackOut + "</td>";
		
	
		

		var stageName = '';
		switch(stampObj.timestamp.Stage) {
			case '1': stageName = 'Sent from production!'; break;
			case '2': stageName = 'Received at bay!'; break;
			case '3': stageName = 'HIPOT test! '+stampObj.hipot.Status; break;
			case '4': stageName = 'HIPOT test completed!'; break;
			case '5': stageName = 'LOW POWER test! '+stampObj.lowpower.Status; break;
			case '6': stageName = 'LOW POWER test completed!'; break;
			case '7': stageName = 'BURN IN test! '+stampObj.burnin.Status; break;
			case '8': stageName = 'BURN IN test completed!'; break;
			case '9': stageName = 'FCt test! '+stampObj.fct.Status; break;
			case '10': stageName = 'FCT test completed!'; break;
			case '11': stageName = 'Product received at Finishing!'; break;
			case '12': stageName = 'Product sent from Finishing!'; break;
			case '13': stageName = 'Product received at Packaging!'; break;
			case '13': stageName = 'Product sent at Packaging!'; break;
		}
		if( stampObj.timestamp.PackOut != 'NA') {
			stageName = 'Test Completed';
			data_table += "<td>" + stageName + "</td>";			
		} else {
			 data_table += "<td>" + stageName + "</td>";
			 
		}
		
		data_table += "<td>" + stampObj.timestamp.ProdOut + "</td>";
		data_table += "<td>" + stampObj.timestamp.ProdOutOpId + "</td>";
		data_table += "<td>" + stampObj.timestamp.BayIn + "</td>";
		data_table += "<td>" + stampObj.timestamp.BayInOpId + "</td>";

		if (stampObj.hipot) {
			var usageTime = 0;
			if (stampObj.hipot.UsageTime) {
				var amount2 = parseFloat(stampObj.hipot.UsageTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				usageTime = hourval2 + ':' + minval2;
			}

			var downTime = 0;
			if (stampObj.hipot.DownTime) {
				var amount2 = parseFloat(stampObj.hipot.DownTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				downTime = hourval2 + ':' + minval2;
			} 

			var totalAmt = parseFloat(stampObj.hipot.DownTime) + parseFloat(stampObj.hipot.UsageTime);
			var totalHour = Math.floor(totalAmt/60);
			var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
			var totalTime = totalHour + ':' + totalMin;
		
			data_table += "<td>" + stampObj.hipot.StartTest + "</td>";
			data_table += "<td>" + stampObj.hipot.EndTest + "</td>";
			data_table += "<td>" + stampObj.hipot.TesterNames + "</td>";
			data_table += "<td>" + usageTime + "</td>";
			data_table += "<td>" + downTime + "</td>";
			data_table += "<td>" + totalTime + "</td>";
			data_table += "<td>" + stampObj.hipot.FailCount + "</td>";
			data_table += "<td>" + stampObj.hipot.Status + "</td>";
		} else {
			data_table += "<td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td>";
		}

		if (stampObj.lowpower) {
			var usageTime = 0;
			if (stampObj.lowpower.UsageTime) {
				var amount2 = parseFloat(stampObj.lowpower.UsageTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				usageTime = hourval2 + ':' + minval2;
			}

			var downTime = 0;
			if (stampObj.lowpower.DownTime) {
				var amount2 = parseFloat(stampObj.lowpower.DownTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				downTime = hourval2 + ':' + minval2;
			} 

			var totalAmt = parseFloat(stampObj.lowpower.DownTime) + parseFloat(stampObj.lowpower.UsageTime);
			var totalHour = Math.floor(totalAmt/60);
			var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
			var totalTime = totalHour + ':' + totalMin;
		
			data_table += "<td>" + stampObj.lowpower.StartTest + "</td>";
			data_table += "<td>" + stampObj.lowpower.EndTest + "</td>";
			data_table += "<td>" + stampObj.lowpower.TesterNames + "</td>";
			data_table += "<td>" + usageTime + "</td>";
			data_table += "<td>" + downTime + "</td>";
			data_table += "<td>" + totalTime + "</td>";
			data_table += "<td>" + stampObj.lowpower.FailCount + "</td>";
			data_table += "<td>" + stampObj.lowpower.Status + "</td>";
		} else {
			data_table += "<td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td>";
		}

		if (stampObj.burnin) {
			var usageTime = 0;
			if (stampObj.burnin.UsageTime) {
				var amount2 = parseFloat(stampObj.burnin.UsageTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				usageTime = hourval2 + ':' + minval2;
			}

			var downTime = 0;
			if (stampObj.burnin.DownTime) {
				var amount2 = parseFloat(stampObj.burnin.DownTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				downTime = hourval2 + ':' + minval2;
			} 

			var totalAmt = parseFloat(stampObj.burnin.DownTime) + parseFloat(stampObj.burnin.UsageTime);
			var totalHour = Math.floor(totalAmt/60);
			var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
			var totalTime = totalHour + ':' + totalMin;
		
			data_table += "<td>" + stampObj.burnin.StartTest + "</td>";
			data_table += "<td>" + stampObj.burnin.EndTest + "</td>";
			data_table += "<td>" + stampObj.burnin.TesterNames + "</td>";
			data_table += "<td>" + usageTime + "</td>";
			data_table += "<td>" + downTime + "</td>";
			data_table += "<td>" + totalTime + "</td>";
			data_table += "<td>" + stampObj.burnin.FailCount + "</td>";
			data_table += "<td>" + stampObj.burnin.Status + "</td>";
		} else {
			data_table += "<td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td>";
		}

		if (stampObj.fct) {
			var usageTime = 0;
			if (stampObj.fct.UsageTime) {
				var amount2 = parseFloat(stampObj.fct.UsageTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				usageTime = hourval2 + ':' + minval2;
			}

			var downTime = 0;
			if (stampObj.fct.DownTime) {
				var amount2 = parseFloat(stampObj.fct.DownTime);
				var hourval2 = Math.floor(amount2/60);
				var minval2 = Math.round((amount2/60 - hourval2) * 60);
				downTime = hourval2 + ':' + minval2;
			} 

			var totalAmt = parseFloat(stampObj.fct.DownTime) + parseFloat(stampObj.fct.UsageTime);
			var totalHour = Math.floor(totalAmt/60);
			var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
			var totalTime = totalHour + ':' + totalMin;
		
			data_table += "<td>" + stampObj.fct.StartTest + "</td>";
			data_table += "<td>" + stampObj.fct.EndTest + "</td>";
			data_table += "<td>" + stampObj.fct.TesterNames + "</td>";
			data_table += "<td>" + usageTime + "</td>";
			data_table += "<td>" + downTime + "</td>";
			data_table += "<td>" + totalTime + "</td>";
			data_table += "<td>" + stampObj.fct.FailCount + "</td>";
			data_table += "<td>" + stampObj.fct.Status + "</td>";
		} else {
			data_table += "<td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td><td>NA</td>";
		}

		data_table += "<td>" + stampObj.timestamp.FinishIn + "</td>";
		data_table += "<td>" + stampObj.timestamp.FinishInOpId + "</td>";
		data_table += "<td>" + stampObj.timestamp.FinishOut + "</td>";
		data_table += "<td>" + stampObj.timestamp.FinishOutOpId + "</td>";
		data_table += "<td>" + stampObj.timestamp.PackIn + "</td>";
		data_table += "<td>" + stampObj.timestamp.PackInOpId + "</td>";
		data_table += "<td>" + stampObj.timestamp.PackOut + "</td>";
		data_table += "<td>" + stampObj.timestamp.PackOutOpId + "</td>";
		var tat='Test not Complete';
		if( stampObj.timestamp.PackOut != 'NA') {
			var difftime1=new Date(stampObj.timestamp.ProdOut).getTime();
			var difftime2=new Date(stampObj.timestamp.PackOut).getTime();
			var tat=difftime2-difftime1;
			tat=convertMS(tat);
			stageName = 'Test Completed';
			data_table += "<td>" + tat.d + " days  : " + tat.h + "hrs  : " + tat.m + " mins  : " + tat.s + " secs</td>";
					
		} else {
			 data_table += "<td>" + 'Test not complete' + "</td>";			 
			 
		}

		data_table += "</tr>";

		return data_table;
	}

	/* logs */
	$(document).on('click', '.log-btn', function (e) {
		e.preventDefault();
		var tId = $(this).data('id');

		components.loader.on('.admin__content');

		$.ajax({
			url: base_url + 'modules/reports/logs.php',
			data: 'id='+tId,
			method: 'get',
			dataType: 'json'
		}).done(function(d) {

			var logHtml = '<ul class="logs-list">';
			$.each(d.data, function(i, data) {
				logHtml += '<li><span>'+data.CreatedAt+'</span> '+data.LogText+' <i>'+data.Author+'</i></li>';
			});	
			logHtml += '</ul>';

			console.log(logHtml);
			$('#testLogs .modal-body').html(logHtml);
			
			components.modal.openModal('#testLogs');

		}).always(function () {
			components.loader.off('.admin__content');
		}).fail(function ( error ) {
			components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
			console.log(error);
		});
	});
	
	function DateDiff(date1, date2) {
    this.days = null;
    this.hours = null;
    this.minutes = null;
    this.seconds = null;
    this.date1 = date1;
    this.date2 = date2;

    this.init();
  }

  DateDiff.prototype.init = function() {
    var data = new DateMeasure(this.date1 - this.date2);
    this.days = data.days;
    this.hours = data.hours;
    this.minutes = data.minutes;
    this.seconds = data.seconds;
  };

  function DateMeasure(ms) {
    var d, h, m, s;
    s = Math.floor(ms / 1000);
    m = Math.floor(s / 60);
    s = s % 60;
    h = Math.floor(m / 60);
    m = m % 60;
    d = Math.floor(h / 24);
    h = h % 24;
    
    this.days = d;
    this.hours = h;
    this.minutes = m;
    this.seconds = s;
  };

  Date.diff = function(date1, date2) {
    return new DateDiff(date1, date2);
  };

  Date.prototype.diff = function(date2) {
    return new DateDiff(this, date2);
  };

  function convertMS(ms) {
	  var d, h, m, s;
	  s = Math.floor(ms / 1000);
	  m = Math.floor(s / 60);
	  s = s % 60;
	  h = Math.floor(m / 60);
	  m = m % 60;
	  d = Math.floor(h / 24);
	  h = h % 24;
  return { d: d, h: h, m: m, s: s };
};
});