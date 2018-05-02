function dibujar(datos){
    Highcharts.setOptions({
        chart: {
            renderTo: 'chart',
            height: 450,
            borderWidth: 1,
            borderColor: 'rgba(179, 224, 232, .9)',
            plotBackgroundColor: 'rgba(255, 255, 255, .9)',
            plotBorderWidth: 0.3,
			type: 'spline',
    	},
    	plotOptions: {
	        series: {
	            marker: {
	                enabled: false,
	            },
	            lineWidth: 1,
	        },
	        xAxis:{
	        	tickInterval: 0.1,
	        },
	        yAxis:{
	        	tickInterval: 0.1,
	        }, 
		},
    });
    
    Highcharts.chart('container', {

    title: {
        text: datos['titulo']
    },

    
    xAxis: {
        title: {
            text: datos['tituloX']
        },
        min: datos['Xmin'],
        max: datos['Xmax'],
        minorTickInterval: datos['XminorTickInterval']
    },
    
    yAxis: {
        title: {
            text: datos['tituloY']
        },
        min: datos['Ymin'],
        max: datos['Ymax'],
        minorTickInterval: datos['YminorTickInterval']
        
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    
    series: datos['series'],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
}

