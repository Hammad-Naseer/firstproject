<div>
</div>
<!--<div class="sidebar-menu">-->
		<div class="sidebar-menu-inner tcher-sidebar">
			<header class="logo-env">
				<!-- logo -->
				<div class="logo text-center py-4">
					<a href="<?= base_url().'teacher/dashboard'; ?>">
						<?php				
                			$logo= system_path($_SESSION['school_logo']);
                			if($_SESSION['school_logo']=="" || !is_file($logo)) { ?>
                				<img class="row__poster" src="<?php echo base_url();?>assets/images/gsims_logo.png">
                			<?php }else {
                				$img_size = getimagesize("uploads/".$_SESSION['folder_name']."/".$_SESSION['school_logo']."");
                				$img_width = $img_size[0];
                				$img_height = $img_size[1];
                			?>
                			    <img class="row__poster" src="<?php echo base_url();?>uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>">
                		<?php } ?>
					</a>
				</div>
			</header>
			<div class="sidebar-user-info">
				<div class="sui-normal">
					<a href="#" class="user-link">
					    <?php if($res[0]['profile_pic']=="") { ?>
    					<img src="<?php echo get_default_pic()?>" alt="" class="img-circle notify_icon123" width="55" alt="" class="img-circle"> 
    					<?php } else {?>
    					<img src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1)?>" alt="" class="img-circle notify_icon123" width="55" alt="" class="img-circle">
    					<?php } ?>
						<!--<img src="https://demo.neontheme.com/assets/images/thumb-1@2x.png" width="55" alt="" class="img-circle" />-->
						<span>Welcome,</span>
						<strong><?php echo $res[0]['name'];?></strong>
					</a>
				</div>
				<div class="sui-hover inline-links animate-in"><!-- You can remove "inline-links" class to make links appear vertically, class "animate-in" will make A elements animateable when click on user profile -->
					<a href="<?=base_url()?>profile/manage_profile">
						<i class="entypo-pencil"></i>
						Profile
					</a>
                    
                    <?php if (isset($_SESSION['multiple_accounts']) && $_SESSION['multiple_accounts'] == 1) { ?>
					<a href="<?php echo base_url();?>switch_user/account_list">
						<i class="entypo-chat"></i>
						Switch
					</a>
					<?php } ?>

					<a href="<?php echo base_url();?>login/logout">
						<i class="entypo-lock"></i>
						Log Off
					</a>

					<span class="close-sui-popup">&times;</span><!-- this is mandatory -->				</div>
			</div>						
			<ul id="main-menu" class="main-menu">
				<!--Code Written By Zeeshan Arain-->
                <!-- DASHBOARD -->
            <li class="<?php if($page_name == 'dashboard') echo 'active'; ?> ">
                <a href="<?php echo base_url();?><?php echo $account_type; ?>/dashboard">
                    <!-- <i class="entypo-gauge"></i>-->
                    <i class="fas fa-tachometer-alt" style="font-size:25px;"></i>
                    <span><?php echo get_phrase('dashboard');?></span>
                </a>
            </li>
        <?php if (in_array("t_school_policies", $assigned_access_rights) ){ ?>
            <li class="<?php if($page_name == 'policies_listing' || $page_name == 'policies_add_edit' )echo 'active';?> ">
                <a href="<?php echo base_url();?>teacher/policies_listing">
                    <i class="fas fa-table"></i>
                    <span><?php echo get_phrase('school_policies');?></span>
                </a>
	        </li>
	    <?php } ?>
        
        <?php
        // if (in_array("t_school_policies", $assigned_access_rights) ){ remaoning action
        ?>
            <li class="<?php if($page_name == 'subjects' || $page_name == 'subjects') echo 'active';?> ">
                <a href="<?php echo base_url();?>teacher/subjects">
                    <i class="fas fa-book"></i>
                    <span><?php echo get_phrase('subjects');?></span>
                </a>
	        </li>
	    <?php
        // }
        ?>
       
        <?php
        if (in_array("t_timetable", $assigned_access_rights) ){
        ?>
        <li class="<?php if($page_name == 'class_routine' || $page_name == 'my_class_routine' ) echo 'opened active';?> ">
            <a href="#">
               <i class="fas fa-calendar-alt" style="font-size:25px;"></i>
                <span><?php echo get_phrase('timetable');?></span>
            </a>
            <ul>
                <?php if (in_array("t_my_timetable", $assigned_access_rights) ){ ?>
                    <li class="<?php if ($page_name == 'my_class_routine') echo 'active';?>">
                        <a href="<?php echo base_url();?><?php echo $account_type;?>/my_class_routine">
                            <span><i class="fas fa-user-clock"></i>
                            <?php echo get_phrase('my_timetable');?></span>
                        </a>
                    </li>
                <?php
                }
                if (in_array("t_class_wise_timetable", $assigned_access_rights) ){
                ?>
                    <li class="<?php if ($page_name == 'class_routine') echo 'active';?>">
                        <a href="<?php echo base_url();?><?php echo $account_type;?>/class_routine">
                           <span><i class="fas fa-calendar-alt"></i>
                            <?php echo get_phrase('class_wise_timetable');?></span>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </li>
        <?php
        }
        ?>
 
        <?php
        if (in_array("t_attendance", $assigned_access_rights) ){
        ?>
        <li class="<?php if($page_name == 'manage_attendance' ||$page_name == 'manage_attendence_student' || $page_name=='manage_subjectwise_attendance' || $page_name == 'teacher_attendance_summary' || 
        $page_name == 'student_attendance_summary' || $page_name=='student_subjectwise_attendance' || $page_name=='view_student_attendance' )echo 'active opened';?> ">
            <a href="<?php echo base_url();?><?php echo $account_type;?>/manage_attendance2/<?php echo date(" d/m/Y ");?>">
               <i class="fas fa-user-check"></i>
               <span><?php echo get_phrase('attendance');?></span>
            </a>
            <ul>
                <?php
                if (in_array("t_my_attendancet_my_attendance", $assigned_access_rights) ){
                ?> 
                <li class="<?php if($page_name == 'manage_attendance')echo 'active';?> ">
                    <a href="<?php echo base_url();?><?php echo $account_type;?>/manage_attendance">
                        <span>
                        <span class="mynavicon"><i class="fas fa-clipboard-list"></i></span>
                          <?php echo get_phrase('my_attendance');?></span>
                    </a>
                </li>
                
                <?php
                }
                if (in_array("t_teacher_attendance_summary", $assigned_access_rights) ){
                ?>
                <li class="<?php if($page_name == 'teacher_attendance_summary')echo 'active';?> ">
                    <a href="<?php echo base_url();?><?php echo $account_type;?>/teacher_summary">
                        <span>
                        <span class="mynavicon"><i class="fas fa-clipboard-check"></i></span>
                          <?php echo get_phrase('My_attendance_summary');?></span>
                    </a>
                </li>
                
                 <li class="<?php if($page_name=='view_student_attendance')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>teacher/view_stud_attendance">
                <i class="far fa-eye">
                </i> 
                <span>
                  <?php echo get_phrase('view_student_attendance'); ?>
                </span>
              </a>
            </li>
                <?php
                }
                if (in_array("t_daily_student_attendance", $assigned_access_rights) ){
                    $attendance_method = get_attendance_method();
                    $attendance_url = "";
                    $text_att = "";
                    $report_view = "";
                    if($attendance_method == 1){
                        $attendance_url = "manage_attendance_student";
                        $text_att = "Mark Student Attendance";
                    }elseif($attendance_method == 2){
                        $attendance_url = "manage_subjectwise_attendance";
                        $text_att = "Mark Subjectwise Attendance";
                        $report_view = "View Subjectwise Attendance";
                    }else{
                        $attendance_url = "manage_attendance_student"; //default
                        $text_att = "Mark Student Attendance";
                    }
                ?>
                <li class="<?php if($page_name == 'manage_attendence_student' || $page_name=='manage_subjectwise_attendance')echo 'active';?> ">
                    <a href="<?php echo base_url();?>teacher/<?php echo $attendance_url; ?>">
                       <span class="mynavicon"><i class="fas fa-file-signature"></i></span>
                       <span><?php echo $text_att;?></span>
                    </a>
                </li>
                <?php if($attendance_method == 2){ ?>
                <li class="<?php if($page_name=='view_subjectwise_attendance' || $page_name=='student_subjectwise_attendance' )echo 'active'; ?>">
                  <a href="<?php echo base_url()."teacher/view_subjectwise_attendance"; ?>">
                    <i class="fas fa-file-signature">
                    </i> 
                    <span>
                      <?php echo $report_view; ?>
                    </span>
                  </a>
                </li> 
                <?php } ?>
                <?php
                }
                if (in_array("t_student_attendance_summary", $assigned_access_rights) ){
                ?>
                <li class="<?php if($page_name == 'student_attendance_summary')echo 'active';?> ">
                    <a href="<?php echo base_url();?>teacher/student_summary">
                        <span>
                        <span class="mynavicon"><i class="fas fa-clipboard-check"></i></span>
                          <?php echo get_phrase('student_attendance_summary');?></span>
                    </a>
                </li> 
                <?php
                }
                ?>
            </ul>
        </li>
        <?php
        }
        ?>
        
        <!-- DAILY DIARY -->
        <?php
        if (in_array("t_manage_diary", $assigned_access_rights) ){
        ?>
           <li class="<?php if($page_name == 'diary')echo 'active';?> ">
            <a href="<?php echo base_url();?><?php echo $account_type;?>/diary">
              <!--  <i class="fa fa-book" aria-hidden="true"></i>-->
               <i class="fas fa-book"></i>
                <span><?php echo get_phrase('manage_diary');?></span>
            </a>
        </li>
        <?php
        }
        ?>
        
        <!-- Academic Planner -->
        <?php
        if (in_array("t_academic_planner", $assigned_access_rights) ){
        ?>
            <li class="<?php if($page_name == 'academic_planner')echo 'active';?> ">
                <a href="<?php echo base_url();?>teacher/academic_planner">
                    <i class="far fa-calendar-alt"></i>
                    <span><?php echo get_phrase('academic_planner');?></span>
                </a>
            </li>
        <?php
        }
        ?>
        
        <!-- Leave Requests -->
        <?php
        if (in_array("t_leave_request", $assigned_access_rights) ){
        ?>
            <li class="<?php if($page_name == 'leave_request')echo 'active';?> ">
                <a href="<?php echo base_url();?><?php echo $account_type;?>/manage_leaves">
                    <i class="fas fa-user-minus"></i>
                    <span><?php echo get_phrase('leave_request');?></span>
                </a>
            </li>
        <?php
        }
        ?>
        
        <!-- EXAMS -->
        <?php
        if (in_array("t_exams", $assigned_access_rights) ){
        ?>
        <li class="<?php if($page_name == 'exam' || $page_name == 'exam_routine' || $page_name == 'marks')echo 'opened active';?> ">
            <a href="#">
                <i class="fas fa-poll-h"></i>
                <span><?php echo get_phrase('exams');?></span>
            </a>
            <ul>
                <?php
                  if (in_array("t_manage_marks", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'marks')echo 'active';?> ">
                        <a href="<?php echo base_url();?><?php echo $account_type;?>/marks">
                            <span>
                            <span class="mynavicon"><i class="fas fa-poll"></i></span>
                            <?php echo get_phrase('manage_marks');?></span>
                        </a>
                    </li>
                <?php
                  }
                  if (in_array("t_datesheet", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'exam_routine')echo 'active';?> ">
                        <a href="<?php echo base_url();?>teacher/exam_routine">
                            <span>
                            <span class="mynavicon"><i class="fas fa-receipt"></i></span>
                            <?php echo get_phrase('datesheet');?></span>
                        </a>
                    </li>
                <?php
                  }
                ?>
            </ul>
        </li>
        <?php }
        
        if(in_array('t_student_evaluation', $assigned_access_rights)){ ?>
            <li class="<?php if($page_name=='stud_evaluation'||$page_name=='stud_evaluation_form'||$page_name=='modal_view_stud_evaluation')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>student_evaluation/stud_eval/">
                <i class="far fa-star">
                </i> 
                <span>
                  <?php echo get_phrase('student_evaluation'); ?>
                </span>
              </a>
            </li>
        <?php
        }
        ?>
        
        <!-- Assessment -->
        <?php
        if (in_array("t_manage_assessments", $assigned_access_rights) ){
        ?>
        <li class="<?php if($page_name == 'assessment/create_assessment' || $page_name == 'assessment/view_assessment' || $page_name == 'assessment/assign_assessment' 
        || $page_name == 'assessment/assessment_details' || $page_name == 'assessment/view_assessment_result' || $page_name == 'assessment/edit' 
        || $page_name == 'assessment/view_assessment_readonly_form'  || $page_name == 'assessment/edit_assessment_details' 
        || $page_name == 'assessment/view_submitted_assessments' || $page_name == 'assessment/submitted_assessment' || $page_name == 'qb_assessment/qb_assessment_create'  )echo 'opened active';?> ">
            <a href="#">
                <i class="las la-tasks"></i>
                <span><?php echo get_phrase('online_assessments');?></span>
            </a>
            <ul>
                <?php
                if (in_array("t_create_assessments", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'assessment/create_assessment')echo 'active';?> ">
                        <a href="<?php echo base_url();?>assessment/create_assessment">
                            <span>
                            <span class="mynavicon"><i class="far fa-edit"></i></span>
                               <?php echo get_phrase('create_assessment');?></span>
                        </a>
                    </li>
                    <li class="<?php if($page_name == 'qb_assessment/qb_assessment_create')echo 'active';?> ">
                        <a href="<?php echo base_url();?>question_bank/create_question_bank">
                            <span>
                            <span class="mynavicon"><i class="far fa-edit"></i></span>
                               <?php echo get_phrase('create_QB_assessment');?></span>
                        </a>
                    </li>

                <?php
                }
                if (in_array("t_view_assessments", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'assessment/view_assessment' || $page_name == 'assessment/assign_assessment' || $page_name == 'assessment/edit' || $page_name == 'assessment/view_assessment_readonly_form' || $page_name == 'assessment/edit_assessment_details' || $page_name == 'assessment/view_submitted_assessments' )echo 'active';?> ">
                        <a href="<?php echo base_url();?>assessment/view_assessment">
                            <span>
                            <span class="mynavicon"><i class="las la-clipboard-list"></i></span>
                            <?php echo get_phrase('view_assessments');?></span>
                        </a>
                    </li>
                <?php
                }
                if (in_array("t_view_result", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'assessment/view_assessment_result')echo 'active';?> ">
                        <a href="<?php echo base_url();?>assessment/view_assessment_result">
                            <span>
                            <span class="mynavicon"><i class="las la-clipboard-list"></i></span>
                            <?php echo get_phrase('view_result');?></span>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </li>
        <?php
        }
        ?>
        
        <!-- QB Assessment - Zeeshan Arain -->
        <?php
        if (in_array("t_manage_assessments", $assigned_access_rights) ){
        ?>
        <!-- 
        <li class="<?php if($page_name == 'question_bank/create_question_bank' || $page_name == 'assessment/view_assessment' || $page_name == 'assessment/assessment_details' || $page_name == 'assessment/view_assessment_result')echo 'opened active';?> ">
            <a href="#">
                <i class="las la-tasks"></i>
                <span><?php echo get_phrase('question_bank');?></span>
            </a>
            <ul>
                <?php
                if (in_array("t_create_assessments", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'question_bank/create_question_bank')echo 'active';?> ">
                        <a href="<?php echo base_url();?>question_bank/create_question_bank">
                            <span>
                            <span class="mynavicon"><i class="far fa-edit"></i></span>
                               <?php echo get_phrase('create_QB_assessment');?></span>
                        </a>
                    </li>
                <?php
                }
                if (in_array("t_view_assessments", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'question_bank/qb_view_assessment')echo 'active';?> ">
                        <a href="<?php echo base_url();?>question_bank/qb_view_assessment">
                            <span>
                                <span class="mynavicon"><i class="las la-clipboard-list"></i></span><?php echo get_phrase('view_QB_assessments');?>
                            </span>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </li>
        -->
        <?php
        }
        ?>
        
        <!-- Lecture Notes -->
        <?php
        if (in_array("t_manage_notes", $assigned_access_rights) ){
        ?>
        <li class="<?php if($page_name == 'notes/create_notes' || $page_name == 'notes/view_notes' || $page_name == 'notes/view_notes_details' ||  $page_name == 'notes/assign_notes'  ||  $page_name == 'notes/notes_details')echo 'opened active';?> ">
            <a href="#">
                <i class="las la-tasks"></i>
                <span><?php echo get_phrase('manage_notes');?></span>
            </a>
            <ul>
                <?php
                if (in_array("t_create_notes", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'notes/create_notes')echo 'active';?> ">
                        <a href="<?php echo base_url();?>notes/create_notes">
                            <span>
                            <span class="mynavicon"><i class="far fa-edit"></i></span>
                               <?php echo get_phrase('create_notes');?></span>
                        </a>
                    </li>
                <?php
                }
                if (in_array("t_view_notes", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'notes/view_notes' ||  $page_name == 'notes/assign_notes' ||  $page_name == 'notes/notes_details')echo 'active';?> ">
                        <a href="<?php echo base_url();?>notes/view_notes">
                            <span>
                            <span class="mynavicon"><i class="las la-clipboard-list"></i></span>
                            <?php echo get_phrase('view_notes');?></span>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </li>
        <?php
        }
        ?>

        <?php
        if (in_array("t_notices_and_circulars", $assigned_access_rights) ){
        ?>
        <li class="<?php if($page_name == 'noticeboard' || $page_name == 'circulars' || $page_name=='staff_circulars')echo 'opened active';?> ">
            <a href="#">
                <i class="fas fa-bullhorn"></i>
                <span><?php echo get_phrase('notices_and_circulars');?></span>
            </a>
            <ul>
                <?php
                if (in_array("t_noticeboard", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'noticeboard')echo 'active';?> ">
                        <a href="<?php echo base_url();?><?php echo $account_type;?>/noticeboard">
                           <span class="mynavicon"><i class="fas fa-chalkboard"></i></span>
                            <span><?php echo get_phrase('noticeboard');?></span>
                        </a>
                    </li>
                <?php
                }
                if (in_array("t_circulars", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'circulars')echo 'active';?> ">
                        <a href="<?php echo base_url();?><?php echo $account_type;?>/circulars">
                           <span class="mynavicon"><i class="fas fa-bullseye"></i></span>
                            <span><?php echo get_phrase('circulars');?></span>
                        </a>
                    </li>
                <?php
                }
                if (in_array("t_staff_circulars", $assigned_access_rights) ){
                ?>
                    <li class="<?php if($page_name == 'staff_circulars')echo 'active';?> ">
                        <a href="<?php echo base_url();?><?php echo $account_type;?>/staff_circular">
                           <span class="mynavicon"><i class="fas fa-bullseye"></i></span>
                            <span><?php echo get_phrase('staff_circulars');?></span>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </li>
        <?php
        }
        ?>
        
        <?php
        if (in_array("t_view_message", $assigned_access_rights) ){
        ?>
            <li class="<?php if($page_name == 'student_listing')echo 'active';?> ">
                <a href="<?php echo base_url();?><?php echo $account_type;?>/student_list">
                    <i class="fas fa-sms"></i>
                    <span><?php echo get_phrase('view');?> / <?php echo get_phrase('send_message');?></span>
                </a>
            </li>
        <?php
        }
        ?>
        <?php
        if (in_array("t_view_message", $assigned_access_rights) ){
        ?>
            <li class="<?php if($page_name == 'student_listing')echo 'active';?> ">
                <a href="<?php echo base_url();?><?php echo $account_type;?>/student_chat_list">
                    <i class="fas fa-sms"></i>
                    <span><?php echo get_phrase('Teacher');?> / <?php echo get_phrase('chat_message');?></span>
                </a>
            </li>
        <?php
        }
        ?>
        
      
        
        <?php
        if (in_array("t_my_profile", $assigned_access_rights) ){
        ?>
            <li class="<?php if($page_name == 'manage_profile')echo 'active';?> ">
                <a href="<?php echo base_url();?>teacher/manage_profile">
                   <i class="far fa-user-circle"></i>
                    <span><?php echo get_phrase('my_profile');?></span>
                </a>
            </li>
        <?php
        }
        ?>
			</ul>
			
		</div>

	<!--</div>-->



