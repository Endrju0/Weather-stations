// Returns deep clone of chart config object
window.generateChart = function(data, label, primaryColor, secondaryColor, bgColor) {
    var config = {
        type: 'line',
        data: {
            labels: timestamp,
            datasets: [{
                backgroundColor: primaryColor,
                borderColor: secondaryColor,
                pointBackgroundColor: 'rgba(0, 0, 0, 0)',
                pointBorderColor: 'rgba(0, 0, 0, 0)',
                data: data
            }]
        },

        // Configuration options go here
        options: {
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) { 
                        return tooltipItem.yLabel + ' ' + label;
                    },
                    labelColor: function(tooltipItem, data) {
                        return {
                            borderColor: secondaryColor,
                            backgroundColor: primaryColor
                        };
                    }
                },
            },
            scales: {
                xAxes: [{
                    gridLines: { color: bgColor },
                    ticks: { fontColor: bgColor }
                    }],
                yAxes: [{
                    ticks: {
                        userCallback: function(item) {
                            return item + ' ' + label;
                        },
                        fontColor: bgColor
                    },
                    gridLines: { color: bgColor }
                }]
            },
        }
    };

    return jQuery.extend(true, {}, config);
}