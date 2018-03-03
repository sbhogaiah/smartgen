// for adding percentage value on top
Chart.defaults.stackedPercentBar = Chart.helpers.clone(Chart.defaults.bar);

var helpers = Chart.helpers;
Chart.controllers.stackedPercentBar = Chart.controllers.bar.extend({
	draw: function(ease) {
		Chart.controllers.bar.prototype.draw.apply(this, arguments);

		var ctx = this.chart.chart.ctx;
			// ctx.shadowColor = "rgba(0,0,0,0.3)";
			// ctx.shadowOffsetX = 0; 
			// ctx.shadowOffsetY = 0; 
			// ctx.shadowBlur = 5;
			ctx.font='normal 13px Segoe UI';
			ctx.textAlign = "center";
			ctx.fillStyle = "#fff";
			ctx.textAlign = 'center';
			ctx.textBaseline = 'middle';
			ctx.fillStyle = "#C6521F";
								

			this.chart.data.datasets.forEach(function (dataset) {
				for (var i = 0; i < dataset.data.length; i++) {
					var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
					if (dataset.data[i] !== 0) {
						if (dataset.data[i] != 24) {
							if(dataset.data[i] < 1 && dataset.label == "Downtime") {
								ctx.fillStyle = "#FF0B0B";
								ctx.fillText(((dataset.data[i]/24)*100).toFixed(2) + "%", model.x + 35, (model.y-8));
							} else if(dataset.data[i] < 1 && dataset.label == "Uptime") {
								ctx.fillStyle = "#46CC34";
								ctx.fillText(((dataset.data[i]/24)*100).toFixed(2) + "%", model.x - 30, (model.y-8));
							} else {
								ctx.fillStyle = "#FFFFFF";
								ctx.fillText(((dataset.data[i]/24)*100).toFixed(2) + "%", model.x, (model.y + model.base) / 2);
							}
						} else {
							ctx.fillStyle = "#FFFFFF";
							ctx.fillText( "Idle", model.x, (model.y + model.base) / 2);
						}
					}
				}
			});
	}
});

// custom icons
Chart.defaults.customIconsBar = Chart.helpers.clone(Chart.defaults.bar);

// images
var inprogressImg = new Image();
inprogressImg.src = 'img/inprogress.png';

var pauseImg = new Image();
pauseImg.src = 'img/pause.png';

var underImg = new Image();
underImg.src = 'img/under.png';

var excessImg = new Image();
excessImg.src = 'img/excess.png';

var failImg = new Image();
failImg.src = 'img/fail.png';

var idleImg = new Image();
idleImg.src = 'img/idle.png';

var helpers = Chart.helpers;
Chart.controllers.customIconsBar = Chart.controllers.bar.extend({
	draw: function(ease) {
		Chart.controllers.bar.prototype.draw.apply(this, arguments);

		var self = this;

		var datasets = this.chart.data.datasets; 

		// check if this is the last dataset in this stack
		if (!this.chart.data.datasets.some(function (dataset, index) { return (dataset.stack === self.getDataset().stack && index > self.index); })) {
			var ctx = this.chart.chart.ctx;
			ctx.save();
			ctx.textAlign = 'center';
			ctx.textBaseline = 'bottom';
			ctx.fillStyle = this.chart.options.defaultFontColor;

			// loop through all its rectangles and draw the label
			helpers.each(self.getMeta().data, function (rectangle, index) {
				var curImg = self.chart.config.data.status[index];
				var img = "";
				switch (curImg) {
					case 'inprogress':
						img = inprogressImg;
						break;
					case 'excess':
						img = excessImg;
						break;
					case 'paused':
						img = pauseImg;
						break;
					case 'under':
						img = underImg;
						break;
					case 'fail':
						img = failImg;
						break;
					case 'idle':
						img = idleImg;
						break;						
				}

				ctx.drawImage(img, rectangle._model.x-14, rectangle._model.y-32, 28, 28);
			}, this);
			ctx.restore();
		}
	}
});


// BAY UTILIZATION CHART OPTIONS
var bayUtilChartOptions = {
	title: {
		display: false
	},
	fullWidth: true,
	maintainAspectRatio: false,
	responsive: true,
	tooltips: {
        enabled: true,
        mode: 'single',
        backgroundColor: '#03A9F4',
        bodyFontSize: 14,
        titleFontSize: 12,
        titleFontColor: '#FFFFFF',
        bodyFontColor: '#fff',
        caretSize: 6,
        cornerRadius: 3,
        animationDuration: 0,
        xPadding: 10,
        yPadding: 10,
        callbacks: {
        	title: function(tooltipItems, data) {
        		return data.datasets[tooltipItems[0].datasetIndex].label;
        	},
            label: function(tooltipItem, data) { 

                var amount = parseFloat(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);
				var hourval = Math.floor(amount);
				var minval = Math.round((amount - hourval) * 60);

				return hourval + ' hrs ' + minval + ' mins';
            }
        }
    },
	scales: {
        xAxes: [{
          	stacked: true,
          	categoryPercentage: 0.8,
          	gridLines: {
          		display: false
          	},
          	scaleLabel: {
				display: true,
				labelString:"Testing Bays"
			}
        }],
        yAxes: [{
            ticks: {
            	max: 24,
				stepSize: 3
			},
            stacked: true,
			gridLines: {
				display: true,
				color: 'rgba(0,0,0,0.2)'
			},
			scaleLabel: {
				display: true,
				labelString:"Interval: Hours"
			}
        }]
    },
    hover: {
    	mode: "single"
    },
    legend: {
    	display: false,
    	position: 'bottom'
    }
};


// PEOPLE PERFORMANCE CHART OPTIONS
var ppiChartOptions = {
	title: {
		display: true
	},
	fullWidth: true,
	maintainAspectRatio: false,
	responsive: true,
	tooltips: {
        enabled: true,
        mode: 'single',
        backgroundColor: '#03A9F4',
        bodyFontSize: 14,
        titleFontSize: 12,
        titleFontColor: '#FFFFFF',
        bodyFontColor: '#fff',
        caretSize: 6,
        cornerRadius: 3,
        animationDuration: 0,
        xPadding: 10,
        yPadding: 10,
        callbacks: {
        	title: function(tooltipItems, data) {
        		return data.datasets[tooltipItems[0].datasetIndex].label;
        	},
            label: function(tooltipItem, data) { 

                var amount = parseFloat(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);
				var hourval = Math.floor(amount);
				var minval = Math.round((amount - hourval) * 60);

				return hourval + ' hrs ' + minval + ' min';
                // console.log(data)
            }
        }
    },
	scales: {
        xAxes: [{
        	ticks: {
				fontColor: 'rgba(255,255,255,.5)'
			},
          	stacked: true,
          	categoryPercentage: 0.6,
          	gridLines: {
          		display: false
          	},
          	scaleLabel: {
				display: true,
				labelString:"Testing Bays",
				fontColor: 'rgba(255,255,255,.5)'
			}
        }],
        yAxes: [{
            ticks: {
				stepSize: 2,
				fontColor: 'rgba(255,255,255,.5)'
			},
            stacked: true,
			gridLines: {
				display: true,
				color: 'rgba(255,255,255,0.2)'
			},
			scaleLabel: {
				display: true,
				labelString:"Interval: Hours",
				fontColor: 'rgba(255,255,255,.5)'
			}
        }]
    },
    hover: {
    	mode: "single"
    },
    legend: {
    	display: false,
    	position: 'bottom',
    }
};



