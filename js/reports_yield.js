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
	var searchType = "modelno";
  	$("#report_search_form").submit(function(e){
  		e.preventDefault();
	  	searchType = "modelno";
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
			model : $('#report-model').val(),
			fromDate : $('#start_date').val(),
			toDate : $('#end_date').val()
		};
		//console.log(data)


		jQuery.ajax({	
			type: "POST",
			url: base_url + "modules/reports/get_products_yield.php",
			data: data,
			success: function(d){
				var jsonD = JSON.parse(d);
				console.log(jsonD);
				if(jsonD.length === 0) {
					components.nag.show('No records found for the selected Model number or date!', 'error');
					$('.report-records').addClass('hide');
					$('.report-no-records').html('<p>No records found!</p>').removeClass('hide');
				} else {
					$('.report-table tbody').empty();
					$('.download-table tbody').empty();
					$('.report-records').removeClass('hide');
					$('.report-no-records').addClass('hide');

					var totalPass = 0;
					var totalFail = 0;
					var startDate = jsonD[0].StartTest;
					var endDate = jsonD[jsonD.length-1].StartTest;

					$.each(jsonD, function(i, cur) {
						$('.report-table tbody').append(dataTemplate(i+1,cur));
						$('.download-table tbody').append(dataTemplate(i+1,cur));

						totalPass += parseInt(cur.Pass);
						totalFail += parseInt(cur.Fail);
					});

					$('.report-table .total-passed').text(totalPass);
					$('.report-table .total-failed').text(totalFail);
					$('.report-summary .total-count').text(totalPass);
					$('.report-summary .from-date').text(startDate);
					$('.report-summary .to-date').text(endDate);
					$('.download-table tbody').append(dataTemplateSum(totalPass, totalFail));


					if(searchType === "modelno") {
						$('.report-summary .modelid').text($('#report-model').val());
						$('.report-summary .modelid').closest('.report-cell').removeClass('hidden');
						$('.report-summary .from-date, .report-summary .to-date').closest('.report-cell').addClass('hidden');
					} else {
						$('.report-summary .modelid').closest('.report-cell').addClass('hidden');
						$('.report-summary .from-date, .report-summary .to-date').closest('.report-cell').removeClass('hidden');
					}

					$('.yield-report').removeClass('hidden');
					$('.report-no-records').addClass('hidden');
				}
			}, 
				error: function(error) {
					console.log(error);
				}
		});
	}

	function dataTemplate(number, obj) {
		var data = "<tr>";
		data += '<td align="center"><span class="number">'+number+'</span></td>';
		data += '<td align="center"><span class="modelid">'+obj.ModelId+'</span></td>';
		data += '<td align="center"><span class="pass-count">'+obj.Pass+'</span></td>';
		data += '<td align="center"><span class="fail-count">'+obj.Fail+'</span></td>';
		data += '</tr>';

		return data;
	}
	function dataTemplateSum(totalPass, totalFail) {
		var data = "<tr>";
		data += '<td colspan="2" align="right">'+"Total"+'</td>';
		data += '<td align="center">'+totalPass+'</td>';
		data += '<td align="center">'+totalFail+'</td>';
		data += '</tr>';

		return data;
	}


	/* logs 
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
	*/

});