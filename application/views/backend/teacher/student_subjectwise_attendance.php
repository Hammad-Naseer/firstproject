<?php
    $q="SELECT status,date FROM ".get_school_db().".subjectwise_attendance WHERE student_id=$stud_id AND month(date)=$date_month AND YEAR(date)=$date_year AND school_id=".$_SESSION['school_id']."";
    $qur_red=$this->db->query($q)->result_array();
        
    // Get Section Subject
    $get_std_section = get_student_section_id($stud_id);
    $subjects = get_section_subject($get_std_section);
    
    $plan=array();
    foreach($qur_red as $red){
        $plan[$red['date']]=array('status'=>$red['status']);
    }
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('subject_wise_attendance');?>
        </h3>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12">
    
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    <thead>
    <tr>
    
        <th><?php echo get_phrase('date');?></th>
        <th><?php echo get_phrase('day');?></th>
        <?php foreach($subjects as $sbj): ?>
            <th><?= $sbj['name']; ?></th> 
        <?php endforeach; ?>
    </tr>
    </thead>
   <tbody>
<?php

    $custom_css=array(1=>'current-day',2=>'holiday');      
    $current_date=date('d-M-Y');                           
 
    $d=cal_days_in_month(CAL_GREGORIAN,$date_month,$date_year);
    for($i=01; $i<=$d; $i++){
    $current = "";
    $current1="";

    $s=mktime(0,0,0,$date_month, $i, $date_year);
    $today_date= date('Y-m-d',$s);
    //to convert in days
    $dw = date( "l", strtotime($today_date));
     $d1 = date( "d-M-Y", strtotime($today_date));
    if($d1==$current_date)
    {
    	$current=$custom_css[1];
    } 
    $date1 = "$date_year-$date_month-$i"; 
    $q1="select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ";	
    $qurrr=$this->db->query($q1)->result_array();
	if(count($qurrr)>0){
		$current1=$custom_css[2];
		//echo " style='background-color:orange;'";
	}	 
 
    $date_num = date('N',strtotime($date1));
    $statuslist=array(1=>get_phrase('present'), 2=>get_phrase('absent'), 3=>get_phrase('leave'),4=>get_phrase('weekend'));
    $statuslist_css="";
    if($dw=='Saturday' or $dw=='Sunday'){
        $statuslist_css=$statuslist[4];  
    }
    if(isset($plan[$today_date]['status']))
    {
    	$statuslist_css = $statuslist[$plan[$today_date]['status']]; 
    }

        echo "<tr class=' ".$current." ".$current1."' > ";
        echo "<td style=''><span style='!important;'>$d1</span></td>";
        echo "<td style=''>$dw</td>";
        foreach($subjects as $sbj):
            $att_status = get_subject_attendance($sbj['subject_id'],$d1,$stud_id);
            $status_text = '';
            $sbj_status_css = '';
            if($att_status == '1')
            {
                $sbj_status_css = '#95e19b';
                $status_text = 'Present';
            }else if($att_status == '2')
            {
                $sbj_status_css = '#ed1c1c';
                $status_text = 'Absent';
            }else if($att_status == '3')
            {
                $sbj_status_css = '#e5cf0e';
                $status_text = 'Leave';
            }
            echo '<td style="background:'.$sbj_status_css.';color:white; " >'.$status_text.'</td>'; 
        endforeach;
      
echo "</tr>"; 
}
 
 ?>  

 </tbody>
</table>
      
</div>       
<div class="row mgt10"> 
  <div class="col-sm-12 py-4">
    
    <div class="present-legend legend-attendance pull-left"> </div>
	<div class="ml pull-left"> <?php echo get_phrase('present');?></div>
	
	<div class="absent-legend legend-attendance pull-left"> </div>
    <div class="ml pull-left"> <?php echo get_phrase('absent');?></div>
	 
    <div class="leave-legend legend-attendance pull-left"> </div>
    <div class="ml pull-left"><?php echo get_phrase('leave'); ?></div>
    
	<div class="weekend-legend legend-attendance pull-left"> </div>
	<div class="ml pull-left"> <?php echo get_phrase('weekend');?></div>

	<div class="holiday-legend legend-attendance pull-left"> </div>
	<div class="ml pull-left"> <?php echo get_phrase('holiday');?></div>
 
	<div class="today-legend legend-attendance pull-left "></div>
	<div class="ml pull-left"> <?php echo get_phrase('today');?></div>
	
  </div>   
</div>

	