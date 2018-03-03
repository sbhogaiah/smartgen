// People Performance ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
components.loader.on('.admin__content');

$(function () {

	var gauge = [];
	/* colors for gauge chart */
	var greenGradient = 'Gradient(#0c0:#cfc:#0c0)';
	var redGradient = 'Gradient(red:#fcc:red)';
	var blueGradient = 'Gradient(blue:#ffc:blue)';
	var grayGradient = 'Gradient(#ccc:#ffc:#ccc)';
	var grayGradient = 'Gradient(gray:#ffc:gray)';
	var yellowGradient = 'Gradient(yellow:#ffc:yellow)';
	var orangeGradient = 'Gradient(orange:#ffc:orange)';
	var whiteGradient = 'Gradient(white:#ffc:white)';


	var ppi = {

		'sel': {
			'ppiCanvas' : ["#cvs1","#cvs2","#cvs3","#cvs4","#cvs5","#cvs6","#cvs7","#cvs8"],
			//'ppiCanvas1' : '#ppiChart1',
			//'ppiCanvas2' : '#ppiChart2',
		},

		'chart1' : '',
		'chart2' : '',
/*
		'colors' : {
			'downtime' : gradientDT,
			'progress' : gradientPR,
			'remaining' : gradientREM,
			'excess' : gradientEX,
			'under' : gradientUN,
			'complete' : gradientCM,
			'idle' : gradientIdle
		},

		'colorsHover' : {
			'downtime' : gradientDTHover,
			'progress' : gradientPRHover,
			'remaining' : gradientREMHover,
			'excess' : gradientEXHover,
			'under' : gradientUN,
			'complete' : gradientCM,
			'idle' : gradientIdleHover
		},
*/
		'data1' : {
			'bays_Label' : [['Hipot','(F848)'],['Low Power','(FT1347)'],['Burn In','(FT1346)'],['FCT','(FT1346)']],
			'bays' : ['hipot 1','low power 1','burn in 1','fct 1'],
			'status' : ['idle','idle','idle','idle'],
			'downtime' : [0,0,0,0],
			'complete' : [0,0,0,0],
			'progress' : [0,0,0,0],
			'remaining' : [0,0,0,0],
			'excess' : [0,0,0,0],
			'under' : [0,0,0,0],
			'idle' : [24,24,24,24],
			'cycle' : [0,0,0,0]
		},

		'data2' : {
			'bays_Label' : [['Hipot','(F838)'],['Low Power','(FT1363)'],['Burn In','(FT1362)'],['FCT','(FT1362)']],
			'bays' : ['hipot 2','low power 2','burn in 2','fct 2'],
			'status' : ['idle','idle','idle','idle'],
			'downtime' : [0,0,0,0],
			'complete' : [0,0,0,0],
			'progress' : [0,0,0,0],
			'remaining' : [0,0,0,0],
			'excess' : [0,0,0,0],
			'under' : [0,0,0,0],
			'idle' : [24,24,24,24],
			'cycle' : [0,0,0,0]
		},

		'init' : function () {

			this.get();

			this.gaugeinit();

			this.render();


			this.showTestDetails();

		},

		'get' : function () {

			$.ajax({
				url: 'modules/charts/get_ppi.php',
				method: 'GET',
				success: function(d) {
					if (d != 'No data found in database: get_ppi.php') {
						data = JSON.parse(d);

						$.each(data, function(index, cur) {
							// for bays 0 - 4
							if (index < 4) {
								ppi.data1.status[index] = cur.Status;
								ppi.data1.cycle[index] = cur.CycleTime;
								// if not idle
								if(cur.Status != "idle") {
									ppi.data1.idle[index] = 0;
									ppi.data1.downtime[index] = parseInt(cur.DownTime) / 60;
									
									var totalTime1 = parseInt(cur.UsageTime) + parseInt(cur.DownTime);
									// calculate if excess
									if (cur.Status != 'complete') {
										if (totalTime1 > parseInt(cur.CycleTol) + parseInt(cur.CycleTime)) {
											ppi.data1.status[index] = 'excess';
											ppi.data1.remaining[index] = 0;
											ppi.data1.progress[index] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
											ppi.data1.excess[index] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
										} else {
											ppi.data1.progress[index] = parseInt(cur.UsageTime) / 60;
											ppi.data1.remaining[index] = Math.abs((parseInt(cur.CycleTime) - (parseInt(cur.UsageTime) + parseInt(cur.DownTime))) / 60);
										}
									}
									// under
									if (cur.Status == 'complete') {
										if(totalTime1 < parseInt(cur.CycleTime) - parseInt(cur.CycleTol)) {
											ppi.data1.status[index] = 'under';
											ppi.data1.excess[index] = 0;
											ppi.data1.remaining[index] = 0;
											ppi.data1.under[index] =  Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
											ppi.data1.complete[index] = parseInt(cur.UsageTime) / 60;
											ppi.data1.progress[index] = 0;
										} else if(totalTime1 > parseInt(cur.CycleTol) + parseInt(cur.CycleTime)) {
											ppi.data1.progress[index] = 0;
											ppi.data1.status[index] = 'excess';
											ppi.data1.remaining[index] = 0;
											ppi.data1.complete[index] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
											ppi.data1.excess[index] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
										} else {
											ppi.data1.progress[index] = 0;
											ppi.data1.complete[index] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime)) / 60);
										}
									}

								} else { 
									ppi.data1.idle[index] = 24;
									ppi.data1.downtime[index] = 0;
									ppi.data1.remaining[index] = 0;
									ppi.data1.progress[index] = 0;
									ppi.data1.under[index] = 0;
									ppi.data1.excess[index] = 0;
									ppi.data1.complete[index] = 0;
								}

							} else if (index >= 4 && index < 8) { // for bays 4 - 8
								ppi.data2.status[index-4] = cur.Status;
								ppi.data2.cycle[index-4] = cur.CycleTime;
								// if inprogress
								if(cur.Status != "idle") {
									ppi.data2.idle[index-4] = 0;
									ppi.data2.downtime[index-4] = parseInt(cur.DownTime) / 60;
									
									var totalTime2 = parseInt(cur.UsageTime) + parseInt(cur.DownTime);
									// calculate if excess
									if (cur.Status != 'complete') {
										if (totalTime2 > parseInt(cur.CycleTol) + parseInt(cur.CycleTime)) {
											ppi.data2.status[index-4] = 'excess';
											ppi.data2.remaining[index-4] = 0;
											ppi.data2.progress[index-4] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
											ppi.data2.excess[index-4] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
										} else {
											ppi.data2.progress[index-4] = parseInt(cur.UsageTime) / 60;
											ppi.data2.remaining[index-4] = Math.abs((parseInt(cur.CycleTime) - (parseInt(cur.UsageTime) + parseInt(cur.DownTime))) / 60);
										}
									}
									// under
									if (cur.Status == 'complete') {
										if(totalTime2 < parseInt(cur.CycleTime) - parseInt(cur.CycleTol)) {
											ppi.data2.status[index-4] = 'under';
											ppi.data2.excess[index-4] = 0;
											ppi.data2.remaining[index-4] = 0;
											ppi.data2.under[index-4] =  Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
											ppi.data2.complete[index-4] = parseInt(cur.UsageTime) / 60;
											ppi.data2.progress[index-4] = 0;
										} else if(totalTime2 > parseInt(cur.CycleTol) + parseInt(cur.CycleTime)) {
											ppi.data2.progress[index-4] = 0;
											ppi.data2.status[index-4] = 'excess';
											ppi.data2.remaining[index-4] = 0;
											ppi.data2.complete[index-4] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
											ppi.data2.excess[index-4] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
										} else {
											ppi.data2.progress[index-4] = 0;
											ppi.data2.complete[index-4] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime)) / 60);
										}
									}

								} else { 
									ppi.data2.idle[index-4] = 24;
									ppi.data2.downtime[index-4] = 0;
									ppi.data2.remaining[index-4] = 0;
									ppi.data2.progress[index-4] = 0;
									ppi.data2.under[index-4] = 0;
									ppi.data2.excess[index-4] = 0;
									ppi.data2.complete[index-4] = 0;
								}

							} 

						});
					} else {
						console.log('No data from server!');
					}
				}
			});
		},

		'gaugeinit': function() {
			var obj = this;

			var cvsId = ["cvs1","cvs2","cvs3","cvs4","cvs5","cvs6","cvs7","cvs8"];
			var label = ['Hipot (F848)','LP (FT1347)','BurnIn (FT1346)','FCT (FT1346)','Hipot (F838)','LP (FT1363)','Burn In (FT1362)','FCT (FT1362)'];
			var i = 0;
			$('.chart-cards .card__body').css('padding', 0);			       
			$('.card__body > div').width($('.card__body').innerWidth());
			$('.card__body > div').height($('.card__body').innerWidth());

			/* Draw meter chart on 8 canvas and initialize them */
			for(i = 0; i < 8 ; i++) {
				var canvas = document.getElementById(cvsId[i]);
        			RGraph.Reset(canvas);
        			var wid = $('.card__body').innerWidth();
        			//console.log(wid);
        		if (wid > 300) {
					wid  = wid - (wid % 50);
				} else {
					wid = wid - (wid % 25);
				}
				canvas.width = wid;
				canvas.height = wid;
				var text_size = (wid/100) * (wid/100);
				if (text_size > 16) {
					text_size = text_size - (wid/100);
				}
				//console.log(text_size);
				if (text_size < 5) {
					text_size = 7;
				} else if (text_size < 10) {
					text_size = 10;
				}
				
				//console.log(canvas.width,text_size);
				
				gauge[i] = new RGraph.Gauge({
			            id: cvsId[i],
			            min: 0,
			            max: 0,
			            value: 0,
			            options: {
			            	//scaleDecimals: 1,
			                titleTop: label[i],
			                titleTopSize: text_size,
			                titleTopPos: 0.25,
			                textSize:(text_size * 80)/100,		                
			                titleTopColor: 'white',
			                titleBottom: 'Hours',
			                titleBottomSize: (text_size * 80)/100,			              
			                titleBottomColor: '#ccc',
			                titleBottomPos: 0.4,
			                backgroundColor: 'black',
							backgroundGradient: true,
			                needleSize: [null, 50],
			                textColor: 'white',
			                tickmarksBigColor: 'white',
			                tickmarksMediumColor: 'white',
			                tickmarksSmallColor: 'white',
			                borderOuter: '#666',
			                borderInner: '#333',
			                colorsRanges: [[0,0,greenGradient],[0,0,redGradient], [0,24,grayGradient]],
			                textAccessible: true,
			            }
			                        
			        }).draw();
			}


		},
		'render': function() {
			var obj = this;

			var downStart = [0,0,0,0,0,0,0,0];
			var downEnd = [0,0,0,0,0,0,0,0];
			var progressStart = [0,0,0,0,0,0,0,0];
			var progressEnd = [0,0,0,0,0,0,0,0];             		
            var completeStart = [0,0,0,0,0,0,0,0];
            var completeEnd = [0,0,0,0,0,0,0,0];
            var remainingStart = [0,0,0,0,0,0,0,0];
            var remainingEnd = [0,0,0,0,0,0,0,0];
            var excessStart = [0,0,0,0,0,0,0,0]; 
            var excessEnd = [0,0,0,0,0,0,0,0];
            var underStart = [0,0,0,0,0,0,0,0];
            var underEnd = [0,0,0,0,0,0,0,0];
			var idleStart = [0,0,0,0,0,0,0,0];
			var idleEnd = [24,24,24,24,24,24,24,24];
			var bay1range = [];
			var bay2range = [];
			var baybarId = ["#barhipot1","#barlp1","#barburnin1","#barfct1","#barhipot2","#barlp2","#barburnin2","#barfct2"]

			var lastVal = 0;
			for (loopcnt = 0; loopcnt < 4; loopcnt++){
				lastVal = 0;
					/* Down Time */
					if (ppi.data1.downtime[loopcnt] != 0) {
						downStart[loopcnt] = 0;
						downEnd[loopcnt] = ppi.data1.downtime[loopcnt];
						lastVal = downEnd[loopcnt];
					} 
			
					/* Progress */
					progressStart[loopcnt] = lastVal;					
					if (ppi.data1.progress[loopcnt] == 0){
						/* Set both start and End to same value */						
						progressEnd[loopcnt] = lastVal;
					} else {
						progressEnd[loopcnt] = lastVal + ppi.data1.progress[loopcnt];
						lastVal += ppi.data1.progress[loopcnt];
					}
					/*complete */
					completeStart[loopcnt] = lastVal;
					if (ppi.data1.complete[loopcnt] == 0) {						
						completeEnd[loopcnt] = lastVal;
					} else {						
						completeEnd[loopcnt] = lastVal + ppi.data1.complete[loopcnt];
						lastVal += ppi.data1.complete[loopcnt];
					}
					/*remaining */
					remainingStart[loopcnt] = lastVal;					
					if (ppi.data1.remaining[loopcnt] == 0) {
						remainingEnd[loopcnt] = lastVal;						
					}else {
						remainingEnd[loopcnt] = lastVal + ppi.data1.remaining[loopcnt];
						lastVal += ppi.data1.remaining[loopcnt];
					}
					
					/* Under */
					underStart[loopcnt] = lastVal;
					if (ppi.data1.under[loopcnt] == 0){
						underEnd[loopcnt] =  lastVal;
					} else {
						underEnd[loopcnt] = lastVal + ppi.data1.under[loopcnt];
						 lastVal += ppi.data1.under[loopcnt];
					}
					/* Excess */
					excessStart[loopcnt] = lastVal;
					if (ppi.data1.excess[loopcnt] == 0) {
						excessEnd[loopcnt] = lastVal;						
					} else {
						//if ((ppi.data1.excess[loopcnt] + lastVal) >=24){
						//	excessEnd[loopcnt] = 24;
						//	lastVal = 24;
						//} else {
							excessEnd[loopcnt] = lastVal + ppi.data1.excess[loopcnt];
							lastVal += ppi.data1.excess[loopcnt];
						//}

					}
					/* Idle */
					idleStart[loopcnt] = lastVal;
					if (lastVal > (ppi.data1.cycle[loopcnt]/60)) {
						gauge[loopcnt].max = parseInt(lastVal + 2);

					} else { 
						gauge[loopcnt].max = (ppi.data1.cycle[loopcnt]/60);
					}
					idleEnd[loopcnt] = gauge[loopcnt].max;
					if (gauge[loopcnt].max == 0) {
						gauge[loopcnt].max = 10;
					}
					
					
					
							
					/* Now set the values and update the chart */
					 bay1range[loopcnt] = [
						[downStart[loopcnt],downEnd[loopcnt], redGradient],
					   	[progressStart[loopcnt],progressEnd[loopcnt], orangeGradient],                 		
                	 			[completeStart[loopcnt],completeEnd[loopcnt], greenGradient],
                	 			[underStart[loopcnt],underEnd[loopcnt],yellowGradient],
                	 			[remainingStart[loopcnt],remainingEnd[loopcnt],whiteGradient],
                	 			[excessStart[loopcnt],excessEnd[loopcnt],blueGradient],
				 		[idleStart[loopcnt],idleEnd[loopcnt], grayGradient]
					];
					
					gauge[loopcnt].Set({colorsRanges: bay1range[loopcnt]});
					gauge[loopcnt].colorsParsed = false;
					gauge[loopcnt].value = lastVal - ppi.data1.remaining[loopcnt];



					switch(ppi.data1.status[loopcnt]) {						

						case 'idle':
							$(baybarId[loopcnt]).addClass("progress-bar__idle")	.find('span').text('Idle');
							break;

						case 'inprogress':
							$(baybarId[loopcnt]).addClass("progress-bar__orange").find('span').text("In Progress");
							$(baybarId[loopcnt]).removeClass('progress-bar__idle');
							break;

						case 'excess':
							$(baybarId[loopcnt]).find('span').text("Excess");
							$(baybarId[loopcnt]).removeClass('progress-bar__idle').removeClass('progress-bar__orange');
							break;

						case 'fail':
							$(baybarId[loopcnt]).addClass("progress-bar__red").find('span').text("Fail");
							$(baybarId[loopcnt]).removeClass('progress-bar__idle').removeClass('progress-bar__orange');
							break;

						case 'complete':
							$(baybarId[loopcnt]).addClass("progress-bar__green").find('span').text("Completed");
							$(baybarId[loopcnt]).removeClass('progress-bar__idle').removeClass('progress-bar__orange');
							break;

						case 'under':
							$(baybarId[loopcnt]).addClass("progress-bar__yellow").find('span').text("Under");
							$(baybarId[loopcnt]).removeClass('progress-bar__green').removeClass('progress-bar__orange').removeClass('progress-bar__red');

						default:
							$(baybarId[loopcnt]).addClass("progress-bar__idle").find('span').text("Idle");													
							break;

					}

				}

				/* Now get values for bay2 */
				for (loopcnt = 4; loopcnt < 8; loopcnt++){
					lastVal = 0;
					/* Down Time */
					if (ppi.data2.downtime[loopcnt-4] != 0) {
						downStart[loopcnt] = 0;
						downEnd[loopcnt] = ppi.data2.downtime[loopcnt-4];
						lastVal = downEnd[loopcnt];
					} 
					/* Progress */
					progressStart[loopcnt] = lastVal;					
					if (ppi.data2.progress[loopcnt-4] == 0){
						/* Set both start and End to same value */						
						progressEnd[loopcnt] = lastVal;
					} else {
						progressEnd[loopcnt] = lastVal + ppi.data2.progress[loopcnt-4];
						lastVal += ppi.data2.progress[loopcnt-4];
					}
					/*complete */
					completeStart[loopcnt] = lastVal;
					if (ppi.data2.complete[loopcnt-4] == 0) {						
						completeEnd[loopcnt] = lastVal;
					} else {						
						completeEnd[loopcnt] = lastVal + ppi.data2.complete[loopcnt-4];
						lastVal += ppi.data2.complete[loopcnt-4];
					}
					/*remaining */
					remainingStart[loopcnt] = lastVal;					
					if (ppi.data2.remaining[loopcnt-4] == 0) {
						remainingEnd[loopcnt] = lastVal;						
					}else {
						remainingEnd[loopcnt] = lastVal + ppi.data2.remaining[loopcnt-4];
						lastVal += ppi.data2.remaining[loopcnt-4];
					}
					
					/* Under */
					underStart[loopcnt] = lastVal;
					if (ppi.data2.under[loopcnt-4] == 0){
						underEnd[loopcnt] =  lastVal;
					} else {
						underEnd[loopcnt] = lastVal + ppi.data2.under[loopcnt-4];
						 lastVal += ppi.data2.under[loopcnt-4];
					}
					/* Excess */
					excessStart[loopcnt] = lastVal;
					if (ppi.data2.excess[loopcnt-4] == 0) {
						excessEnd[loopcnt] = lastVal;						
					} else {
						//if ((ppi.data2.excess[loopcnt-4] + lastVal) >=24){
						//	excessEnd[loopcnt] = 24;
						//	lastVal = 24;
						//} else {
							excessEnd[loopcnt] = lastVal + ppi.data2.excess[loopcnt-4];
							lastVal += ppi.data2.excess[loopcnt-4];
						//}
		
					}
					/* Idle */

					idleStart[loopcnt] = lastVal;
					if (lastVal > (ppi.data2.cycle[loopcnt-4]/60)) {
						gauge[loopcnt].max = parseInt(lastVal + 2);

					} else { 
						gauge[loopcnt].max = (ppi.data2.cycle[loopcnt-4]/60);
					}
					
					idleEnd[loopcnt] = gauge[loopcnt].max;
					if (gauge[loopcnt].max == 0) {
						gauge[loopcnt].max = 10;
					}
			
					/* Now set the values and update the chart */
					bay2range[loopcnt] = [
						[downStart[loopcnt],downEnd[loopcnt], redGradient],
					   	[progressStart[loopcnt],progressEnd[loopcnt], orangeGradient],                 		
                	 			[completeStart[loopcnt],completeEnd[loopcnt], greenGradient],
                	 			[underStart[loopcnt],underEnd[loopcnt],yellowGradient],
                	 			[remainingStart[loopcnt],remainingEnd[loopcnt],whiteGradient],
                	 			[excessStart[loopcnt],excessEnd[loopcnt],blueGradient],
				 		[idleStart[loopcnt],idleEnd[loopcnt], grayGradient]
					];
					
					switch(ppi.data2.status[loopcnt-4]) {
						

						case 'idle':
							$(baybarId[loopcnt]).addClass("progress-bar__idle")	.find('span').text('Idle');
							break;

						case 'inprogress':
							$(baybarId[loopcnt]).addClass("progress-bar__orange").find('span').text("In Progress");
							$(baybarId[loopcnt]).removeClass('progress-bar__idle');
							break;

						case 'excess':
							$(baybarId[loopcnt]).find('span').text("Excess");
							$(baybarId[loopcnt]).removeClass('progress-bar__idle').removeClass('progress-bar__orange');
							break;

						case 'fail':
							$(baybarId[loopcnt]).addClass("progress-bar__red").find('span').text("Fail");
							$(baybarId[loopcnt]).removeClass('progress-bar__idle').removeClass('progress-bar__orange');
							break;

						case 'complete':
							$(baybarId[loopcnt]).addClass("progress-bar__green").find('span').text("Completed");
							$(baybarId[loopcnt]).removeClass('progress-bar__idle').removeClass('progress-bar__orange');
							break;

						case 'under':
							$(baybarId[loopcnt]).addClass("progress-bar__yellow").find('span').text("Under");
							$(baybarId[loopcnt]).removeClass('progress-bar__green').removeClass('progress-bar__orange').removeClass('progress-bar__red');

						default:
							$(baybarId[loopcnt]).addClass("progress-bar__idle").find('span').text("Idle");													
							break;

					}

					gauge[loopcnt].Set({colorsRanges: bay2range[loopcnt]});
					gauge[loopcnt].colorsParsed = false;
					gauge[loopcnt].value = lastVal - ppi.data2.remaining[loopcnt-4];

				}
	

				/* Now redraw the charts */
				RGraph.redraw();

		},

		'showTestDetails' : function () {
			var ppi = this;

			function testInfo1(baylabel,baystatus,evt) {	

				var activeElementBay = baylabel;
				if (baystatus < 4) {
					var status = ppi.data1.status[baystatus];
				} else {
					var status = ppi.data2.status[baystatus-4];
				}
				//console.log(status,activeElementBay );							
				if(status != 'idle') {
					// get details
					components.modal.openModal('#test-details');
					components.loader.on('#test-details .modal-contents');

					$.ajax({
						url: base_url+'modules/charts/get_test_details.php?system=' + activeElementBay,
						method: 'GET',
						success: function(d) {
							//console.log(d);
							var data = JSON.parse(d); console.log(data)
							$('#test-details .active-system').text(data.System);
							$('#test-details .active-serial').text(data.SerialNumber);
							$('#test-details .active-model').text(data.ModelId);
							$('#test-details .active-user').text(data.TesterNames);
							components.loader.off('#test-details .modal-contents');
						}
					});
				}				
			}

			function testInfo2(evt) {
				var activeElement = ppi.chart2.getElementAtEvent(evt);
				if(activeElement.length !== 0) {
					var elements = ppi.chart2.getDatasetAtEvent(evt);
					var activeElementBay = activeElement[0]._model.label;
					var status = ppi.chart2.config.data.status[activeElement[0]._index];

					if(status != 'idle') {
						// get details
						components.modal.openModal('#test-details');
						components.loader.on('#test-details .modal-contents');
						
						$.ajax({
							url: 'modules/charts/get_test_details.php?system=' + activeElementBay,
							method: 'GET',
							success: function(d) {
								var data = JSON.parse(d);
								$('#test-details .active-system').text(data.System);
								$('#test-details .active-serial').text(data.SerialNumber);
								$('#test-details .active-model').text(data.ModelId);
								$('#test-details .active-user').text(data.TesterNames);
								components.loader.off('#test-details .modal-contents');
							}
						});
					}
				}
			}

			//var bayindex;
			for(let bayindex = 0; bayindex < 4 ; bayindex++) {
				$(ppi.sel.ppiCanvas[bayindex]).on('click', testInfo1.bind(this,ppi.data1.bays_Label[bayindex],bayindex));
				$(ppi.sel.ppiCanvas[bayindex]).on('mouseenter', function() {
					var toolhour = 0;
					var toolmin = 0;
					var tooltotal = 0;
					tooltotal = parseFloat(gauge[bayindex].value) * 60;
					toolhour = parseInt(tooltotal/60);
					toolmin = parseInt(tooltotal - (toolhour * 60));

					//$(this).attr('title', "Total Time: "+ gauge[bayindex].value + Minutes);
					$(this).attr('title', "Total Time: "+ toolhour +" Hours " + toolmin + " Minutes");
					//console.log(gauge[bayindex].value, bayindex);
					components.tooltip.show(this);
				});
				
				$(ppi.sel.ppiCanvas[bayindex]).on('mouseleave', function() {	
					components.tooltip.hide(this);
				});
			}
			for(let bayindex = 4; bayindex < 8 ; bayindex++) {
				$(ppi.sel.ppiCanvas[bayindex]).on('click', testInfo1.bind(this,ppi.data2.bays_Label[bayindex - 4],bayindex));
				$(ppi.sel.ppiCanvas[bayindex]).on('mouseenter', function() {
					var toolhour = 0;
					var toolmin = 0;
					var tooltotal = 0;
					tooltotal = parseFloat(gauge[bayindex].value) * 60;
					toolhour = parseInt(tooltotal/60);
					toolmin = parseInt(tooltotal - (toolhour * 60));

					//$(this).attr('title', "Total Time: "+ gauge[bayindex].value + Minutes);
					$(this).attr('title', "Total Time: "+ toolhour +" Hours " + toolmin + " Minutes");
					components.tooltip.show(this);
				});
				
				$(ppi.sel.ppiCanvas[bayindex]).on('mouseleave', function() {	
					components.tooltip.hide(this);
				});
			}
		}

	};
	
	ppi.init(); 


	function ppiInterval() {
	
		ppi.get();
		
		setTimeout(function() {
		
			ppi.render();
			//ppi.chart1.update();
			//ppi.chart2.update();
			ppiInterval();
		},5000);
	}

	
	
	setTimeout(function () {
		components.loader.off('.admin__content');
		// $('.legend-container').html(ppi.chart1.generateLegend());
	}, 1000);
	
	ppiInterval();
	//setTimeout(ppiInterval, 5000);
});

	
