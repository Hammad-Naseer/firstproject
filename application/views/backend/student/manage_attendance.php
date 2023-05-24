<style>
.red{color:red;font-weight:700}.orange{color:#319cff;font-weight:700}.green{color:#208e1a;font-weight:700}.mytt{font-size:15px;color:#0a73b7;font-weight:700}.due{color:#972d2d}.mygrey{color:#a6a6a6}.dataTables_wrapper{box-shadow:0 0 10px 1px #cccccc94;background:#fff!important}.table-info{background:#fff;padding:20px;margin:0 0}@media only screen and (max-width:460px){table#DataTables_Table_0{width:90%!important}}
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('attendance');?>  
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
            $a = month_option_list("month","form-control",$month);
            $y=date('Y');
            $b= year_option_list("year","form-control",($y-1),($y+1),$year);
        ?>
	    <form method="post" action="<?php echo base_url();?>student_p/manage_attendance">
	        <div class="row filterContainer" data-step="1" data-position='top' data-intro="Select month and press show attendance button to get monthly attendance report">
                <div class="col-lg-6 col-sm-6 col-xs-12 form-group">
                    <?php echo  $monthName = date('F', mktime(0, 0, 0, $month, 10));?>
                    <?php echo $year;?>
                    <select name="month" class="form-control" required>
                        <?php 
                            $acadmic_year_start = $this->db->query("select start_date from ".get_school_db().".acadmic_year where academic_year_id =".$_SESSION['academic_year_id']." and school_id=".$_SESSION['school_id']." ")->result_array();
                            $selected = date('m',strtotime($monthName)).'-'.$year;
                            echo month_year_option($acadmic_year_start[0]['start_date'], date('Y-m-d'), $selected);
                        ?>
                    </select>
                </div>
                <div class="col-lg-6 col-sm-6 col-xs-12 form-group mt-4">
                    <input type="submit" name="submit" value="<?php echo get_phrase('show_attendance');?>" class="btn btn-primary" />
                </div>
            </div>
        </form>    
            <?php 
                $custom_css=array(1=>'current-day',2=>'holiday'); 
                $date_month= $month;//date();// $_POST['month'];
                $date_year=$year;//$_POST['year'];
                $stud_id=$_SESSION['student_id'];//$_POST['section_id'];
                $q="select a.status,a.date,attendance_timing.check_in,attendance_timing.check_out 
                    FROM ".get_school_db().".attendance a
                    LEFT JOIN ".get_school_db()." .attendance_timing ON attendance_timing.attendance_id = a.attendance_id
                    WHERE a.student_id=$stud_id AND month(a.date)=$date_month AND YEAR(a.date)=$date_year AND a.school_id=".$_SESSION['school_id']."";
                  
                $qur_red=$this->db->query($q)->result_array();
                //print_r($qur_red);
                $plan=array();
                foreach($qur_red as $red){
                    $plan[$red['date']]=array('status'=>$red['status'],'check_in'=> $red['check_in'],'check_out'=> $red['check_out']);
                }
            ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 mb-4" style="background: white;padding: 15px;">
            <table data-step="2" data-position='top' style="width:100% !important" data-intro="Current month attendance report" width="100%" class="table table-bordered p-3" aria-describedby="table_export_info">
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
        }   
        $statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');
        $statuslist_css="";
        if($dw=='Saturday' or $dw=='Sunday'){
        $statuslist_css=$statuslist[4];  
    }
    
    if(isset($plan[$today_date]['status']))
    {
      $statuslist_css = $statuslist[$plan[$today_date]['status']]; 
     // print_r($statuslist_css);
      //$statuslist_css=$statuslist[4];
    }
    echo "<tr class='".$statuslist_css." ".$current." ".$current1."'";
    echo ">";
    //echo ">";
    echo "<td style=''><span style='!important;'>$d1</span> </td>";
    echo "<td style=''>$dw</td>";
    echo "<td>";
    
    if (is_array($plan[$today_date]) || is_object($plan[$today_date])){
        // foreach($plan[$today_date] as $std )
        // {
            if($plan[$today_date]['status'] ==1)
            	{
		?> 
		<?php echo get_phrase("present");?>
		 <?php echo '<br>'; ?>
           <?php if(empty($plan[$today_date]['check_in']) && empty($plan[$today_date]['check_out'])){?>
               <?php echo "<strong>".get_phrase("check_in")."</strong>:"?> <?php echo '00:00:00';?>
               <?php echo '<br>';echo "<strong>".get_phrase("check_out")."</strong>:"?> <?php echo '00:00:00';?>
           <?php }
           else{ ?>
              <?php echo "<strong>".get_phrase("check_in")."</strong>:".$plan[$today_date]['check_in'];?>
    	      <?php echo '<br>';echo "<strong>".get_phrase("check_out")."</strong>:".$plan[$today_date]['check_out'];?>
           <?php     } ?>
		<?php
	}
            elseif($plan[$today_date]['status']==2)
            {
                echo "<span class='red'>".get_phrase('absent')."</span>";
            }
            elseif($plan[$today_date]['status']==3)
            {
                echo "<span class='orange'>".get_phrase('leave')."</span>";
            }
        // }
    }
    
    echo "</td>";
    
    echo "<td>";
    echo $qurrr[0]['title'];
    //print_r($plan);
    if (is_array($plan[$today_date]) || is_object($plan[$today_date])){
        // foreach($plan[$today_date] as $std )
        // {
            if($plan[$today_date]['status']==3)
            {
                $leave_date = date('Y-m-d', strtotime($d1));
                $leave_qry = "select * from ".get_school_db().".leave_student 
                    where student_id='$stud_id' 
                    and (DATE('".$leave_date."') between start_date and end_date)
                    and school_id=".$_SESSION['school_id']." ";
               
                $leave_arr = $this->db->query($leave_qry)->result_array();
                
            ?>
                                        <?php
            
            
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
        // }
    }
    
    echo "</td>";
    
    echo "</tr>";
    } ?>
                            </tbody>
                        </table>
                  
         
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
    </div>  