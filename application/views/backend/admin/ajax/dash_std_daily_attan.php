<div class="col-lg-12 col-md-12 col-sm-12">
<?php 
    if($class_id==0){
        $class_check="";	
    }else{
        $class_check=" and cs.section_id=$class_id ";	
    }


    $qur_c = $this->db->query( "select  count(s.student_id) as total_count , a.status from " . get_school_db() .".attendance a 
    inner join " . get_school_db() . ".student s on s.student_id=a.student_id
    inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
    inner join ".get_school_db().".class c on c.class_id=cs.class_id
    where a.school_id=$d_school_id $class_check and a.date='$date_val' group by  a.status" )->result_array();

    if(count($qur_c) > 0)
    {
    	
    
    $present_ary = array();
    $ary_val_name=array(1=>get_phrase('present'),2=>get_phrase('absent'),3=>get_phrase('leave'));
    
    $count_ary=array(1=>0,2=>0,3=>0);
    foreach ( $qur_c as  $qur_row) {	
        $count_ary[$qur_row['status']] = $qur_row['total_count'];
    }
    
    $qur_c = $this->db->query( "select  a.status , s.name ,s.student_id from " . get_school_db() .".attendance a 
    inner join " . get_school_db() . ".student s on s.student_id=a.student_id
    inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
    inner join ".get_school_db().".class c on c.class_id=cs.class_id
    where a.school_id=$d_school_id $class_check and a.date='$date_val' group by  s.student_id   " )->result_array();
    
    $present_ary=array();
    foreach ($qur_c as  $qur_row) {	
        $present_ary[$qur_row['status']][$qur_row['student_id']]=$qur_row['name'];
    }
    ?>
    <div id="staff_name_vv" style=""></div>
</div>
				
<script>
// Create the chart
Highcharts.chart('staff_name_vv', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '<?php
        if($date_val==""){
			echo date("d-M-Y");
		}else{
		echo date("d-M-Y",strtotime($date_val)) ;	
		}
        ?>'
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
        name: "<?php echo get_phrase('student');?>",
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
            foreach($present_ary[$key] as $key=>$value)
            {?>
                ['<?php echo $value; ?>', 1],
            
            <?php } ?>
            ]
        }, 
        
        <?php } ?>
        ]
    }
});
</script>
    <?php
    }
    else
    {
    	echo '<img src="'.base_url().'/assets/images/blue-screen.png" class="chart_404_img">';
	    echo "<br>";
	    echo "<b>".get_phrase("no_record_found")."</b>";
    }