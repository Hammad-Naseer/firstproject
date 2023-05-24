<div class="col-lg-12 col-md-12 col-sm-12">
<?php
        $month = date( 'm');
		$year = date( 'Y');
		$qur_c = $this->db->query( "select  count(s.staff_id) as total_count , a.date from " . get_school_db() . ".attendance_staff a 
		inner join " . get_school_db() . ".staff s on s.staff_id=a.staff_id where  a.status=1 and a.school_id=$d_school_id and month(date)=$month and year(date)=$year group by date" )->result_array();
		$present_ary = array();
		foreach ($qur_c as $qur_row ){
			$present_ary[ $qur_row[ 'date' ] ] = $qur_row[ 'total_count' ];
		}

		$month = date( 'm');
		$year = date( 'Y');
		$qur_a = $this->db->query( "select  count(s.staff_id) as total_count , a.date from " . get_school_db() . ".attendance_staff a inner join " . get_school_db() . ".staff s on s.staff_id=a.staff_id where  a.status=2 and a.school_id=$d_school_id and month(date)=$month and year(date)=$year group by date" )->result_array();
		$absent_ary = array();
		foreach ( $qur_a as $qur_r ) {
			$absent_ary[ $qur_r[ 'date' ] ] = $qur_r[ 'total_count' ];
		}


		$month = date( 'm');
		$year = date( 'Y');
		$qur_l = $this->db->query( "select  count(s.staff_id) as total_count , a.date from " . get_school_db() . ".attendance_staff a inner join " . get_school_db() . ".staff s on s.staff_id=a.staff_id where  a.status=3 and a.school_id=$d_school_id and month(date)=$month and year(date)=$year group by date" )->result_array();
		$leave_ary = array();
		foreach ( $qur_l as $qur_l ) {
			$leave_ary[ $qur_l[ 'date' ] ] = $qur_l[ 'total_count' ];
		}
		if(count($present_ary) > 0 || count($absent_ary) > 0 || count($leave_ary) > 0)
		{
?>
		
		<div id="attandance"></div>
</div>
				
    <script>
    	Highcharts.chart( 'attandance', {
    		chart: {
    			type: 'column'
    		},
    		title: {
    			text: ''
    		},
    		xAxis: {
    			categories: [ <?php $day=date('t');
     	
             	for($i = 1;  $i<=$day ; $i++){
             	    echo 	$com_date="'".sprintf("%02d", $i)."-".date('M-Y')."',";
             	}?> ]
    		},
    		yAxis: {
    			min: 0,
    			title: {
    				text: ''
    			}
    		},
    		tooltip: {
    			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
    			shared: true
    		},
    		plotOptions: {
    			column: {
    				stacking: 'percent'
    			}
    		},
    		series: [
    			{
    				name: "<?php echo get_phrase('leave');?>",
    				data: [ <?php 
                    for($i = 1;  $i<=$day ; $i++){
                     		
                     		$com_date=date('Y-m')."-".sprintf("%02d", $i);
                     		$leave_ary[$com_date];
                     		if($leave_ary[$com_date]==""){
                    			echo 0;
                    		}else{
                    	        echo $leave_ary[$com_date];	
                    	    }
                     	    echo ",";		
                    }
                     ?> ]
    			},
    
    			{
    				name: "<?php echo get_phrase('absent');?>",
    				data: [ <?php 
               
               
                    for($i = 1;  $i<=$day ; $i++){
                    
                        $com_date=date('Y-m')."-".sprintf("%02d", $i);
                        $absent_ary[$com_date];
                        if($absent_ary[$com_date]==""){
                            echo 0;
                        }else{
                            echo $absent_ary[$com_date];	
                        }
                        echo ",";	
                     
                    }	
    
    
    
                    ?> ]
    			},
    
    			{
    				name: "<?php echo get_phrase('present');?>",
    				data: [ <?php 
               
               
                    for($i = 1;  $i<=$day ; $i++){
                     		
                     		$com_date=date('Y-m')."-".sprintf("%02d", $i);
                     		$present_ary[$com_date];
                     		if($present_ary[$com_date]==""){
                    			echo 0;
                    		}
                    else{
                    	echo $present_ary[$com_date];	
                    	}
                     	echo ",";	
                     			
                    	 }	
                    
                    
    
                     ?> ]
    			}
    		]
    
    	} );
    </script>
    <?php
    }
    else
    {
    	echo get_phrase("no_record_found");
    }
