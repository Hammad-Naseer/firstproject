<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('attendance'); ?>
        </h3>
    </div>
</div>

    
    

    <?php
  
    $student_id=$_SESSION['student_id'];
    $month=date('n');
    $year=date('Y');
    if(isset($_POST['submit']))
    {
        $date_submitted = explode('-',$this->input->post('month'));
        $year = intval($date_submitted[1]);
        $month= intval($date_submitted[0]);//date('Y',$this->input->post('month'));
    }
    $a=month_option_list("month","form-control",$month);
    $y=date('Y');
    $b= year_option_list("year","form-control",($y-1),($y+1),$year);
    ?>

                   
    <form method="post" action="<?php echo base_url();?>parents/manage_attendance" class="form">
        
        <div class="row filterContainer">

        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <?php echo  $monthName = date('F', mktime(0, 0, 0, $month, 10));?>
                <?php echo $year;  ?>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <select name="month" class="form-control" required>
                    <?php 
                        $acadmic_year_start = $this->db->query("select start_date from ".get_school_db().".acadmic_year where academic_year_id =".$_SESSION['academic_year_id']." and school_id=".$_SESSION['school_id']." ")->result_array();
                        $selected = date('m',strtotime($monthName)).'-'.$year;
                        echo month_year_option($acadmic_year_start[0]['start_date'], date('Y-m-d'), $selected); 
                    ?>
                </select>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <input type="submit" name="submit" value="<?php echo get_phrase('show_attendance');?>" class="btn btn-primary" />
            </div>
        </div>
        
        </div>
        
    </form>
                    <?php 
$custom_css=array(1=>'current-day',2=>'holiday'); 
$date_month= $month;//date();// $_POST['month'];
$date_year=$year;//$_POST['year'];
$stud_id=$_SESSION['student_id'];//$_POST['section_id'];

/*$q="select a.status,a.date FROM ".get_school_db().".attendance a
INNER JOIN  ".get_school_db().".yearly_terms yt
ON yt.yearly_terms_id=a.yearly_terms_id
INNER JOIN  ".get_school_db().".acadmic_year ay
ON ay.academic_year_id=yt.academic_year_id
WHERE 
a.student_id=$stud_id 
AND month(a.date)=$date_month 
AND YEAR(a.date)=$date_year 
AND a.school_id=".$_SESSION['school_id']."
AND ay.academic_year_id=".$_SESSION['academic_year_id']."
";*/

$q="select a.status,a.date,attendance_timing.check_in,attendance_timing.check_out
    FROM ".get_school_db().".attendance a
    LEFT JOIN ".get_school_db()." .attendance_timing ON attendance_timing.attendance_id=a.attendance_id
    WHERE a.student_id=$stud_id 
    AND month(a.date)=$date_month 
    AND YEAR(a.date)=$date_year 
    AND a.school_id=".$_SESSION['school_id']."
";

$qur_red=$this->db->query($q)->result_array();


$plan=array();
?>
                 <div class="col-lg-12 col-md-12 ">
<?php
foreach($qur_red as $red){
    
$plan[$red['date']]=array('status'=>$red['status'],'check_in'=>$red['check_in'],'check_out'=>$red['check_out']);

}
?>
<div class="tbl-class"> 
                    <table cellpadding="0" cellspacing="0" border="0" data-step="1" data-position='top' data-intro="current month attendance report" class="table table-bordered" aria-describedby="table_export_info">
                        <thead>
                            <tr>
                            </tr>
                            <tr>
                                <th style=" width: 109px;"><?php echo get_phrase('date');?></th>
                                <th style=" width: 109px;"><?php echo get_phrase('day');?></th>
                                <th style=" width: 140px;"><?php echo get_phrase('attendance');?></th>
                                <th><?php echo get_phrase('comments');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$custom_css=array(1=>'current-day',2=>'holiday');               
$current_date=date('d-M-Y');                                   
     
$d=cal_days_in_month(CAL_GREGORIAN,$date_month,$date_year);
for($i=01; $i<=$d; $i++){
$current = ""; 
$current1 = ""; 
$s=mktime(0,0,0,$date_month, $i, $date_year);
$today_date= date('Y-m-d',$s);
//to convert in days
$dw = date( "l", strtotime($today_date));
 $d1 = convert_date($today_date);
 if($d1==$current_date)
{
  
  $current=$custom_css[1];
} 

$date1 = "$date_year-$date_month-$i";
$date_num = date('N',strtotime($date1));
$q1="select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ";    
$qurrr=$this->db->query($q1)->result_array();
    if(count($qurrr)>0){
        $current1=$custom_css[2];
       // echo " style='background-color:#fceed4;'";
            }   
$statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');
$statuslist_css="";
if($dw=='Saturday' or $dw=='Sunday'){
$statuslist_css=$statuslist[4];  

}
if(isset($plan[$today_date]['status']))
{
  $statuslist_css = $statuslist[$plan[$today_date]['status']]; 
  //$statuslist_css=$statuslist[4];
}
echo "<tr class='".$statuslist_css." ".$current." ".$current1."'";
echo ">";
//echo ">";
echo "<td style='        
   '><span style='!important;'>$d1</span> </td>";
   
   
   
   echo "<td style='        
   '>$dw</td>";
echo "<td>";


if (is_array($plan[$today_date]) || is_object($plan[$today_date])){
   // foreach($plan[$today_date] as $std )
   // {
        if($plan[$today_date]['status']==1)
        {
            echo "<span class='green'>".get_phrase('present')."</span>";
            echo '<br>';
          if(empty($plan[$today_date]['check_in']) && empty($plan[$today_date]['check_out'])){?>
           <?php echo "<strong>".get_phrase("check_in")."</strong>:"?> <?php echo'00:00:00';?>
           <?php echo'<br>';echo "<strong>".get_phrase("check_out")."</strong>:"?><?php echo'00:00:00';?>
         <?php }
        else{ ?>
          <?php echo "<strong>".get_phrase("check_in")."</strong>:".$plan[$today_date]['check_in'];?>
	      <?php echo '<br>';echo "<strong>".get_phrase("check_out")."</strong>:".$plan[$today_date]['check_out'];?>
         <?php  } 
        }
        
        elseif($plan[$today_date]['status']==2)
        {
            echo "<span class='red'>".get_phrase('absent')."</span>";
        }
        elseif($plan[$today_date]['status']==3)
        {
            echo "<span class='orange'>".get_phrase('leave')."</span>";
        }
    //}
}

echo "</td>";

echo "<td>";
echo $qurrr[0]['title'];
//print_r($plan);

if (is_array($plan[$today_date]) || is_object($plan[$today_date])){
    foreach($plan[$today_date] as $std )
    {
        if($std['status']==3)
        {
            $leave_date = date('Y-m-d', strtotime($d1));
            $leave_qry = "select * from ".get_school_db().".leave_student 
                where student_id='$stud_id' 
                and (DATE('".$leave_date."') between start_date and end_date)
                and school_id=".$_SESSION['school_id']." ";
           
            $leave_arr = $this->db->query($leave_qry)->result_array();
        
            echo "<strong>".get_phrase('category').":".
        "</strong>".leave_category_name($leave_arr[0]['leave_category_id']);
        //$this->crud_model->get_type_name_by_id('leave_category',$leave_arr[0]['leave_category_id']);
           
        ?>
                                        <!-------------------------------------------->
        <?php
            echo '<br>';
            echo "<strong>".get_phrase('status').": </strong>";
            if($leave_arr[0]['status']==0){
                echo '<span class="orange">'.get_phrase("pending").'</span>';
            } 
            if($leave_arr[0]['status']==1){
                echo '<span class="green">'.get_phrase("approved").'</span>';
            }
            if($leave_arr[0]['status']==2){
                echo '<span class="red">'.get_phrase("rejected").'</span>';
            }
            ?>
                                            <!-------------------------------------------->
                                            <!-------------------------------------------->
                                            <?php
            echo '<br>';
            echo "<strong>".get_phrase('approval_date')."</strong>:";
                if($leave_arr[0]['process_date'] != '0000-00-00')
                    { echo convert_date($leave_arr[0]['process_date']); }
        
        ?>
                                                <!-------------------------------------------->
                                                <?php
        
        echo '<br>';
           
        
        
        
        echo "<strong>".get_phrase('description').": </strong>".$leave_arr[0]['reason']; 
           
        if ($leave_arr[0]['proof_doc']!="")
            { ?>
                                                    <a href=" <?php echo display_link($leave_arr[0]['proof_doc'],'leaves_student'); ?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                    <?php 
            }
        ?>
                                                    <?php
        }
    }
}

echo "</td>";

echo "</tr>";
} ?>
                        </tbody>
                    </table>
</div>
                          
         
                            <div class="row p-0"> 
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

    </div>
