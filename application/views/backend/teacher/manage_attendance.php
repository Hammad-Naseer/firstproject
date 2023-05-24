<style>
    .red {
        color: red;
        font-weight: bold;
    }

    .orange {
        color: #319cff;
        font-weight: bold;
    }

    .green {
        color: #208e1a;
        font-weight: bold;
    }

    .mytt {
        font-size: 15px;
        color: #0A73B7;
        font-weight: bold;
    }

    .due {
        color: #972d2d;
    }

    .mygrey {
        color: #A6A6A6;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Watch Tutorial</b>
        </a>
        <h3 class="system_name inline">
        
         <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/viewattendence.png">
          <?php echo get_phrase('attendance');?>
             </h3>
    </div>
    <div class="col-lg-12 col-md-12 ">
        <?php
        $month=date('n');
        $year=date('Y');
        if(isset($_POST['submit']))
        {
            $date_submitted = explode('-',$this->input->post('month'));
            $year = intval($date_submitted[1]);
            $month= intval($date_submitted[0]);
        }
?>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" data-step="1" data-position='top' data-intro="select month & press filter button get your specific month attendance report">
                <tbody>
                    <form method="post" action="<?php echo base_url();?>teacher/manage_attendance" class="form">
                        <tr class="gradeA">
                            <td>
                                <div class="mytt text-center">
                                    <?php echo  $monthName = date('F', mktime(0, 0, 0, $month, 10));
                     ?>
                                    <?php echo $year;  ?>
                                </div>
                            </td>
                            <td>
                                <select name="month" class="form-control" required>
                                    <?php 
                    $acadmic_year_start = $this->db->query("select start_date 
                            from ".get_school_db().".acadmic_year 
                            where 
                            academic_year_id =".$_SESSION['academic_year_id']." 
                            and school_id=".$_SESSION['school_id']." 
                            ")->result_array();
                    $selected = date('m',strtotime($monthName)).'-'.$year;
                    echo month_year_option($acadmic_year_start[0]['start_date'], date('Y-m-d'), $selected); 
                ?>
                                </select>
                            </td>
                            <td align="center" colspan="2">
                                <input type="submit" name="submit" value="<?php echo get_phrase('show_attendance');?>" class="btn btn-primary" />
                            </td>
                        </tr>
                    </form>
                </tbody>
            </table>
            <?php 

$date_month = $month;//date();// $_POST['month'];
$date_year = $year;//$_POST['year'];
$login_detail_id = $_SESSION['login_detail_id'];//$_POST['section_id'];

$q="select a.status,a.date FROM ".get_school_db().".attendance_staff a
INNER JOIN  ".get_school_db().".staff staff ON staff.staff_id = a.staff_id
WHERE 
staff.user_login_detail_id =$login_detail_id 
AND month(a.date)=$date_month 
AND YEAR(a.date)=$date_year 
AND a.school_id=".$_SESSION['school_id']."
";
$qur_red=$this->db->query($q)->result_array();
//print_r($qur_red);
$plan=array();
foreach($qur_red as $red){
    
$plan[$red['date']]=array('status'=>$red['status']);

}
//print_r($plan);
?>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered dataTable1" id="1table_export" aria-describedby="table_export_info" data-step="2" data-position='top' data-intro="attendance report details">
                <thead>
                    <tr>
                    </tr>
                    <tr>
                        <th style=" width: 109px; background:#29638d; color:#ffffff;"><?php echo get_phrase('date');?></th>
                        <th style=" width: 109px; background:#29638d; color:#ffffff;"><?php echo get_phrase('day');?></th>
                        <th style=" width: 109px; background:#29638d; color:#ffffff;"><?php echo get_phrase('attendance');?></th>
                        <th style=" background:#29638d; color:#ffffff;"><?php echo get_phrase('comments');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$custom_css=array(1=>get_phrase('current-day'),2=>get_phrase('holiday')); 
$statuslist=array(1=>get_phrase('present'), 2=>get_phrase('absent'), 3=>get_phrase('leave'),4=>get_phrase('weekend'));     
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
$statuslist_css="";
if(isset($plan[$today_date]['status']))
{
	$statuslist_css = $statuslist[$plan[$today_date]['status']]; 
}
$date1 = "$date_year-$date_month-$i"; 
$q1="select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ";    
$qurrr=$this->db->query($q1)->result_array();
    if(count($qurrr)>0)
    {
    	$current1=$custom_css[2];
        //echo " style='background-color:#fceed4;'";
    }   

$date_num = date('N',strtotime($date1));

if($dw=='Saturday' or $dw=='Sunday'){
$statuslist_css=$statuslist[4];
}   


echo "<tr class='".$statuslist_css." ".$current." ".$current1."'";




echo ">";
echo "<td style='        
   '><span style='!important;'>$d1</span> </td>";
   echo "<td style='        
   '>$dw</td>";
echo "<td>";
foreach($plan[$today_date] as $std )
{
    if($std['status']==1)
    {
        echo "<span class='green'>" .get_phrase('present'). "</span>";
    }
    elseif($std['status']==2)
    {
        echo "<span class='red'>" .get_phrase('absent'). "  </span>";
    }
    elseif($std['status']==3)
    {
        echo "<span class='orange'>" .get_phrase('leave'). "  </span>";
    }
}

echo "</td>";

echo "<td>";

//print_r($plan);

foreach($plan[$today_date] as $std )
{
    if($std['status']==3)
    {
        $leave_date = date('Y-m-d', strtotime($d1));
        $leave_qry = "select * from ".get_school_db().".leave_staff ls 
            inner join ".get_school_db().".staff staff on staff.staff_id = ls.staff_id
            where 
            staff.user_login_detail_id ='$login_detail_id' 
            and (DATE('".$leave_date."') between ls.start_date and ls.end_date)
            and ls.school_id=".$_SESSION['school_id']." ";
       
        $leave_arr = $this->db->query($leave_qry)->result_array();
        
    ?>
                        <?php
        echo "<strong>".get_phrase('category').":".
    "</strong>".$this->crud_model->get_type_name_by_id('leave_category',$leave_arr[0]['leave_category_id']);
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
        echo '<br>';
        
        echo "<strong>".get_phrase('approval_date')."</strong>:";
            if($leave_arr[0]['process_date'] != '0000-00-00')
                { echo convert_date($leave_arr[0]['process_date']); }
    
    echo '<br>';
    
    echo "<strong>".get_phrase('description').": </strong>".$leave_arr[0]['reason']; 
    if ($leave_arr[0]['proof_doc']!="")
        { ?>
                            <a href=" <?php echo display_link($leave_arr[0]['proof_doc'],'leaves_staff'); ?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                            <?php 
        }
    }
}

echo "</td>";
echo "</tr>";
} ?>
                </tbody>
            </table>
<div class="table-info">
                                	<ul>
                                        <li class="present-detail">
                                            <div></div>
                                            <span><?php echo get_phrase('present');?></span>
                                        </li>
                                        <li class="absent-detail">
                                            <div></div>
                                            <span><?php echo get_phrase('absent');?></span>
                                        </li>
                                        <li class="leave-detail">
                                            <div></div>
                                            <span><?php echo get_phrase('leave');?></span>
                                        </li>
                                        <li class="weekend-detail">
                                            <div></div>
                                            <span><?php echo get_phrase('weekend');?></span>
                                        </li>
                                    </ul>
                                </div>
    </div>
</div>
