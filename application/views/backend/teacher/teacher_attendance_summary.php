<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('teacher_attendance_summary'); ?>
        </h3>
    </div>
</div>

<?php
 $acadmic_year_start = $this->db->query("select start_date,end_date from ".get_school_db().".acadmic_year where 
                                         academic_year_id =".$_SESSION['academic_year_id']."  and school_id=".$_SESSION['school_id']." ")->result_array();
 $start_date=$acadmic_year_start[0]['start_date'];
 $end_date=$acadmic_year_start[0]['end_date'];   
 ?>
 
 <div class="col-lg-12 col-md-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-responsive" aria-describedby="table_export_info" data-step="1" data-position='top' data-intro="Yearly attendance summary report">
    <thead>
        <tr>
        </tr>
        <tr>
            <th style=" width: 109px;" class="td_middle"><?php echo get_phrase('month_-_year');?></th>
            <th style=" width: 109px;" class="td_middle"><?php echo get_phrase('present');?></th>
            <th style=" width: 109px;" class="td_middle"><?php echo get_phrase('absent');?></th>
            <th style=" width: 109px;" class="td_middle"><?php echo get_phrase('leave');?></th>
        </tr>
    </thead>
    <tbody>
                        
   <?php
   $array_attend=array();
   $login_detail_id = $_SESSION['login_detail_id'];
   
   $q="select a.status,count(a.status) as status_count,month(a.date) as month, YEAR(a.date) as year,monthname(a.date) as month_name FROM ".get_school_db().".attendance_staff a
   INNER JOIN  ".get_school_db().".staff staff ON staff.staff_id = a.staff_id
   WHERE 
   staff.user_login_detail_id =$login_detail_id 
   AND a.school_id=".$_SESSION['school_id']."
   group by status, month, year
   order by  month, year";
   $qur_red=$this->db->query($q)->result_array();
   $qur_array=array();
    foreach($qur_red as $row)
    {
    	$qur_array[$row['year']][$row['month_name']][$row['status']]=$row['status_count'];
    }

    $total_present=0;
    $total_absent=0;
    $total_leave=0;
        while (strtotime($start_date) <= strtotime($end_date)) 
        {
        ?>
                         	
            <tr>
            <td class="td_middle"><?php echo $month=date('F', strtotime($start_date));
            echo "&nbsp";
        	echo $year=date('Y', strtotime($start_date));
        	echo "<br>";
        	$start_date = date('d M Y', strtotime($start_date.
                '+ 1 month'));
             ?>  
             </td>
             
        	<td class="td_middle"><?php 
        	echo $present=$qur_array[$year][$month][1];
        	$total_present=$present+$total_present;    	
        	?></td>
        	<td class="td_middle"><?php 
        	echo $absent=$qur_array[$year][$month][2];
        	$total_absent=$absent+$total_absent;
        	
        	?></td>
        	<td class="td_middle"><?php 
        	
        	echo $leave=$qur_array[$year][$month][3];
        	$total_leave=$leave+$total_leave;
        	?></td>
        	</tr>
    	<?php 
    	}
        ?>
		<tr>
		<td class="td_middle"><strong><?php echo get_phrase("total");?></strong></td>
		<td class="td_middle"><?php echo $total_present;?></td>
		<td class="td_middle"><?php echo $total_absent;?></td>
		<td class="td_middle"><?php echo $total_leave;?></td>
		</tr>
   
    </tbody>
</table>
</div>
 