<style>
	.mynavimg{height:17px;display:inline;margin-top:-4px}.panel-danger>.panel-heading{color:#fff;background-color:#29638d;border-color:#29638d}.panel-heading>.panel-title{color:#fff!important}.fa fa-bell-o{color:#fff!important}.panel-danger{border-color:#29638d!important}.student_details{background-color:#f6f6f6;color:#fff;width:100%;height:auto;padding:20px 0;display:inline-block}.student_details li{list-style:none;border-bottom:none!important}.userpic{height:70px;width:70px;border:2px solid #4a8cbb}@media (min-width:768px){.sidebar-nav .navbar .navbar-collapse{padding:0;max-height:none}.sidebar-nav .navbar ul{float:none;display:block}.sidebar-nav .navbar li{float:none;display:block}.sidebar-nav .navbar li a{padding-top:12px;padding-bottom:12px}.navbar-default{background-color:#fff;border-bottom:0;border-left:0}}
</style>

<div>
</div>
<!--<div class="sidebar-menu">-->
		<div class="sidebar-menu-inner">
			<header class="logo-env">
				<!-- logo -->
				<div class="logo text-center py-4">
					<a href="<?php echo base_url()."parents/dashboard"?>">
						<?php				
                			$logo=system_path($_SESSION['school_logo']);
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
				<!-- logo collapse icon -->
				
				<!--<div class="sidebar-mobile-menu visible-xs">-->
				<!--	<a href="#" class="with-animation">-->
				<!--		<i class="entypo-menu"></i>-->
				<!--	</a>-->
				<!--</div>-->
			</header>
			<div class="sidebar-user-info">
				<div class="sui-normal">
					<a href="#" class="user-link">
					    <?php if($res[0]['profile_pic']=="") { ?>
    					<img src="<?php echo get_default_pic()?>" alt="" class="img-circle notify_icon123" width="55" alt="" class="img-circle"> 
    					<?php } else {?>
    					<img src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1)?>" alt="" class="img-circle notify_icon123" width="55" alt="" class="img-circle">
    					<?php } ?>
						<span>Welcome,</span>
						<strong><?php echo $res[0]['name'];?></strong>
					</a>
				</div>
				<div class="sui-hover inline-links animate-in"><!-- You can remove "inline-links" class to make links appear vertically, class "animate-in" will make A elements animateable when click on user profile -->
					<a href="<?=base_url()?>profile/manage_profile">
						<i class="entypo-pencil"></i> Profile
					</a>
                    <?php if (isset($_SESSION['multiple_accounts']) && $_SESSION['multiple_accounts'] == 1) { ?>
			        <a href="<?php echo base_url();?>switch_user/account_list">
				        <i class="entypo-chat"></i> Switch
			        </a>
					<?php } ?>
					<a href="<?php echo base_url();?>login/logout">
						<i class="entypo-lock"></i> Log Off
					</a>
					<span class="close-sui-popup">&times;</span>
			    </div>
			</div>						
			<ul id="main-menu" class="main-menu">
				<!--Code Written By Zeeshan Arain-->
                <!-- DASHBOARD -->
                <li class="<?php if($page_name == 'dashboard')echo 'active';?> ">
    				<a href="<?php echo base_url();?>parents/dashboard">
        				<i class="fas fa-tachometer-alt"></i>
            			<span><?php echo get_phrase('dashboard');?></span>
        			</a>
                </li>
                <?php if (in_array("p_school_policies", $assigned_access_rights) ){?>
                <li class="<?php if($page_name == 'policies_listing' || $page_name == 'policies_add_edit' )echo 'active';?> ">
	                <a href="<?php echo base_url();?>parents/policies_listing">
	                    <i class="fas fa-vote-yea"></i>
	                    <span><?php echo get_phrase('school_policies');?></span>
	                </a>
    	        </li>
    	        <?php
                  }
                ?>
                <?php //if (in_array("p_school_policies", $assigned_access_rights) ){ Remaining Action ?>
                <li class="<?php if($page_name == 'subjects')echo 'active';?> ">
	                <a href="<?php echo base_url();?>parents/subjects">
	                    <i class="fas fa-vote-yea"></i>
	                    <span><?php echo get_phrase('subjects');?></span>
	                </a>
    	        </li>
    	        <?php
                  //}
                ?>
                <?php
                  if (in_array("p_class_diary", $assigned_access_rights) ){
                ?>
                <li class="<?php if($page_name == 'diary')echo 'active';?> ">
    				<a href="<?php echo base_url();?>parents/diary">
    					<i class="fas fa-book"></i>
    					<span><?php echo get_phrase('class_diary');?></span>
    				</a>
                </li>
                <?php
                  }
                ?>
       
                <!-- NOTICEBOARD --> 
                <?php
                  if (in_array("p_notices_and_circulars", $assigned_access_rights) ){
                ?>
                <li class="<?php if($page_name == 'noticeboard' || $page_name == 'circulars')echo 'opened active';?> ">
    				<a href="#">
    					<!--<i class="entypo-doc-text-inv"></i>-->
    					<i class="fas fa-bullhorn"></i>
    					<span><?php echo get_phrase('notices_and_circulars');?></span>
    				</a>
                    <ul>
                        <?php
                          if (in_array("p_noticeboard", $assigned_access_rights) ){
                        ?>
                        <li class="<?php if($page_name == 'noticeboard')echo 'active';?> ">
                            <a href="<?php echo base_url();?>parents/noticeboard">
                            <i class="fas fa-chalkboard"></i>
                            <span><?php echo get_phrase('noticeboard');?></span>
                            </a>
                        </li>
                        <?php } if (in_array("p_circulars", $assigned_access_rights) ){ ?>        
                        <li class="<?php if($page_name == 'circulars')echo 'active';?> ">
                            <a href="<?php echo base_url();?>parents/circulars">
                                <i class="fas fa-bullseye"></i>
                                <span><?php echo get_phrase('circulars');?></span>
                            </a>
                        </li>
                        <?php } ?>        
                    </ul>
                </li>
                <?php } ?>
                
                <!-- daily attendane -->
                <?php 
                    if (in_array("p_attendance", $assigned_access_rights) ){ 
                        $attendance_method = get_attendance_method();
                        $attendance_url = "";
                        if($attendance_method == 1){
                            $attendance_url = "parents/manage_attendance";
                            $text_att = "Mark Attendance";
                        }elseif($attendance_method == 2){
                            $attendance_url = "parents/view_subjectwise_attendance";
                        }else{
                            $attendance_url = "parents/manage_attendance"; //default
                        }
                ?>
    			<li class="<?php if($page_name == 'manage_attendance' || $page_name == 'summary' || $page_name == 'view_subjectwise_attendance')echo 'opened active';?> ">
    				<a href="#">
    					<i class="fas fa-user-check"></i>
    					<span><?php echo get_phrase('Attendance');?></span>
    				</a>
                    <ul>
                        <?php
                          if (in_array("p_details", $assigned_access_rights) ){
                        ?>
                        <li class="<?php if($page_name == 'daily attandane' || $page_name == 'manage_attendance' )echo 'active';?> ">
                            <a href="<?php echo base_url().'/'.$attendance_url ;?>">
                                <i class="fas fa-clipboard-check"></i>
                                <span><?php echo get_phrase('daily_attendance_summary');?></span>
                            </a>
                        </li>
                        <?php
                          }
                          if (in_array("p_attendance_summary", $assigned_access_rights) ){
                        ?>        
                        <li class="<?php if($page_name == 'summary')echo 'active';?> ">
                            <a href="<?php echo base_url();?>parents/summary">
                                <i class="fas fa-clipboard-check"></i>
                                <span><?php echo get_phrase('monthly_attendance_summary');?></span>
                            </a>
                        </li>
                        <?php } ?>        
                    </ul>
                </li>
                <?php } if (in_array("p_time_table", $assigned_access_rights) ){?>
                <li class="<?php if($page_name == 'class_routine')echo 'active';?> ">
    				<a href="<?php echo base_url();?>parents/class_routine">
    				    <i class="fas fa-table"></i>
    					<span><?php echo get_phrase('time_table');?></span>
    				</a>
                </li>
                <?php } ?>
                
                <!-- message -->
                <?php if (in_array("p_message", $assigned_access_rights) ){ ?>
                <li class="<?php if($page_name == 'message' || $page_name=='teacher')echo 'active';?> ">
    				<a href="<?php echo base_url();?>parents/teacher_list">
    				    <i class="fas fa-sms"></i>
    					<span><?php echo get_phrase('View / Send Message');?></span>
    				</a>
                </li>
                <li class="<?php if($page_name == 'message' || $page_name=='teacher')echo 'active';?> ">
    				<a href="<?php echo base_url();?>parents/parent_chat_list">
    				    <i class="fas fa-sms"></i>
    					<span><?php echo get_phrase('Parent_chat / Send Message');?></span>
    				</a>
                </li>
                <?php } ?>
                <!-- PAYMENT -->
                <?php if (in_array("p_payment", $assigned_access_rights) ){ ?>
                <li class="<?php if($page_name == 'invoice')echo 'active';?> ">
    				<a href="<?php echo base_url();?>parents/invoice">
    					<i class="fas fa-dollar-sign"></i>
    					<span><?php echo get_phrase('payment');?></span>
    				</a>
                </li>
                <?php } ?>
                
                <!-- EXAMS -->
                <?php
                  if (in_array("p_exams", $assigned_access_rights) ){
                ?>
                <li class="<?php if($page_name == 'exam' || $page_name == 'exam_routine' || $page_name =='marks')echo 'opened active';?> ">
    				<a href="#">
    				    <i class="fas fa-poll-h"></i>
    					<span><?php echo get_phrase('exams');?></span>
    				</a>
                    <ul>
                        <?php if (in_array("p_datesheet", $assigned_access_rights) ){ ?>
    					<li class="<?php if($page_name == 'exam_routine')echo 'active';?> ">
    						<a href="<?php echo base_url();?>parents/exam_routine">
    							 <i class="far fa-file-alt"></i>
    							 <span><?php echo get_phrase('date_sheet');?></span>
    						</a>
    					</li>
            			<?php } if (in_array("p_examination_result", $assigned_access_rights) ){ ?>
    					<li class="<?php if($page_name == 'marks')echo 'active';?> ">
    						<a href="<?php echo base_url();?>parents/marks">
    							 <i class="fas fa-poll"></i>
    							 <span><?php echo get_phrase('examination_result');?></span>
    						</a>
    					</li>
                        <?php } ?>      
                    </ul>
               </li>
                <?php } ?>
                <!-- Asssessment -->
                <?php if (in_array("p_manage_assessments", $assigned_access_rights) ){ ?>
                <li class="<?php if($page_name == 'assessment/view_assessment' || $page_name == 'assessment/assessment_details' || $page_name == 'assessment/assessment_result' || $page_name == 'assessment/result_details' )echo 'opened active';?> ">
                <a href="#">
                    <i class="las la-tasks"></i>
                    <span><?php echo get_phrase('online_assessments');?></span>
                </a>
                <ul>
                    <?php
                      if (in_array("p_view_assessments", $assigned_access_rights) ){
                    ?>
                    <li class="<?php if($page_name == 'assessment/view_assessment')echo 'active';?> ">
                        <a href="<?php echo base_url();?>assessment_student/view_assessment">
                            <i class="las la-clipboard-list"></i>
                            <span><?php echo get_phrase('view_assessments');?></span>
                        </a>
                    </li>
                    <?php }if (in_array("p_assessment_result", $assigned_access_rights) ){ ?>
                    <li class="<?php if($page_name == 'assessment/assessment_result' || $page_name == 'assessment/result_details')echo 'active';?> ">
                        <a href="<?php echo base_url();?>assessment_student/assessment_result">
                            <i class="fas fa-file-invoice"></i>
                            <span><?php echo get_phrase('assessment_result');?></span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
                <?php } ?>
                <!-- Leave Requests -->
                <?php
                  if (in_array("p_leave_request", $assigned_access_rights) ){
                ?>
                <li class="<?php if($page_name == 'leave_request')echo 'active';?> ">
    				<a href="<?php echo base_url();?>parents/manage_leaves">
                        <i class="fas fa-user-minus"></i>
    					<span><?php echo get_phrase('leave_request');?></span>
    				</a>
                </li>
                <?php
                  }
                ?>
                
                
                
               <?php 
                    
                $parent_exist = $this->db->query("select id_no from ".get_system_db().". user_login 
                where  id_no = '".$_SESSION[$_SESSION['student_id']]."' ")->result_array();
                if(count($parent_exist) != 0){
                    if (in_array("p_update_child_password", $assigned_access_rights) ){
               ?>
                       <li class="<?php if($page_name == 'leave_request')echo 'active';?> ">
            				<a href="<?php echo base_url();?>parents/update_password">
            					 <i class="fas fa-sync-alt"></i>
            					<span><?php echo get_phrase('update_'.$_SESSION["student_name"].'_password');?></span>
            				</a>
                       </li>
               <?php } } ?>
               
                <?php
                  if (in_array("p_my_profile", $assigned_access_rights) ){
                ?>
                <li class="<?php if($page_name == 'manage_profile')echo 'active';?> ">
    				<a href="<?php echo base_url();?>parents/manage_profile">
    					    <i class="far fa-user-circle"></i>
    					<span><?php echo get_phrase('my_profile');?></span>
    				</a>
                </li>
               <?php
                  }
                ?>
               
                <?php
                  if (in_array("p_student_evaluation_results", $assigned_access_rights) ){
                ?>
                <li class="<?php if($page_name == 'p_student_evaluation_results')echo 'active';?> ">
    				<a href="<?php echo base_url();?>parents/child_evaluation_results">
    					    <i class="far fa-comment"></i>
    					<span><?php echo get_phrase('child_evaluations');?></span>
    				</a>
                </li>
               <?php
                  } 
                ?>
			</ul>
		</div>

