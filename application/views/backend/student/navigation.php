<div>
</div>
<div class="sidebar-menu-inner">
	<header class="logo-env">
		<!-- logo -->
		<div class="logo text-center py-4">
			<a href="<?=base_url()?>">
				<?php				
				    $logo=system_path($_SESSION['school_logo']);
        			if($_SESSION['school_logo'] =="" || !is_file($logo)) { ?>
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
		<!-- logo collapse icon -->
		
		<!--<div class="sidebar-mobile-menu visible-xs">-->
		<!--	<a href="#" class="with-animation">-->
		<!--		<i class="entypo-menu"></i>-->
		<!--	</a>-->
		<!--</div>-->
	</header>
	<div class="sidebar-user-info">
		<div class="sui-normal">
			<a href="#" class="user-link py-4">
			    <?php if($res[0]['profile_pic']=="") { ?>
				<img src="<?php echo get_default_pic() ?>" alt="" class="img-circle notify_icon123" width="55" alt="" class="img-circle"> 
				<?php } else {?>
				<img src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1)?>" alt="" class="img-circle notify_icon123" width="55" alt="" class="img-circle">
				<?php } ?>
				<span>Welcome, </span>
				<strong><?php echo $res[0]['name'];?></strong>
			</a>
		</div>
		<div class="sui-hover inline-links animate-in"><!-- You can remove "inline-links" class to make links appear vertically, class "animate-in" will make A elements animateable when click on user profile -->
			<a href="<?=base_url()?>profile/manage_profile">
				<i class="entypo-pencil"></i>
				Profile
			</a>
            <?php 
                if (isset($_SESSION['multiple_accounts']) && $_SESSION['multiple_accounts'] == 1) { ?>
			        <a href="<?php echo base_url();?>switch_user/account_list">
				    <i class="entypo-chat"></i>
				        Switch
			        </a>
			<?php } ?>
			<a href="<?php echo base_url();?>login/logout">
				<i class="entypo-lock"></i>
				Log Off
			</a>
			<span class="close-sui-popup">&times;</span>
	    </div>
	</div>
	<div style="text-align: center;color: white;border: 2px solid #f7f7f7;border-radius: 10px;padding: 5px;margin-bottom: 5px;">
	    <?php
	    echo $_SESSION['department_name']."-".$_SESSION['class_name']."-".$_SESSION['section_name'];
	    ?>
	</div>
	
	<ul id="main-menu" class="main-menu">
        <!-- DASHBOARD -->
        <li class="<?php if($page_name == 'dashboard')echo 'active';?> ">
				<a href="<?php echo base_url();?>student_p/dashboard">
					<i class="fas fa-tachometer-alt"></i>
					<span><?php echo get_phrase('dashboard');?></span>
				</a>
           </li>
           
          <?php if (in_array("s_school_policies", $assigned_access_rights)){ ?>
            <li class="<?php if($page_name == 'policies_listing' || $page_name == 'policies_add_edit' )echo 'active';?> ">
                <a href="<?php echo base_url();?>student_p/policies_listing">
                    <i class="fas fa-vote-yea"></i>
                    <span><?php echo get_phrase('school_policies');?></span>
                </a>
            </li>
        <?php //if (in_array("p_school_policies", $assigned_access_rights) ){ Remaining Action ?>
        <li class="<?php if($page_name == 'subjects')echo 'active';?> ">
            <a href="<?php echo base_url();?>student_p/subjects">
                <i class="fas fa-vote-yea"></i>
                <span><?php echo get_phrase('subjects');?></span>
            </a>
        </li>
        <?php
          //}
        ?>    
	     <?php } if (in_array("s_class_diary", $assigned_access_rights)){ ?>
            <li class="<?php if($page_name == 'diary')echo 'active';?> ">
				<a href="<?php echo base_url();?>student_p/diary">
					<i class="fas fa-book"></i>
					<span><?php echo get_phrase('class_diary');?></span>
				</a>
           </li>
         <?php } if (in_array("s_notices_and_circulars", $assigned_access_rights)){ ?>
            <!-- NOTICEBOARD -->
           <li class="<?php if($page_name == 'noticeboard' || $page_name == 'circulars')echo 'opened active';?> ">
				<a href="#">
					<i class="fas fa-bullhorn"></i>
					<span><?php echo get_phrase('notices_and_circulars');?></span>
				</a>
              <ul>
                  <?php if (in_array("s_noticeboard", $assigned_access_rights)){ ?>
                    <li class="<?php if($page_name == 'noticeboard')echo 'active';?> ">
                    <a href="<?php echo base_url();?>student_p/noticeboard">
                    <i class="fas fa-chalkboard"></i>
                    <span><?php echo get_phrase('noticeboard');?></span>
                    </a>
                    </li>
                  <?php } if (in_array("s_circulars", $assigned_access_rights)){ ?>    
                    <li class="<?php if($page_name == 'circulars')echo 'active';?> ">
                    <a href="<?php echo base_url();?>student_p/circulars">
                    <i class="fas fa-bullseye"></i>
                    <span><?php echo get_phrase('circulars');?></span>
                    </a>
                    </li>
                    <?php } ?>

              </ul>
           </li>
           <?php 
                } if (in_array("s_attendance", $assigned_access_rights)){ 
                $attendance_method = get_attendance_method();
                $attendance_url = "";
                if($attendance_method == 1){
                    $attendance_url = "student_p/manage_attendance";
                    $text_att = "Mark Attendance";
                }elseif($attendance_method == 2){
                    $attendance_url = "student_p/view_subjectwise_attendance";
                }else{
                    $attendance_url = "student_p/manage_attendance"; //default
                }
           ?>
            <!-- daily attendane -->
		   <li class="<?php if($page_name == 'manage_attendance' || $page_name == 'summary' || $page_name == 'view_subjectwise_attendance')echo 'opened active';?> ">
				<a href="#">
					<!--<i class="entypo-doc-text-inv"></i>-->
					<i class="fas fa-user-check"></i>
					<span><?php echo get_phrase('Attendance');?></span>
				</a>
              <ul>
               <?php if (in_array("s_details", $assigned_access_rights)){ ?>
               <li class="<?php if($page_name == 'daily attandane' || $page_name == 'manage_attendance' )echo 'active';?> ">
                    <a href="<?php echo base_url().'/'.$attendance_url;?>">
                    	<i class="fas fa-clipboard-list"></i>
					    <span><?php echo get_phrase('daily_attendance_summary');?></span>
				    </a>
                </li>
                <?php } if (in_array("s_attendance_summary", $assigned_access_rights)){ ?>
                    <li class="<?php if($page_name == 'summary')echo 'active';?> ">
                    <a href="<?php echo base_url();?>student_p/summary">
                    <i class="fas fa-clipboard-check"></i>
                    <span><?php echo get_phrase('monthly_attendance_summary');?></span>
                    </a>
                    </li>
                <?php } ?>
              </ul>
           </li>
           <?php } if (in_array("s_time_table", $assigned_access_rights)){ ?>
           <li class="<?php if($page_name == 'class_routine')echo 'active';?> ">
				<a href="<?php echo base_url();?>student_p/class_routine">
				    <i class="fas fa-table"></i>
					<span><?php echo get_phrase('time_table');?></span>
				</a>
           </li>
           <?php } if (in_array("s_exams", $assigned_access_rights)){ ?>
           <!-- EXAMS -->
           <li class="<?php if($page_name == 'exam'  || $page_name == 'exam_routine' || $page_name =='marks')echo 'opened active';?> ">
				<a href="#">
					<i class="fas fa-poll-h"></i>
					<span><?php echo get_phrase('exams');?></span>
				</a>
                <ul>
                <?php if (in_array("s_datesheet", $assigned_access_rights)){ ?>
					<li class="<?php if($page_name == 'exam_routine')echo 'active';?> ">
						<a href="<?php echo base_url();?>student_p/exam_routine">
							 <i class="far fa-file-alt"></i>
							 <span><?php echo get_phrase('date_sheet');?></span>
						</a>
					</li>
				<?php } if (in_array("s_examination_result", $assigned_access_rights)){ ?>	
					<li class="<?php if($page_name == 'marks')echo 'active';?> ">
						<a href="<?php echo base_url();?>student_p/marks">
							 <i class="fas fa-poll"></i>
							 <span><?php echo get_phrase('examination_result');?></span>
						</a>
					</li>
                <?php } ?>    
                </ul>
           </li>
        <?php } if (in_array("s_manage_assessments", $assigned_access_rights)){ ?>
           <!-- Asssessment -->
        <li class="<?php if($page_name == 'assessment/view_assessment' || $page_name == 'assessment/assessment_details' || $page_name == 'assessment/assessment_result' || $page_name == 'assessment/result_details'  )echo 'opened active';?> ">
            <a href="#">
                <i class="las la-tasks"></i>
                <span><?php echo get_phrase('online_assessments');?></span>
            </a>
            <ul>
                <?php if (in_array("s_view_assessments", $assigned_access_rights)){ ?>
                <li class="<?php if($page_name == 'assessment/view_assessment')echo 'active';?> ">
                    <a href="<?php echo base_url();?>assessment_student/view_assessment">
                        <span>
                        <!--<i class="entypo-dot"></i> -->
                        <i class="las la-clipboard-list"></i>
                        <span><?php echo get_phrase('view_assessments');?></span>
                    </a>
                </li>
                <?php } if (in_array("s_assessment_result", $assigned_access_rights)){ ?>
                <li class="<?php if($page_name == 'assessment/assessment_result' || $page_name == 'assessment/result_details')echo 'active';?> ">
                    <a href="<?php echo base_url();?>assessment_student/assessment_result">
                        <i class="fas fa-file-invoice"></i>
                        <?php echo get_phrase('assessment_result');?></span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </li>
        <?php //if(right_granted('manage_payroll')){ Remaining ?>
    <li class="<?php if($page_name=='student_p/books' || $page_name=='library/book_issue' || $page_name == 'library/members')echo 'opened active'; ?>">
      <a href="#">
        <i class="fas fa-book">
        </i> 
        <span>
          <?php echo get_phrase('library'); ?>
        </span>
      </a>
        <ul>
            <?php //if(right_granted('add_book')) Remaining{ ?>
            <li class="<?php if($page_name=='student_p/book')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>student_p/books"class="asterisk">
                <i class="fas fa-book">
                </i>
                <?php echo get_phrase('books'); ?>
              </a>
            </li>
            <?php //} ?>
            
             <?php //if(right_granted('add_book')) Remaining{ ?>
            <!--<li class="<?php if($page_name=='library/book_issue')echo 'opened active'; ?>">-->
            <!--  <a href="<?php echo base_url(); ?>library/book_issue"class="asterisk">-->
            <!--    <i class="fas fa-book">-->
            <!--    </i>-->
            <!--    <?php echo get_phrase('book_issue'); ?>-->
            <!--  </a>-->
            <!--</li>-->
            <?php //} ?>
        </ul>
    </li>
    <?php //} ?>
        <?php if (in_array("s_leave", $assigned_access_rights)){ ?>
        <li class="<?php if($page_name == 'leave_request')echo 'active';?> ">
			<a href="<?php echo base_url();?>student_p/manage_student_leaves">
				 <i class="fas fa-user-minus"></i>
				 <span><?php echo get_phrase('leaves');?></span>
			</a>
       </li>
       <?php } ?>
        <?php } if (in_array("s_virtual_class_recordings", $assigned_access_rights)){ ?>   
            <li class="<?php if($page_name == 'subjectwise_recording')echo 'active';?> ">
				<a href="<?php echo base_url();?>student_p/subject_recording">
					 <i class="fas fa-vr-cardboard"></i>
					 <span><?php echo get_phrase('virtual_class_recordings');?></span>
				</a>
           </li>    
           <?php } if (in_array("s_my_profile", $assigned_access_rights)){ ?>  
           <li class="<?php if($page_name == 'manage_profile')echo 'active';?> ">
				<a href="<?php echo base_url();?>student_p/manage_profile">
					<i class="far fa-user-circle"></i>
					<span><?php echo get_phrase('my_profile');?></span>
				</a>
           </li>
           <?php } ?>
           
           <?php if (in_array("s_view_notes", $assigned_access_rights)){ ?> 
           <li class="<?php if($page_name == 'notes/view_notes')echo 'active';?> ">
				<a href="<?php echo base_url();?>notes_student/view_notes">
					 <i class="fas fa-vr-cardboard"></i>
					 <span><?php echo get_phrase('lecture_notes');?></span>
				</a>
           </li>
           <?php } if(in_array('s_teacher_rating', $assigned_access_rights)){ ?>
                <li class="<?php if($page_name=='teacher_rating'||$page_name=='stud_evaluation_form'||$page_name=='modal_view_stud_evaluation')echo 'opened active'; ?>">
                  <a href="<?php echo base_url(); ?>evaluation/teacher_rating/">
                    <i class="far fa-star"></i> 
                    <span>
                      <?php echo get_phrase('teacher_rating'); ?>
                    </span>
                  </a>
                </li>
           <?php } if(in_array('s_student_evaluation_results', $assigned_access_rights)){ ?>
                <li class="<?php if($page_name=='my_evaulation_results'||$page_name=='stud_evaluation_form'||$page_name=='modal_view_stud_evaluation')echo 'opened active'; ?>">
                  <a href="<?php echo base_url(); ?>student_evaluation/my_evaulation_results/">
                    <i class="far fa-comment"></i> 
                    <span>
                      <?php echo get_phrase('my_evaulations'); ?>
                    </span>
                  </a>
                </li>
            <?php
            } ?>
	</ul>
</div>