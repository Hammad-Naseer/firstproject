<div class="col-lg-12 col-md-12 col-sm-12">
<?php
    $adam=$this->db->query("select * from " . get_school_db() . ".acadmic_year where status=2 and is_closed=0 LIMIT 1")->result_array();
    
    if(count($adam)>0){
    
        $start_date=$adam[0]['start_date'];
        $end_date=$adam[0]['end_date'];
        $months = array();
        
    	while (strtotime($start_date) <= strtotime($end_date)) {
    	     $months[] = array(
    	    'year' => date('Y', strtotime($start_date)), 
    	    'month' => date('m', strtotime($start_date)), 
    	    );
    	   
    	    $start_date = date('d M Y', strtotime($start_date.
    	        '+ 1 month'));
    	}
        
        $month_str="";
        $year_str_r=array();
        $year_str="";
        foreach($months as $key=>$m_row){
        	$coma=",";
        	if((count($months)-1)==$key){
        		$coma="";
        	}
        	$month_str.=$m_row['month'].$coma;
        	$year_str_r[]=$m_row['year'];
        }
        
        $ary_uni=array_unique($year_str_r);
        $year_str=implode(',',$ary_uni);
    }

    $qur_t="select sum(scf.received_amount) as total_count, scf.fee_month_year,form_type, s_c_f_month as fee_month, s_c_f_year as fee_year from " . get_school_db() . ".student_chalan_form scf
    inner join " . get_school_db() . ".student s on scf.student_id=s.student_id 
    where scf.school_id=$d_school_id 
    and s_c_f_month in ($month_str) and s_c_f_year in ($year_str)
    and scf.status=5  
    group by fee_month,fee_year";		
		
		
    $qur_unpay="select sum(scf.actual_amount) as total_count, scf.fee_month_year,form_type, s_c_f_month as fee_month, s_c_f_year as fee_year from " . get_school_db() . ".student_chalan_form scf
    inner join " . get_school_db() . ".student s on scf.student_id=s.student_id
    where scf.school_id=$d_school_id 
    and s_c_f_month in ($month_str) and s_c_f_year in ($year_str)
    and scf.status=4 $section_id_count
    group by fee_month,fee_year
    ";		
	
    $qur_c = $this->db->query($qur_t)->result_array();
    $paid_ary = array();
    foreach ( $qur_c as $qur_row ) {
        $paid_ary[sprintf("%02d", $qur_row['fee_month']).'-'.$qur_row['fee_year']] = $qur_row[ 'total_count' ];
    }
    
    $qur_c_un = $this->db->query($qur_unpay)->result_array();
    $unpaid_ary = array();
    foreach ( $qur_c_un as $qur_row_unpay ) {
        $unpaid_ary[sprintf("%02d", $qur_row_unpay['fee_month']).'-'.$qur_row_unpay['fee_year']] = $qur_row_unpay[ 'total_count' ];
    }

	?>
    <div id="branch_total_rev" ></div>
</div>

<script>

Highcharts.chart('branch_total_rev', {
   
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [<?php 
        
        foreach($months as $row_months){
             $com_date=$row_months['year'].'-'.$row_months['month'];
             echo "'".date("M-Y", strtotime($com_date))."',";
        }
 	

           ?> ],
        tickmarkPlacement: 'on',
        title: {
            enabled: false
        }
    },
    yAxis: {
        title: {
            text: '<?php echo get_phrase("rupees");?>'
        },
        labels: {
            formatter: function () {
                return this.value;
            }
        }
    },
    tooltip: {
        split: true,
        valueSuffix: ' <?php echo get_phrase("rupees");?>'
    },
    plotOptions: {
        area: {
            stacking: 'normal',
            lineColor: '#666666',
            lineWidth: 1,
            marker: {
                lineWidth: 1,
                lineColor: '#666666'
            }
        }
    },
    series: [
    
    
    {
        name: '<?php echo get_phrase("total_received");?>',
        data: [<?php 
           
           
foreach($months as $row_months){
 		
 $com_date=$row_months['month'].'-'.$row_months['year'];
 //$paid_ary[$com_date];
 		if(@$paid_ary[$com_date]==""){
			echo 0;
		}
else{
	echo $paid_ary[$com_date];	
	}
 	echo ",";	
 			
	 }	





 ?>]
    }, 


    {
        name: '<?php echo get_phrase("total_pending");?>',
        data: [<?php 
           
            
            foreach($months as $row_months){
                $com_date=$row_months['month'].'-'.$row_months['year'];
                //$unpaid_ary[$com_date];
                if(@$unpaid_ary[$com_date]==""){
                    echo 0;
                }
                else{
                echo $unpaid_ary[$com_date];	
                }
                echo ",";	
            
            }	

 ?>]
    }, 
    
    
    ]
});
	</script>
			
			