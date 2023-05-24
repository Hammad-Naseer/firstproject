
<style>
.nav-tabs{background:0 0!important;padding:0;margin-top:0;margin-bottom:0;font-size:12px}.nav-tabs>li>a{margin-right:4px;line-height:1.42857143;border-radius:3px 3px 0 0;background-color:#ebebeb!important}.nav.nav-tabs a{padding:5px 10px 5px 10px!important;margin-bottom:6px}
@media only screen and (max-width: 768px) {
    .container-fluid.foter-top {
    display:none;
}
} 
</style>

<?php
    $yearly_term_id = $_SESSION['yearly_term_id'];
    $login_detail_id = $_SESSION['login_detail_id'];
    
    
?>
<div class="row">
    <?php if(count($assigned_access_rights) > 1 && !$action_blocked) { ?>
        
    
    <div class="col-lg-3 col-md-12 col-sm-12 my-3">
        <?php } ?>
        <!--<h3 class="system_name inline" style="margin:0 0 5px 0!important">-->
        <!--    <?php echo get_phrase('dashboard');?>  -->
        <!--</h3>-->
        
        <div style="display:flex;">
            <?php if($res[0]['profile_pic']==""){ ?>
            <img alt=""alt=""  src="<?php echo get_default_pic() ?>" style="height: 50px;border-radius: 8px;">
            <?php }else{ ?>
            <img alt=""alt=""alt=""  src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1) ?>" style="height: 50px;border-radius: 8px;">
            <?php } ?>
            <span style="margin-top: -10px;margin-left: 5px;">
                <h3 style="">Welcome, <?php echo $res[0]['name']; ?> 	<p><?= date("l")." ". ordinal(date("d"))." ".date("M, Y")  ?> </p>
                </h3>
            </span>
        
        </div>
    </div>
    
    <div class="col-lg-9 col-md-12 col-sm-12 myt  my-3 topbar d-md-flex justify-content-md-end" style="align-items: flex-end;">
        <div class="top">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#quick_menu"><?php echo get_phrase('home');?></a></li>
                <?php if (in_array("t_manage_diary", $assigned_access_rights)){ ?>
                <li><a data-toggle="tab" href="#diary"><?php echo get_phrase('class_diary');?></a></li>
                <?php }if (in_array("t_attendance", $assigned_access_rights)){ ?>
                <li><a data-toggle="tab" href="#attendance"><?php echo get_phrase('my_attendance');?></a></li>
                <?php }if (in_array("t_datesheet", $assigned_access_rights)){ ?>
                <li><a data-toggle="tab" href="#class_exam_routine"><?php echo get_phrase('my_datesheet');?></a></li>
                <?php }if (in_array("t_academic_planner", $assigned_access_rights)){ ?>
                <li><a data-toggle="tab" href="#planner"><?php echo get_phrase('academic_planner');?></a></li>
                <?php }if (in_array("t_notices_and_circulars", $assigned_access_rights)){ ?>
                <li><a data-toggle="tab" href="#circular_notice"><?php echo get_phrase('circulars_and_notices');?></a></li>
                <?php }if (in_array("t_exams", $assigned_access_rights)){ ?>
                <li><a data-toggle="tab" href="#exam_graph"><?php echo get_phrase('exam_graph');?></a></li>
                <?php } ?>
            </ul>
        </div>
        
    </div>
    
</div>

<div class="tab-content tcher-dboard"> 
    
    <div id="quick_menu" class="tab-pane in active">
        <div class="row mt-4">
          <!--Panel body Start Stats-->
          
            <div class="col-sm-12 col-md-6 col-lg-3">
                <a href="<?php echo base_url().'teacher/teacher_summary'?>" target="_blank">
                <div class="tile-stats tile-green tile_custom">
                  <div class="icon border_green">
                    <i class="fas fa-user-check main_counter_icon ">
                    </i>
                  </div>
                  <div class="num"data-delay="0"data-duration="800"data-end="<?php echo $total_present; ?>"data-postfix=""data-start="0">
                    <?php echo $total_present; ?>
                  </div>
                  <h3 class="white2">
                    <?php echo get_phrase('present'); ?><sup>This month</sup>
                  </h3>
                </div>
                </a>
            </div>
            
            <div class="col-sm-12 col-md-6 col-lg-3">
                <a href="<?php echo base_url().'teacher/teacher_summary'?>" target="_blank">
                <div class="tile-stats tile-blue tile_custom">
                  <div class="icon border_blue">
                    <i class="fas fa-user main_counter_icon "></i>
                  </div>
                  <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $total_absent; ?>"
                  data-postfix=""data-start="0">
                    <?php echo $total_absent; ?>
                  </div>
                  <h3 class="white2">
                    <?php echo get_phrase('absent'); ?><sup>This month</sup>
                  </h3>
                </div>
                </a>
            </div>
            
            <div class="col-sm-12 col-md-6 col-lg-3">
                <a href="<?php echo base_url().'teacher/teacher_summary'?>" target="_blank">
                <div class="tile-stats tile-red tile_custom">
                  <div class="icon border_orange">
                    <i class="fas fa-user-minus main_counter_icon "></i>
                  </div>
                  <div class="num" data-delay="0" data-duration="800"data-end="<?php echo $total_leave; ?>"  data-postfix=""  data-start="0">
                    <?php echo $total_leave; ?>
                  </div>
                  <h3 class="white2">
                    <?php echo get_phrase('leaves'); ?><sup>This month</sup>
                  </h3>
                </div>
                </a>
            </div>
            
            <div class="col-sm-12 col-md-6 col-lg-3">
                <a href="<?php echo base_url().'teacher/students_birthday'?>" target="_blank">
                <div class="tile-stats tile-purple tile_custom">
                  <div class="icon border_purple">
                    <i class="fas fa-birthday-cake main_counter_icon "></i>
                  </div>
                  <div class="num"data-delay="0"data-duration="800" data-end="<?php echo $std_birthday; ?>"data-postfix=""data-start="0">
                    <?php echo $std_birthday; ?>
                    </div>
                <h3 class="white2">
                  <?php echo get_phrase('student_birthday'); ?><sup>Today</sup>
                </h3>
                </div>
                </a>
          </div>
        
            <!--End Panel Body Stats-->
        </div>
        <?php
           if(count($assigned_access_rights) > 1 && !$action_blocked) {
        ?>
        <?php if (in_array("t_quick_links", $assigned_access_rights)){ ?>
        
            <!--Quick Links Start-->
            <div class="row pt-5"> 
			    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2 ">
		        <a href="<?=base_url()?>teacher/class_routine">
		        <div class="quick_links text-center gradientone">
					<i class="fas fa-table"></i>
					<span class="quick_link_txt">Timetable/Class Routine</span>
				</div>
				</a>
		        </div>
			    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2 ">
		        <a href="<?=base_url()?>teacher/manage_attendance_student">
		        <div class="quick_links text-center gradienttwo">
					<i class="fas fa-users"></i>
					<span class="quick_link_txt">Student Attendance</span>
				</div>
				</a>
		        </div>
			    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2 ">
		        <a href="<?=base_url()?>teacher/marks">
		        <div class="quick_links text-center gradientone">
					<i class="fas fa-chalkboard-teacher"></i>
					<span class="quick_link_txt">Exams</span>
				</div>
				</a>
		        </div>
		        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2 ">
		        <a href="<?=base_url()?>assessment/create_assessment">
		        <div class="quick_links text-center gradienttwo">
					<i class="fas fa-edit"></i>
					<span class="quick_link_txt">Create Assessment</span>
				</div>
				</a>
	            </div>
			    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2 ">
			        <a href="<?=base_url()?>assessment/view_assessment_result">
			              <div class="quick_links text-center gradientone">
						        <i class="fas fa-poll-h"></i>
						    <span class="quick_link_txt">View result</span>
					    </div>
					</a>
			    </div>
			    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2 ">
			        <a href="<?=base_url()?>teacher/manage_profile">
			        <div class="quick_links text-center gradienttwo">
						<i class="fas fa-user"></i>
						<span class="quick_link_txt">Profile</span>
					</div>
					</a>
			    </div>
           </div>
           <!--End Quick Links-->


		<?php } ?>
		
		<?php } ?>	
		<?php if(count($assigned_access_rights) > 1 && !$action_blocked) {?>
		<style>
            .black {
                color: #000;
                font-weight: bold;
            }
        </style>
        <?php if (in_array("t_dashboard_my_timetable", $assigned_access_rights)){ ?>
        
              <?php $this->load->view('backend/teacher/my_class_routine');   ?>
        
        <?php } ?>
		<!--Quick Menus Code By Zeeshan Arain-->
    </div>
    
    <div id="diary" class="tab-pane dash-m">
        <!--subject  Diary here -->
        <?php 
        $subject = array();
        $d_c_s_sec = get_teacher_dep_class_section($login_detail_id);
        $sub_arr = get_teacher_section_subjects($d_c_s_sec); //get section subjects
        
        if ( count($sub_arr) > 0 )
        {
            foreach ($sub_arr as $value) 
            {
                $subject[] = $value; 
            }
        }
        $time_table_t_sub = array_unique(get_time_table_teacher_subject($login_detail_id));
        $sub_in = 0;
        if (count($time_table_t_sub) > 0)
        {
            $sub_in = implode(',', array_unique($time_table_t_sub));
        }

        // $today_subjects_arr = array();

        // $today_subjects_section_arr = array();
        
        // if(is_array($timetable_arr) || is_object($timetable_arr)){
        //     foreach ($timetable_arr as $key => $value) 
        //     {
        //         if (strtolower($key) == strtolower(date('l')))
        //         {
        //             for ($i = 1; $i<=$period_max; $i++ )
        //             {
        //                 if (isset($dcs_arr[$key][$i])) 
        //                 {
        //                     $today_subjects_arr[] = $planner_today_subject_ids[$key][$i];//.' - '.$value[$i];//$dcs_arr[strtolower(date('l'))];
        //                     $today_subjects_section_arr[] = $dcs_arr[$key][$i];
        //                 }
        //             }
        //         }
        //     }
        // }
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 b-s p-4">
                <div class="box">
                    <div class="box-header with-border mgbb">
                        <h3 class="box-title"> <i class="fa fa-graduation-cap" aria-hidden="true"></i><?php echo get_phrase('class_diary');?></h3>						
                    </div>
                    <div id="text"> 
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <h3 style="font-size:18px;">
                                        <?php //echo $class_section_name ; ?>
                                    </h3> 
                                </div>
                            
                            <?php
                            
                            $sectionIds = get_time_table_teacher_section($_SESSION['login_detail_id'], $_SESSION['yearly_term_id']);
	                        $implode_sectionIds = implode("','",$sectionIds);
                                $diary = $this->db->query("select dr.*,  concat(sub.name,' - ', sub.code) as subject_name
                                        FROM ".get_school_db().".diary dr
                                        INNER JOIN ".get_school_db().".staff on staff.staff_id = dr.teacher_id
                                        INNER JOIN ".get_school_db().".subject sub ON sub.subject_id = dr.subject_id
                                        Where 
                                        dr.school_id=".$_SESSION['school_id']." 
                                        AND dr.due_date >= DATE(NOW())
                                        AND staff.user_login_detail_id = $login_detail_id
                                        AND dr.section_id IN('$implode_sectionIds')
                                        ")->result_array();
                                                       
                            if (count($diary) > 0)
                            {
                                $diary_index = 0;
                                foreach($diary as $dr)
                                {
                                    $diary_index++;
                                ?>
                                
                                    <div class="col-lg-12">
                                        <div class="nds nshov" style="border-left:3px solid #00859a; padding-left:0px;background-color: #fbfbfb !important; ">
                                        <div class="panel-group joined" id="accordion" style="margin-bottom:0px;">
                                        	<div class="panel panel-default">
                                                <div class="panel-heading">
                                                	<h4 class="panel-title">
                                                    	<a href="#collapse<?php echo $dr['diary_id'].'_'.$diary_index; ?>" data-toggle="collapse" data-parent="#accordion" class="collapsed mb-3" style="font-size:14px !important;">
                                                        	<strong><?php echo get_phrase('title');?>:</strong>
                                                        	<span><?php echo $dr['title']; ?></span>  
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse<?php echo $dr['diary_id'].'_'.$diary_index; ?>" class="panel-collapse collapse" style="max-height:210px; min-height:auto; overflow-y:auto;">
                                                	<div class="panel-body">
                                                    <h3><strong><?php echo get_phrase('assign_date');?>:</strong>
                                                        <span><?php echo convert_date($dr['assign_date']); ?></span>
                                                    </h3>
                                                    <h3>
                                                        <strong><?php echo get_phrase('due_date');?>:</strong>
                                                        <span><?php echo convert_date($dr['due_date']); ?></span>
                                                    </h3>
                                                    <h3>
                                                        <strong><?php echo get_phrase('class');?>:</strong>
                                                        <span>
                                                        <?php 
                                                            $sec_arr = section_hierarchy($dr['section_id']);
                                                            echo $sec_arr['d'].' -> '.$sec_arr['c'].' -> '.$sec_arr['s']; 
                                                        ?>
                                                        </span>
                                                    </h3>
                                                    <?php if($dr['attachment']!=""){ ?>
                                                    <h3>
                                                        <strong><?php echo get_phrase('Attachment');?>:</strong>
                                                        <span>
                                                            <a style="font-size:14px;" href="<?php echo display_link($row['attachment'],'diary');?>" target="_blank"><span class="glyphicon glyphicon-download-alt"></span></a>
                                                        </span>
                                                    </h3>
                                                    <?php } ?>
                                                    
                                                    
                                                        
                                                        <?php 
                                                        
                                                        $planner_arr = $this->db->query("select ap.* 
                                                            from ".get_school_db().".academic_planner_diary apd
                                                            inner join ".get_school_db().".academic_planner ap
                                                                on ap.planner_id = apd.planner_id
                                                            where apd.diary_id = ".$dr['diary_id']." 
                                                            and apd.school_id = ".$_SESSION['school_id']."
                                                            ")->result_array();
															
                                                        if (count($planner_arr)>0)
                                                        {
                                                            echo '<h3> <strong>'.get_phrase("academic_planner_tasks"). ':</strong><span>';
                                                            $p_count=1;
                                                            foreach ($planner_arr as $key => $value) 
                                                            {
                                                                echo '<br>'.$p_count++.')'.get_phrase('title').": ".$value['title'];
                                                            }
															echo '</span></h3>';
                                                        } 

                                                        ?>
                                                    
                                                    
                                                    <h3> <strong><?php echo get_phrase('detail');?>: </strong>
                                                        <span><?php echo $dr['task']; ?></span>
                                                    </h3>
                                                    <?php 
                                                    if ($dr['submission_date'] != '0000-00-00 00:00:00')
                                                    
                                                        {
                                                    echo '<h3> <strong>'.get_phrase("submission_date"). ':</strong><span>'.convert_date($dr["submission_date"]).' '.date("h:i:s", strtotime($dr["submission_date"])).'</span></h3>';
                                                        } ?>
                                            	</div>
                                            	</div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                <?php 
                                }
                            }
                            else
                            {
                                ?>
                                <div class="text-center">
                                <i class="fas fa-book-open" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                                <h2><b>Diary is Empty</b></h2>
                                <a href="<?php echo base_url();?>teacher/diary" style="color:black;"> <b><?php echo get_phrase('go_to_diary_page');?> <i class="fas fa-long-arrow-alt-right"></i></b></a>
                            </div>
                            <?php
                            }
                            ?>
                            </div>
                    </div>
                </div>
                <div class="link">
                    <a href="<?php echo base_url(); ?>teacher/diary" class="more"> Go To Diary &gt;&gt; </a>
                </div>
            </div>
        </div>
    </div>
    <div id="attendance" class="tab-pane dash-m">
        <?php
        $custom_css=array(1=>'current-day',2=>'holiday');      
		$current_date=date('d-M-Y');                           
		 
        $date_month = date('m');
        $date_year = date('Y');

        $qur_red = $this->db->query("select a.status,a.date,attendance_staff_timing.check_in,attendance_staff_timing.check_out
            FROM ".get_school_db().".attendance_staff a
            INNER JOIN ".get_school_db().".staff staff on staff.staff_id = a.staff_id
            LEFT JOIN ".get_school_db()." .attendance_staff_timing ON attendance_staff_timing.attend_staff_id=a.attend_staff_id
            WHERE 
            staff.user_login_detail_id=$login_detail_id 
            AND month(a.date)=$date_month 
            AND YEAR(a.date)=$date_year 
            AND a.school_id=".$_SESSION['school_id']."
            ")->result_array();
           
       // print_r($qur_red);exit;
        $plan=array();
        foreach($qur_red as $red)
        {
            $plan[$red['date']]=array('status'=>$red['status'],'check_in'=>$red['check_in'],'check_out'=>$red['check_out']);
        }
        ?>
        <!-- ////////////////// attandance//////-->
        <div class="row">
            <div class="col-lg-12 b-s p-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-check-square-o" aria-hidden="true"></i> <?php echo get_phrase('monthly_attendance');?> </h3>
                    </div>

            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-responsive" aria-describedby="table_export_info">
                <thead>
                    <tr>
                    </tr>
                    <tr>
                        <th><?php echo get_phrase('date');?></th>
                        <th><?php echo get_phrase('day');?></th>
                        <th><?php echo get_phrase('attendance');?></th>
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
$current1="";

if(count($qurrr)>0){
		$current1=$custom_css[2];
		//echo " style='background-color:#FFF;'";
			}	
 
//echo "<tr class='".$current." ".$current1."'";



$date_num = date('N',strtotime($date1));
$statuslist=array(1=>get_phrase('present'), 2=>get_phrase('absent'), 3=>get_phrase('leave'),4=>get_phrase('weekend'));
$statuslist_css="";
if($dw=='Saturday' or $dw=='Sunday'){
$statuslist_css=$statuslist[4];  
//echo " style='background-color:#EEE'";	
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


//print_r($plan[$today_date]);
	//foreach($plan[$today_date] as $std )
//{
	echo "<td>";
	if($plan[$today_date]['status']==1)
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
	{ ?>
		
	  <?php
		echo get_phrase("absent");?> 
			
		 
		   <?php
	}
	elseif($plan[$today_date]['status']==3)
	 {
	 ?>
	 	
	  
	    <?php	echo get_phrase("leave");?> 
	    	</span>
	    
	    <?php
	}
	echo "</td>";
	
	echo "<td>";
	echo $qurrr[0]['title'];
	if($plan[$today_date]['status']==3)
	{
            $leave_date = date('Y-m-d', strtotime($d1));
            $leave_qry = "select ls.*,lc.name as leave_categ from ".get_school_db().".leave_staff ls
            INNER JOIN ".get_school_db().".leave_category lc 
            ON lc.leave_category_id=ls.leave_category_id
                where ls.staff_id='$staff_id' 
                and (DATE('".$leave_date."') between ls.start_date and ls.end_date)
                and ls.school_id=".$_SESSION['school_id']." ";
                //exit;
           
            $leave_arr = $this->db->query($leave_qry)->result_array();
            
            echo "<strong>".get_phrase('category').": </strong>".$leave_arr[0]['leave_categ'];
           
			
						
			echo '<br>';
			
			echo "<strong>".get_phrase('status').": </strong>";
            if($leave_arr[0]['status']==0)
			{
                echo '<span class="orange">'.get_phrase('pending').'</span>';
            } 
            if($leave_arr[0]['status']==1)
			{
                echo '<span class="green">'.get_phrase('approved').'</span>';
            }
            if($leave_arr[0]['status']==2)
			{
                echo '<span class="red">'.get_phrase('rejected').'</span>';
            }
			

            echo '<br>';
            echo "<strong>".get_phrase('start_date')."</strong>:";
            $start_date= $leave_arr[0]['start_date'];
             
            echo date( "d-M-Y", strtotime($start_date));
            echo '<br>';
            echo "<strong>".get_phrase('end_date')."</strong>:";
            $end_date= $leave_arr[0]['end_date'];
            echo date( "d-M-Y", strtotime($end_date));
            echo '<br>';
            echo "<strong>".get_phrase('process_date')."</strong>:";
                if($leave_arr[0]['process_date']!="")
                    { 
                    $process_date=$leave_arr[0]['process_date']; 
                    echo date( "d-M-Y", strtotime($process_date));
                    }
                    else
                    {
                    	 echo get_phrase("n")."/".get_phrase("a"); 
                   	};
			
			echo '<br>';
            echo "<strong>".get_phrase('description').": </strong>".$leave_arr[0]['reason']; 
            if ($leave_arr[0]['proof_doc']!="")
            { ?> 
                <a href=" <?php echo display_link($leave_arr[0]['proof_doc'],'leaves_staff'); ?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a> 
            <?php 
            }  
            
        }  
        
   echo "</td>";       
//}
 

echo "</tr>"; 
}
 
 ?>  

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
        </div>
    </div>
    <div id="class_exam_routine" class="tab-pane dash-m">
        <div class="row">
            <div class="col-sm-12 col-md-12 b-s p-4">
                <div class="box">
                    <div class="box-header with-border mgbb">
                        <h3 class="box-title"> <i class="fa fa-graduation-cap" aria-hidden="true"></i><?php echo get_phrase('my_datesheet');?></h3>
                    </div>
                    <?php 
                    $teacher_subject = array_unique(get_time_table_teacher_subject($login_detail_id, $yearly_term_id));
                    $time_table_t_sec = array_unique(get_time_table_teacher_section($login_detail_id));
                    $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
                    if (count($teacher_section) > 0)
                        $section_in = implode(',', $teacher_section);
                    else
                        $section_in = 0;
                    $exams = $this->db->query("select e.*, group_concat(distinct er.section_id SEPARATOR ',') as section_ids
                            from ".get_school_db().".exam e 
                            inner join ".get_school_db().".exam_routine er on er.exam_id=e.exam_id 
                            inner join ".get_school_db().".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id 
                            where e.school_id=".$_SESSION['school_id']." 
                            and y.yearly_terms_id=".$_SESSION['yearly_term_id']." 
                            /*and ( DATE(NOW()) between e.start_date and e.end_date)*/
                            and y.academic_year_id=".$_SESSION['academic_year_id']."
                            and section_id in ($section_in)
                            group by exam_id 
                            order by e.start_date DESC 
                        ")->result_array();
                    /*echo '<pre>';
                    print_r($exams);*/
                    foreach($exams as $row)
                    {?>
                        <div class="panel panel-primary">
                            <div class="panel-body with-table margin0">
                                <div style="/*overflow:hidden;   height:263px;*/" id="text">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                           
                                            <h3 class="myttl">
                                         <?php echo $row['name']; ?>
                                            <span style="font-size:12px;">
                                            <?php echo '(<b>'.date('d M Y',strtotime($row['start_date'])).'</b> - <b>'.date('d M Y',strtotime($row['end_date'])).'</b>)'; ?>
                                                
                                            </span>
                                            </h3>
                                        </div>

                                    <?php 
                                    $q="select er.* from ".get_school_db().".exam_routine er 
                                    where er.school_id=".$_SESSION['school_id']." and er.exam_id=".$row['exam_id']." and section_id in (".$row['section_ids'].") 
                                    order by er.exam_date
                                    ";
                                    $routines=$this->db->query($q)->result_array();
                                    /*echo '<pre>';
                                    print_r($routines);*/
                                    foreach($routines as $row2)
                                    {
                                        if (in_array($row2['subject_id'], $teacher_subject))
                                        {
                                            $exam_highlight = (convert_date($row2['exam_date']) == date('d-M-Y')) ? 'highlight_bg tm_tbl' : '';
                                        ?>
                                        <div class="col-lg-6">
                                            <div class="nds nshov" style="border-left:3px solid #00859a; padding-left:20px;   
                                                background-color: #fbfbfb !important;padding:0px 10px;">
                                                <div <?php echo 'class="'.$exam_highlight.'"'; ?>>
                                                <h3>
                                                    <span style="font-weight:bold;">
                                                    <?php 
                                                    echo convert_date($row2['exam_date']).' '.'('.date('l', strtotime(   $row2['exam_date'])).')';
                                                    ?>
                                                    </span>
                                                </h3>

                                                <h3><strong><?php echo get_phrase('subject');?>:</strong>
                                                    <span><?php echo get_subject_name($row2['subject_id']); ?></span>
                                                </h3>
                                                <?php 
                                                /*
                                                <h3><strong>Section(s):</strong>
                                                    <span>
                                                    <?php 
                                                    $exam_sections = explode(',', $row['section_ids']);
                                                    foreach ($exam_sections as $e_sec) 
                                                    {
                                                       $sec_arr = section_hierarchy($e_sec);

                                                        echo '<br>'.$sec_arr['d'].' -> '.$sec_arr['c'].' -> '.$sec_arr['s'];

                                                        echo "<strong>Exam Date:</strong>
                                                        <span>
                                                         convert_date(".$row2['exam_date'].");
                                                        
                                                        </span>";

                                                    }
                                                    
                                                    ?>
                                                    </span>
                                                </h3>
                                                */
                                                ?>
                                                <h3>
                                                    <span>
                                                    <?php 
                                                    $sec_arr = section_hierarchy($row2['section_id']);
													?>
                                                    <strong><?php echo get_phrase('section');?>:</strong>
                                                    
                                                    <ul class="breadcrumb breadcrumb2">
                                                    	<li><?php echo $sec_arr['d']; ?></li>
                                                        <li><?php echo $sec_arr['c']; ?></li>
                                                        <li><?php echo $sec_arr['s']; ?></li>
                                                    </ul>
                                                    </span>
                                                </h3>

                                                <h3>
                                                    <span style="font-weight:bold; text-decoration:underline;">
                                                    <?php
                                                    $start_am = ($row2['time_start'] >= 12) ? 'PM' : 'AM';
                                                    $end_am = ($row2['time_end'] >= 12) ? 'PM' : 'AM';
                                                    echo $row2['time_start'].$start_am.' - '.$row2['time_end'].$end_am;
                                                    ?>
                                                    </span>
                                                </h3>
                                                </div>

                                            </div>
                                        </div>
                                        <?php
                                        }
                                    }
                                    ?>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    <?php    
                    }
                    ?>
                    <div class="link">
                        <!--<a class="readmore" onclick="changeheight(this)" style="cursor: pointer">Read more</a>-->
                        <a href="<?php echo base_url();?>teacher/exam_routine" class="more"> <?php echo get_phrase('go_to_datesheet');?> >> </a>
                    </div>                            
                </div>
            </div>
        </div>

        <?php 
        /*
        <div class="row">
            <div class="col-md-12">
                <div class="panel-group joined" id="accordion-test-2">
                    <?php 
                    
                    foreach($exams as $row)
                    { 
                        $q="select er.* from ".get_school_db().".exam_routine er where er.school_id=".$_SESSION['school_id']." and er.exam_id=".$row['exam_id']." and section_id=".$row['section_id']." ";
                                $routines=$this->db->query($q)->result_array();
                                //echo implode(',', $teacher_subject);
                                foreach($routines as $row2)
                                {?>
                                    <div class="btn-group" id="er<?php echo $row2['class_exam_id'];?>">
                                    <?php 
                                    if(strtotime($row2['exam_date'])==$i)
                                    { 
                                        $my_sub = "";
                                        //echo $row2['subject_id'].'<br>';
                                        if (in_array($row2['subject_id'], $teacher_subject)) 
                                            $my_sub = "green";

                                        echo '<span class="'.$my_sub.'">'.get_subject_name($row2['subject_id']);
                                        echo ' ('.$row2['time_start'].'-'.$row2['time_end'].')';
                                        echo '</span>';
                                    }?>
                                    
                                    </div>
                                <?php 
                                }?>
                               
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion-test-<?php echo $row['exam_id'];?>" href="#collapse<?php echo $row['exam_id'].'-'.$row['section_id'];?>">
                                    
                                    <i class="entypo-rss"></i> <?php 
                                    $sec_arr = section_hierarchy($row['section_id']);
                                    echo $sec_arr['d'].' -> '.$sec_arr['c'].' -> '.$sec_arr['s'].': ';

                                    echo $row['name'];?> (<b><?php echo date('d M Y',strtotime($row['start_date']));?></b> - <b><?php echo date('d M Y',strtotime($row['end_date']));?></b>)
                                    <?php ?>
                                </a>
                            </h4>
                        </div>
                        
                        <div id="collapse<?php echo $row['exam_id'].'-'.$row['section_id'];?>" class="panel-collapse collapse <?php if($toggle){echo 'in';$toggle=false;}?>">
                            <div class="panel-body">
                                <table cellpadding="0" cellspacing="0" border="0"  class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td>Date</td>
                                        <td>Day</td>
                                        <td>Subject</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    

                                    $date_from = strtotime($row['start_date']);
                                    $date_to = strtotime($row['end_date']);
                                    $oneDay = 60*60*24;
                                    for($i=$date_from; $i<=$date_to; $i=$i+$oneDay)
                                    {
                                        $day = date("l", $i);
                                        ?>
                                        <tr class="gradeA">
                                            <td width="100">
                                                <?php echo convert_date(date("l F j, Y", $i));?>
                                            </td>
                                            <td width="100">
                                                <?php echo $day;?>
                                            </td>
                                            <td>
                                            
                                            </td>
                                        </tr>
                                    <?php 
                                    }
                                
                                    ?>
                                </tbody>
                             </table>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>   
        */?>     
    </div>
    <div id="planner" class="tab-pane dash-m">
        <div class="row">
            <div class="col-sm-12 col-md-12 b-s p-4">
                <div class="box">
                    <div class="box-header with-border mgbb">
                        <h3 class="box-title"> <i class="fa fa-graduation-cap" aria-hidden="true"></i><?php echo get_phrase('today_academic_planner');?></h3>
                    </div>
                    <div style="/*overflow:hidden;   height:263px;*/" id="text">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <h3 style="font-size:18px;color:black !important">
                                    <?php //echo $class_section_name ; ?> 
                                </h3> 
                            </div>
                        <?php
                        $sectionIds = get_time_table_teacher_section($_SESSION['login_detail_id'], $_SESSION['yearly_term_id']);
                	    $implode_sectionIds = implode("','",$sectionIds);
                	    //echo $implode_sectionIds;exit;
                		$query = "select s.subject_id FROM 
                		".get_school_db().".class_routine cr 
                            inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
                            inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
                            inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
                            inner join ".get_school_db().".subject s on s.subject_id=st.subject_id
                            inner join ".get_school_db().".staff staff on staff.staff_id=st.teacher_id
                            inner join ".get_school_db().".subject_section SS on SS.subject_id = st.subject_id
                            inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
                            inner join ".get_school_db().".class on class.class_id = cs.class_id
                            inner join ".get_school_db().".departments d on d.departments_id = class.departments_id
                            where 
                            staff.user_login_detail_id = ".$_SESSION['login_detail_id']."
                            and cr.school_id=".$_SESSION['school_id']."
                            and crs.section_id IN('$implode_sectionIds')
                            group by s.subject_id
                            ";
                        $data = $this->db->query( $query )->result_array();
                        
                        $subject_ids = array();
                        foreach($data as $d){
                            $subject_ids[] = $d['subject_id'];
                        }
                        
                        
                        $implode_subjectIds = implode("','",$subject_ids);
                        $planner_arr =$this->db->query("select p.*
                            from ".get_school_db().".academic_planner p 
                            inner join ".get_school_db().".subject s on s.subject_id=p.subject_id
                            where p.subject_id IN('$implode_subjectIds') and
                            p.school_id=".$_SESSION['school_id']."
                            and (DATE(NOW()) = p.start) ")->result_array();

                       
                        if (count($planner_arr) >0 )
                        {
                            $acc_planner_dashboard_counter = 0;
                            foreach($planner_arr as $pr)
                            {
                                $acc_planner_dashboard_counter++;
                            ?>
                                <div class="col-lg-12">    
                                <div class="nds nshov" style="border-left:3px solid #00859a;padding-left:0px; background-color: #fbfbfb !important;">
                                    <div class="panel-group joined" id="accordion" style="margin-bottom:0px;">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a style="font-size:14px !important;" href="#collapse<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>" data-toggle="collapse" class="collapsed">
                                                        <strong><?php echo get_phrase('title');?>:</strong>
                                                        <span><?php echo $pr['title']; ?></span>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>" class="panel-collapse collapse" style="max-height:210px; min-height:auto; overflow-y:auto;">
                                                <div class="panel-body">
                                                  
                                                    <h3> <strong><?php echo get_phrase('required_time');?>: </strong>
                                                        <span><?php echo $pr['required_time']; ?></span>
                                                    </h3>
                                                    <h3>
                                                        <span>
                                                            <strong><?php echo get_phrase('Attachment');?>: </strong>
                                                            <?php if($pr['attachment']!=""){ ?>
                                                                <a href="<?php echo display_link($pr['attachment'],'academic_planner');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
                                                            <?php } ?>
                                                        </span>
                                                    </h3>
                                                    
                                                    <div class="inner-tabs">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                    
                                                    <li role="presentation" class="active"><a href="#detail<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>" aria-controls="settings" role="tab" data-toggle="tab"><?php echo get_phrase('detail');?></a></li>
                                                    <li role="presentation"><a href="#objectives<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>" aria-controls="profile" role="tab" data-toggle="tab"><?php echo get_phrase('objectives');?></a></li>
                                                    <li role="presentation"><a href="#assessment<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>" aria-controls="home" role="tab" data-toggle="tab"><?php echo get_phrase('assesment');?></a></li>
                                                    <li role="presentation"><a href="#requirement<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>" aria-controls="messages" role="tab" data-toggle="tab"><?php echo get_phrase('requirements');?></a></li>
                                                    
                                                    </ul>
                                                    
                                                    <!-- Tab panes -->
                                                    <div class="tab-content">
                                                    <div role="tabpanel" class="tab-pane active" id="detail<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>"><h3>
                                                                                                            <span><?php echo $pr['detail']; ?></span></h3></div>
                                                                                                            <div role="tabpanel" class="tab-pane" id="objectives<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>"><h3>
                                                                                                            <span><?php echo $pr['objective']; ?></span></h3></div>
                                                                                                            
                                                    <div role="tabpanel" class="tab-pane" id="assessment<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>"><h3>
                                                                                                            <span><?php echo $pr['assesment']; ?></span></h3></div>
                                                    
                                                    <div role="tabpanel" class="tab-pane" id="requirement<?php echo $pr['planner_id']; ?>_<?php echo $acc_planner_dashboard_counter ?>"><h3>
                                                                                                            <span><?php echo $pr['requirements']; ?></span></h3></div>
                                                    
                                                    </div>
                                                    
                                                    </div>
                                                        
                                                        
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            <?php 
                            }
                        }
                        else
                        {
                        ?>
                           <div class="text-center">
                            <i class="far fa-calendar-alt" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                            <h2><b><?php echo get_phrase('academic_planner_is_empty');?></b></h2>
                        </div>
                        <div class="text-center">
                            <a href="<?php echo base_url();?>teacher/academic_planner" style="color:black;"> <b><?php echo get_phrase('go_to_academic_planner');?> <i class="fas fa-long-arrow-alt-right"></i></b></a>
                        </div>
                        <?php
                        }
                        ?>
                        
                        </div>
                    </div>    
                </div>
                <div class="link">
                    <a href="<?php echo base_url(); ?>teacher/academic_planner" class="more"> Go To Academic Planner &gt;&gt; </a>
                </div>
            </div>
        </div>
    </div>
    <div id="circular_notice" class="tab-pane dash-m">
        
        <?php 
        $noticeboard = $this->db->query("select * from ".get_school_db().".noticeboard 
            where 
            is_active = 1 and 
            school_id=".$_SESSION['school_id']." LIMIT 3")->result_array();
        ?>
        <div class="row">
        	<div class="col-lg-12 col-md-12 col-sm-12 b-s p-4">
            	<div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i><?php echo get_phrase('latest_notices_and_circulars');?></h3>
                    </div>
                </div>
            	<div class="row">
                	<div class=" col-lg-6 col-md-6 col-sm-6  ">
                        <!-- Fluid width widget -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-bell-o" aria-hidden="true"></i> <?php echo get_phrase('notice_board');?>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <ul class="media-list" style="border:1px solid #DDD;">
                                    <?php foreach($noticeboard as $rt){?>
                                    <li class="media">
                                        <div class="media-left">
                                            <div class="panel text-center date">
                                                <div class="panel-heading month">
                                                    <span class="panel-title strong nc_month_heading">
                    <?php 
        
        
                    maketime($rt['create_timestamp'],'m');
                                                    
                                                    ?>
                                                </span>
                                                </div>
                                                <div class="panel-body day text-danger">
                                                    <?php 
                                                
                                                   
                maketime($rt['create_timestamp'],'d');
          
                ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <?php echo $rt['notice_title'];?>
                                            </h4>
                                            <p>
                                                <?php echo $rt['notice'];?>
                                            </p>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <a href="<?php echo base_url();?>teacher/noticeboard" class="more"><?php echo get_phrase('more');?> »</a>
                            </div>
                        </div>
                        <!-- End fluid width widget -->
                    </div>
                <?php 
                //$d_c_s_sec = get_teacher_dep_class_section($login_detail_id);
                //$time_table_t_sec = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
                $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
    
                
                /*$circular = $this->db->query("select c.circular_id, c.circular_title, c.circular, c.section_id, c.student_id, c.create_timestamp, c.attachment, cs.title as class_section, d.title as department, class.name as class_name
                    FROM ".get_school_db().".circular c 
                    inner join ".get_school_db().".yearly_terms yt on yt.yearly_terms_id = c.yearly_terms_id
                    inner join ".get_school_db().".acadmic_year ay on ay.academic_year_id = yt.academic_year_id
                    
                    inner join ".get_school_db().".class_section cs on cs.section_id = c.section_id
                    inner join ".get_school_db().".class on class.class_id = cs.class_id
                    inner join ".get_school_db().".departments d on class.departments_id = d.departments_id
                    where 
                    c.school_id=".$_SESSION['school_id']."
                    and ay.academic_year_id=".$_SESSION['academic_year_id']."
                    $filter
                    group by c.circular_id
                    order by create_timestamp desc
                    LIMIT 3
                    ")->result_array();*/

                 $circular = $this->db->query("select c.circular_id, c.circular_title, c.circular, c.section_id, c.student_id, c.create_timestamp, c.attachment, cs.title as class_section, d.title as department, class.name as class_name
                    FROM ".get_school_db().".circular c 
                    
                    inner join ".get_school_db().".class_section cs on cs.section_id = c.section_id
                    inner join ".get_school_db().".class on class.class_id = cs.class_id
                    inner join ".get_school_db().".departments d on class.departments_id = d.departments_id
                    where 
                    c.school_id=".$_SESSION['school_id']." and 
                    c.is_active = 1 
                    group by c.circular_id
                    order by create_timestamp desc
                    LIMIT 3
                    ")->result_array();
                ?>
                    <div class=" col-lg-6 col-md-6 col-sm-6  " id="circulars">
                        <!-- Fluid width widget -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <span class="glyphicon glyphicon-calendar"></span> <?php echo get_phrase('circular');?>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <ul class="media-list"  style="border:1px solid #DDD;">
                                    <?php foreach($circular as $rt){?>
                                    <li class="media">
                                        <div class="media-left">
                                            <div class="panel text-center date">
                                                <div class="panel-heading month nc_month_heading">
                                                    <span class="panel-title strong nc_month_heading">
                                                    <?php 
                                                    $date_array=explode('-',$rt['create_timestamp']);     
                                                    echo date('M',mktime(0, 0, 0, $date_array[1], $date_array[2], $date_array[0]));
                    //echo $rt['create_timestamp']?>
                                                </span>
                                                </div>
                                                <div class="panel-body day text-danger">
                                                    <?php echo date('d',mktime(0, 0, 0, $date_array[1], $date_array[2], $date_array[0]));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">
                                                <?php echo $rt['circular_title'];
                                            
                                                if($rt['attachment']!="")
                                                {?>
                                                    <a target="_blank" href="<?php echo display_link($rt['attachment'],'circular');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
                                                <?php   
                                                }
                                                ?>
                                            </h4>
                                            <?php if($rt['student_id']>0)
                                            { 
                                                $str_qur=$this->db->query("select name,school_id from ".get_school_db().".student where school_id=".$_SESSION['school_id']." and student_id=".$rt['student_id'])->result_array();
                                                ?>
                                                <p>
                                                    <?php echo $str_qur[0]['name'];?>
                                                </p>
                                            <?php 
                                            } ?>
                                            <p>
                                                <?php echo $rt['circular'];?>
                                            </p>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <a href="<?php echo base_url();?>teacher/circulars" class="more"><?php echo get_phrase('more');?> »</a>
                            </div>
                        </div>
                        <!-- End fluid width widget -->
                    </div>
        	</div>
            </div>
        </div>
    </div>
    <div id="exam_graph" class="tab-pane dash-m">
        <div class="row">
            <div class="col-sm-12 col-md-12 b-s p-4">
                <div class="box">
                    <div class="box-header with-border mgbb">
                        <h3 class="box-title"> <i class="fa fa-bar-chart" aria-hidden="true"></i><?php echo get_phrase('exam_graph');?> </h3>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-body with-table">
                            <div id="container" style="width:70%;">
                                </div>
                        <?php
                        //echo implode(',',$time_table_t_sec);
                        //print_r($time_table_t_sub);

                        $sub_in = 0;
                        if (count($time_table_t_sub)>0)
                            $sub_in = implode(',', $time_table_t_sub);
                        $sec_in = 0;
                        if (count($time_table_t_sec)>0)
                            $sec_in = implode(',', $time_table_t_sec);

                        $exam_arr = array();
                        $subject_arr = array();

                        $exam_result_arr = array();
                        $exam_routine_section_arr = array();
                        $sec_sub_assoc = ' case';
                        /*s.subject_id, group_concat(distinct section_id  SEPARATOR ',') as section_id*/
                        $section_subjects_arr = $this->db->query("select section_id , group_concat(distinct s.subject_id SEPARATOR ',') as subject_id
                            FROM 
                            ".get_school_db().".class_routine cr 
                                inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
                                inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
                                inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
                                inner join ".get_school_db().".subject s on st.subject_id=s.subject_id
                                inner join ".get_school_db().".staff on st.teacher_id=staff.staff_id
                                where 
                                staff.user_login_detail_id = $login_detail_id
                                and cr.school_id=".$_SESSION['school_id']."
                                group by section_id
                                ")->result_array();
                        
                        foreach ($section_subjects_arr as $ssa) 
                        {
                            $exam_result = $this->db->query("select e.exam_id, e.name as exam_name, er.section_id, er.subject_id, s.name as subject_name, s.code as subject_code, yt.title as term,c.name as class, cs.title as section_name
                                    from ".get_school_db().".exam e 
                                    inner join ".get_school_db().".exam_routine er on e.exam_id =  er.exam_id
                                    inner join ".get_school_db().".yearly_terms yt on e.yearly_terms_id = yt.yearly_terms_id  
                                    inner join ".get_school_db().".subject s on s.subject_id = er.subject_id 
                                    inner join ".get_school_db().".class_section cs on cs.section_id = er.section_id
                                    inner join ".get_school_db().".class c on c.class_id = cs.class_id
                                    
                                    where 
                                    er.section_id in (".$ssa['section_id'].")
                                    and er.subject_id in (".$ssa['subject_id'].")
                                    and yt.academic_year_id = ".$_SESSION['academic_year_id']." 
                                    and e.school_id=".$_SESSION['school_id']."
                                    and er.is_approved=1
                                    
                                ")->result_array();
                            if (count($exam_result) > 0)
                            { 
                                foreach ($exam_result as $value) 
                                {
                                    $exam_result_arr[$value['exam_id']] = $value['exam_name'];
                                    
                                    $exam_routine_section_arr[$value['exam_id']][] = $value['section_id'];
                                    $compArr=$this->db->query("select subject_component_id,sc.subject_id,percentage 
                                                from ".get_school_db().".subject_components sc
                                                where 
                                                sc.subject_id=".$value['subject_id']." 
                                                and sc.school_id=".$_SESSION['school_id']."
                                                ")->result_array();
                                    if(sizeof($compArr)>0)
                                    {
                                        $sum=0;
                                        $avg = 0;
                                        foreach($compArr as $comp)
                                        {
                                            $graph_arr = $this->db->query("select marks_obtained 
                                            from ".get_school_db().".marks m 
                                            inner join ".get_school_db().".marks_components mc on m.marks_id = mc.marks_id
                                            inner join ".get_school_db().".student stdnt on stdnt.student_id = m.student_id
                                            where 
                                            mc.subject_component_id=".$comp['subject_component_id']."
                                            and m.subject_id = ".$value['subject_id']."
                                            and m.exam_id = ".$value['exam_id']."
                                            and m.school_id = ".$_SESSION['school_id']."
                                            and stdnt.section_id=".$value['section_id']." 
                                            and stdnt.academic_year_id=".$_SESSION['academic_year_id']." 
                                            and stdnt.student_status in (".student_query_status().")
                                            ")->result_array();

                                            $count = 0;
                                            $sum = 0;
                                            foreach ($graph_arr as $com_inner) 
                                            {
                                                $count++;
                                                $sum+=$com_inner['marks_obtained'];
                                            }
                                            $avg += $sum/$count;
                                        }


                                        $subject_arr[$value['section_id']][$value['subject_id']]['title'] = $value['subject_name'].' - '.$value['subject_code'].' '.$value['class'].' '.$value['section_name'];

                                        $subject_arr[$value['section_id']][$value['subject_id']]['marks'][$value['exam_id']] = $avg;
                                    }
                                    else
                                    {
                                        $graph_arr = $this->db->query("select avg(marks_obtained) as avg_marks
                                            from ".get_school_db().".marks m 
                                            inner join ".get_school_db().".marks_components mc on m.marks_id = mc.marks_id
                                            inner join ".get_school_db().".student stdnt on stdnt.student_id = m.student_id
                                            where 
                                            m.subject_id = ".$value['subject_id']."
                                            and m.exam_id = ".$value['exam_id']."
                                            and m.school_id = ".$_SESSION['school_id']."
                                            and stdnt.section_id=".$value['section_id']." 
                                            and stdnt.academic_year_id=".$_SESSION['academic_year_id']." 
                                            and stdnt.student_status in (".student_query_status().")
                                            
                                            ")->result_array();

                                        foreach ($graph_arr as $key => $inner) 
                                        {
                                            $exam_arr[$value['exam_id']] = $value['term'].' - '.$value['exam_name'];

                                            $subject_arr[$value['section_id']][$value['subject_id']]['title'] = $value['subject_name'].' - '.$value['subject_code'].' '.$value['class'].' '.$value['section_name'];

                                            $subject_arr[$value['section_id']][$value['subject_id']]['marks'][$value['exam_id']] = $inner['avg_marks'];
                                        }
                                    }
                                }
                            }
                        }   
                        ?>
                        </div>
                    </div>
                </div>
                <script>
                    $(function() {
                        $('#container').highcharts({
                            title: {
                                text: '',
                                x: -20 //center
                            },
                            subtitle: {
                                text: "<?php echo get_phrase('exam_result');?>",
                                x: -20
                            },
                            xAxis: {
                                categories: [<?php echo "'".implode("','", $exam_arr)."'"; ?>]//, 'Final Term'
                            },
                            yAxis: {
                                title: {
                                    text: '<?php echo get_phrase("marks_obtained");?>'
                                },
                                plotLines: [{
                                    value: 0,
                                    width: 1,
                                    color: '#808080'
                                }]
                            },
                            tooltip: {
                                valueSuffix: '<?php echo get_phrase("marks");?>'
                            },
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                borderWidth: 0
                            },
                            series: [
                            <?php
                              foreach ($subject_arr as $sub_outer) 
                                {
                                    foreach ($sub_outer as $sub_inner) 
                                    {?>
                                        {
                                            name: "<?php echo $sub_inner['title']; ?>",
                                            data: [<?php echo implode(',',$sub_inner['marks']); ?>]
                                        },
                                    <?php 
                                    }
                                } ?>
                            ]
                        });
                    });
                        
                    </script>

            <script src="<?php echo base_url();?>assets/js/highcharts.js"></script>
            </div>
        </div>
    </div>
    <?php } ?>
    
    
    
</div>
<script>
    function show_modal(id){
		jQuery('#'+id).modal('show', {backdrop: 'static'});
	}
	
    function goAndClose(id){
		jQuery('#'+id).modal('hide');
	}
	
    var auto_refresh = setInterval(
        function () {
            location.reload();
        }, 300000);
</script>

<!-- One Signal code -->
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js"></script>
<script>

    var OneSignal = OneSignal || [];
    
    OneSignal.push(["init", {
      appId: "c0c462a2-13b1-4dc9-a93c-d45b65c10b55",
      notificationClickHandlerAction: 'focus'
    }]);
    
    OneSignal.push(function() {
        var isPushSupported = OneSignal.isPushNotificationsSupported();
        if (isPushSupported) {
            getuserID();
        }
    });

    function getuserID()
    {
        var user_id   = '<?php echo $_SESSION['user_id']; ?>';
        var user_type = '<?php echo $_SESSION['login_type'] ?>';

        OneSignal.isPushNotificationsEnabled(function(isEnabled) {
            if (isEnabled){
                console.log("Push notifications are enabled!");
                OneSignal.getUserId(function(userId) {
                    $.ajax({
                        url:"<?php echo base_url(); ?>notifications/set_firebase_data",
                        type:"post",
                        data:{device_id:userId,user_id:user_id,user_type:user_type,platform:'WEB'},
                        success:function (response) {
                          
                            console.log(response);
                            
                        }
                    });
                });
            }
            else{
                console.log("Push notifications are not enabled yet.");
                OneSignal.push(function() {
                    OneSignal.showHttpPrompt();
                });
            }
        });
        
    }

</script>