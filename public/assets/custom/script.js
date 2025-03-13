$(document).ready(function () {
    var lineChartOptions = {
        chart: {
            type: 'line'
        },
        colors: ["#FF1654", "#247BA0"],
        series: [
            {
                name: 'sales',
                data: [30,40,35,50,49,60,70,91,125]
            },
            {
                name: 'sales 1',
                data: [20,31,35,66,77,34,78,77,100]
            }
        ],
        xaxis: {
            categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
        }
    }

    var lineChart = new ApexCharts(document.querySelector("#lineChart"), lineChartOptions);

    lineChart.render();

    var barChartOptions = {
        chart: {
            type: 'bar'
        },
        series: [{
            name: 'sales',
            data: [30,40,35,50,49,60,70,91,125]
        }],
        xaxis: {
            categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
        }
    }

    var barChart = new ApexCharts(document.querySelector("#barChart"), barChartOptions);

    barChart.render();

    $.ajax({
        url: 'https://10.99.0.187:8043/openapi/v1/msp/950c1327d64a1b53de3882530e979b99/customers?page=1&pageSize=1000',
        type: 'GET',
        headers: {
            'Authorization': 'Bearer AccessToken=AT-t8ITVdBrxXFXp6C6MYGdhCRYOLE2xIZ2'
        },
        success: function(res) {
            console.log(res);
        },
        error: function(err) {
            console.log('Error:', err);
        }
    });

})
