	

<?php

$month = date( 'm');
		$year = date( 'Y');
		$qur_c = $this->db->query( "select  count(s.staff_id) as total_count ,s.name,s.staff_id  from " . get_school_db() . ".attendance_staff a 

inner join " . get_school_db() . ".staff s on s.staff_id=a.staff_id

where  a.status=$status_val and a.school_id=$d_school_id and month(date)=$month and year(date)=$year group by s.staff_id")->result_array();

if(count($qur_c) > 0)
{?>
<div class="col-lg-12 col-md-12 col-sm-12">
<div id="monthly_status_detail" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
</div>
	
<?php
		$pre_ary = array();
foreach ($qur_c as $qur_row ) {
	
	
$pre_ary[$qur_row['staff_id']]['name'] = $qur_row['name'];
$pre_ary[$qur_row['staff_id']]['total_count'] = $qur_row['total_count'];

		}
		
		

		
		
		
		?>


<script>
	Highcharts.chart('monthly_status_detail', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<?php echo date("M-Y"); ?>'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
        format: '<b>{point.name}</b>: {point.y}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Count',
        colorByPoint: true,
        data: [
        
        
        <?php foreach($pre_ary as $key=>$value){ ?>
        {
            name: '<?php echo $value["name"]; ?>',
            y: <?php echo  $value["total_count"]; ?>
        }, 
        
<?php } ?>

]
    }]
});
	
</script>
<?php
}
else
{
	echo get_phrase("no_record_found");
}