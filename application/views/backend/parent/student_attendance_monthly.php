<div class="row" style="justify-content: center;">
    <div id="student_attendance_monthly"></div>
</div>
<!--Charts for student monthely attendance -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<style>
    .highcharts-data-table table,.highcharts-figure{min-width:310px;max-width:800px;margin:1em auto}#student_attendance_monthly{height:400px}
    .highcharts-data-table table{font-family:Verdana,sans-serif;border-collapse:collapse;border:1px solid #ebebeb;margin:10px auto;text-align:center;width:100%;max-width:500px}
    .highcharts-data-table caption{padding:1em 0;font-size:1.2em;color:#555}
    .highcharts-data-table th{font-weight:600;padding:.5em}.highcharts-data-table caption,.highcharts-data-table td,.highcharts-data-table th{padding:.5em}
    .highcharts-data-table thead tr,.highcharts-data-table tr:nth-child(even){background:#f8f8f8}.highcharts-data-table tr:hover{background:#f1f7ff}
</style>

<script>
Highcharts.chart('student_attendance_monthly', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: '<?php echo ucfirst($_SESSION['student_name']); ?> Monthly Attendance'
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  accessibility: {
    point: {
      valueSuffix: '%'
    }
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
      }
    }
  },
  series: [{
    name: 'Attendance',
    colorByPoint: true,
    data: [
        
        {
          name: 'Present',
          y: <?php echo  $attendance_arr->present;?>,
          sliced: true,
          selected: true
        },
        
        {
          name: 'Absent',
          y: <?php echo  $attendance_arr->absent;?>,
          sliced: true,
          selected: true
        },
        
        {
          name: 'Leave',
          y: <?php echo  $attendance_arr->leaves;?>,
          sliced: true,
          selected: true
        },
    
    ]
  }]
});
</script>