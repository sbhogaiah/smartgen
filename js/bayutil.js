$(function () {
	components.sidebar.init();
				// datepicker
	$('#date-selector').datetimepicker({
	  	timepicker:false,
	  	format:'Y-m-d',
	  	minDate:'-1970/01/30',//yesterday is minimum date(for today use 0 or -1970/01/01)
		maxDate:'0'//today is maximum date calendar
	});

	// refresh page at midnight
	var now = new Date();
	var night = new Date(
	    now.getFullYear(),
	    now.getMonth(),
	    now.getDate() + 1, // the next day, ...
	    0, 0, 0 // ...at 00:00:00 hours
	);
	var msTillMidnight = night.getTime() - now.getTime();
	setTimeout(function () {
		window.location.reload();
	}, msTillMidnight);

	// bay utlization ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	var bayUtil = {

		'sel': {
			'bayCanvas1' : '#bayChart1',
			'bayCanvas2' : '#bayChart2',

		},

		'chart1' : '',
		'chart2' : '',


		'colors' : {
			'downtime' : 'rgba(255, 32, 0, 0.75)',
			'uptime' : '#58e52c',
			'idle' : 'rgba(232, 232, 232, 0.75)'
		},

		'data1' : {			
			'bays_Label' : [['Hipot','(F848)'],['Low Power','(FT1347)'],['Burn In','(FT1346)'],['FCT','(FT1346)']],
			'bays' : ['hipot 1','low power 1','burn in 1','fct 1'],
			'downtime' : [0,0,0,0],
			'uptime' : [0,0,0,0],
			'idle' : [24,24,24,24]
		},
		'data2' : {
			'bays_Label' : [['Hipot','(F838)'],['Low Power','(FT1363)'],['Burn In','(FT1362)'],['FCT','(FT1362)']],
			'bays' : ['hipot 2','low power 2','burn in 2','fct 2'],
			'downtime' : [0,0,0,0],
			'uptime' : [0,0,0,0],
			'idle' : [24,24,24,24]
		},

		'init' : function () {
			this.get();
			this.render();
		},

		'resetData' : function () {
			bayUtil.data1 = {
				'bays_Label' : [['Hipot','(F848)'],['Low Power','(FT1347)'],['Burn In','(FT1346)'],['FCT','(FT1346)']],
				'bays' : ['hipot 1','low power 1','burn in 1','fct 1'],
				'downtime' : [0,0,0,0],
				'uptime' : [0,0,0,0],
				'idle' : [24,24,24,24]
			};
			bayUtil.data2 = {
				'bays_Label' : [['Hipot','(F838)'],['Low Power','(FT1363)'],['Burn In','(FT1362)'],['FCT','(FT1362)']],
				'bays' : ['hipot 2','low power 2','burn in 2','fct 2'],
				'downtime' : [0,0,0,0],
				'uptime' : [0,0,0,0],
				'idle' : [24,24,24,24]
			};

		},

		'get' : function (date) {
			$.ajax({
				url: 'modules/charts/get_bayutil.php',
				data: 'date='+date,
				method: 'GET',
				success: function(d) {
					//alert(d);
					if (d != 'No data found!') {														
						jsondata = JSON.parse(d);

						console.log(jsondata)

						//data1
						$.each(jsondata, function(indexJSON, curJSON) {
							$.each(bayUtil.data1.bays, function(index, cur) {										
								if(curJSON.System == cur) {

									bayUtil.data1.downtime[index] = parseInt(curJSON.DownTime) / 60;
									bayUtil.data1.uptime[index] = parseInt(curJSON.UsageTime) / 60;
									bayUtil.data1.idle[index] = (1440 - (parseInt(curJSON.DownTime) + parseInt(curJSON.UsageTime))) / 60;
								}
							});
						});

						//data2
						$.each(jsondata, function(indexJSON, curJSON) {
							$.each(bayUtil.data2.bays, function(index, cur) {

								if(curJSON.System == cur) {		
									bayUtil.data2.downtime[index] = parseInt(curJSON.DownTime) / 60;
									bayUtil.data2.uptime[index] = parseInt(curJSON.UsageTime) / 60;
									bayUtil.data2.idle[index] = (1440 - (parseInt(curJSON.DownTime) + parseInt(curJSON.UsageTime))) / 60;
								} 
							});

						});

					} else {
						console.log('No data from server!');
					}
				}
			});
		},

		'render': function() {

			var obj = this;

			var d1 = {
			    labels: bayUtil.data1.bays_Label,
	            datasets: [{
	                label: 'Downtime',
	                backgroundColor: bayUtil.colors.downtime,
					data: bayUtil.data1.downtime,
	            },{
	                label: 'Uptime',
	                backgroundColor: bayUtil.colors.uptime,
					data: bayUtil.data1.uptime,
	            },{
	                label: 'Idle',
	                backgroundColor: bayUtil.colors.idle,
					data: bayUtil.data1.idle,
	            }]
			};

			var d2 = {
			    labels: bayUtil.data2.bays_Label,
	            datasets: [{
	                label: 'Downtime',
	                backgroundColor: bayUtil.colors.downtime,
					data: bayUtil.data2.downtime,
	            },{
	                label: 'Uptime',
	                backgroundColor: bayUtil.colors.uptime,
					data: bayUtil.data2.uptime,
	            },{
	                label: 'Idle',
	                backgroundColor: bayUtil.colors.idle,
					data: bayUtil.data2.idle,
	            }]
			};

			bayUtil.chart1 = new Chart($(bayUtil.sel.bayCanvas1), {
			    type: 'stackedPercentBar',
			    data: d1,
			    options: bayUtilChartOptions
			});

			bayUtil.chart2 = new Chart($(bayUtil.sel.bayCanvas2), {
			    type: 'stackedPercentBar',
			    data: d2,
			    options: bayUtilChartOptions
			});

			var lastChartOptions = bayUtilChartOptions;
			lastChartOptions.scales.xAxes[0].categoryPercentage = 0.9;

		}

	};
	
	bayUtil.init();

	setTimeout(function () {
		$('.legend-container').html(bayUtil.chart1.generateLegend());
	}, 1000);
	

	function bayIntervalFunc() {
		bayUtil.get('today');
		setTimeout(function () {
			bayUtil.chart1.update();
			bayUtil.chart2.update();
		}, 1000);
	}

	bayIntervalFunc();

	// interval for realtime
	var bayInterval = setInterval(bayIntervalFunc, 10000);
		
	// date extend
	function formatDate(date) {
	    var d = new Date(date),
	        month = '' + (d.getMonth() + 1),
	        day = '' + d.getDate(),
	        year = d.getFullYear();

	    if (month.length < 2) month = '0' + month;
	    if (day.length < 2) day = '0' + day;

	    return [year, month, day].join('-');
	}

	// for old date
	$('#date-selector-btn').on('click', function () {
		var dateObj = new Date();
		var today = formatDate(dateObj);
		var selectedDate = $('#date-selector').val();

		if(selectedDate) {

			components.loader.on('.admin__content');
			clearInterval(bayInterval);
			bayUtil.resetData();

			bayUtil.chart1.destroy();
			bayUtil.chart2.destroy();


			if(today != selectedDate) {

				bayUtil.get(selectedDate);
				setTimeout(function () {
					bayUtil.render();
					components.loader.off('.admin__content');
				}, 500);

			} else {
				bayUtil.get('today');
				bayUtil.render();
				bayInterval = setInterval(bayIntervalFunc, 10000);
				setTimeout(function () {
					 components.loader.off('.admin__content'); 
				}, 500);
			}
		} else {
			components.nag.show('Select date before clicking submit.', 'error');
		}
	});

});