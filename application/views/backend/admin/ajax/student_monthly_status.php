	

<?php


$month = date( 'm');
		$year = date( 'Y');
		$qur_c = $this->db->query( "select  count(s.student_id) as total_count ,s.name,s.student_id  from " . get_school_db() . ".attendance a 

inner join " . get_school_db() . ".student s on s.student_id=a.student_id


inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id

where  a.status=$status_val

and cs.section_id=$section_id

 and a.school_id=$d_school_id and month(date)=$month and year(date)=$year group by s.student_id")->result_array();




		$pre_ary = array();
foreach ($qur_c as $qur_row ) {
	
	
$pre_ary[$qur_row['student_id']]['name'] = $qur_row['name'];
$pre_ary[$qur_row['student_id']]['total_count'] = $qur_row['total_count'];

		}
		
		
		
if(count($pre_ary)>0)
{	
		
		
		
		?>
<div class="col-lg-12 col-md-12 col-sm-12">
<div id="monthly_status_detail_list" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
</div>

<script>
	Highcharts.chart('monthly_status_detail_list', {
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
        name: "<?php echo get_phrase('count');?>",
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