<div class="col-lg-12 col-md-12 col-sm-12">
<?php 
$qur_c = $this->db->query( "select  count(s.staff_id) as total_count , a.status from " . get_school_db() .
 ".attendance_staff a 

inner join " . get_school_db() . ".staff s on s.staff_id=a.staff_id

where a.school_id=$d_school_id and date=date(now()) group by  a.status" )->result_array();
$present_ary = array();
$ary_val_name=array(1=>get_phrase('present'),2=>get_phrase('absent'),3=>get_phrase('leave'));

$count_ary=array(1=>0,2=>0,3=>0);
foreach ( $qur_c as  $qur_row) {	
$count_ary[$qur_row['status']] = $qur_row['total_count'];
}



$qur_c = $this->db->query( "select  a.status , s.name ,s.staff_id from " . get_school_db() .
 ".attendance_staff a 

inner join " . get_school_db() . ".staff s on s.staff_id=a.staff_id

where a.school_id=$d_school_id and date=date(now()) group by  s.staff_id" )->result_array();



$present_ary=array();




foreach ($qur_c as  $qur_row) {	
$present_ary[$qur_row['status']][$qur_row['staff_id']]=$qur_row['name'];

}



?>
<div id="branch_staff_daily_chart_home" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div>
				</div>
				
<script>
// Create the chart
Highcharts.chart('branch_staff_daily_chart_home', {
    chart: {
        type: 'pie'
    },
    
    
    
    
    title: {
        text: '<?php echo date("d-M-Y") ?>'
    },
    
    
    
    
    
    subtitle: {
        text: ''
    },
    
    
    
    
    
    
    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
    },
    series: [{
        name: '<?php echo get_phrase("staff");?>',
        colorByPoint: true,
        data: [
        
        <?php foreach($count_ary as $key=>$value){ ?>
        
        {
            name: '<?php echo $ary_val_name[$key];  ?>',
            y: <?php echo $value;  ?>,
            drilldown: '<?php echo $ary_val_name[$key];  ?>'
        }, 
        
        <?php } ?>

]
    
    
    
    }],
    
    
    drilldown: {
        series: [
        
      <?php 
      foreach($ary_val_name as $key=>$value){
	  	
	  
      
      
      ?>  
        
        
        {
            name: '<?php echo  $value; ?>',
            id: '<?php echo  $value; ?>',
            data: [
            <?php
            if (is_array($present_ary[$key]) || is_object($present_ary[$key])){
                foreach($present_ary[$key] as $key=>$value)
                {?>
                    ['<?php echo $value; ?>', 1],
                  
                
                <?php   
                }
            }?>
            
            
            
            ]
        }, 
        
        <?php } ?>
        
        
        
        
        
    

]
    }
});
</script>