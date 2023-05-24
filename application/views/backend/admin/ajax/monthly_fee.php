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


if($section_id==0)
{
	
$section_id_count="";
	
}else
    {
	$section_id_count="  and cs.section_id=$section_id ";
}

		
		
	$qur_t = "select count(s.student_id) as total_count ,bmc.fee_month,bmc.fee_year  from " . get_school_db() . ".student s 
 inner join " . get_school_db() . ".student_chalan_form scf on scf.student_id=s.student_id
 inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id
 inner join " . get_school_db() . ".bulk_monthly_chalan bmc on bmc.b_m_c_id=scf.bulk_req_id
 where scf.is_bulk=2 and scf.is_processed=0 and  scf.school_id=$d_school_id and scf.status=5
 and bmc.fee_month in ($month_str) and bmc.fee_year in ($year_str)
 $section_id_count
 group by bmc.fee_month,bmc.fee_year
		";
@$qur_c = $this->db->query($qur_t)->result_array();

	$paid_ary = array();
		foreach ( $qur_c as $qur_row )
		{
			$paid_ary[sprintf("%02d", $qur_row['fee_month']).'-'.$qur_row['fee_year']] = $qur_row[ 'total_count' ];
		}
		
		?>
		<?php
		
		$qq_w="select count(s.student_id) as total_count ,bmc.fee_month,bmc.fee_year  from " . get_school_db() . ".student s 

inner join " . get_school_db() . ".student_chalan_form scf on scf.student_id=s.student_id

inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id

inner join " . get_school_db() . ".bulk_monthly_chalan bmc on bmc.b_m_c_id=scf.bulk_req_id

where scf.is_bulk=2 and scf.is_processed=0 and  scf.school_id=$d_school_id and scf.status=4

and bmc.fee_month in ($month_str) and bmc.fee_year in ($year_str)

$section_id_count

group by bmc.fee_month,bmc.fee_year
		";
		
		$qur_a = $this->db->query($qq_w)->result_array();
		$unpaid_ary = array();
		foreach ( $qur_a as $qur_r )
		{
			$unpaid_ary[sprintf("%02d", $qur_r['fee_month']).'-'.$qur_r['fee_year']] = $qur_r[ 'total_count' ];
		}

	

		?>


			<div id="attandance_class_val_num"></div>
		</div>
<?php
if((count($months) > 0) && (count($paid_ary) > 0 || count($unpaid_ary)>0))
{
?>
			
			
			<script>
		Highcharts.chart( 'attandance_class_val_num', {
			chart: {
				type: 'column'
			},
			title: {
				text: ''
			},
			xAxis: {
				categories: [ <?php	
 foreach($months as $row_months)
 {
 	$com_date=$row_months['year'].'-'.$row_months['month'];
 	echo "'".date("M-Y", strtotime($com_date))."',";	
 }
 	

           ?> ]
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
					name: "<?php echo get_phrase('paid');?>",
					data: [ <?php 
           
            
    foreach($months as $row_months){
        $com_date = $row_months['month'].'-'.$row_months['year'];
 		// $paid_ary[$com_date];
 		if(@$paid_ary[$com_date] == ""){
			echo 0;
		}else{
	        echo $paid_ary[$com_date];	
	    }
     	echo ",";	
	}

 ?> ]
				},

				{
					name: "<?php echo get_phrase('unpaid');?>",
					data: [ <?php 
           
           
					foreach($months as $row_months)
					{
						$com_date=$row_months['month'].'-'.$row_months['year'];
				// 		$unpaid_ary[$com_date];
					 	if(@$unpaid_ary[$com_date]=="")
					 		{
					 			echo 0;
							}
							else
							{
								echo $unpaid_ary[$com_date];	
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
			