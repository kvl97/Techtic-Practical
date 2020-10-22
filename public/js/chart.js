$(function () {
    var start = moment().subtract(29, 'days');
    var end = moment();
    var url = ADMIN_URL + 'register-chart-data';
    var user = '';
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        opens: 'left',
        ranges: {
            // 'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year': [moment().startOf('years'), moment().endOf('years')]
        }
    }, cb);
    cb(start, end);

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $.post(url, { start: start.format('MMMM D, YYYY'), end: end.format('MMMM D, YYYY') }, function (data) {
            highchartdata(data);
        });
    }
    function highchartdata(data) {
        if (data.records.length == 0) {
            result = 'Nodata';
        }
        else {
            result = data.records;
        }
        var legends = [];

        if( data.reservations !== undefined) {
            data.reservations.forEach(function(v, i) {
                console.log(v);
                legends.push({
                    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                    "fillAlphas": 0.8,
                    "labelText": "[[value]]",
                    "lineAlpha": 0.9,
                    "title": v,
                    "type": "column",
                    "fontSize":'16',
                    "color": "#333333",
                    "valueField": v
                })
            });
        }
        console.log(result);
        
        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end
            
            var chart = am4core.create("chartdiv", am4charts.XYChart);
            chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
            
            chart.data = result;
            
            chart.colors.step = 2;
            chart.padding(30, 30, 10, 30);
            chart.legend = new am4charts.Legend();
            chart.scrollbarX = false;
            
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "date";            
            categoryAxis.renderer.grid.template.disabled = true;
            
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;
            valueAxis.max = 10;
            valueAxis.title.text = "No. Of Bookings";
            valueAxis.strictMinMax = true;
            valueAxis.calculateTotals = true;
            valueAxis.renderer.minWidth = 50;
            
            
            var series1 = chart.series.push(new am4charts.ColumnSeries());
            series1.columns.template.width = am4core.percent(80);
            series1.columns.template.tooltipText =
              "{name}: {valueY}";
            series1.name = "Private Booking";
            series1.dataFields.categoryX = "date";
            series1.dataFields.valueY = "private";
            // series1.dataFields.valueYShow = "totalPercent";
            series1.dataItems.template.locations.categoryX = 0.5;
            series1.stacked = true;
            series1.tooltip.pointerOrientation = "vertical";
            
            var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
            bullet1.interactionsEnabled = false;
            // bullet1.label.text = "{valueY}";
            bullet1.label.fill = am4core.color("#ffffff");
            bullet1.locationY = 0.5;
            
            var series2 = chart.series.push(new am4charts.ColumnSeries());
            series2.columns.template.width = am4core.percent(80);
            series2.columns.template.tooltipText =
              "{name}: {valueY}";
            series2.name = "Shared Booking";
            series2.dataFields.categoryX = "date";
            series2.dataFields.valueY = "shared";
            // series2.dataFields.valueYShow = "totalPercent";
            series2.dataItems.template.locations.categoryX = 0.5;
            series2.stacked = true;
            series2.tooltip.pointerOrientation = "vertical";
            
            var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
            bullet2.interactionsEnabled = false;
            // bullet2.label.text = "{valueY}";
            bullet2.locationY = 0.5;
            bullet2.label.fill = am4core.color("#ffffff");
            
            // chart.scrollbarX = new am4core.Scrollbar();
            
            }); // end am4core.ready()

       /*  var chart = AmCharts.makeChart("chartdiv", {
            "hideCredits":true,
            "type": "serial",
            "theme": "light",
            "addClassNames": true,
            "legend": legends,
            "dataProvider": result,

            "valueAxes": [{
                "stackType": "regular",
                "axisAlpha": 0.3,
                "gridAlpha": 0
            }],
            "graphs": [
                {
                    "valueField": "shared",
                    "color": "#333333",
                    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>shared</b></span>",
                    "labelText": "Total Fare",
                    
                },
                {
                    "valueField": "private",
                    "color": "#333333",
                    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>shared</b></span>",
                    "labelText": "Total Fare",
                }
            ],
            "categoryField": "date",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "gridAlpha": 0,
                "position": "left",
                "labelRotation": 45
            },
            
            // "listeners": [{
            //     "event": "drawn",
            //     "method": addLabelBoxes
            // }]
        });
        

        chart.addListener("rendered", zoomChart);
        zoomChart();
        function zoomChart() {
            chart.zoomToIndexes(chart.dataProvider.length - 20, chart.dataProvider.length - 1);
        }
        AmCharts.checkEmptyData = function (chart) {
            console.log(chart);
            if ('Nodata' == chart.dataProvider) {
                var dataPoint = {
                    dummyValue: 0
                };
                dataPoint[chart.categoryField] = '0';
                chart.dataProvider = [dataPoint];
                chart.addLabel(0, '50%', 'The chart contains no data', 'center');
            }
            else {
                chart.clearLabels();
            }
            chart.validateData();
            chart.invalidateSize();
        chart.write('chartdiv');
            //zoomChart();
        }
        AmCharts.checkEmptyData(chart); */
    }
});
