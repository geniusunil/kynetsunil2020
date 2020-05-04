var subs            = parseInt(document.getElementById('subscription').value);
var free    		= parseInt(document.getElementById('free').value);
var onetime 		= parseInt(document.getElementById('onetime').value);
var freetrl 		= parseInt(document.getElementById('freetrial').value);
var freem   		= parseInt(document.getElementById('freemium').value);
var totalitems  	= parseInt(document.getElementById('total').value);


var freetrlothers   = 100-freetrl;
var freemothers     = 100- freem;


		var subscription = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [subs, onetime, free
					],
					backgroundColor: [
					"#43556d",
					"#2f75ba",
					"#c2d5f3",
						
					],
					label: 'Dataset 1'
				}],
				labels: [
					'Subscription',
					'Onetime fee ' ,
					'free ',
				]
			},
			options: {
				responsive: false,
				title: {
					display: true,
					text: subs + '% of solutions offer a Subscriptipn plan'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				},
				tooltips: {
  callbacks: {
    label: function(tooltipItem, data) {
      //get the concerned dataset
      var dataset = data.datasets[tooltipItem.datasetIndex];
      //calculate the total of this data set
      var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
        return previousValue + currentValue;
      });
      //get the current items value
      var currentValue = dataset.data[tooltipItem.index];
      //calculate the precentage based on the total and current item, also this does a rough rounding to give a whole number
      var percentage = Math.floor(((currentValue/total) * 100)+0.5);

      return percentage + "%";
    }
  }
} 
			}
		};
		//////////////for second chart//////////////////
		var freetrial = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [freetrl, freetrlothers
					],
					backgroundColor: [
					"#43556d",
					"#2f75ba",	
					],
					label: 'Dataset 2'
				}],
				labels: [
					'Free Trial',
					'Other',
				]
			},
			options: {
				responsive: false,
				title: {
					display: true,
					text: freetrl + '% of solutions offer a Free Trial'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				},
				tooltips: {
  callbacks: {
    label: function(tooltipItem, data) {
      //get the concerned dataset
      var dataset = data.datasets[tooltipItem.datasetIndex];
      //calculate the total of this data set
      var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
        return previousValue + currentValue;
      });
      //get the current items value
      var currentValue = dataset.data[tooltipItem.index];
      //calculate the precentage based on the total and current item, also this does a rough rounding to give a whole number
      var percentage = Math.floor(((currentValue/total) * 100)+0.5);

      return percentage + "%";
    }
  }
} 
				
			}
			  
		
		};
		///////////////////end 2end/////////////////////
		//////////////for 3rd chart//////////////////
		var freemium = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [freem, freemothers
					],
					backgroundColor: [
					"#43556d",
					"#2f75ba",	
					],
					label: 'Dataset 3'
				}],
				labels: [
					'Freemium',
					'Others' 
				]
			},
			options: {
				responsive: false,
				title: {
					display: true,
					text: freem+'% of solutions offer a Freemium plan'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				},
				
				tooltips: {
  callbacks: {
    label: function(tooltipItem, data) {
      //get the concerned dataset
      var dataset = data.datasets[tooltipItem.datasetIndex];
      //calculate the total of this data set
      var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
        return previousValue + currentValue;
      });
      //get the current items value
      var currentValue = dataset.data[tooltipItem.index];
      //calculate the precentage based on the total and current item, also this does a rough rounding to give a whole number
      var percentage = Math.floor(((currentValue/total) * 100)+0.5);

      return percentage + "%";
    }
  }
} 
			}
			   
		};
		///////////////////end 3end/////////////////////

		window.onload = function() {
			
		/* 	var ctx = document.getElementById('chart-area').getContext('2d');
			window.myDoughnut = new Chart(ctx, subscription);
			window.myDoughnut.options.circumference = Math.PI;
				window.myDoughnut.options.rotation = -Math.PI;
			window.myDoughnut.update(); 
			var ctx1 = document.getElementById('chart-area1').getContext('2d');
			window.myDoughnut = new Chart(ctx1, freetrial);
			window.myDoughnut.options.circumference = Math.PI;
				window.myDoughnut.options.rotation = -Math.PI;
			window.myDoughnut.update();
			var ctx2 = document.getElementById('chart-area2').getContext('2d');
			window.myDoughnut = new Chart(ctx2, freemium);
			window.myDoughnut.options.circumference = Math.PI;
				window.myDoughnut.options.rotation = -Math.PI;
			window.myDoughnut.update();
			*/
			///////////scater/////////////////
			//jQuery("#canvas_bar").CanvasJSChart(scatterChartData);
			var ctx4 = document.getElementById('chart3').getContext('2d');
			var theme_price = document.getElementById('maxo').value;
			var obj = JSON.parse(theme_price);
			var arr = [];
			i = 0;
			jQuery.each(obj, function (key, data1) {
				jQuery.each(data1, function (index, data1) {
					arr[i++]  =data1.replace(/[^0-9.]/g, "");
				});
			})
 var ccount = arr.length;
 var arr = arr.filter(function(v){return v!==''});
 var a = Math.max.apply(Math, arr);
 var b = a/10;
 var divi =  Math.round(b);
	var x = 0;
	var y = divi;
	var range1 = [];
	 var y_array = [];
	for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
	 range1.push(Math.round(arr[j])); 
	}
}

  y_array[0] = range1.length;
	 if(y_array[0] != 0){
	var r1 = x+'-'+y;
	 }else{
		var r1 = ""; 
	 }
	
	 x = y;
	  y = x+divi;
	  var range2 = [];
	  for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
	  range2.push(Math.round(arr[j])); 
	}
}
  y_array[1] = range2.length;
   if(y_array[1] != 0){
	var r2 = x+'-'+y;
	 }else{
		var r2 = ""; 
	 }
	
	 x = y;
	y = x+divi;
	var range3 =[];
	for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
	 range3.push(Math.round(arr[j])); 
	}
}
  y_array[2] = range3.length;
    if(y_array[2] != 0){
	var r3 = x+'-'+y;
	 }else{
		var r3 = ""; 
	 }
	
	 x = y;
	y = x+divi;
	var range4 = [];
	for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
    range4.push(Math.round(arr[j])); 
	}
}
   y_array[3] = range4.length;
	 if(y_array[3] != 0){
	var r4 = x+'-'+y;
	 }else{
		var r4 = ""; 
	 }
	
	 x = y;
	 y = x+divi;
	var range5  = [];
	 for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
    range5.push(Math.round(arr[j])); 
	}
}
  y_array[4] = range5.length;
	 if(y_array[4] != 0){
	var r5 = x+'-'+y;
	 }else{
		var r5 = ""; 
	 }
	
	x = y;
	 y = x+divi;
	var range6  = [];
	 for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
    range6.push(Math.round(arr[j])); 
	}
}
	y_array[5] = range6.length;
	 if(y_array[5] != 0){
	var r6 = x+'-'+y;
	 }else{
		var r6 = ""; 
	 }
	
	x = y;
	 y = x+divi;
	var range7  = [];
	 for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
    range7.push(Math.round(arr[j])); 
	}
}
	y_array[6] = range7.length;
	 if(y_array[6] != 0){
	var r7 = x+'-'+y;
	 }else{
		var r7 = ""; 
	 }
	
	x = y;
	 y = x+divi;
	var range8  = [];
	 for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
    range8.push(Math.round(arr[j])); 
	}
}
  y_array[7] = range8.length;
   if(y_array[7] != 0){
	var r8 = x+'-'+y;
	 }else{
		var r8 = ""; 
	 }
	
	x = y;
	 y = x+divi;
	var range9  = [];
	 for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
    range9.push(Math.round(arr[j])); 
	}
}
	 y_array[8] = range9.length;
	 if(y_array[8] != 0){
	var r9 = x+'-'+y;
	 }else{
		var r9 = ""; 
	 }
	
	x = y;
	 y = x+divi;
	var range10 = [];
	 for(var j = 0; j < arr.length; ++j){
    if(arr[j] >= x && arr[j] <= y ){
    range10.push(Math.round(arr[j])); 
	}
}

   y_array[9] = range10.length;
    if(y_array[9] != 0){
	var r10 = x+'-'+y;
	 }else{
		var r10 = ""; 
	 }
 
	
			var scatterChart = new Chart(ctx4, {
				responsive: true,
				type: 'bar',
    data: {
      labels: [r1,r2,r3,r4,r5,r6,r7,r8,r9,r10],
      datasets: [
        {
          label: "Number of solutions",
		  fontSize: 20,
          backgroundColor: "#0b62a4",
          data: y_array
        }
      ]
    },
    options: {
		responsive:false,
    maintainAspectRatio: false,
		
      legend: { display: false,
	   },
	   scales: {
		         xAxes: [{
					 ticks: {
          fontSize: 16
        },
					 gridLines: {
                color: "rgba(0, 0, 0, 0)",
				
              },
					scaleLabel: {
								display: true,
								labelString: 'Avg Price ($)',
								fontSize: 20 
								}
								}],
					yAxes: [{
						ticks: {
                display: false
            },
						gridLines: {
                show: true
              },
					scaleLabel: {
								display: true,
								labelString: 'Number of solutions',
								fontSize: 20
								}
								}]
								},
	  
    }
				});
		};

		
		/*'use strict';

module.exports = function(Chart) {

	Chart.Doughnut = function(context, subscription) {
		subscription.type = 'doughnut';
		return new Chart(context, subscription);
	};
	Chart.Doughnut = function(context, freetrial) {
		freetrial.type = 'doughnut';
		return new Chart(context, freetrial);
	};
	Chart.Doughnut = function(context, freemium) {
		freemium.type = 'doughnut';
		return new Chart(context, freemium);
	};

};*/