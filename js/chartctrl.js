// backgroundColor: [
//     'rgba(255, 99, 132, 0.2)',
//     'rgba(54, 162, 235, 0.2)',
//     'rgba(255, 206, 86, 0.2)',
//     'rgba(75, 192, 192, 0.2)',
//     'rgba(153, 102, 255, 0.2)',
//     'rgba(255, 159, 64, 0.2)'
// ],
// borderColor: [
//     'rgba(255, 99, 132, 1)',
//     'rgba(54, 162, 235, 1)',
//     'rgba(255, 206, 86, 1)',
//     'rgba(75, 192, 192, 1)',
//     'rgba(153, 102, 255, 1)',
//     'rgba(255, 159, 64, 1)'
// ],
var CHARTJS = CHARTJS || {};
CHARTJS._myLine;

var _chartColors = {
  red: 'rgb(255, 99, 132)',
  orange: 'rgb(255, 159, 64)',
  yellow: 'rgb(255, 205, 86)',
  green: 'rgb(75, 192, 192)',
  blue: 'rgb(54, 162, 235)',
  purple: 'rgb(153, 102, 255)',
  grey: 'rgb(201, 203, 207)'
};
const CHART_TITLE = "グラフ タイトル";
const LABEL1_TITLE = "CO2(左軸)";
const LABEL2_TITLE = "湿度(右軸)";
const LABEL3_TITLE = "気温(右軸)";

CHARTJS._ary_labels = [];
CHARTJS._ary_timedata = [];
CHARTJS._ary_co2data = [
  // 1090,
  // 1900,
  // 1700,
  // 780,
  // 650,
  // 300,
  // 150,
  // 1090
];
CHARTJS._ary_humdata = [
  // 58,
  // 62,
  // 64,
  // 45,
  // 40,
  // 30,
  // 80
];
CHARTJS._ary_tmpdata = [
  // 18.6,
  // 19.6,
  // 20.2,
  // 22.4,
  // 25.6,
  // 28.8,
  // 15
];

CHARTJS._lineChartData = {
  labels: CHARTJS._ary_labels,
  datasets: [{
    label: LABEL1_TITLE,
    borderColor: _chartColors.red,
    backgroundColor: _chartColors.red,
    fill: false,
    data: CHARTJS._ary_co2data,
    yAxisID: 'y-axis-1',
  }, {
    label: LABEL2_TITLE,
    borderColor: _chartColors.blue,
    backgroundColor: _chartColors.blue,
    fill: false,
    data: CHARTJS._ary_humdata,
    yAxisID: 'y-axis-2'
  }, {
    label: LABEL3_TITLE,
    borderColor: _chartColors.yellow,
    backgroundColor: _chartColors.yellow,
    fill: false,
    data: CHARTJS._ary_tmpdata,
    yAxisID: 'y-axis-2'
  }]
};

CHARTJS.drowChart = function(ctx){
    CHARTJS._myLine = Chart.Line(ctx, {
      data: CHARTJS._lineChartData,
      options: {
        responsive: true,
        hoverMode: 'index',
        stacked: false,
        title: {
          display: true,
          text: CHART_TITLE
        },
        scales: {
          yAxes: [{
            type: 'linear',
            display: true,
            position: 'left',
            id: 'y-axis-1'
          }, {
            type: 'linear',
            display: true,
            position: 'right',
            id: 'y-axis-2',
            gridLines: {
              // drawOnChartArea: true,
              drawOnChartArea: false,
            },
          }]
        }
      }
    });
  };
